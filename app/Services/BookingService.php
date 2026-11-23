<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Consultant;
use App\Models\Schedule;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * BookingService — core orchestration for slot availability,
 * consultant assignment, and double-booking prevention.
 */
class BookingService
{
    public const SLOT_DURATION_MINUTES = 30;

    // ──────────────────────────────────────────────────────────────────────────
    // Slot Availability
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Check whether at least one consultant has capacity for the UTC slot.
     */
    public function isSlotAvailable(Carbon $utcSlot): bool
    {
        return $this->findAvailableConsultant($utcSlot) !== null;
    }

    /**
     * Return all 30-minute UTC slots available on a given UTC date.
     *
     * @return Collection<int, Carbon>
     */
    public function getAvailableSlotsForDate(Carbon $utcDate): Collection
    {
        $slots = collect();
        $dayOfWeek = (int) $utcDate->dayOfWeek;

        // No weekends
        if (in_array($dayOfWeek, [0, 6])) {
            return $slots;
        }

        // Gather schedules across all active consultants for this weekday
        $schedules = Schedule::with('consultant')
            ->active()
            ->forDay($dayOfWeek)
            ->whereHas('consultant', fn ($q) => $q->active())
            ->get();

        foreach ($schedules as $schedule) {
            $consultant = $schedule->consultant;
            // Convert consultant schedule window to UTC for the target date
            $start = Carbon::createFromFormat('H:i:s', $schedule->start_time, $consultant->timezone)
                ->setDateFrom($utcDate->copy()->setTimezone($consultant->timezone))
                ->utc();
            $end   = Carbon::createFromFormat('H:i:s', $schedule->end_time, $consultant->timezone)
                ->setDateFrom($utcDate->copy()->setTimezone($consultant->timezone))
                ->utc();

            // Generate 30-min slots
            $cursor = $start->copy();
            while ($cursor->copy()->addMinutes(self::SLOT_DURATION_MINUTES)->lessThanOrEqualTo($end)) {
                $slot = $cursor->copy();
                // Only include future slots
                if ($slot->isFuture() && ! $slots->contains(fn ($s) => $s->equalTo($slot))) {
                    $slots->push($slot);
                }
                $cursor->addMinutes(self::SLOT_DURATION_MINUTES);
            }
        }

        // Remove slots that are fully booked
        return $slots
            ->filter(fn (Carbon $slot) => $this->findAvailableConsultant($slot) !== null)
            ->sortBy(fn (Carbon $slot) => $slot->timestamp)
            ->values();
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Booking Creation (race-condition safe via DB transaction + lock)
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Persist a booking inside a serialisable DB transaction.
     * Uses SELECT FOR UPDATE on the bookings table to prevent double-booking.
     *
     * @param  array<string, mixed>  $data
     * @throws \RuntimeException If the slot is taken by the time we lock.
     */
    public function createBooking(array $data, Carbon $scheduledAtUtc): Booking
    {
        return DB::transaction(function () use ($data, $scheduledAtUtc) {
            // Lock the target slot; prevents concurrent inserts for same time
            $conflictCount = Booking::where('scheduled_at', $scheduledAtUtc)
                ->whereIn('status', ['pending', 'confirmed'])
                ->lockForUpdate()
                ->count();

            $consultant = $this->findAvailableConsultant($scheduledAtUtc);

            if ($consultant === null) {
                throw new \RuntimeException('The selected time slot is no longer available. Please choose another slot.');
            }

            $booking = Booking::create([
                'client_name'            => $data['client_name'],
                'client_email'           => $data['client_email'],
                'client_phone'           => $data['client_phone'] ?? null,
                'company_name'           => $data['company_name'],
                'operations_country'     => $data['operations_country'] ?? null,
                'operational_challenges' => $data['operational_challenges'] ?? null,
                'service_id'             => $data['service_id'],
                'consultant_id'          => $consultant->id,
                'headcount_range'        => $data['headcount_range'],
                'scheduled_at'           => $scheduledAtUtc,
                'client_timezone'        => $data['client_timezone'],
                'duration_minutes'       => self::SLOT_DURATION_MINUTES,
                'meeting_link'           => $consultant->meeting_link ?? config('services.google_meet.default_link'),
                'nda_requested'          => (bool) ($data['nda_requested'] ?? false),
                'status'                 => 'pending',
            ]);

            Log::info('Booking created', [
                'reference' => $booking->reference_code,
                'slot'      => $scheduledAtUtc->toIso8601String(),
                'consultant'=> $consultant->name,
            ]);

            return $booking;
        }, attempts: 3);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Consultant Assignment
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Round-robin: pick the active consultant with lowest bookings on slot day
     * who has a schedule covering this UTC slot and hasn't hit their daily cap.
     */
    public function findAvailableConsultant(Carbon $utcSlot): ?Consultant
    {
        $slotDayOfWeek = (int) $utcSlot->dayOfWeek;

        $consultants = Consultant::active()
            ->with(['schedules' => fn ($q) => $q->active()->forDay($slotDayOfWeek)])
            ->withCount([
                'bookings as today_bookings_count' => fn ($q) => $q
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->whereDate('scheduled_at', $utcSlot->toDateString()),
            ])
            ->get();

        foreach ($consultants->sortBy('today_bookings_count') as $consultant) {
            // Check daily cap
            if ($consultant->today_bookings_count >= $consultant->max_daily_bookings) {
                continue;
            }

            // Check their schedule covers this slot in their own timezone
            foreach ($consultant->schedules as $schedule) {
                $scheduleStart = Carbon::createFromFormat('H:i:s', $schedule->start_time, $consultant->timezone)
                    ->setDateFrom($utcSlot->copy()->setTimezone($consultant->timezone))
                    ->utc();
                $scheduleEnd   = Carbon::createFromFormat('H:i:s', $schedule->end_time, $consultant->timezone)
                    ->setDateFrom($utcSlot->copy()->setTimezone($consultant->timezone))
                    ->utc();

                if ($utcSlot->greaterThanOrEqualTo($scheduleStart) &&
                    $utcSlot->copy()->addMinutes(self::SLOT_DURATION_MINUTES)->lessThanOrEqualTo($scheduleEnd)) {
                    // No overlapping confirmed/pending booking?
                    $conflict = Booking::where('consultant_id', $consultant->id)
                        ->where('scheduled_at', $utcSlot)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->exists();

                    if (! $conflict) {
                        return $consultant;
                    }
                }
            }
        }

        return null;
    }
}

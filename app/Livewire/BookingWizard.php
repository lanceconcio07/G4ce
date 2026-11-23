<?php

namespace App\Livewire;

use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Models\Service;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule as LivewireRule;
use Livewire\Component;

/**
 * BookingWizard — 3-step reactive booking form.
 *
 * Step 1 → Service & Headcount
 * Step 2 → Date, Time & Timezone
 * Step 3 → Client Credentials
 * Step 4 → Confirmation summary
 */
class BookingWizard extends Component
{
    // ──────────────────────────────────────────
    // Wizard state
    // ──────────────────────────────────────────
    public int $step = 1;

    // ──────────────────────────────────────────
    // Step 1 fields
    // ──────────────────────────────────────────
    public ?int    $serviceId       = null;
    public string  $headcountRange  = '';

    // ──────────────────────────────────────────
    // Step 2 fields
    // ──────────────────────────────────────────
    public string  $selectedDate    = '';
    public string  $selectedTime    = '';
    public string  $clientTimezone  = 'UTC';

    // Calendar UI state
    public int     $calendarYear    = 0;
    public int     $calendarMonth   = 0;

    // ──────────────────────────────────────────
    // Step 3 fields
    // ──────────────────────────────────────────
    public string  $clientName      = '';
    public string  $companyName     = '';
    public string  $clientEmail     = '';
    public string  $clientPhone     = '';
    public string  $operationsCountry = '';
    public string  $operationalChallenges = '';
    public bool    $ndaRequested    = false;

    // ──────────────────────────────────────────
    // Confirmation state
    // ──────────────────────────────────────────
    public ?string $confirmationReference = null;
    public ?int    $confirmedBookingId    = null;

    // ──────────────────────────────────────────
    // Generic free email domains to block
    // ──────────────────────────────────────────
    private const GENERIC_DOMAINS = [
        'gmail.com','yahoo.com','hotmail.com','outlook.com',
        'aol.com','icloud.com','mail.com','proton.me',
        'protonmail.com','zohomail.com','yandex.com',
        'live.com','msn.com','me.com',
    ];

    // ──────────────────────────────────────────
    // Mount
    // ──────────────────────────────────────────
    public function mount(): void
    {
        $now = now();
        $this->calendarYear  = (int) $now->year;
        $this->calendarMonth = (int) $now->month;
        $this->clientTimezone = request()->header('X-Client-Timezone', 'UTC');
    }

    // ──────────────────────────────────────────
    // Computed properties
    // ──────────────────────────────────────────

    #[Computed]
    public function services()
    {
        return Service::active()->get();
    }

    #[Computed]
    public function availableSlots(): array
    {
        if (! $this->selectedDate) {
            return [];
        }

        try {
            $utcDate = Carbon::parse($this->selectedDate, $this->clientTimezone)->utc();
        } catch (\Exception) {
            return [];
        }

        $slots = app(BookingService::class)->getAvailableSlotsForDate($utcDate);

        return $slots->map(function (Carbon $slot) {
            $local = $slot->copy()->setTimezone($this->clientTimezone);
            return [
                'value'   => $local->format('H:i'),
                'label'   => $local->format('g:i A'),
                'utc'     => $slot->toIso8601String(),
            ];
        })->toArray();
    }

    #[Computed]
    public function calendarDays(): array
    {
        $firstDay   = Carbon::create($this->calendarYear, $this->calendarMonth, 1);
        $lastDay    = $firstDay->copy()->endOfMonth();
        $startPad   = $firstDay->dayOfWeek; // Sunday = 0
        $today      = now()->startOfDay();

        $days = [];

        // Empty padding at start
        for ($i = 0; $i < $startPad; $i++) {
            $days[] = null;
        }

        for ($d = 1; $d <= $lastDay->day; $d++) {
            $date      = Carbon::create($this->calendarYear, $this->calendarMonth, $d);
            $isWeekend = in_array($date->dayOfWeek, [0, 6]);
            $isPast    = $date->lt($today);

            $days[] = [
                'day'       => $d,
                'date'      => $date->format('Y-m-d'),
                'disabled'  => $isWeekend || $isPast,
                'isToday'   => $date->isToday(),
                'selected'  => $this->selectedDate === $date->format('Y-m-d'),
            ];
        }

        return $days;
    }

    #[Computed]
    public function calendarLabel(): string
    {
        return Carbon::create($this->calendarYear, $this->calendarMonth, 1)->format('F Y');
    }

    #[Computed]
    public function confirmedBooking(): ?Booking
    {
        if (! $this->confirmedBookingId) return null;
        return Booking::with(['service','consultant'])->find($this->confirmedBookingId);
    }

    // ──────────────────────────────────────────
    // Step navigation
    // ──────────────────────────────────────────

    public function nextStep(): void
    {
        match ($this->step) {
            1 => $this->validateStep1(),
            2 => $this->validateStep2(),
            3 => $this->submitBooking(),
        };
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    // ──────────────────────────────────────────
    // Step validations
    // ──────────────────────────────────────────

    private function validateStep1(): void
    {
        $this->validate([
            'serviceId'      => ['required', 'integer', Rule::exists('services','id')->where('is_active', true)],
            'headcountRange' => ['required', Rule::in(['1-5','6-20','20+'])],
        ], [
            'serviceId.required'      => 'Please select a BPO service.',
            'headcountRange.required' => 'Please select a headcount range.',
        ]);
        $this->step = 2;
    }

    private function validateStep2(): void
    {
        $this->validate([
            'selectedDate'   => ['required', 'date_format:Y-m-d'],
            'selectedTime'   => ['required', 'date_format:H:i'],
            'clientTimezone' => ['required', 'timezone'],
        ], [
            'selectedDate.required' => 'Please select a consultation date.',
            'selectedTime.required' => 'Please select a time slot.',
        ]);

        // Ensure slot still available
        try {
            $utc = Carbon::createFromFormat('Y-m-d H:i', $this->selectedDate.' '.$this->selectedTime, $this->clientTimezone)->utc();
        } catch (\Exception) {
            $this->addError('selectedTime', 'Invalid date/time combination.');
            return;
        }

        if (! app(BookingService::class)->isSlotAvailable($utc)) {
            $this->addError('selectedTime', 'This slot is no longer available. Please select another.');
            return;
        }

        $this->step = 3;
    }

    // ──────────────────────────────────────────
    // Final submission
    // ──────────────────────────────────────────

    private function submitBooking(): void
    {
        $this->validate([
            'clientName'             => ['required','string','max:150'],
            'companyName'            => ['required','string','max:150'],
            'clientEmail'            => ['required','email','max:255', function ($attr, $value, $fail) {
                $domain = strtolower(substr(strrchr($value, '@'), 1));
                if (in_array($domain, self::GENERIC_DOMAINS, true)) {
                    $fail('Please use your official corporate email. Generic providers are not accepted.');
                }
            }],
            'clientPhone'            => ['nullable','string','max:50'],
            'operationsCountry'      => ['nullable','string','max:100'],
            'operationalChallenges'  => ['nullable','string','max:2000'],
            'ndaRequested'           => ['boolean'],
        ]);

        try {
            $utc = Carbon::createFromFormat(
                'Y-m-d H:i',
                $this->selectedDate.' '.$this->selectedTime,
                $this->clientTimezone
            )->utc();

            $booking = app(BookingService::class)->createBooking([
                'client_name'            => $this->clientName,
                'client_email'           => $this->clientEmail,
                'client_phone'           => $this->clientPhone,
                'company_name'           => $this->companyName,
                'operations_country'     => $this->operationsCountry,
                'operational_challenges' => $this->operationalChallenges,
                'service_id'             => $this->serviceId,
                'headcount_range'        => $this->headcountRange,
                'client_timezone'        => $this->clientTimezone,
                'nda_requested'          => $this->ndaRequested,
            ], $utc);

            // Queue confirmation email
            Mail::to($booking->client_email)->queue(new BookingConfirmedMail($booking));

            $this->confirmationReference = $booking->reference_code;
            $this->confirmedBookingId    = $booking->id;
            $this->step = 4;
        } catch (\RuntimeException $e) {
            $this->addError('selectedTime', $e->getMessage());
            $this->step = 2;
        }
    }

    // ──────────────────────────────────────────
    // Calendar helpers
    // ──────────────────────────────────────────

    public function prevMonth(): void
    {
        $date = Carbon::create($this->calendarYear, $this->calendarMonth, 1)->subMonth();
        $this->calendarYear  = (int) $date->year;
        $this->calendarMonth = (int) $date->month;
        $this->selectedDate  = '';
        $this->selectedTime  = '';
    }

    public function nextMonth(): void
    {
        $date = Carbon::create($this->calendarYear, $this->calendarMonth, 1)->addMonth();
        $this->calendarYear  = (int) $date->year;
        $this->calendarMonth = (int) $date->month;
        $this->selectedDate  = '';
        $this->selectedTime  = '';
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->selectedTime = '';
        unset($this->availableSlots);
    }

    public function selectTime(string $time): void
    {
        $this->selectedTime = $time;
    }

    public function setTimezone(string $tz): void
    {
        $this->clientTimezone = $tz;
        $this->selectedTime   = '';
        unset($this->availableSlots);
    }

    // ──────────────────────────────────────────
    // Render
    // ──────────────────────────────────────────

    public function render()
    {
        return view('livewire.booking-wizard');
    }
}

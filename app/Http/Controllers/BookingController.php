<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService,
    ) {}

    /**
     * Display the booking portal landing page.
     */
    public function index(): View
    {
        return view('booking.index');
    }

    /**
     * JSON endpoint: return available time slots for a given date and timezone.
     * Used as a public API fallback if needed (Livewire handles this natively).
     */
    public function availableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'date'     => ['required', 'date_format:Y-m-d'],
            'timezone' => ['required', 'timezone'],
        ]);

        try {
            $utcDate = Carbon::parse($request->date, $request->timezone)->utc();
        } catch (\Exception) {
            return response()->json(['slots' => []]);
        }

        $slots = $this->bookingService->getAvailableSlotsForDate($utcDate);

        $formatted = $slots->map(function (Carbon $slot) use ($request) {
            $local = $slot->copy()->setTimezone($request->timezone);
            return [
                'value'   => $local->format('H:i'),
                'label'   => $local->format('g:i A'),
                'utc'     => $slot->toIso8601String(),
            ];
        });

        return response()->json(['slots' => $formatted]);
    }
}

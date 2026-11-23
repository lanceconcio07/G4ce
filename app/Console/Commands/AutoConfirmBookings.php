<?php

namespace App\Console\Commands;

use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AutoConfirmBookings extends Command
{
    protected $signature   = 'bookings:auto-confirm';
    protected $description = 'Automatically confirm pending bookings that have passed without cancellation';

    public function handle(): int
    {
        $confirmed = Booking::pending()
            ->where('scheduled_at', '>', now())
            ->where('created_at', '<=', now()->subHours(24))
            ->each(function (Booking $booking) {
                $booking->update([
                    'status'       => 'confirmed',
                    'confirmed_at' => now(),
                ]);

                // Send confirmation email if not already sent
                Mail::to($booking->client_email)->queue(new BookingConfirmedMail($booking));

                $this->info("Confirmed: {$booking->reference_code}");
            });

        $this->info("Auto-confirm complete.");
        return self::SUCCESS;
    }
}

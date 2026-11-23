<?php

namespace App\Mail;

use App\Models\Booking;
use App\Services\CalendarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Booking $booking,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Discovery Call is Confirmed — ' . $this->booking->reference_code . ' | G4ce BPO Services',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmed',
            with: [
                'booking'    => $this->booking->loadMissing(['consultant', 'service']),
                'clientName' => $this->booking->client_name,
                'reference'  => $this->booking->reference_code,
            ],
        );
    }

    public function attachments(): array
    {
        $ics     = app(CalendarService::class)->generateIcs($this->booking);
        $filename = 'g4ce-discovery-' . $this->booking->reference_code . '.ics';

        return [
            Attachment::fromData(fn () => $ics, $filename)
                ->withMime('text/calendar'),
        ];
    }
}

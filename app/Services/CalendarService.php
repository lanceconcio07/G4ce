<?php

namespace App\Services;

use App\Models\Booking;

/**
 * CalendarService — generates an RFC 5545 compliant .ics file content
 * for a confirmed booking.
 */
class CalendarService
{
    public function generateIcs(Booking $booking): string
    {
        $booking->loadMissing(['consultant', 'service']);

        $uid         = $booking->reference_code . '@g4ce.com';
        $dtStamp     = $this->formatDt(now());
        $dtStart     = $this->formatDt($booking->scheduled_at);
        $dtEnd       = $this->formatDt($booking->scheduled_at->copy()->addMinutes($booking->duration_minutes));
        $summary     = 'G4ce BPO Discovery Call — ' . $booking->service->name;
        $description = $this->buildDescription($booking);
        $organizer   = 'MAILTO:consultations@g4ce.com';
        $attendee    = 'MAILTO:' . $booking->client_email;
        $location    = $booking->meeting_link ?? '';

        return implode("\r\n", [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//G4ce BPO Services//Booking Portal//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:REQUEST',
            'BEGIN:VEVENT',
            'UID:' . $uid,
            'DTSTAMP:' . $dtStamp,
            'DTSTART:' . $dtStart,
            'DTEND:' . $dtEnd,
            'SUMMARY:' . $this->icsEscape($summary),
            'DESCRIPTION:' . $this->icsEscape($description),
            'LOCATION:' . $this->icsEscape($location),
            'ORGANIZER;CN="G4ce BPO Services":' . $organizer,
            'ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN="' . $booking->client_name . '":' . $attendee,
            'STATUS:CONFIRMED',
            'TRANSP:OPAQUE',
            'BEGIN:VALARM',
            'TRIGGER:-PT30M',
            'ACTION:DISPLAY',
            'DESCRIPTION:Reminder: G4ce Discovery Call in 30 minutes',
            'END:VALARM',
            'BEGIN:VALARM',
            'TRIGGER:-PT10M',
            'ACTION:DISPLAY',
            'DESCRIPTION:Reminder: G4ce Discovery Call in 10 minutes',
            'END:VALARM',
            'END:VEVENT',
            'END:VCALENDAR',
        ]);
    }

    private function buildDescription(Booking $booking): string
    {
        $lines = [
            'G4ce BPO Services — Discovery & Scoping Call',
            '',
            'Booking Reference: ' . $booking->reference_code,
            'Service: ' . $booking->service->name,
            'Headcount: ' . $booking->headcount_range . ' agents',
            '',
            'Join Meeting: ' . ($booking->meeting_link ?? 'Link will be shared separately'),
            '',
        ];

        if ($booking->consultant) {
            $lines[] = 'Your Consultant: ' . $booking->consultant->name . ' (' . $booking->consultant->title . ')';
            $lines[] = 'Contact: ' . $booking->consultant->email;
        }

        if ($booking->nda_requested) {
            $lines[] = '';
            $lines[] = 'Note: An NDA will be sent prior to this call.';
        }

        return implode('\n', $lines);
    }

    private function formatDt(\DateTimeInterface $dt): string
    {
        return (new \DateTime('now', new \DateTimeZone('UTC')))
            ->setTimestamp($dt->getTimestamp())
            ->format('Ymd\THis\Z');
    }

    private function icsEscape(string $value): string
    {
        return str_replace(["\r\n", "\n", "\r", ',', ';'], ['\\n', '\\n', '\\n', '\\,', '\\;'], $value);
    }
}

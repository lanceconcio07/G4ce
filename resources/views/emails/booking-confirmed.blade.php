<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed — G4ce BPO Services</title>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #F8FAFC; color: #0F172A; }

        /* Container */
        .wrapper   { max-width: 600px; margin: 40px auto; background: #ffffff; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; }

        /* Header */
        .header    { background: #2563EB; padding: 32px 40px; }
        .logo      { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; }
        .logo-box  { width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; color: #fff; }
        .logo-name { font-size: 14px; font-weight: 600; color: rgba(255,255,255,0.9); letter-spacing: -0.01em; }
        .header h1 { font-size: 22px; font-weight: 700; color: #fff; line-height: 1.3; }
        .header p  { font-size: 14px; color: rgba(255,255,255,0.75); margin-top: 6px; }

        /* Reference badge */
        .ref-badge { background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); border-radius: 8px; padding: 10px 16px; margin-top: 20px; display: inline-block; }
        .ref-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); }
        .ref-code  { font-size: 18px; font-weight: 800; color: #fff; font-family: 'Courier New', monospace; letter-spacing: 0.05em; }

        /* Body */
        .body      { padding: 32px 40px; }
        .greeting  { font-size: 15px; color: #0F172A; margin-bottom: 16px; }
        .intro     { font-size: 14px; color: #64748B; line-height: 1.7; margin-bottom: 28px; }

        /* Details card */
        .details   { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; margin-bottom: 24px; }
        .detail-header { background: #F1F5F9; padding: 12px 20px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748B; }
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 14px 20px; border-bottom: 1px solid #E2E8F0; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 12px; color: #64748B; font-weight: 500; }
        .detail-value { font-size: 13px; color: #0F172A; font-weight: 600; text-align: right; max-width: 60%; }

        /* CTA button */
        .cta-wrap  { text-align: center; margin: 28px 0; }
        .cta-btn   { display: inline-block; background: #2563EB; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 14px 32px; border-radius: 10px; letter-spacing: -0.01em; }

        /* Consultant section */
        .consultant { border: 1px solid #E2E8F0; border-radius: 12px; padding: 18px 20px; margin-bottom: 24px; display: flex; align-items: center; gap: 14px; }
        .consultant-avatar { width: 44px; height: 44px; border-radius: 50%; background: #DBEAFE; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; color: #2563EB; flex-shrink: 0; }
        .consultant-name { font-size: 14px; font-weight: 700; color: #0F172A; }
        .consultant-title { font-size: 12px; color: #64748B; margin-top: 2px; }
        .consultant-email { font-size: 12px; color: #2563EB; margin-top: 4px; }

        /* NDA notice */
        .nda-notice { background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 10px; padding: 14px 18px; margin-bottom: 24px; font-size: 13px; color: #92400E; }

        /* ICS notice */
        .ics-notice { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 14px 18px; margin-bottom: 24px; font-size: 12px; color: #1E40AF; }

        /* Footer */
        .footer    { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 24px 40px; text-align: center; font-size: 11px; color: #94A3B8; }
        .footer a  { color: #2563EB; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <div class="logo">
            <div class="logo-box">G4</div>
            <span class="logo-name">G4ce BPO Services</span>
        </div>
        <h1>Your Discovery Call is Confirmed ✓</h1>
        <p>We're looking forward to connecting with you.</p>
        <div class="ref-badge">
            <div class="ref-label">Booking Reference</div>
            <div class="ref-code">{{ $reference }}</div>
        </div>
    </div>

    {{-- Body --}}
    <div class="body">
        <p class="greeting">Hi {{ $clientName }},</p>
        <p class="intro">
            Your 30-minute BPO discovery call with G4ce has been confirmed. Please find the details
            below. A calendar invite (.ics file) is attached to this email — simply open it to add
            the event to your calendar.
        </p>

        {{-- Booking details --}}
        <div class="details">
            <div class="detail-header">Booking Details</div>
            <div class="detail-row">
                <span class="detail-label">Service</span>
                <span class="detail-value">{{ $booking->service->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Headcount</span>
                <span class="detail-value">{{ $booking->headcount_range }} agents</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value">
                    {{ $booking->scheduled_at->setTimezone($booking->client_timezone)->format('l, F j, Y') }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time</span>
                <span class="detail-value">
                    {{ $booking->scheduled_at->setTimezone($booking->client_timezone)->format('g:i A') }}
                    ({{ $booking->client_timezone }})
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Duration</span>
                <span class="detail-value">30 minutes</span>
            </div>
            @if($booking->company_name)
            <div class="detail-row">
                <span class="detail-label">Company</span>
                <span class="detail-value">{{ $booking->company_name }}</span>
            </div>
            @endif
        </div>

        {{-- Meeting link CTA --}}
        @if($booking->meeting_link)
        <div class="cta-wrap">
            <a href="{{ $booking->meeting_link }}" class="cta-btn" target="_blank" rel="noopener noreferrer">
                🎥 &nbsp; Join Meeting
            </a>
            <p style="margin-top:10px; font-size:12px; color:#94A3B8;">
                Or copy this link: <a href="{{ $booking->meeting_link }}" style="color:#2563EB;">{{ $booking->meeting_link }}</a>
            </p>
        </div>
        @endif

        {{-- Consultant --}}
        @if($booking->consultant)
        <div class="consultant">
            <div class="consultant-avatar">{{ substr($booking->consultant->name, 0, 2) }}</div>
            <div>
                <div class="consultant-name">{{ $booking->consultant->name }}</div>
                <div class="consultant-title">{{ $booking->consultant->title }}</div>
                <div class="consultant-email">{{ $booking->consultant->email }}</div>
            </div>
        </div>
        @endif

        {{-- ICS hint --}}
        <div class="ics-notice">
            📅 &nbsp;<strong>Calendar Invite Attached</strong> — An .ics calendar file is attached to this email.
            Open it to instantly add this consultation to your calendar.
        </div>

        {{-- NDA notice --}}
        @if($booking->nda_requested)
        <div class="nda-notice">
            🔒 &nbsp;<strong>NDA Requested</strong> — Our legal team will send you a Mutual Non-Disclosure Agreement
            at this email address for review and signature before your call.
        </div>
        @endif

        <p style="font-size:13px; color:#64748B; line-height:1.7;">
            If you need to reschedule or cancel, please reply to this email with your booking reference
            <strong>{{ $reference }}</strong>, or contact us at
            <a href="mailto:consultations@g4ce.com" style="color:#2563EB;">consultations@g4ce.com</a>.
        </p>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>© {{ date('Y') }} G4ce BPO Services &middot; All rights reserved.</p>
        <p style="margin-top:4px;">
            <a href="#">Privacy Policy</a> &middot;
            <a href="#">Terms of Service</a> &middot;
            <a href="mailto:consultations@g4ce.com">Contact Us</a>
        </p>
    </div>

</div>
</body>
</html>

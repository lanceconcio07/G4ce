<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use POST.']);
    exit;
}

require_once __DIR__ . '/../app/Services/SimpleSmtpMailer.php';
use App\Services\SimpleSmtpMailer;

// Read input
$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input)) {
    $input = json_decode(stripslashes($raw), true);
}
if (!is_array($input)) {
    $input = $_POST;
}

// Extract booking fields
$clientName     = trim($input['client_name'] ?? 'Enterprise Lead');
$companyName    = trim($input['company_name'] ?? 'Apex Enterprises');
$clientEmail    = trim($input['client_email'] ?? '');
$clientPhone    = trim($input['client_phone'] ?? 'N/A');
$country        = trim($input['operations_country'] ?? 'Global');
$challenges     = trim($input['operational_challenges'] ?? 'None specified');
$ndaRequested   = !empty($input['nda_requested']);
$serviceName    = trim($input['service_name'] ?? 'Inbound / Outbound Customer Care');
$headcountRange = trim($input['headcount_range'] ?? '1-5');
$scheduledDate  = trim($input['scheduled_date'] ?? date('Y-m-d', strtotime('+1 day')));
$scheduledTime  = trim($input['scheduled_time'] ?? '10:00 AM');
$timezone       = trim($input['client_timezone'] ?? 'Asia/Manila');

if (empty($clientEmail)) {
    http_response_code(422);
    echo json_encode(['error' => 'Corporate Email is required.']);
    exit;
}

// Generate unique reference code
$refCode = 'G4CE-BK-' . strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 5));

// Pick Assigned Consultant
$consultants = [
    [
        'name'     => 'Maria Santos',
        'title'    => 'Senior BPO Solutions Consultant',
        'email'    => 'maria.santos@g4ce.com',
        'timezone' => 'Asia/Manila',
        'meet'     => 'https://meet.google.com/g4c-scop-bpo',
    ],
    [
        'name'     => 'James Reyes',
        'title'    => 'Enterprise Solutions Consultant',
        'email'    => 'james.reyes@g4ce.com',
        'timezone' => 'Asia/Manila',
        'meet'     => 'https://meet.google.com/g4c-scop-bpo',
    ],
    [
        'name'     => 'Ava Lin',
        'title'    => 'North America Client Director',
        'email'    => 'ava.lin@g4ce.com',
        'timezone' => 'America/Los_Angeles',
        'meet'     => 'https://meet.google.com/g4c-scop-bpo',
    ]
];
$assigned = (str_contains(strtolower($timezone), 'los_angeles') || str_contains(strtolower($timezone), 'new_york')) 
    ? $consultants[2] 
    : $consultants[0];

$meetingLink = $assigned['meet'];

// Build .ics calendar invite content
$icsContent = 
"BEGIN:VCALENDAR\r\n" .
"VERSION:2.0\r\n" .
"PRODID:-//G4ce BPO Services//Discovery Call//EN\r\n" .
"CALSCALE:GREGORIAN\r\n" .
"METHOD:REQUEST\r\n" .
"BEGIN:VEVENT\r\n" .
"UID:{$refCode}@g4ce.com\r\n" .
"DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n" .
"SUMMARY:G4ce BPO Discovery Call — {$serviceName}\r\n" .
"DESCRIPTION:Confidential Discovery & Scoping Call with {$assigned['name']} ({$assigned['title']}).\\nCompany: {$companyName}\\nClient: {$clientName}\\nJoin Google Meet: {$meetingLink}\r\n" .
"LOCATION:{$meetingLink}\r\n" .
"STATUS:CONFIRMED\r\n" .
"BEGIN:VALARM\r\n" .
"TRIGGER:-PT15M\r\n" .
"ACTION:DISPLAY\r\n" .
"DESCRIPTION:Reminder: G4ce Discovery Call in 15 minutes\r\n" .
"END:VALARM\r\n" .
"END:VEVENT\r\n" .
"END:VCALENDAR";

// Save booking record to disk (storage/bookings)
$bookingData = [
    'reference_code'         => $refCode,
    'client_name'            => $clientName,
    'company_name'           => $companyName,
    'client_email'           => $clientEmail,
    'client_phone'           => $clientPhone,
    'operations_country'     => $country,
    'operational_challenges' => $challenges,
    'nda_requested'          => $ndaRequested,
    'service_name'           => $serviceName,
    'headcount_range'        => $headcountRange,
    'scheduled_date'         => $scheduledDate,
    'scheduled_time'         => $scheduledTime,
    'client_timezone'        => $timezone,
    'consultant_assigned'    => $assigned,
    'meeting_link'           => $meetingLink,
    'status'                 => 'confirmed',
    'created_at'             => date('c'),
];

$storageDir = __DIR__ . '/../storage/bookings';
if (!is_dir($storageDir)) {
    @mkdir($storageDir, 0777, true);
}
@file_put_contents($storageDir . '/' . $refCode . '.json', json_encode($bookingData, JSON_PRETTY_PRINT));

// Target company email as requested
$companyRecipient = 'scholargrantgatid@gmail.com';

// Read .env to check if company email override exists
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        if (str_starts_with(trim($line), 'COMPANY_EMAIL=')) {
            $val = trim(substr(trim($line), 14), "\"' ");
            if (!empty($val)) {
                $companyRecipient = $val;
            }
        }
    }
}

// Construct beautiful HTML email for G4ce / Company Notification
$ndaBadge = $ndaRequested 
    ? '<span style="display:inline-block;background:#FEF3C7;color:#92400E;padding:4px 10px;border-radius:6px;font-weight:700;font-size:12px;">YES (Mutual NDA Requested)</span>' 
    : '<span style="color:#64748B;font-size:12px;">No NDA requested</span>';

$companyHtml = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>New Discovery Call Booking</title>
</head>
<body style="margin:0;padding:0;background-color:#F8FAFC;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#1E293B;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#F8FAFC;padding:30px 15px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background:#FFFFFF;border-radius:16px;border:1px solid #E2E8F0;box-shadow:0 4px 12px rgba(0,0,0,0.06);overflow:hidden;">
          
          <!-- Header Banner -->
          <tr>
            <td style="background:linear-gradient(135deg,#0F172A 0%,#1E3A8A 100%);padding:32px 36px;color:#FFFFFF;">
              <table width="100%">
                <tr>
                  <td>
                    <div style="font-size:11px;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:#93C5FD;margin-bottom:6px;">
                      G4ce BPO Services &bull; New Booking Alert
                    </div>
                    <h1 style="margin:0;font-size:22px;font-weight:800;color:#FFFFFF;line-height:1.3;">
                      New Discovery Call Scheduled
                    </h1>
                  </td>
                  <td align="right">
                    <span style="display:inline-block;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.25);border-radius:8px;padding:6px 12px;font-family:monospace;font-size:14px;font-weight:700;color:#FFFFFF;">
                      {$refCode}
                    </span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Key Highlights Card -->
          <tr>
            <td style="padding:28px 36px;">
              <p style="font-size:15px;line-height:1.6;color:#334155;margin:0 0 20px 0;">
                A new client has completed the G4ce booking portal and reserved a <strong>30-minute Discovery &amp; Scoping Consultation</strong>.
              </p>

              <!-- Session Time Box -->
              <div style="background:#EFF6FF;border-left:4px solid #2563EB;padding:16px 20px;border-radius:8px;margin-bottom:24px;">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:#1E40AF;font-weight:700;">Scheduled Session</div>
                <div style="font-size:17px;font-weight:800;color:#1E3A8A;margin-top:4px;">
                  {$scheduledDate} at {$scheduledTime} ({$timezone})
                </div>
                <div style="font-size:12px;color:#3B82F6;margin-top:2px;">
                  Duration: 30 minutes &bull; Assigned: <strong>{$assigned['name']}</strong> ({$assigned['title']})
                </div>
              </div>

              <!-- Client Credentials Table -->
              <h2 style="font-size:14px;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:#64748B;margin:0 0 12px 0;">
                Client &amp; Company Profile
              </h2>
              <table width="100%" cellpadding="10" cellspacing="0" style="border:1px solid #E2E8F0;border-radius:10px;font-size:13px;margin-bottom:24px;">
                <tr style="border-bottom:1px solid #E2E8F0;background:#F8FAFC;">
                  <td width="35%" style="color:#64748B;font-weight:600;">Client Contact:</td>
                  <td style="font-weight:700;color:#0F172A;">{$clientName}</td>
                </tr>
                <tr style="border-bottom:1px solid #E2E8F0;">
                  <td style="color:#64748B;font-weight:600;">Company Name:</td>
                  <td style="font-weight:700;color:#0F172A;">{$companyName}</td>
                </tr>
                <tr style="border-bottom:1px solid #E2E8F0;background:#F8FAFC;">
                  <td style="color:#64748B;font-weight:600;">Corporate Email:</td>
                  <td><a href="mailto:{$clientEmail}" style="color:#2563EB;font-weight:700;text-decoration:none;">{$clientEmail}</a></td>
                </tr>
                <tr style="border-bottom:1px solid #E2E8F0;">
                  <td style="color:#64748B;font-weight:600;">Phone / WhatsApp:</td>
                  <td style="font-weight:600;color:#334155;">{$clientPhone}</td>
                </tr>
                <tr style="background:#F8FAFC;">
                  <td style="color:#64748B;font-weight:600;">Operations Country:</td>
                  <td style="font-weight:600;color:#334155;">{$country}</td>
                </tr>
              </table>

              <!-- Requirements Table -->
              <h2 style="font-size:14px;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:#64748B;margin:0 0 12px 0;">
                BPO Operational Scope
              </h2>
              <table width="100%" cellpadding="10" cellspacing="0" style="border:1px solid #E2E8F0;border-radius:10px;font-size:13px;margin-bottom:24px;">
                <tr style="border-bottom:1px solid #E2E8F0;background:#F8FAFC;">
                  <td width="35%" style="color:#64748B;font-weight:600;">Service Category:</td>
                  <td style="font-weight:700;color:#2563EB;">{$serviceName}</td>
                </tr>
                <tr style="border-bottom:1px solid #E2E8F0;">
                  <td style="color:#64748B;font-weight:600;">Required Team Scale:</td>
                  <td style="font-weight:700;color:#0F172A;">{$headcountRange} Agents</td>
                </tr>
                <tr style="border-bottom:1px solid #E2E8F0;background:#F8FAFC;">
                  <td style="color:#64748B;font-weight:600;">NDA Requirement:</td>
                  <td>{$ndaBadge}</td>
                </tr>
                <tr>
                  <td style="color:#64748B;font-weight:600;vertical-align:top;">Operational Challenges:</td>
                  <td style="color:#334155;line-height:1.5;">{$challenges}</td>
                </tr>
              </table>

              <!-- Action CTA Buttons -->
              <div style="text-align:center;padding:15px 0 5px 0;">
                <a href="{$meetingLink}" target="_blank" style="display:inline-block;background:#2563EB;color:#FFFFFF;font-weight:700;font-size:14px;padding:12px 28px;border-radius:8px;text-decoration:none;margin-right:10px;">
                  Open Google Meet
                </a>
                <a href="mailto:{$clientEmail}?subject=Re:%20G4ce%20Discovery%20Call%20({$refCode})" style="display:inline-block;background:#F1F5F9;border:1px solid #CBD5E1;color:#1E293B;font-weight:700;font-size:14px;padding:12px 24px;border-radius:8px;text-decoration:none;">
                  Email Client
                </a>
              </div>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#F8FAFC;border-top:1px solid #E2E8F0;padding:20px 36px;font-size:11px;color:#94A3B8;text-align:center;">
              Notification sent automatically by G4ce BPO Booking System to <strong>{$companyRecipient}</strong>.<br>
              A calendar invite (.ics) has been attached to this email.
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;

// Dispatch SMTP email
$mailer = new SimpleSmtpMailer();

$companySubject = "🔔 New Discovery Call Booked: {$refCode} — {$companyName} ({$serviceName})";
$sendResult = $mailer->send(
    $companyRecipient,
    $companySubject,
    $companyHtml,
    $icsContent,
    "g4ce-discovery-{$refCode}.ics"
);

// Optional: also attempt sending confirmation to client email if different from company email
$clientSendResult = ['success' => false];
if (strtolower($clientEmail) !== strtolower($companyRecipient)) {
    $clientHtml = str_replace(
        "A new client has completed the G4ce booking portal",
        "Thank you for scheduling your discovery call with G4ce BPO Services. Our team has reserved your session",
        $companyHtml
    );
    $clientSubject = "Your Discovery Call is Confirmed — {$refCode} | G4ce BPO Services";
    $clientSendResult = $mailer->send(
        $clientEmail,
        $clientSubject,
        $clientHtml,
        $icsContent,
        "g4ce-discovery-{$refCode}.ics"
    );
}

// Log status
$logLine = date('Y-m-d H:i:s') . " | Booking: {$refCode} | To: {$companyRecipient} | Result: " . ($sendResult['success'] ? 'OK' : ('FAILED: ' . ($sendResult['error'] ?? 'Unknown')));
@file_put_contents(__DIR__ . '/../storage/logs/bookings.log', $logLine . "\n", FILE_APPEND);

echo json_encode([
    'success'          => true,
    'reference_code'   => $refCode,
    'company_notified' => $sendResult['success'],
    'company_email'    => $companyRecipient,
    'smtp_error'       => $sendResult['error'] ?? null,
    'consultant'       => $assigned['name'] . ' (' . $assigned['title'] . ')',
    'meeting_link'     => $meetingLink,
    'scheduled_date'   => $scheduledDate,
    'scheduled_time'   => $scheduledTime,
    'timezone'         => $timezone,
    'message'          => $sendResult['success'] 
        ? "Booking confirmed and notification dispatched to {$companyRecipient}!"
        : "Booking saved! (Note: Live email delivery requires SMTP credentials in .env)",
]);

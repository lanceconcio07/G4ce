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

$name    = trim($input['name'] ?? '');
$company = trim($input['company'] ?? '');
$email   = trim($input['email'] ?? '');
$message = trim($input['message'] ?? '');

if (empty($name) || empty($email) || empty($message)) {
    http_response_code(422);
    echo json_encode(['error' => 'Name, Business Email, and Message are required.']);
    exit;
}

$inquiryId = 'INQ-' . strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 6));

// Target company email
$companyRecipient = 'scholargrantgatid@gmail.com';
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

// Save inquiry to disk (storage/inquiries)
$inquiryData = [
    'inquiry_id' => $inquiryId,
    'name'       => $name,
    'company'    => $company,
    'email'      => $email,
    'message'    => $message,
    'created_at' => date('c'),
];

$storageDir = __DIR__ . '/../storage/inquiries';
if (!is_dir($storageDir)) {
    @mkdir($storageDir, 0777, true);
}
@file_put_contents($storageDir . '/' . $inquiryId . '.json', json_encode($inquiryData, JSON_PRETTY_PRINT));

// HTML Email for Company
$companySubject = "📩 New Contact Inquiry: {$company} — {$name} [{$inquiryId}]";
$companyHtml = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>New Website Contact Inquiry</title>
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
                      G4ce BPO Services &bull; Contact Inquiry
                    </div>
                    <h1 style="margin:0;font-size:22px;font-weight:800;color:#FFFFFF;line-height:1.3;">
                      New Client Inquiry Received
                    </h1>
                  </td>
                  <td align="right">
                    <span style="display:inline-block;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.25);border-radius:8px;padding:6px 12px;font-family:monospace;font-size:14px;font-weight:700;color:#FFFFFF;">
                      {$inquiryId}
                    </span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Inquiry Details -->
          <tr>
            <td style="padding:28px 36px;">
              <p style="font-size:15px;line-height:1.6;color:#334155;margin:0 0 20px 0;">
                A prospective client has submitted an inquiry through the <strong>Get In Touch</strong> contact form on the G4ce website.
              </p>

              <!-- Profile Table -->
              <table width="100%" cellpadding="10" cellspacing="0" style="border:1px solid #E2E8F0;border-radius:10px;font-size:13px;margin-bottom:24px;">
                <tr style="border-bottom:1px solid #E2E8F0;background:#F8FAFC;">
                  <td width="30%" style="color:#64748B;font-weight:600;">Contact Name:</td>
                  <td style="font-weight:700;color:#0F172A;">{$name}</td>
                </tr>
                <tr style="border-bottom:1px solid #E2E8F0;">
                  <td style="color:#64748B;font-weight:600;">Company:</td>
                  <td style="font-weight:700;color:#0F172A;">{$company}</td>
                </tr>
                <tr style="border-bottom:1px solid #E2E8F0;background:#F8FAFC;">
                  <td style="color:#64748B;font-weight:600;">Business Email:</td>
                  <td><a href="mailto:{$email}" style="color:#2563EB;font-weight:700;text-decoration:none;">{$email}</a></td>
                </tr>
                <tr>
                  <td style="color:#64748B;font-weight:600;">Received:</td>
                  <td style="color:#334155;">{$inquiryData['created_at']}</td>
                </tr>
              </table>

              <!-- Message Content Box -->
              <h2 style="font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:#64748B;margin:0 0 10px 0;">
                Message / Operational Requirement:
              </h2>
              <div style="background:#F1F5F9;border-left:4px solid #2563EB;padding:16px 20px;border-radius:8px;font-size:14px;color:#1E293B;line-height:1.6;margin-bottom:24px;white-space:pre-wrap;">{$message}</div>

              <!-- Reply Action -->
              <div style="text-align:center;padding:10px 0;">
                <a href="mailto:{$email}?subject=Re:%20G4ce%20BPO%20Inquiry%20({$inquiryId})" style="display:inline-block;background:#2563EB;color:#FFFFFF;font-weight:700;font-size:14px;padding:12px 30px;border-radius:8px;text-decoration:none;">
                  Reply Directly to {$name}
                </a>
              </div>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#F8FAFC;border-top:1px solid #E2E8F0;padding:20px 36px;font-size:11px;color:#94A3B8;text-align:center;">
              Notification dispatched automatically to <strong>{$companyRecipient}</strong> via G4ce SMTP.
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;

// Dispatch via SimpleSmtpMailer
$mailer = new SimpleSmtpMailer();
$sendResult = $mailer->send($companyRecipient, $companySubject, $companyHtml);

// Client Auto-Responder
if ($sendResult['success'] && strtolower($email) !== strtolower($companyRecipient)) {
    $clientSubject = "We received your inquiry — G4ce BPO Services [{$inquiryId}]";
    $clientHtml = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family:'Segoe UI',sans-serif;background:#F8FAFC;padding:30px 15px;color:#1E293B;">
  <table width="600" align="center" style="background:#fff;border-radius:12px;border:1px solid #E2E8F0;padding:32px;">
    <tr>
      <td>
        <h2 style="color:#0F172A;margin-top:0;">Thank you for reaching out, {$name}!</h2>
        <p style="font-size:14px;line-height:1.6;color:#475569;">
          We've successfully received your inquiry regarding <strong>{$company}</strong> (Ref: <code>{$inquiryId}</code>).
        </p>
        <p style="font-size:14px;line-height:1.6;color:#475569;">
          A G4ce BPO Solutions Consultant is currently reviewing your operational requirements and will respond within <strong>4 business hours</strong>.
        </p>
        <div style="margin:24px 0;padding:16px;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;font-size:13px;">
          <strong>Your Message:</strong><br>
          <span style="color:#64748B;">{$message}</span>
        </div>
        <p style="font-size:13px;color:#64748B;">
          Want to discuss immediately? You can also <a href="http://localhost/G4ce%20website/public/#booking" style="color:#2563EB;font-weight:600;">book a 30-minute discovery call directly on our portal</a>.
        </p>
        <hr style="border:none;border-top:1px solid #E2E8F0;margin:24px 0;">
        <p style="font-size:11px;color:#94A3B8;">&copy; G4ce BPO Services &bull; Global Operations Team</p>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
    @$mailer->send($email, $clientSubject, $clientHtml);
}

// Log status
$logLine = date('Y-m-d H:i:s') . " | Inquiry: {$inquiryId} | From: {$email} ({$name}) | To: {$companyRecipient} | Result: " . ($sendResult['success'] ? 'OK' : ('FAILED: ' . ($sendResult['error'] ?? 'Unknown')));
@file_put_contents(__DIR__ . '/../storage/logs/contact.log', $logLine . "\n", FILE_APPEND);

echo json_encode([
    'success'          => true,
    'inquiry_id'       => $inquiryId,
    'company_notified' => $sendResult['success'],
    'company_email'    => $companyRecipient,
    'message'          => $sendResult['success'] 
        ? "Inquiry sent! Email notification dispatched to {$companyRecipient}."
        : "Inquiry saved to records.",
]);

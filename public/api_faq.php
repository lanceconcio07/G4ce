<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// Enable CORS if accessed from different port
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

// Read and decode input
$raw = file_get_contents('php://input');
$input = json_decode($raw, true);

if (!is_array($input)) {
    // Try unescaping if quotes were slashed
    $input = json_decode(stripslashes($raw), true);
}

if (!is_array($input)) {
    $input = $_POST;
}

$userMessage = trim($input['message'] ?? '');
$history = is_array($input['history'] ?? null) ? $input['history'] : [];

if (empty($userMessage)) {
    http_response_code(422);
    echo json_encode(['error' => 'Message is required.']);
    exit;
}

// Load API key from environment or fallback
$apiKey = getenv('GEMINI_API_KEY');
if (!$apiKey) {
    // Read from .env if available
    $envPath = __DIR__ . '/../.env';
    if (file_exists($envPath)) {
        $envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($envLines as $line) {
            if (str_starts_with(trim($line), 'GEMINI_API_KEY=')) {
                $apiKey = trim(substr(trim($line), 15), "\"' ");
                break;
            }
        }
    }
}

// Candidate models for graceful fallback
$candidateModels = [
    'gemini-3.1-flash-lite',
    'gemini-3.8-flash',
    'gemini-3.5-flash',
    'gemini-flash-latest',
];

$systemPrompt = <<<PROMPT
You are the official AI Assistant and FAQ Specialist for "G4ce BPO Services" (G4ce Business Process Outsourcing).
Your role is to help enterprise leaders, founders, and operations managers learn about G4ce's outsourcing services and guide them toward booking a free discovery consultation call.

KEY COMPANY FACTS & DETAILS:
1. About G4ce BPO Services:
   - High-performance, global BPO solutions provider offering flexible staff augmentation and fully managed dedicated teams.
   - Vetted English-fluent talent, enterprise data security, and tailored KPIs.

2. Services Catalogue:
   - Inbound / Outbound Customer Care: 24/7 omnichannel CX, phone, live chat, email, ticket resolution, customer retention.
   - Technical Support & Helpdesk L1/L2: Tier 1 & 2 technical troubleshooting, incident management, SLA compliance.
   - Back-Office & Data Processing: Accurate, secure data entry, verification, invoice processing, catalog management, quality assurance.
   - Virtual Assistance & Executive Ops: Remote Executive Assistants, calendar coordination, market research, workflow management.
   - Dedicated Teams / Staff Augmentation: Scalable teams (from 1-5 agents to 50+ enterprise headcount) seamlessly embedded into client tools (Slack, Teams, Zendesk, Jira, Salesforce, etc.).

3. The Discovery Call:
   - Duration: 30 minutes, 100% free and confidential with zero obligation.
   - Consultants: Led by Senior Solutions Consultants (Maria Santos, James Reyes, Ava Lin).
   - Instant Confirmation: Attendees receive an immediate email confirmation with an attached .ics calendar invite and Google Meet link.
   - NDA (Non-Disclosure Agreement): Can be requested with a single click during booking before the call.
   - Hours & Timezones: Support across global timezones (Asia/Manila, America/Los_Angeles, America/New_York, UTC, etc.), Monday through Friday.

4. Booking Flow:
   - Visitors can schedule their call directly through the 3-step interactive wizard on this page:
     Step 1: Choose Service & Team Size.
     Step 2: Pick Date, Time & Timezone.
     Step 3: Provide Name & Corporate Email.

RESPONSE GUIDELINES:
- Keep responses friendly, professional, confident, and concise (typically 2-4 sentences or a short bulleted list).
- Format using clean Markdown (bold key terms, short bullet points).
- If relevant, encourage the user to click the "Book a Discovery Call" button or pick a slot on the page.
- Never invent pricing numbers (state that custom rates depend on headcount and tier, discussed transparently on the 30-min discovery call).
- Only speak on behalf of G4ce BPO Services.
PROMPT;

// Build Gemini contents array with history
$contents = [];
$recentHistory = array_slice($history, -6);
foreach ($recentHistory as $msg) {
    $role = (($msg['role'] ?? '') === 'user') ? 'user' : 'model';
    $text = trim($msg['text'] ?? '');
    if (!empty($text)) {
        $contents[] = [
            'role' => $role,
            'parts' => [['text' => $text]]
        ];
    }
}
$contents[] = [
    'role' => 'user',
    'parts' => [['text' => $userMessage]]
];

$reply = null;

foreach ($candidateModels as $model) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $apiKey;
    $payload = json_encode([
        'systemInstruction' => [
            'parts' => [['text' => $systemPrompt]]
        ],
        'contents' => $contents,
        'generationConfig' => [
            'temperature'     => 0.4,
            'topK'            => 40,
            'topP'            => 0.9,
            'maxOutputTokens' => 2048,
        ]
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 12);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $result) {
        $data = json_decode($result, true);
        $candidateText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if (!empty($candidateText)) {
            $reply = $candidateText;
            break;
        }
    }
}

// If Gemini is unreachable or rate limited, provide intelligent local knowledge-base fallback
if (!$reply) {
    $lower = strtolower($userMessage);
    if (str_contains($lower, 'service') || str_contains($lower, 'offer') || str_contains($lower, 'what do you do')) {
        $reply = "**G4ce BPO Services** provides 5 core solutions:\n\n" .
                 "• **Customer Care (Inbound/Outbound):** 24/7 omnichannel CX & retention.\n" .
                 "• **Tech Support & Helpdesk:** L1 & L2 SLA-backed troubleshooting.\n" .
                 "• **Back-Office & Data:** Secure processing, verification & QA.\n" .
                 "• **Virtual Assistance:** Executive ops & calendar administration.\n" .
                 "• **Dedicated Teams:** Scalable staff augmentation (1 to 50+ agents).\n\n" .
                 "You can select your preferred service right on this page to book a call!";
    } elseif (str_contains($lower, 'discovery') || str_contains($lower, 'call') || str_contains($lower, 'how does it work') || str_contains($lower, 'consultation')) {
        $reply = "Our **30-Minute Discovery Call** is completely free with no commitment. A Senior Solutions Consultant will analyze your operational challenges, discuss required team size, and design a customized outsourcing roadmap. An instant Google Meet calendar invite is sent upon booking.";
    } elseif (str_contains($lower, 'nda') || str_contains($lower, 'confidential') || str_contains($lower, 'privacy')) {
        $reply = "Yes! **Data security and privacy are top priorities.** During Step 3 of our booking wizard, you can check the **'Request an NDA'** box, and our team will issue a signed non-disclosure agreement prior to your consultation.";
    } elseif (str_contains($lower, 'price') || str_contains($lower, 'cost') || str_contains($lower, 'rate')) {
        $reply = "G4ce offers customized, cost-effective staffing models based on your required team size and operational complexity. Our solutions typically save clients 40%–70% compared to onshore hiring. Book a 30-minute discovery call to receive a transparent proposal tailored to your needs.";
    } elseif (str_contains($lower, 'timezone') || str_contains($lower, 'hour') || str_contains($lower, 'schedule')) {
        $reply = "We provide **24/7 global coverage**! Our consultants and operational centers span multiple timezones including **Asia/Manila (PHT)** and **America/Los_Angeles (PST)**. You can easily pick your local timezone in Step 2 of the booking wizard.";
    } else {
        $reply = "Thank you for asking! G4ce BPO Services helps businesses scale efficiently with enterprise-grade customer support, technical helpdesk, back-office processing, and dedicated staffing. Would you like to schedule a free 30-minute discovery call with one of our Solutions Consultants?";
    }
}

echo json_encode([
    'success' => true,
    'reply'   => $reply,
]);

<?php

namespace App\Services;

class SimpleSmtpMailer
{
    private string $host;
    private int $port;
    private ?string $username;
    private ?string $password;
    private string $encryption;
    private string $fromEmail;
    private string $fromName;
    private int $timeout = 15;
    private array $logs = [];

    public function __construct(?array $config = null)
    {
        $env = $this->loadEnv();

        $this->host       = $config['host'] ?? $env['MAIL_HOST'] ?? 'smtp.gmail.com';
        $this->port       = (int) ($config['port'] ?? $env['MAIL_PORT'] ?? 587);
        $this->username   = $config['username'] ?? $env['MAIL_USERNAME'] ?? null;
        $this->password   = $config['password'] ?? $env['MAIL_PASSWORD'] ?? null;
        $this->encryption = strtolower($config['encryption'] ?? $env['MAIL_ENCRYPTION'] ?? 'tls');
        $this->fromEmail  = $config['from_email'] ?? $env['MAIL_FROM_ADDRESS'] ?? 'scholargrantgatid@gmail.com';
        $this->fromName   = $config['from_name'] ?? $env['MAIL_FROM_NAME'] ?? 'G4ce BPO Services';
    }

    /**
     * Send email via SMTP socket connection.
     *
     * @param string $to
     * @param string $subject
     * @param string $htmlBody
     * @param string|null $icsAttachment
     * @param string|null $icsFilename
     * @return array{success: bool, error: ?string}
     */
    public function send(
        string $to,
        string $subject,
        string $htmlBody,
        ?string $icsAttachment = null,
        ?string $icsFilename = 'meeting.ics'
    ): array {
        $this->logs = [];

        // Check if password or host is configured
        if (empty($this->host)) {
            return ['success' => false, 'error' => 'SMTP Host not configured.'];
        }

        $isSsl = ($this->encryption === 'ssl' || $this->port === 465);
        $prefix = $isSsl ? 'ssl://' : 'tcp://';
        $remote = $prefix . $this->host . ':' . $this->port;

        $context = stream_context_create([
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ]
        ]);

        $socket = @stream_socket_client($remote, $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT, $context);
        if (!$socket) {
            $this->logError("Connection failed to {$remote}: [{$errno}] {$errstr}");
            return ['success' => false, 'error' => "Could not connect to SMTP server {$this->host}: {$errstr}"];
        }

        stream_set_timeout($socket, $this->timeout);

        // Read server welcome
        $res = $this->readResponse($socket);
        if (substr($res, 0, 3) !== '220') {
            fclose($socket);
            return ['success' => false, 'error' => "Server initial error: {$res}"];
        }

        // Send EHLO
        $this->sendCommand($socket, "EHLO " . gethostname());
        $res = $this->readResponse($socket);

        // STARTTLS if TLS enabled and not SSL
        if (!$isSsl && ($this->encryption === 'tls' || $this->port === 587)) {
            $this->sendCommand($socket, "STARTTLS");
            $res = $this->readResponse($socket);
            if (substr($res, 0, 3) !== '220') {
                fclose($socket);
                return ['success' => false, 'error' => "STARTTLS failed: {$res}"];
            }

            // Enable TLS cryptography
            $cryptoMethod = STREAM_CRYPTO_METHOD_TLS_CLIENT;
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
                $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
            }
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT')) {
                $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
            }

            if (!@stream_socket_enable_crypto($socket, true, $cryptoMethod)) {
                fclose($socket);
                return ['success' => false, 'error' => "TLS encryption negotiation failed."];
            }

            // Resend EHLO after TLS handshake
            $this->sendCommand($socket, "EHLO " . gethostname());
            $this->readResponse($socket);
        }

        // Authenticate if credentials provided
        if (!empty($this->username) && !empty($this->password)) {
            $this->sendCommand($socket, "AUTH LOGIN");
            $res = $this->readResponse($socket);
            if (substr($res, 0, 3) !== '334') {
                fclose($socket);
                return ['success' => false, 'error' => "AUTH LOGIN rejected: {$res}"];
            }

            $this->sendCommand($socket, base64_encode($this->username));
            $res = $this->readResponse($socket);
            if (substr($res, 0, 3) !== '334') {
                fclose($socket);
                return ['success' => false, 'error' => "Username rejected: {$res}"];
            }

            $this->sendCommand($socket, base64_encode($this->password));
            $res = $this->readResponse($socket);
            if (substr($res, 0, 3) !== '235') {
                fclose($socket);
                return ['success' => false, 'error' => "Authentication failed. Check your email/app password. Server: {$res}"];
            }
        }

        // MAIL FROM
        $this->sendCommand($socket, "MAIL FROM: <{$this->fromEmail}>");
        $res = $this->readResponse($socket);
        if (substr($res, 0, 3) !== '250') {
            fclose($socket);
            return ['success' => false, 'error' => "MAIL FROM rejected: {$res}"];
        }

        // RCPT TO
        $this->sendCommand($socket, "RCPT TO: <{$to}>");
        $res = $this->readResponse($socket);
        if (substr($res, 0, 3) !== '250') {
            fclose($socket);
            return ['success' => false, 'error' => "RCPT TO rejected for {$to}: {$res}"];
        }

        // DATA
        $this->sendCommand($socket, "DATA");
        $res = $this->readResponse($socket);
        if (substr($res, 0, 3) !== '354') {
            fclose($socket);
            return ['success' => false, 'error' => "DATA rejected: {$res}"];
        }

        // Construct MIME Message
        $messageData = $this->buildMimeMessage($to, $subject, $htmlBody, $icsAttachment, $icsFilename);
        $this->sendCommand($socket, $messageData . "\r\n.");
        $res = $this->readResponse($socket);

        // QUIT
        $this->sendCommand($socket, "QUIT");
        fclose($socket);

        if (substr($res, 0, 3) === '250') {
            $this->logInfo("Email successfully delivered via SMTP to {$to}");
            return ['success' => true, 'error' => null];
        }

        return ['success' => false, 'error' => "Sending data failed: {$res}"];
    }

    private function buildMimeMessage(
        string $to,
        string $subject,
        string $htmlBody,
        ?string $icsAttachment,
        ?string $icsFilename
    ): string {
        $boundary = '=_g4ce_' . md5(uniqid((string)mt_rand(), true));
        $date = date('r');

        $headers = [];
        $headers[] = "Date: {$date}";
        $headers[] = "From: \"{$this->fromName}\" <{$this->fromEmail}>";
        $headers[] = "To: <{$to}>";
        $headers[] = "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=";
        $headers[] = "MIME-Version: 1.0";

        if ($icsAttachment) {
            $headers[] = "Content-Type: multipart/mixed; boundary=\"{$boundary}\"";

            $body = "--{$boundary}\r\n";
            $body .= "Content-Type: text/html; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= chunk_split(base64_encode($htmlBody)) . "\r\n";

            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: text/calendar; charset=UTF-8; method=REQUEST; name=\"{$icsFilename}\"\r\n";
            $body .= "Content-Disposition: attachment; filename=\"{$icsFilename}\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= chunk_split(base64_encode($icsAttachment)) . "\r\n";

            $body .= "--{$boundary}--";
        } else {
            $headers[] = "Content-Type: text/html; charset=UTF-8";
            $headers[] = "Content-Transfer-Encoding: base64";
            $body = chunk_split(base64_encode($htmlBody));
        }

        return implode("\r\n", $headers) . "\r\n\r\n" . $body;
    }

    private function sendCommand($socket, string $cmd): void
    {
        fwrite($socket, $cmd . "\r\n");
    }

    private function readResponse($socket): string
    {
        $response = '';
        while ($line = fgets($socket, 512)) {
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        return trim($response);
    }

    private function loadEnv(): array
    {
        $env = [];
        $path = __DIR__ . '/../../.env';
        if (file_exists($path)) {
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#')) continue;
                if (str_contains($line, '=')) {
                    [$k, $v] = explode('=', $line, 2);
                    $env[trim($k)] = trim($v, "\"' ");
                }
            }
        }
        return $env;
    }

    private function logInfo(string $msg): void
    {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        @file_put_contents($logDir . '/smtp.log', '[' . date('Y-m-d H:i:s') . '] [INFO] ' . $msg . "\n", FILE_APPEND);
    }

    private function logError(string $msg): void
    {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        @file_put_contents($logDir . '/smtp.log', '[' . date('Y-m-d H:i:s') . '] [ERROR] ' . $msg . "\n", FILE_APPEND);
    }
}

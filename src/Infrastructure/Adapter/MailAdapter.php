<?php

namespace DeinBrett\Infrastructure\Adapter;

class MailAdapter
{
    private const LOG_PATH = __DIR__ . '/../../../storage/logs/mail.log';

    public function __construct(
        private string $fromAddress,
        private string $fromName,
        private bool   $logOnly = false,
    ) {}

    public function send(string $to, string $subject, string $htmlBody, ?string $replyTo = null): bool
    {
        $clean = fn(string $s): string => str_replace(["\r", "\n"], '', $s);
        $to      = $clean($to);
        $subject = $clean($subject);
        $from    = $this->fromName ? "{$this->fromName} <{$this->fromAddress}>" : $this->fromAddress;

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$clean($from)}\r\n";
        $headers .= "Reply-To: " . $clean($replyTo ?? $this->fromAddress) . "\r\n";

        if ($this->logOnly) {
            $this->log($to, $subject, $htmlBody, $headers);
            return true;
        }

        $ok = @mail($to, $subject, $htmlBody, $headers);
        if (!$ok) {
            $this->log($to, $subject, $htmlBody, $headers, 'MAIL SEND FAILED');
        }
        return (bool) $ok;
    }

    private function log(string $to, string $subject, string $body, string $headers, string $prefix = 'MAIL LOGGED'): void
    {
        $dir = dirname(self::LOG_PATH);
        if (!is_dir($dir)) @mkdir($dir, 0755, true);
        $entry  = "--- {$prefix} " . date('c') . " ---\n";
        $entry .= "To: {$to}\nSubject: {$subject}\n{$headers}\n{$body}\n\n";
        @file_put_contents(self::LOG_PATH, $entry, FILE_APPEND);
    }
}

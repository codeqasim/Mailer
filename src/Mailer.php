<?php

namespace CodeQasim\Mailer;

class Mailer
{
    private $smtpServer;
    private $smtpPort;
    private $security;
    private $username;
    private $password;

    public function configure(array $config): void
    {
        $this->smtpServer = $config['server'];
        $this->smtpPort = $config['port'];
        $this->security = $config['security'];
        $this->username = $config['username'];
        $this->password = $config['password'];
    }

    public function sendEmail(array $emailDetails): void
    {
        // Create socket connection
        $socket = fsockopen($this->smtpServer, $this->smtpPort, $errno, $errstr, 30);
        if (!$socket) {
            throw new \Exception("Connection failed: $errstr ($errno)");
        }

        // Helper function to send SMTP commands
        $sendCommand = function ($command, $expectedCode) use ($socket) {
            fwrite($socket, $command . "\r\n");
            $response = "";
            do {
                $line = fgets($socket, 515);
                $response .= $line;
            } while (substr($line, 3, 1) === '-');

            $code = substr($response, 0, 3);
            if ($code != $expectedCode) {
                throw new \Exception("SMTP Error: Expected $expectedCode, got: $response");
            }

            return $response;
        };

        // Read server greeting
        fgets($socket, 515);

        // Start SMTP conversation
        $sendCommand("EHLO " . gethostname(), 250);
        
        // Start TLS if specified
        if ($this->security === 'tls') {
            $sendCommand("STARTTLS", 220);
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            $sendCommand("EHLO " . gethostname(), 250);
        }

        // Authenticate
        $sendCommand("AUTH LOGIN", 334);
        $sendCommand(base64_encode($this->username), 334);
        $sendCommand(base64_encode($this->password), 235);

        // Send email headers
        $from = $emailDetails['from'];
        $to = $emailDetails['to'];
        $subject = $emailDetails['subject'];
        $body = $this->processTemplate($emailDetails['template'], $emailDetails['variables']);
        $headers = [
            "From: {$from}",
            "To: {$to}",
            "Subject: {$subject}",
            "MIME-Version: 1.0",
            "Content-Type: text/html; charset=utf-8",
        ];

        // Send MAIL FROM, RCPT TO, and DATA
        $sendCommand("MAIL FROM:<{$from}>", 250);
        $sendCommand("RCPT TO:<{$to}>", 250);
        $sendCommand("DATA", 354);

        // Send email content
        fwrite($socket, implode("\r\n", $headers) . "\r\n\r\n" . $body . "\r\n.\r\n");
        $sendCommand("", 250);

        // Close connection
        $sendCommand("QUIT", 221);
        fclose($socket);
    }

    private function processTemplate($templatePath, $variables)
    {
        if (!file_exists($templatePath)) {
            throw new \Exception("Template file not found: {$templatePath}");
        }

        $template = file_get_contents($templatePath);
        foreach ($variables as $key => $value) {
            $template = str_replace("{" . $key . "}", htmlspecialchars($value), $template);
        }

        return $template;
    }
}

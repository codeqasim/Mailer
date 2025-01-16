<?php

require 'vendor/autoload.php';

use CodeQasim\Mailer\Mailer;

$mailer = new Mailer();

// Configure SMTP
$mailer->configure([
    'server' => 'smtp.gmail.com',
    'port' => 587,
    'security' => 'tls',
    'username' => 'your_email@gmail.com',
    'password' => 'your_password',
]);

// Send Email
try {
    $mailer->sendEmail([
        'from' => 'your_email@gmail.com',
        'to' => 'recipient@example.com',
        'subject' => 'Test Email',
        'template' => 'path/to/template.html',
        'variables' => [
            'name' => 'John Doe',
            'date' => date('Y-m-d'),
        ],
    ]);
    echo "Email sent successfully!";
} catch (Exception $e) {
    echo "Failed to send email: " . $e->getMessage();
}

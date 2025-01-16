
# CodeQasim Mailer

**CodeQasim Mailer** is a lightweight, professional PHP library for sending emails via SMTP. It supports templated emails with variable placeholders, attachments, and secure authentication (TLS/SSL). Built for simplicity and flexibility, this library is perfect for developers who need an efficient and customizable mailer solution.

---

## Features

- **SMTP Support**: Send emails using secure SMTP configurations.
- **Templated Emails**: Use `.html` templates with placeholders (e.g., `{name}`, `{date}`).
- **Secure Authentication**: Supports TLS and SSL encryption.
- **PSR-4 Autoloading**: Easy integration with Composer.
- **Customizable**: Flexible email headers, body, and configurations.

---

## Installation

Install the library using Composer:

```bash
composer require codeqasim/mailer
```

---

## Usage

### Configuration
Set up the mailer with your SMTP server details:

```php
require 'vendor/autoload.php';

use CodeQasim\Mailer\Mailer;

$mailer = new Mailer();

$mailer->configure([
    'server' => 'smtp.gmail.com',
    'port' => 587,
    'security' => 'tls', // 'tls' or 'ssl'
    'username' => 'your_email@gmail.com',
    'password' => 'your_email_password',
]);
```

### Sending an Email
Send an email using an HTML template and variable placeholders:

```php
$mailer->sendEmail([
    'from' => 'your_email@gmail.com',
    'to' => 'recipient@example.com',
    'subject' => 'Test Email',
    'template' => 'path/to/template.html', // Path to your template
    'variables' => [ // Variables for placeholders in the template
        'name' => 'John Doe',
        'date' => date('Y-m-d'),
    ],
]);
```

### Example HTML Template
Create a `.html` file for your email template:

```html
<!DOCTYPE html>
<html>
<head>
    <title>{subject}</title>
</head>
<body>
    <p>Dear {name},</p>
    <p>This is a test email sent on {date}.</p>
</body>
</html>
```

---

## License

This library is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

---

## Contributing

Contributions are welcome! Please open an issue or submit a pull request to contribute.

---

## Support

For support or questions, feel free to contact us at [compoxition@gmail](mailto:compoxition@gmail.com).

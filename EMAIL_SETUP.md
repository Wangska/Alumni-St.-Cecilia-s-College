# Email Notification Setup Guide

This guide will help you set up email notifications for the Alumni Management System using Mailtrap for testing.

## What is Mailtrap?

Mailtrap is an email testing tool that captures all outgoing emails in a test inbox instead of sending them to real recipients. This is perfect for development and testing.

## Setup Instructions

### Step 1: Create a Mailtrap Account

1. Go to [https://mailtrap.io/](https://mailtrap.io/)
2. Sign up for a free account (no credit card required)
3. Verify your email address

### Step 2: Get Your SMTP Credentials

1. Log in to your Mailtrap account
2. Go to "Email Testing" → "Inboxes"
3. Select your inbox (or create a new one)
4. Click on "SMTP Settings"
5. You'll see your credentials:
   - **Host**: `sandbox.smtp.mailtrap.io`
   - **Port**: `2525`
   - **Username**: (copy this)
   - **Password**: (copy this)

### Step 3: Install PHPMailer

Open your terminal/command prompt and run:

```bash
cd C:\xampp\htdocs\scratch
composer install
```

If you don't have Composer installed:
1. Download from [https://getcomposer.org/download/](https://getcomposer.org/download/)
2. Install it
3. Run the command above

### Step 4: Configure Email Settings

1. Open the file: `C:\xampp\htdocs\scratch\inc\mailer.php`
2. Find these lines near the top:

```php
const MAILTRAP_USERNAME = 'YOUR_MAILTRAP_USERNAME'; // Update with your Mailtrap username
const MAILTRAP_PASSWORD = 'YOUR_MAILTRAP_PASSWORD'; // Update with your Mailtrap password
```

3. Replace `YOUR_MAILTRAP_USERNAME` and `YOUR_MAILTRAP_PASSWORD` with your actual Mailtrap credentials from Step 2

Example:
```php
const MAILTRAP_USERNAME = '1a2b3c4d5e6f7g';
const MAILTRAP_PASSWORD = 'a1b2c3d4e5f6g7';
```

### Step 5: Test the Email System

#### Test Registration Email:
1. Go to your website homepage
2. Click "Register"
3. Fill out the registration form
4. Submit the form
5. Check your Mailtrap inbox - you should see the welcome email!

#### Test Verification Email:
1. Log in as Admin or Alumni Officer
2. Go to "Users" or "Verify Alumni" section
3. Approve a pending user
4. Check your Mailtrap inbox - you should see the approval email!

## Email Templates

The system includes two email templates:

### 1. Registration Email
- **Subject**: "Welcome to St. Cecilia's College Alumni Network!"
- **Sent when**: A new user registers
- **Content**: Welcome message, account details, and what to expect next

### 2. Verification/Approval Email
- **Subject**: "Your Alumni Account Has Been Approved!"
- **Sent when**: An admin or alumni officer approves a user account
- **Content**: Approval confirmation, login credentials, and available features

## Troubleshooting

### Emails not appearing in Mailtrap?

1. **Check credentials**: Make sure your Mailtrap username and password are correct in `inc/mailer.php`
2. **Check error logs**: Look at `C:\xampp\php\logs\php_error_log` for any error messages
3. **Verify Composer**: Make sure PHPMailer is installed by checking if `vendor/phpmailer` folder exists
4. **Test connection**: Try the SMTP test below

### Testing SMTP Connection

Create a test file `test_email.php` in your scratch folder:

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/mailer.php';

if (sendRegistrationEmail('test@example.com', 'Test User', 'testuser')) {
    echo "Email sent successfully! Check your Mailtrap inbox.";
} else {
    echo "Failed to send email. Check error logs.";
}
```

Access it at: `http://localhost/scratch/test_email.php`

### Common Issues:

1. **"Class 'PHPMailer\PHPMailer\PHPMailer' not found"**
   - Solution: Run `composer install` in the scratch folder

2. **"SMTP connect() failed"**
   - Solution: Check your Mailtrap credentials are correct

3. **Emails sent but not received**
   - Solution: Check the correct Mailtrap inbox (you might have multiple)

## Moving to Production

When you're ready to send real emails:

1. Update the SMTP settings in `inc/mailer.php` to use a real email service:
   - Gmail SMTP
   - SendGrid
   - Amazon SES
   - Or your hosting provider's SMTP

2. Update these constants:
```php
const MAILTRAP_HOST = 'smtp.gmail.com'; // or your SMTP host
const MAILTRAP_PORT = 587;
const MAILTRAP_USERNAME = 'your-email@gmail.com';
const MAILTRAP_PASSWORD = 'your-app-password';
const MAIL_FROM_EMAIL = 'noreply@yourdomain.com';
```

## Features

- ✅ Beautiful HTML email templates
- ✅ Plain text fallback for email clients that don't support HTML
- ✅ Branded emails with school colors and logo
- ✅ Professional email design
- ✅ Mobile-responsive layouts
- ✅ Automatic error handling (won't break registration/approval if email fails)

## Support

If you need help:
1. Check the error logs
2. Review this documentation
3. Test with the test script above
4. Contact your system administrator

---

**Note**: Mailtrap's free plan includes 500 emails per month, which is more than enough for testing!


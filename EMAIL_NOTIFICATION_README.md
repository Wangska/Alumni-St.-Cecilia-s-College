# 📧 Email Notification System

The Alumni Management System now includes automated email notifications for registration and account verification!

## 🎯 Features Added

### 1. **Registration Email** 
When a new user registers, they receive a welcome email containing:
- ✅ Confirmation of successful registration
- 📋 Their account details (username, email)
- ⏳ Information about account review process
- 📅 Timeline for verification

### 2. **Verification Email**
When an admin or alumni officer approves an account, the user receives:
- 🎉 Account approval notification
- 🔑 Login credentials reminder
- 🚀 Link to login page
- 📋 List of available features

## 🚀 Quick Start Guide

### Step 1: Install Dependencies

**Option A - With Composer (Recommended):**
```bash
cd C:\xampp\htdocs\scratch
composer install
```

**Option B - Manual Installation:**
See [INSTALL_COMPOSER.md](INSTALL_COMPOSER.md) for detailed instructions.

### Step 2: Configure Mailtrap

1. Create free account at [mailtrap.io](https://mailtrap.io)
2. Get your SMTP credentials
3. Edit `inc/mailer.php` and update:
   ```php
   const MAILTRAP_USERNAME = 'your-username-here';
   const MAILTRAP_PASSWORD = 'your-password-here';
   ```

### Step 3: Test the System

Visit: `http://localhost/scratch/test_email_system.php`

This test page will:
- ✅ Check if PHPMailer is installed
- ✅ Verify configuration
- ✅ Let you send a test email
- ✅ Provide troubleshooting tips

## 📁 Files Modified/Added

### New Files:
- `inc/mailer.php` - Email functions and configuration
- `composer.json` - PHPMailer dependency
- `test_email_system.php` - Test and verification page
- `EMAIL_SETUP.md` - Detailed setup instructions
- `INSTALL_COMPOSER.md` - Composer installation guide
- `EMAIL_NOTIFICATION_README.md` - This file

### Modified Files:
- `auth_register.php` - Added registration email
- `admin.php` - Added verification email on approval
- `alumni-officer.php` - Added verification email on approval

## 📧 Email Templates

Both emails feature:
- 🎨 Beautiful HTML design with school branding
- 📱 Mobile-responsive layout
- 🔤 Plain text fallback for compatibility
- 🎨 School colors (Red gradient theme)
- 📋 Professional formatting

## 🧪 Testing Workflow

### Test Registration Email:
1. Go to homepage
2. Click "Register"
3. Fill out the form completely
4. Submit
5. Check Mailtrap inbox → See welcome email!

### Test Verification Email:
1. Login as Admin or Alumni Officer
2. Go to Users/Verify Alumni section
3. Approve a pending account
4. Check Mailtrap inbox → See approval email!

## 🔧 Configuration Options

In `inc/mailer.php`, you can customize:

```php
// SMTP Settings
const MAILTRAP_HOST = 'sandbox.smtp.mailtrap.io';
const MAILTRAP_PORT = 2525;
const MAILTRAP_USERNAME = 'your-username';
const MAILTRAP_PASSWORD = 'your-password';

// Email Sender Info
const MAIL_FROM_EMAIL = 'noreply@stcecilia.edu.ph';
const MAIL_FROM_NAME = 'St. Cecilia\'s College Alumni System';
```

## 🐛 Troubleshooting

### Problem: "Class 'PHPMailer' not found"
**Solution:** PHPMailer not installed. Run `composer install` or see manual installation guide.

### Problem: Emails not appearing in Mailtrap
**Solution:** 
1. Check credentials in `inc/mailer.php`
2. Verify correct Mailtrap inbox
3. Check PHP error logs: `C:\xampp\php\logs\php_error_log`

### Problem: "SMTP connect() failed"
**Solution:** Invalid credentials. Double-check your Mailtrap username and password.

### Problem: Cannot install Composer
**Solution:** Follow manual installation in [INSTALL_COMPOSER.md](INSTALL_COMPOSER.md)

## 🔄 Error Handling

The system is designed to be fault-tolerant:
- ✅ If email sending fails, registration/approval still succeeds
- 📝 Errors are logged to PHP error log
- 🔒 System won't crash if email service is down

## 📊 Current Implementation

### When Registration Happens:
```
User Registers → Account Created → Email Sent → User Notified
                      ✅               📧            📬
```

### When Account is Approved:
```
Admin Approves → Status Updated → Email Sent → User Notified
      ✅              ✅             📧            📬
```

## 🚀 Production Deployment

When moving to production (sending real emails):

1. **Choose an email service:**
   - Gmail SMTP
   - SendGrid
   - Amazon SES
   - Your hosting provider's SMTP

2. **Update configuration in `inc/mailer.php`:**
```php
const MAILTRAP_HOST = 'smtp.gmail.com';  // Change this
const MAILTRAP_PORT = 587;
const MAILTRAP_USERNAME = 'your-real-email@gmail.com';
const MAILTRAP_PASSWORD = 'your-app-password';
const MAIL_FROM_EMAIL = 'noreply@yourdomain.com';
```

3. **Test thoroughly** before going live!

## 📚 Documentation

- **[EMAIL_SETUP.md](EMAIL_SETUP.md)** - Complete setup guide with screenshots
- **[INSTALL_COMPOSER.md](INSTALL_COMPOSER.md)** - Install Composer and PHPMailer
- **[test_email_system.php](test_email_system.php)** - Interactive testing tool

## ✨ Benefits

- 🎯 **Better User Experience** - Users know their registration status
- 📧 **Professional Communication** - Branded, beautiful emails
- 🔔 **Instant Notifications** - Real-time updates via email
- 🧪 **Safe Testing** - Mailtrap prevents accidental emails to real users
- 🛡️ **Reliable** - System continues working even if email fails

## 🎓 Features Summary

| Event | Email Sent | Template |
|-------|-----------|----------|
| User Registers | ✅ Yes | Welcome + Review Notice |
| Account Approved | ✅ Yes | Approval + Login Info |
| Account Rejected | ❌ No | (Future enhancement) |

## 💡 Future Enhancements

Possible additions:
- ✉️ Password reset emails
- 📅 Event reminder emails
- 📰 Newsletter emails
- 🎉 Birthday greetings
- 🔔 Announcement notifications

## 🤝 Support

If you need help:
1. Check [test_email_system.php](http://localhost/scratch/test_email_system.php)
2. Review [EMAIL_SETUP.md](EMAIL_SETUP.md)
3. Check PHP error logs
4. Verify Mailtrap credentials

---

**🎉 Congratulations!** Your Alumni Management System now has professional email notifications! 📧✨


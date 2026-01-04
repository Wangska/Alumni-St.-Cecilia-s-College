# 🔧 How to Fix Email Error: Invalid Credentials

## Problem Identified
Your email system is failing with error: **`535 5.7.0 Invalid credentials`**

This means the Mailtrap username and password in your configuration are **incorrect or incomplete**.

---

## Solution: Update Your Mailtrap Credentials

### Step 1: Get Your CORRECT Credentials from Mailtrap

1. **Open your browser** and go to: [https://mailtrap.io/inboxes](https://mailtrap.io/inboxes)

2. **Log in** to your Mailtrap account

3. **Click on your Inbox** - You should see a list of your inboxes (or create a new one if you don't have any)

4. Once inside an inbox, look for the **"SMTP Settings"** section or **"Integrations"** tab

5. You'll see credentials that look like this:

```
Host: sandbox.smtp.mailtrap.io
Port: 2525 or 587
Username: a1b2c3d4e5f6g7h8  ← Copy this ENTIRE string
Password: 1a2b3c4d5e6f7g8h  ← Copy this ENTIRE string
Auth: PLAIN, LOGIN and CRAM-MD5
TLS: Optional
```

**IMPORTANT:** Make sure you copy the **COMPLETE** username and password. They are usually:
- **Longer than 16 characters**
- Made of letters (a-f) and numbers (0-9)
- All lowercase or mixed case

### Step 2: Update Your Configuration File

1. **Open the file**: `C:\xampp\htdocs\scratch\inc\mailer.php`

2. **Find lines 11-12** which currently show:

```php
const MAILTRAP_USERNAME = '18ab2ddab4169bE'; // ← REPLACE THIS
const MAILTRAP_PASSWORD = '2068d476c349ab'; // ← REPLACE THIS
```

3. **Replace with your ACTUAL credentials** from Mailtrap:

```php
const MAILTRAP_USERNAME = 'paste_your_complete_username_here';
const MAILTRAP_PASSWORD = 'paste_your_complete_password_here';
```

**Example** (with fake credentials):
```php
const MAILTRAP_USERNAME = 'a1b2c3d4e5f6g7h8i9';
const MAILTRAP_PASSWORD = '9i8h7g6f5e4d3c2b1a';
```

4. **Save the file**

### Step 3: Test Again

After updating the credentials:

1. **Open your browser** and go to: `http://localhost/scratch/test_email_system.php`

2. **Fill in the test email form** with any email address (like `test@example.com`)

3. **Click "Send Test Email"**

4. You should now see: ✅ **Email sent successfully!**

5. **Check your Mailtrap inbox** at [https://mailtrap.io/inboxes](https://mailtrap.io/inboxes) - the test email should appear there!

---

## Alternative: Test from Command Line

You can also test directly from the terminal:

```bash
cd C:\xampp\htdocs\scratch
php quick_test_email.php
```

This will show you detailed debug information about the connection.

---

## Common Mistakes to Avoid

❌ **Don't** include any spaces before or after the credentials  
❌ **Don't** use quotes from another inbox (each inbox has different credentials)  
❌ **Don't** copy only part of the username/password  
❌ **Don't** use your Mailtrap login email/password (those are different!)  

✅ **Do** copy the complete SMTP username and password exactly as shown  
✅ **Do** make sure there are no extra spaces or line breaks  
✅ **Do** use credentials from the specific inbox you want to test with  

---

## Still Having Issues?

If you're still getting errors after updating credentials:

1. **Generate new credentials** in Mailtrap:
   - Go to your inbox
   - Click "SMTP Settings"
   - Look for "Reset credentials" or create a new inbox

2. **Make sure your Mailtrap account is active**:
   - Free accounts can expire if not used
   - Log in to verify your account is still active

3. **Check the debug output**: Run `php quick_test_email.php` and look for the exact error message

4. **Screenshot your Mailtrap SMTP settings page** (without showing the actual credentials) to verify you're looking at the right place

---

## Visual Guide

When you're in Mailtrap inbox, it should look something like this:

```
┌─────────────────────────────────────────┐
│  Mailtrap Inbox: My Project             │
├─────────────────────────────────────────┤
│  Messages  │  SMTP Settings  │  API     │  ← Click here
├─────────────────────────────────────────┤
│                                          │
│  SMTP Settings                           │
│  ─────────────                           │
│  Host: sandbox.smtp.mailtrap.io         │
│  Port: 2525                              │
│  Username: [long_string_here]           │  ← Copy this
│  Password: [long_string_here]           │  ← Copy this
│  Auth: PLAIN, LOGIN and CRAM-MD5        │
│                                          │
└─────────────────────────────────────────┘
```

---

## Need More Help?

Contact support or check:
- [Mailtrap Documentation](https://help.mailtrap.io/)
- Your PHP error logs: `C:\xampp\php\logs\php_error_log`







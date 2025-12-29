# Event Reminder System - Setup & Documentation

## Overview

The Event Reminder System automatically sends email notifications to event participants **1 day before an event**. This helps ensure participants don't forget about upcoming events they've registered for.

## Features

- ✅ Automatic email reminders sent 24 hours before events
- ✅ Beautiful HTML email templates with event details
- ✅ Tracks sent reminders to prevent duplicates
- ✅ Logs all activities for monitoring and debugging
- ✅ Manual test scripts for verification
- ✅ Handles multiple events and participants efficiently

---

## Installation Steps

### Step 1: Create Database Table

Run the SQL script to create the `event_reminders` table:

```sql
-- Execute this in your MySQL/phpMyAdmin:
SOURCE database/event_reminders_table.sql;

-- OR run this in phpMyAdmin SQL tab:
```

Open `database/event_reminders_table.sql` and copy/paste the contents into phpMyAdmin SQL tab, then execute.

**Verification:**
- Check that the `event_reminders` table now exists in your database
- It should have columns: `id`, `event_id`, `user_id`, `reminder_sent_date`, `email_status`, `error_message`

---

### Step 2: Verify Email Configuration

Make sure your email system is properly configured in `inc/mailer.php`:

```php
// Check these constants:
const MAILTRAP_HOST = 'sandbox.smtp.mailtrap.io';
const MAILTRAP_PORT = 2525;
const MAILTRAP_USERNAME = 'your-username'; // Update this
const MAILTRAP_PASSWORD = 'your-password'; // Update this
```

**For Production:** Replace Mailtrap credentials with real SMTP credentials (Gmail, SendGrid, etc.)

---

### Step 3: Test the System

#### Option A: View Events & Participants

1. Open your browser and visit:
   ```
   http://localhost/scratch/test_event_reminders.php
   ```

2. This will show:
   - Events scheduled for tomorrow
   - Participants registered for each event
   - Reminder status (sent/not sent)
   - Database status

#### Option B: Send Test Email

1. Edit `send_test_reminder.php` and update line 20:
   ```php
   $testEmail = 'your-email@example.com'; // Change to your email
   ```

2. Open your browser and visit:
   ```
   http://localhost/scratch/send_test_reminder.php
   ```

3. Check your email inbox (or Mailtrap inbox) for the test reminder

---

### Step 4: Setup Automated Reminders (Cron Job)

Choose the method based on your operating system:

#### For Windows (Task Scheduler)

1. Open **Task Scheduler** (Press Win+R, type `taskschd.msc`)

2. Click **Create Basic Task**

3. Configure:
   - **Name:** Send Event Reminders
   - **Trigger:** Daily at 9:00 AM
   - **Action:** Start a program
   - **Program/script:** `C:\xampp\php\php.exe`
   - **Arguments:** `C:\xampp\htdocs\scratch\cron\send_event_reminders.php`

4. Click **Finish**

#### For Linux/Mac (Crontab)

1. Open terminal and edit crontab:
   ```bash
   crontab -e
   ```

2. Add this line (runs daily at 9:00 AM):
   ```
   0 9 * * * /usr/bin/php /path/to/scratch/cron/send_event_reminders.php
   ```

3. Save and exit

#### Manual Testing (Before Setting Up Cron)

You can manually run the cron script to test:

```bash
# Via command line:
cd C:\xampp\htdocs\scratch
php cron\send_event_reminders.php

# OR via browser:
# Note: Not recommended for production, but useful for testing
```

---

## How It Works

### System Flow

```
┌─────────────────────────────────────────────────────────┐
│  1. Cron Job Runs Daily (9:00 AM)                       │
└───────────────────┬─────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────┐
│  2. Check Database for Events Tomorrow                  │
│     SELECT * FROM events                                │
│     WHERE schedule BETWEEN tomorrow_start AND tomorrow_end│
└───────────────────┬─────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────┐
│  3. For Each Event, Get Participants                    │
│     SELECT users FROM event_commits                     │
│     WHERE event_id = ?                                  │
└───────────────────┬─────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────┐
│  4. Check if Reminder Already Sent                      │
│     SELECT * FROM event_reminders                       │
│     WHERE event_id = ? AND user_id = ?                  │
└───────────────────┬─────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────┐
│  5. Send Email Reminder                                 │
│     - Beautiful HTML template                           │
│     - Event details (title, date, description)          │
│     - Personalized to participant                       │
└───────────────────┬─────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────┐
│  6. Record in Database                                  │
│     INSERT INTO event_reminders                         │
│     (event_id, user_id, email_status)                   │
└─────────────────────────────────────────────────────────┘
```

### Database Schema

**event_reminders table:**
| Column | Type | Description |
|--------|------|-------------|
| id | int(11) | Primary key |
| event_id | int(11) | Foreign key to events table |
| user_id | int(11) | Foreign key to users table |
| reminder_sent_date | datetime | When the reminder was sent |
| email_status | enum('sent','failed') | Status of email delivery |
| error_message | text | Error details if failed |

---

## Email Template Preview

The reminder email includes:

- 🎨 Beautiful HTML design matching your alumni system branding
- ⏰ Clear "Event Tomorrow" notification
- 📅 Event title, date, and time
- 📝 Event description (first 300 characters)
- ⚠️ Important reminders checklist
- 🔗 Link to view full event details
- 🏫 St. Cecilia's College branding and logo

---

## Monitoring & Logs

### View Logs

Check the log file to see when reminders were sent:

```bash
# Location:
logs/event_reminders.log

# Example log entry:
[2025-12-22 09:00:01] Starting Event Reminder Cron Job
[2025-12-22 09:00:02] Found 2 event(s) scheduled for tomorrow
[2025-12-22 09:00:03] Processing event #36: "Tree Planting Activity"
[2025-12-22 09:00:04] ✓ Reminder sent successfully to Joshua Espanillo
[2025-12-22 09:00:05] Summary: Reminders sent: 5, Failed: 0
```

### Check Database

Query the database to see all sent reminders:

```sql
-- View all sent reminders
SELECT 
    er.*,
    e.title as event_title,
    u.name as participant_name,
    ab.email as participant_email
FROM event_reminders er
JOIN events e ON er.event_id = e.id
JOIN users u ON er.user_id = u.id
JOIN alumnus_bio ab ON u.alumnus_id = ab.id
ORDER BY er.reminder_sent_date DESC;

-- Count reminders by status
SELECT email_status, COUNT(*) as count
FROM event_reminders
GROUP BY email_status;
```

---

## Troubleshooting

### Problem: No emails being sent

**Solutions:**
1. Check email configuration in `inc/mailer.php`
2. Verify SMTP credentials are correct
3. Check `logs/event_reminders_error.log` for errors
4. Test with `send_test_reminder.php` first

### Problem: Duplicate reminders sent

**Solution:**
- The system automatically prevents duplicates using the UNIQUE KEY on (event_id, user_id)
- If duplicates occur, check if the cron job is running multiple times

### Problem: Cron job not running

**Solutions:**

**Windows:**
1. Open Task Scheduler and check if task exists
2. Verify the PHP path is correct: `C:\xampp\php\php.exe`
3. Check task history for errors

**Linux/Mac:**
1. Check crontab: `crontab -l`
2. Check cron logs: `grep CRON /var/log/syslog`
3. Verify PHP path: `which php`

### Problem: Participants not receiving emails

**Check:**
1. Participants must have a valid email in `alumnus_bio` table
2. Email field must not be NULL or empty
3. User must be registered in `event_commits` table
4. Check spam/junk folder

---

## Customization

### Change Reminder Timing

To send reminders at a different time (e.g., 2 days before):

Edit `cron/send_event_reminders.php` line 53-54:

```php
// Current: 1 day before
$tomorrowStart = date('Y-m-d 00:00:00', strtotime('+1 day'));
$tomorrowEnd = date('Y-m-d 23:59:59', strtotime('+1 day'));

// Change to: 2 days before
$tomorrowStart = date('Y-m-d 00:00:00', strtotime('+2 days'));
$tomorrowEnd = date('Y-m-d 23:59:59', strtotime('+2 days'));
```

### Customize Email Template

Edit the email template in `inc/mailer.php`, function `sendEventReminderEmail()`:

- Change colors, fonts, layout
- Add additional information
- Modify button URLs
- Adjust branding

---

## Testing Checklist

Before deploying to production:

- [ ] Database table created successfully
- [ ] Email configuration verified
- [ ] Test email sent successfully
- [ ] Manual cron script executed without errors
- [ ] Cron job/task scheduler configured
- [ ] Logs directory exists and is writable
- [ ] Test with a real event scheduled for tomorrow
- [ ] Verify participants receive emails
- [ ] Check that duplicates are prevented
- [ ] Review log files for errors

---

## Support

For issues or questions:

1. Check the logs: `logs/event_reminders.log`
2. Run the test script: `test_event_reminders.php`
3. Verify database structure matches the schema
4. Ensure Composer dependencies are installed: `composer install`

---

## Files Reference

| File | Purpose |
|------|---------|
| `database/event_reminders_table.sql` | Database schema |
| `inc/mailer.php` | Email functions (including `sendEventReminderEmail()`) |
| `cron/send_event_reminders.php` | Main cron job script |
| `test_event_reminders.php` | View events and participants |
| `send_test_reminder.php` | Send a single test email |
| `logs/event_reminders.log` | Activity log |
| `logs/event_reminders_error.log` | Error log |

---

## License

Part of St. Cecilia's College Alumni Management System

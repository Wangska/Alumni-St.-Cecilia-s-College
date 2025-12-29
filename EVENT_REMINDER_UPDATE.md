# Event Reminder System - Update to Dual Reminders

## What Changed?

The event reminder system has been **upgraded** to send **TWO reminders** per event instead of one:

### New Reminder Schedule:
- ✅ **First Reminder:** 2 days before the event
- ✅ **Second Reminder:** 1 day before the event (original)

---

## Why This Is Better

1. **Better Advance Notice**: Participants get an earlier heads-up (2 days before)
2. **Reduced No-Shows**: Second reminder (1 day before) catches people who forgot
3. **Professional**: Mimics how major event platforms handle reminders
4. **Flexible**: Easy to add more reminder intervals in the future

---

## Setup Instructions

### If You Already Created the Database Table:

Run this migration script to add the new `reminder_type` field:

```sql
-- Open phpMyAdmin and run this SQL:
-- File: database/update_event_reminders_add_type.sql

ALTER TABLE `event_reminders` 
ADD COLUMN `reminder_type` enum('2_days','1_day') NOT NULL DEFAULT '1_day' 
COMMENT 'Type of reminder: 2 days or 1 day before event' 
AFTER `user_id`;

ALTER TABLE `event_reminders` DROP INDEX `unique_reminder`;

ALTER TABLE `event_reminders` 
ADD UNIQUE KEY `unique_reminder` (`event_id`, `user_id`, `reminder_type`);

ALTER TABLE `event_reminders` 
ADD KEY `reminder_type` (`reminder_type`);
```

### If You Haven't Created the Table Yet:

Just run the updated SQL file:

```sql
-- File: database/event_reminders_table.sql
-- This now includes the reminder_type field
```

---

## How It Works Now

### Daily Cron Job (9:00 AM)

The cron job now checks for **TWO intervals**:

1. **Events in 2 days** → Sends "Event in 2 Days" reminder
2. **Events tomorrow** → Sends "Event Tomorrow" reminder

### Example Timeline:

| Day | Event Date | Reminder Sent |
|-----|-----------|---------------|
| Monday 9 AM | Event on Wednesday | ✅ **2-day reminder sent** |
| Tuesday 9 AM | Event on Wednesday | ✅ **1-day reminder sent** |
| Wednesday | Event happens | 🎉 |

---

## Email Content Differences

### 2-Day Reminder Email:
- **Subject:** "Reminder: Event in 2 Days - [Event Title]"
- **Content:** "Your event is happening in **2 days**!"
- **Tone:** Informative, gives advance notice

### 1-Day Reminder Email:
- **Subject:** "Reminder: Event Tomorrow - [Event Title]"
- **Content:** "Your event is happening **tomorrow**!"
- **Tone:** More urgent, last-minute reminder

---

## Testing the New System

### 1. Test Script

Visit the test page to see upcoming events and reminder status:

```
http://localhost/scratch/test_event_reminders.php
```

This now shows:
- Events scheduled in 2 days
- Events scheduled tomorrow
- Status of both 2-day and 1-day reminders for each participant

### 2. Send Test Emails

Edit and run the test email script:

```php
// File: send_test_reminder.php

// Test 2-day reminder:
$reminderType = 2;

// Test 1-day reminder:
$reminderType = 1;
```

### 3. Manual Cron Run

Run the cron job manually to see it in action:

```bash
cd C:\xampp\htdocs\scratch
php cron\send_event_reminders.php
```

Check the log output - it will show:
- Events found for 2-day reminders
- Events found for 1-day reminders
- Total reminders sent for each type

---

## Database Changes

### New `reminder_type` Column

The `event_reminders` table now tracks **which type** of reminder was sent:

| Column | Type | Values | Description |
|--------|------|--------|-------------|
| reminder_type | enum | '2_days', '1_day' | Type of reminder sent |

### Updated Unique Constraint

**Old:** `UNIQUE (event_id, user_id)` - Only one reminder per user per event

**New:** `UNIQUE (event_id, user_id, reminder_type)` - Allows multiple reminders per user per event

This means:
- ✅ Same user can receive both 2-day and 1-day reminders
- ✅ Won't send duplicate 2-day reminders
- ✅ Won't send duplicate 1-day reminders

---

## Monitoring & Logs

### Check Log File

The log now shows both reminder types:

```
[2025-12-22 09:00:01] Starting Event Reminder Cron Job
[2025-12-22 09:00:02] Checking for events scheduled in 2 days
[2025-12-22 09:00:03]   Found 1 event(s) for 2 days reminder
[2025-12-22 09:00:04]   Processing event #36: "Tree Planting" (2 days reminder)
[2025-12-22 09:00:05]     - Sending 2 days reminder to John Doe...
[2025-12-22 09:00:06]     - ✓ 2 days reminder sent successfully
[2025-12-22 09:00:07] Checking for events scheduled in 1 day
[2025-12-22 09:00:08]   Found 1 event(s) for 1 day reminder
...
```

### Query Database

Check reminder statistics:

```sql
-- Count reminders by type
SELECT 
    reminder_type,
    COUNT(*) as count,
    email_status
FROM event_reminders
GROUP BY reminder_type, email_status;

-- View all reminders for a specific event
SELECT 
    u.name as participant,
    ab.email,
    er.reminder_type,
    er.reminder_sent_date,
    er.email_status
FROM event_reminders er
JOIN users u ON er.user_id = u.id
JOIN alumnus_bio ab ON u.alumnus_id = ab.id
WHERE er.event_id = 36
ORDER BY er.reminder_sent_date DESC;
```

---

## Customization Options

### Want Different Timing?

Edit `cron/send_event_reminders.php` line 70:

```php
// Current: 2 days and 1 day before
$reminderIntervals = [
    ['days' => 2, 'type' => '2_days', 'label' => '2 days'],
    ['days' => 1, 'type' => '1_day', 'label' => '1 day (tomorrow)']
];

// Want 3 days instead? Change to:
$reminderIntervals = [
    ['days' => 3, 'type' => '3_days', 'label' => '3 days'],
    ['days' => 1, 'type' => '1_day', 'label' => '1 day (tomorrow)']
];
```

**Note:** If you add new reminder types, update the database enum:

```sql
ALTER TABLE event_reminders 
MODIFY reminder_type enum('3_days','2_days','1_day') NOT NULL;
```

---

## Files Updated

| File | Changes |
|------|---------|
| `database/event_reminders_table.sql` | Added `reminder_type` column |
| `database/update_event_reminders_add_type.sql` | Migration script (NEW) |
| `inc/mailer.php` | Added `$daysBefore` parameter, customized email content |
| `cron/send_event_reminders.php` | Now loops through multiple reminder intervals |
| `test_event_reminders.php` | Shows both 2-day and 1-day reminder status |
| `send_test_reminder.php` | Can test both reminder types |

---

## Troubleshooting

### Problem: Only seeing 1-day reminders in database

**Solution:** Run the migration script to add `reminder_type` column

### Problem: Getting duplicate key errors

**Solution:** The unique constraint includes `reminder_type` now, so this shouldn't happen. Check your database schema.

### Problem: Email says wrong number of days

**Solution:** Check that you're passing the correct `$daysBefore` parameter in `sendEventReminderEmail()`

---

## Verification Checklist

After updating, verify everything works:

- [ ] Database migration completed (reminder_type column added)
- [ ] Run test script: `test_event_reminders.php` shows both reminder types
- [ ] Send test 2-day reminder email successfully
- [ ] Send test 1-day reminder email successfully
- [ ] Manual cron run completes without errors
- [ ] Log file shows both 2-day and 1-day reminders being processed
- [ ] Database has records with both reminder_type values

---

## Summary

✅ **What You Get:**
- 2 reminders per event instead of 1
- Better participant engagement
- More professional event management
- Same cron job (still runs daily at 9 AM)

✅ **What Stayed the Same:**
- Cron job schedule (daily at 9 AM)
- Email templates (with minor text changes)
- Setup process (Windows Task Scheduler or crontab)
- Mailtrap/SMTP configuration

🎉 **Your participants will now receive better advance notice of upcoming events!**

# Upgrade to 3-Day Reminder System

## What's New?

Your event reminder system has been upgraded to send **THREE reminders** per event:

- ✅ **First Reminder:** 3 days before the event (NEW!)
- ✅ **Second Reminder:** 2 days before the event
- ✅ **Third Reminder:** 1 day before the event

---

## Quick Setup (3 Steps)

### Step 1: Update Your Database

Open phpMyAdmin and run this SQL script:

```sql
-- File: database/migrate_add_3day_reminders.sql
ALTER TABLE `event_reminders` 
MODIFY COLUMN `reminder_type` enum('3_days','2_days','1_day') NOT NULL DEFAULT '1_day' 
COMMENT 'Type of reminder: 3 days, 2 days, or 1 day before event';
```

**OR** just run the migration file directly in phpMyAdmin:
- Navigate to: http://localhost/phpmyadmin
- Select your database
- Click "Import" tab
- Choose file: `database/migrate_add_3day_reminders.sql`
- Click "Go"

### Step 2: Test the System

Run the test script to verify everything works:

```bash
cd C:\xampp\htdocs\scratch
php test_event_reminders.php
```

This will show:
- Events scheduled in 3 days
- Events scheduled in 2 days  
- Events scheduled tomorrow
- Reminder status for each participant

### Step 3: Test Send Reminders

Manually run the cron job to test sending:

```bash
php cron\send_event_reminders.php
```

Check the log file:
```
logs/event_reminders.log
```

And check your Mailtrap inbox for the test emails!

---

## Example Timeline

For an event on **Friday, January 3rd**:

| Day | Date | Reminder Sent |
|-----|------|---------------|
| Tuesday | Dec 31 | ✅ **3-day reminder sent** |
| Wednesday | Jan 1 | ✅ **2-day reminder sent** |
| Thursday | Jan 2 | ✅ **1-day reminder sent** |
| Friday | Jan 3 | 🎉 **Event happens** |

---

## Why This Is Better

1. **Even Earlier Notice**: Participants get advance warning 3 days ahead
2. **More Preparation Time**: Better for events requiring travel or preparation
3. **Higher Attendance**: Multiple reminders reduce no-shows
4. **Professional**: Matches how major event platforms work

---

## Troubleshooting

### Problem: Migration fails with error

**Solution:** Your database might already have the correct structure. Check by running:

```sql
DESCRIBE event_reminders;
```

If `reminder_type` already shows `'3_days','2_days','1_day'`, you're good to go!

### Problem: Emails not sending

**Possible causes:**

1. **Cron job not running** - Run manually first to test:
   ```bash
   php cron\send_event_reminders.php
   ```

2. **No future events** - Create a test event for 3 days from now

3. **No participants** - Register at least one participant for the test event

4. **Mailtrap credentials wrong** - Check `inc/config.php`

### Problem: Old cron job still running

If your scheduled task is still running the old version:

**Windows Task Scheduler:**
1. Press Win+R, type: `taskschd.msc`
2. Find "Send Event Reminders" task
3. Right-click → Delete
4. Recreate it following the setup guide

**Linux/Mac crontab:**
1. Run: `crontab -e`
2. Verify the path points to the correct file
3. Save and exit

---

## Testing Tips

### Create a Test Event

1. Login to admin panel
2. Create new event scheduled for 3 days from now
3. Register yourself as a participant
4. Run: `php cron\send_event_reminders.php`
5. Check Mailtrap for the 3-day reminder email

### Check Logs

Always check the log file after running the cron:

```bash
# View the log
cat logs/event_reminders.log

# Or in Windows:
type logs\event_reminders.log
```

Look for:
- Number of events found for each interval (3-day, 2-day, 1-day)
- Number of reminders sent
- Any errors or failures

---

## Questions?

- **Q: Will participants get 3 reminders for every event?**  
  A: Yes! Each participant gets 3 reminders: 3 days before, 2 days before, and 1 day before.

- **Q: What about events I already created?**  
  A: They will automatically get 3-day reminders if they're scheduled at least 3 days in the future.

- **Q: Can I customize the number of days?**  
  A: Yes! Edit `cron/send_event_reminders.php` and modify the `$reminderIntervals` array.

- **Q: What if I only want 3-day and 1-day reminders (skip 2-day)?**  
  A: Remove the 2-day entry from the `$reminderIntervals` array in the cron script.

---

## Next Steps

✅ Run the migration SQL  
✅ Test with `test_event_reminders.php`  
✅ Create a test event for 3 days from now  
✅ Run the cron manually: `php cron\send_event_reminders.php`  
✅ Check Mailtrap for the email  
✅ Verify the log file shows success  

**You're all set!** The system will now automatically send 3 reminders for each upcoming event.


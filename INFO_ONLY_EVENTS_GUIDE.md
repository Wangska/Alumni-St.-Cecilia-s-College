# Information-Only Events Feature

## Overview

You can now create **Information-Only Events** that appear on the calendar but don't require participant registration!

Perfect for:
- **School Holidays** (Christmas Break, Summer Break, etc.)
- **Important Dates** (Foundation Day, Graduation, Enrollment Periods)
- **General Announcements** (School closure, Schedule changes)
- **Reminders** (Exam schedules, Deadline reminders)

---

## Setup (One-Time)

### Step 1: Run the Migration

Open **phpMyAdmin** and run this SQL:

```sql
-- Add the column
ALTER TABLE `events` 
ADD COLUMN `allow_registration` TINYINT(1) NOT NULL DEFAULT 1 
COMMENT '1 = Allow registration, 0 = Information only (no registration)' 
AFTER `participant_limit`;
```

**OR** import the file:
- File: `database/add_info_only_events.sql`
- Click Import in phpMyAdmin
- Select the file and click "Go"

---

## How to Create Information-Only Events

### For Alumni Officer:

1. **Login** as Alumni Officer

2. Go to **"Events & Activities"** in sidebar

3. Click **"Create Event"**

4. Fill out the form:
   - Event Title: e.g., "Christmas Break 2025"
   - Event Schedule: Pick the date
   - Event Description: Explain the event

5. **Check the box**: ✅ **"Information Only Event (No Registration Required)"**

6. Click **"Create Event"**

### Result:
- Event appears on the calendar
- Shows as **blue** (information-only)
- **No registration button** displayed
- Alumni can see it but can't register

---

## Event Types & Colors

| Type | Color | Description |
|------|-------|-------------|
| **Information Only** | 🔵 Blue | No registration needed |
| **Registration Open** | 🟢 Green | Available spots |
| **Event Full** | 🔴 Red | No spots left |
| **Past Event** | ⚫ Gray | Already happened |

---

## Examples

### ✅ Information-Only Events:
- Christmas Break: Dec 20 - Jan 5
- Foundation Day Celebration: March 15
- Summer Enrollment Period: May 1-31
- Graduation Day: April 12
- Midterm Exams Week: Oct 15-19
- School Maintenance Day (No Classes): Nov 10

### ❌ Regular Events (with Registration):
- Alumni Homecoming 2026
- Career Workshop
- Sports Fest
- Tree Planting Activity
- Alumni General Assembly

---

## Features

### What Alumni See:

#### For Information-Only Events:
- ✅ Event appears on calendar (blue color)
- ✅ Can view event details
- ✅ Shows "Information Only" badge
- ❌ **No registration button**
- ❌ **No participant count**

#### For Regular Events:
- ✅ Event appears on calendar (green/red color)
- ✅ Can view event details
- ✅ Shows participant count and limit
- ✅ **"Join Event" button** displayed
- ✅ Can register/leave event

---

## How It Looks

### On Calendar:
```
🔵 Blue Badge = Information Only
🟢 Green Badge = Registration Open
🔴 Red Badge = Event Full
```

### On Event Page:
```
┌─────────────────────────────────────┐
│ 📅 Christmas Break 2025              │
│                                     │
│ 🔵 Information Only                  │
│                                     │
│ ℹ️ This is an information-only      │
│    event. No registration required. │
│                                     │
│ School will be closed from...       │
└─────────────────────────────────────┘
```

---

## Quick Reference

### Creating Events:

| Want to... | Check the box? | Result |
|------------|---------------|--------|
| Let alumni register | ❌ Unchecked | Regular event with registration |
| Just inform alumni | ✅ Checked | Info-only, no registration |

### When to Use Info-Only:

✅ **Use Info-Only For:**
- Holidays and breaks
- Important school dates
- General announcements
- Deadlines and reminders
- School closures

❌ **Don't Use Info-Only For:**
- Alumni gatherings → Use regular event
- Workshops/seminars → Use regular event
- Activities needing headcount → Use regular event
- Events with limited capacity → Use regular event

---

## Troubleshooting

### Q: I created an info-only event but it still shows registration button?

**A:** You need to run the migration SQL first. The column `allow_registration` must exist in your database.

### Q: How do I convert a regular event to info-only?

**A:** Edit the event and check the "Information Only" checkbox, then save.

### Q: Can I set participant limits for info-only events?

**A:** No. When you check "Information Only", the participant limit field is hidden and ignored.

### Q: Will reminders be sent for info-only events?

**A:** Yes! The automatic reminder system still works for info-only events. Participants won't receive reminders though since there are no participants for info-only events.

### Q: Can I convert an info-only event back to regular?

**A:** Yes, just edit the event and uncheck the "Information Only" checkbox.

---

## Database Details

### New Column Added:
```sql
allow_registration TINYINT(1) NOT NULL DEFAULT 1
```

Values:
- `1` = Regular event (allow registration) ← Default
- `0` = Information-only event (no registration)

---

## Summary

✅ **What You Can Do Now:**
- Create events that don't need registration
- Mark important dates on the calendar
- Inform alumni about school events
- Reduce clutter from non-registration events

✅ **Benefits:**
- Cleaner event system
- Alumni know what requires registration
- Easy to add holidays and important dates
- Better calendar organization

---

## Need Help?

1. Make sure you ran the migration SQL
2. Check that XAMPP is running
3. Test with a simple event first
4. View the calendar to see color differences

**You're all set!** Start adding information-only events today! 🎉


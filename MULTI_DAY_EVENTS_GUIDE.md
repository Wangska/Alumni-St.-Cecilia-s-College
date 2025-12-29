# Multi-Day Events Feature

## Overview

Events can now span **multiple days**! Perfect for:
- 📚 **Multi-day Seminars** (3-Day Leadership Workshop)
- 🏃 **Week-Long Events** (Sports Week, Science Fair Week)
- 🌳 **Extended Activities** (5-Day Tree Planting Campaign)
- 🎄 **Holiday Periods** (Christmas Break: Dec 20 - Jan 5)

---

## What's New?

### Before:
- Events only had a single date/time
- Multi-day events had to be created separately
- Confusing for participants

### After:
- ✅ **Start Date & Time** - When event begins
- ✅ **End Date & Time** - When event ends  
- ✅ **Automatic Duration** - Shows "3 days", "5 days", etc.
- ✅ **Calendar Spans** - Multi-day events span across calendar

---

## Setup (Already Done!)

The database migration has already been applied:
```sql
✅ Column added: end_date (DATETIME)
✅ Existing events updated with end_date
```

---

## How to Create Multi-Day Events

### For Alumni Officer:

1. **Login** as Alumni Officer

2. Go to **"Events & Activities"** in sidebar

3. Click **"Create Event"**

4. Fill out the form:
   - **Event Title**: e.g., "Leadership Workshop 2026"
   - **Event Start Date & Time**: Pick when it begins
   - **Event End Date & Time**: Pick when it ends
   - **Event Description**: Full details

5. Click **"Create Event"**

### Result:
- Event shows duration (e.g., "3 days")
- Calendar displays event across all days
- Participants see clear start and end

---

## Examples

### Single-Day Events:
Leave end date empty or same as start date:
- **Alumni Homecoming** (Jan 15, 2026 @ 9:00 AM - 5:00 PM)
  - Start: Jan 15, 2026 @ 9:00 AM
  - End: *Leave empty* → Auto-sets to same day

### Multi-Day Events:
Set different end date:
- **Leadership Workshop** (3 days)
  - Start: March 15, 2026 @ 8:00 AM
  - End: March 17, 2026 @ 5:00 PM
  - Duration: **3 days**

- **Sports Week**
  - Start: April 1, 2026 @ 8:00 AM
  - End: April 7, 2026 @ 6:00 PM
  - Duration: **7 days**

- **Christmas Break**
  - Start: Dec 20, 2025 @ 12:00 AM
  - End: Jan 5, 2026 @ 11:59 PM
  - Duration: **17 days**

---

## How It Looks

### On Event List:

#### Single-Day Event:
```
┌────────────────────────────────────────┐
│ 📅 Alumni Homecoming 2026              │
│                                        │
│ 📆 Jan 15, 2026 at 9:00 AM            │
│                                        │
│ Join us for a day of reunion...       │
└────────────────────────────────────────┘
```

#### Multi-Day Event:
```
┌────────────────────────────────────────┐
│ 📅 Leadership Workshop                 │
│                                        │
│ 📆 Mar 15, 2026 - Mar 17, 2026        │
│    🕐 3 days                           │
│                                        │
│ A comprehensive 3-day workshop...      │
└────────────────────────────────────────┘
```

### On Calendar:
- **Single-day events**: Shows on one day
- **Multi-day events**: Spans across multiple days
- **Color-coded**: Same color system applies

---

## Features

### Automatic Duration Display:
- **1 day** → Shows as single-day event (normal display)
- **2+ days** → Shows "2 days", "3 days", "7 days" badge

### Smart Date Display:
- **Same day**: "Jan 15, 2026 at 9:00 AM"
- **Different days**: "Jan 15, 2026 - Jan 17, 2026" + duration badge

### Calendar Integration:
- Events span the full date range on calendar
- Click any day to see event details
- Blue for info-only, green for registration open, red for full

### Validation:
- ✅ End date cannot be before start date
- ✅ Auto-fills end date if left empty
- ✅ Shows error if dates are invalid

---

## Creating Different Event Types

### 1. Single-Day Event (Workshop)
```
Title: Digital Marketing Workshop
Start: Jan 20, 2026 @ 9:00 AM
End: [Leave empty] or Jan 20, 2026 @ 5:00 PM
Result: Shows as single-day event
```

### 2. Multi-Day Event (Conference)
```
Title: Alumni Conference 2026
Start: March 10, 2026 @ 8:00 AM
End: March 12, 2026 @ 5:00 PM
Result: Shows "3 days" badge
```

### 3. Week-Long Event
```
Title: Entrepreneurship Week
Start: April 1, 2026 @ 8:00 AM
End: April 7, 2026 @ 6:00 PM
Result: Shows "7 days" badge
```

### 4. Holiday Period (Info-Only)
```
Title: Summer Break 2026
Start: May 1, 2026 @ 12:00 AM
End: May 31, 2026 @ 11:59 PM
☑️ Information Only Event
Result: Shows "31 days" badge, blue color, no registration
```

---

## Use Cases

### ✅ Perfect For:

**Educational Events:**
- Multi-day training programs
- Certificate courses (5-day, 10-day)
- Workshop series

**School Events:**
- Sports week
- Science fair week
- Cultural festival (3 days)
- Exam periods

**Campaigns:**
- Tree planting drive (multiple days)
- Blood donation campaign
- Community outreach week

**Holidays & Breaks:**
- Christmas vacation
- Summer break
- Mid-term break
- Holiday periods

### ❌ Not Needed For:
- Single meetings
- One-time gatherings
- Quick events (2-3 hours)

---

## Tips & Best Practices

### 1. Set Realistic Times
```
✅ Good:
   Start: 8:00 AM
   End: 5:00 PM (same or next days)

❌ Avoid:
   Start: 11:59 PM
   End: 12:01 AM (confusing)
```

### 2. Use Info-Only for Long Periods
```
Christmas Break (17 days)
→ Check "Information Only"
→ No registration needed
```

### 3. Clear Descriptions
```
"3-Day Leadership Workshop

Day 1: Introduction to Leadership
Day 2: Team Building Activities
Day 3: Strategic Planning

Time: 8:00 AM - 5:00 PM daily
Venue: Main Hall"
```

### 4. Participant Limits
For multi-day events, set limits carefully:
```
Workshop (3 days, limited seating)
→ Set Participant Limit: 50
→ Ensures commitment for all days
```

---

## Troubleshooting

### Q: I created a multi-day event but it shows as single-day?

**A:** Check if end_date was saved. View the event and verify the dates are different.

### Q: Can I edit an existing event to make it multi-day?

**A:** Yes! Edit the event and set a different end date.

### Q: What if I leave end date empty?

**A:** The system automatically sets it to the same as start date (single-day event).

### Q: End date validation not working?

**A:** Make sure JavaScript is enabled in your browser. The validation happens client-side.

### Q: How do reminders work for multi-day events?

**A:** Reminders are sent based on the START date:
- 3 days before start
- 2 days before start  
- 1 day before start

### Q: Can participants register after event starts?

**A:** No, registration closes once the start date/time passes (same behavior as before).

---

## Technical Details

### Database Schema:
```sql
ALTER TABLE events 
ADD COLUMN end_date DATETIME NULL 
COMMENT 'End date/time of the event' 
AFTER schedule;
```

### Date Logic:
- If `end_date` IS NULL → Use `schedule` (backward compatibility)
- If `end_date` = `schedule` → Single-day event
- If `end_date` > `schedule` → Multi-day event

### Calendar API:
```json
{
  "id": 1,
  "title": "Workshop",
  "start": "2026-03-15 08:00:00",
  "end": "2026-03-17 17:00:00",
  "allDay": false
}
```

---

## Quick Reference

### Event Duration Display:

| Duration | Display |
|----------|---------|
| Same day | "Mar 15, 2026 at 9:00 AM" |
| 2 days | "Mar 15 - Mar 16, 2026" + badge "2 days" |
| 3 days | "Mar 15 - Mar 17, 2026" + badge "3 days" |
| 7+ days | "Mar 15 - Mar 21, 2026" + badge "7 days" |

### Form Fields:

| Field | Required | Default |
|-------|----------|---------|
| Start Date & Time | ✅ Yes | - |
| End Date & Time | ❌ No | Same as start |

---

## Summary

✅ **What You Can Do Now:**
- Create events that span multiple days
- Show clear duration to participants
- Better represent workshops, seminars, campaigns
- Display holiday periods accurately

✅ **Benefits:**
- Clearer communication
- Better calendar visualization
- More professional event management
- Flexible for all event types

**Start creating multi-day events today!** 🎉


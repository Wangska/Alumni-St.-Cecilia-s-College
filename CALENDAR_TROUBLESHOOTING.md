# Calendar Integration Troubleshooting Guide

## Quick Checklist

### 1. Test the Calendar Isolated
Open this URL in your browser:
```
http://localhost/scratch/test_calendar.html
```

**Expected Result:** You should see a working calendar with your events.

**If this works:** The calendar code is fine, issue is with the landing page integration.
**If this doesn't work:** API or database issue.

---

### 2. Test the API Directly
Open this URL in your browser:
```
http://localhost/scratch/api/calendar_events.php
```

**Expected Result:** You should see JSON data with your events:
```json
[
  {
    "id": "36",
    "title": "Tree Planting Activity",
    "start": "2025-10-21 05:00:00",
    "description": "The Tree Planting Activity...",
    "participantCount": 3,
    "participantLimit": 10,
    "isFull": false,
    "backgroundColor": "#10b981",
    "borderColor": "#059669",
    "textColor": "#ffffff"
  }
]
```

**If you see errors:** Database connection issue.
**If empty array `[]`:** No events in database.

---

### 3. Check Browser Console
1. Open your landing page: `http://localhost/scratch/`
2. Press `F12` to open Developer Tools
3. Go to the **Console** tab
4. Look for these messages:

**Good signs:**
```
DOM loaded, initializing calendar...
Calendar element: <div id="calendar">...</div>
Rendering calendar...
Events loaded successfully: [...]
Event mounted: Tree Planting Activity
Calendar rendered successfully
```

**Bad signs:**
```
Calendar element not found!
Failed to load events: ...
```

---

### 4. Check Network Tab
1. In Developer Tools, go to **Network** tab
2. Refresh the page (`F5`)
3. Look for request to `calendar_events.php`

**Check:**
- Status should be: `200 OK`
- Response should contain JSON array
- If you see 404: API file not found
- If you see 500: PHP error in API

---

## Common Issues & Fixes

### Issue 1: Calendar Element Not Found
**Symptom:** Console shows "Calendar element not found!"

**Fix:** The calendar `<div>` is missing from the HTML.

**Solution:** The calendar section is at line ~349-420 in `index.php`. Make sure it's there between the Hero section and Gallery section.

---

### Issue 2: FullCalendar Not Loading
**Symptom:** Console shows "FullCalendar is not defined" or similar

**Fix:** CDN link not loaded

**Solution:** Check that these are in the `<head>` section (around line 102-104):
```html
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
```

---

### Issue 3: API Returns Empty Array
**Symptom:** API works but returns `[]`

**Fix:** No events in database

**Solution:** Add test events:
1. Login as admin
2. Go to Events section
3. Create new events with future dates

---

### Issue 4: Database Connection Error
**Symptom:** API returns error or blank page

**Fix:** Check database connection

**Solution:** 
1. Make sure MySQL/XAMPP is running
2. Check `inc/config.php` for correct database credentials
3. Verify the `events` table exists

---

### Issue 5: Events Not Showing on Calendar
**Symptom:** Calendar loads but no events visible

**Possible causes:**
1. **Events are too far in past/future:** Calendar only shows current month by default
2. **Event dates are invalid:** Check date format in database
3. **Events have no schedule date:** NULL dates won't display

**Solution:**
- Navigate to different months using prev/next buttons
- Check events table: `SELECT id, title, schedule FROM events;`
- Make sure dates are in format: `YYYY-MM-DD HH:MM:SS`

---

## Step-by-Step Verification

### Step 1: Verify Files Exist
```bash
✓ C:\xampp\htdocs\scratch\index.php (main landing page)
✓ C:\xampp\htdocs\scratch\api\calendar_events.php (API endpoint)
✓ C:\xampp\htdocs\scratch\test_calendar.html (test page)
```

### Step 2: Verify Database
Open phpMyAdmin and run:
```sql
-- Check if events table exists
SHOW TABLES LIKE 'events';

-- Check if events have data
SELECT id, title, schedule FROM events ORDER BY schedule DESC LIMIT 5;

-- Check participant counts
SELECT 
    e.title, 
    e.schedule,
    COUNT(ec.id) as participants
FROM events e
LEFT JOIN event_commits ec ON e.id = ec.event_id
GROUP BY e.id;
```

### Step 3: Clear Browser Cache
Sometimes old cached files cause issues:
1. Press `Ctrl + Shift + Delete`
2. Clear "Cached images and files"
3. Refresh page with `Ctrl + F5`

---

## Manual Debug Steps

### 1. Check if Calendar Section HTML is Present
View page source (`Ctrl + U`) and search for:
```html
<!-- Events Calendar Section -->
```

Should appear around line 349-420 and include:
```html
<div id="calendar" style="width: 100%;"></div>
```

### 2. Check if JavaScript is Running
Add this test code temporarily right before calendar initialization:
```javascript
alert('JavaScript is running!');
console.log('Testing console');
```

If alert doesn't show: JavaScript is blocked or not loading.

### 3. Test with Minimal Calendar
Replace the calendar initialization with this simple version:
```javascript
var calendar = new FullCalendar.Calendar(calendarEl, {
  initialView: 'dayGridMonth',
  events: [
    { title: 'Test Event', start: '2025-12-25' }
  ]
});
calendar.render();
```

If this works: Problem is with the API call.
If this doesn't work: FullCalendar library issue.

---

## Success Indicators

✅ Test page shows calendar
✅ API returns JSON array
✅ Console shows "Calendar rendered successfully"
✅ Network tab shows 200 OK for calendar_events.php
✅ Events are visible on the calendar
✅ Clicking events opens modal
✅ Calendar responds to prev/next buttons

---

## Still Not Working?

### Check PHP Error Log
Location: `C:\xampp\php\logs\php_error_log`

Look for errors related to:
- calendar_events.php
- Database connection
- PDO errors

### Enable PHP Errors
Add to top of `api/calendar_events.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Contact Support Info
If all else fails, check:
1. Console errors (F12)
2. Network tab responses
3. PHP error log
4. Database has events with valid dates

---

## Quick Fix Script

Run this in your browser console to test if calendar can be rendered manually:

```javascript
// Test if FullCalendar is loaded
console.log('FullCalendar loaded:', typeof FullCalendar);

// Try to find calendar element
var el = document.getElementById('calendar');
console.log('Calendar div found:', el);

// Test render
if (el && FullCalendar) {
    var cal = new FullCalendar.Calendar(el, {
        initialView: 'dayGridMonth',
        events: [{title: 'Test', start: '2025-12-22'}]
    });
    cal.render();
    console.log('Manual render successful!');
}
```

---

## Files Modified

These files contain the calendar integration:

1. **index.php** - Lines 102-104 (CSS), 136-206 (Styles), 349-420 (HTML), 1285-1600 (JavaScript)
2. **api/calendar_events.php** - API endpoint for fetching events
3. **test_calendar.html** - Isolated test page

---

## Expected Behavior

When working correctly:
1. Landing page loads
2. Scroll down to see "EVENT CALENDAR" section
3. Calendar shows current month grid
4. Your events appear as colored blocks on their scheduled dates
5. Green = open for registration
6. Red = event full
7. Click any event to see details in modal
8. Modal has "Login to Register" button

---

Good luck! The calendar should work perfectly now with the enhanced error handling and console logging.

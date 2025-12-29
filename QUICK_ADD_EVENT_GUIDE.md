# Quick Add Event Feature - User Guide

## Overview

The **Quick Add Event** feature allows Alumni Officers to create events instantly from anywhere in the dashboard without navigating to a separate page. Events created this way automatically appear on the public calendar.

---

## How to Use

### Step 1: Access the Quick Add Button

1. **Login** as an Alumni Officer
2. Look for the **floating red button** with a calendar icon (📅+) in the bottom-right corner of your screen
3. This button is visible on **every page** in the alumni officer dashboard

### Step 2: Open the Quick Add Sidebar

1. Click the **floating red button**
2. A sliding sidebar will appear from the right side of your screen
3. The sidebar contains a simple form for creating events

### Step 3: Fill Out the Event Form

**Required Fields:**

- **Event Title**
  - Enter a clear, descriptive name
  - Examples: "Career Fair 2026", "Alumni Homecoming", "Workshop: Digital Skills"
  - Maximum 200 characters

- **Event Date & Time**
  - Use the date-time picker
  - Select both date and time
  - Events can be scheduled for future dates

- **Description**
  - Brief overview of the event
  - Include key details (what, where, why)
  - Maximum 5000 characters

**Optional Field:**

- **Participant Limit**
  - Leave blank for unlimited participants
  - Enter a number to set maximum capacity
  - Example: 50 for a workshop with limited seats

### Step 4: Submit the Event

1. Review your information
2. Click the **"Create Event"** button
3. Wait for the success message (appears in 1-2 seconds)
4. The form will automatically close
5. Your event is now live! ✅

### Step 5: Verify on Calendar

1. Go to the public homepage: `http://localhost/scratch/index.php`
2. Scroll to the **Event Calendar** section
3. Your event should appear on the selected date
4. Alumni can now view and register for the event

---

## Features

### ✅ What This Feature Offers:

1. **Quick Access**
   - Floating button visible on all pages
   - No need to navigate to events page
   - One-click access from anywhere

2. **Fast Creation**
   - Simplified form with essential fields only
   - No banner upload required (optional for full form)
   - Submit in under 30 seconds

3. **Instant Feedback**
   - Real-time validation
   - Success/error messages
   - Form resets after submission

4. **Automatic Integration**
   - Events appear on calendar immediately
   - Alumni can register right away
   - 3-day reminder emails automatically scheduled

5. **Mobile Friendly**
   - Works on all screen sizes
   - Responsive design
   - Touch-friendly interface

---

## Comparison: Quick Add vs Full Form

| Feature | Quick Add Sidebar | Full Event Form |
|---------|-------------------|-----------------|
| **Access** | Floating button (all pages) | Navigate to Events page |
| **Speed** | ~30 seconds | ~2-3 minutes |
| **Banner Upload** | ❌ No | ✅ Yes |
| **Title** | ✅ Yes | ✅ Yes |
| **Date/Time** | ✅ Yes | ✅ Yes |
| **Description** | ✅ Yes | ✅ Yes |
| **Participant Limit** | ✅ Yes | ✅ Yes |
| **Rich Text Editor** | ❌ Plain text | ✅ Full formatting |
| **Best For** | Quick announcements | Detailed events |

**Recommendation:**
- Use **Quick Add** for: Simple events, announcements, quick reminders
- Use **Full Form** for: Major events with banners, detailed descriptions

---

## Tips & Best Practices

### When to Use Quick Add

✅ **Good for:**
- Monthly alumni meetups
- Urgent announcements
- Recurring events (e.g., "Monthly Coffee Chat - January")
- Simple workshops
- Quick reminders

❌ **Not ideal for:**
- Major events requiring promotional banners
- Events with complex descriptions
- When you need rich text formatting

### Writing Effective Quick Descriptions

**Good Example:**
```
Join us for our monthly alumni coffee meetup!

📅 Date: Jan 15, 2026, 10:00 AM
📍 Location: Alumni Lounge, Main Campus
☕ Free coffee and snacks provided

Connect with fellow alumni and share stories.
All batches welcome!
```

**Too Brief:**
```
Alumni meetup. Come join us.
```

**Include:**
- Event purpose
- Location/venue
- What to expect
- Who should attend
- Special instructions

### Participant Limits

- **Virtual events**: Usually unlimited
- **Physical workshops**: Set based on room capacity
- **Intimate gatherings**: Set lower limits (20-30)
- **Large gatherings**: Set higher or leave unlimited

---

## Troubleshooting

### Problem: Button Not Visible

**Possible causes:**
1. Not logged in as Alumni Officer
2. Browser cache issue
3. JavaScript disabled

**Solution:**
- Refresh the page (Ctrl + F5)
- Clear browser cache
- Check that you're logged in as Alumni Officer (Type 2)

### Problem: Sidebar Won't Open

**Possible causes:**
1. JavaScript error
2. Browser compatibility

**Solution:**
- Open browser console (F12)
- Check for errors
- Try a different browser (Chrome, Firefox, Edge)

### Problem: "Unauthorized" Error

**Cause:** Not logged in as Alumni Officer

**Solution:**
- Logout and login again
- Verify your account type is "Alumni Officer"

### Problem: Event Not Showing on Calendar

**Possible causes:**
1. Event date is too far in future/past
2. Browser cache
3. Calendar API issue

**Solution:**
- Check that event date is within calendar range
- Refresh the homepage (Ctrl + F5)
- Verify event was saved: Go to Events page in dashboard

### Problem: Form Submission Fails

**Possible causes:**
1. Missing required fields
2. Network error
3. CSRF token expired

**Solution:**
- Ensure all required fields are filled
- Check your internet connection
- Refresh page and try again

---

## Technical Details

### API Endpoint

```
POST /scratch/api/quick_add_event.php
```

**Required Headers:**
- Content-Type: application/x-www-form-urlencoded

**Required Fields:**
- csrf_token (auto-included)
- title (string, max 200 chars)
- schedule (datetime, format: Y-m-d\TH:i)
- content (string, max 5000 chars)

**Optional Fields:**
- participant_limit (integer, min 1)

**Response Format:**
```json
{
  "success": true,
  "message": "Event created successfully!",
  "event": {
    "id": 42,
    "title": "Career Fair 2026",
    "schedule": "2026-03-15T09:00",
    "formatted_date": "March 15, 2026 at 9:00 AM",
    "participant_limit": 100
  }
}
```

### Database Table

Events are stored in the `events` table:
- `id` - Auto-increment primary key
- `title` - Event title (VARCHAR 200)
- `content` - Event description (TEXT)
- `schedule` - Date and time (DATETIME)
- `banner` - Image filename (VARCHAR, optional)
- `participant_limit` - Max participants (INT, nullable)
- `date_created` - Creation timestamp (DATETIME)

### Security Features

1. **CSRF Protection**: Token validation on every request
2. **Authentication**: Alumni Officer only (Type 2)
3. **Input Validation**: Server-side validation of all fields
4. **SQL Injection Prevention**: Prepared statements
5. **XSS Prevention**: Sanitized output

---

## Keyboard Shortcuts

When sidebar is open:
- **Escape (ESC)** - Close sidebar
- **Enter** (in form) - Submit (if valid)

---

## Mobile Experience

On mobile devices (< 768px):
- Sidebar takes full screen width
- Floating button slightly smaller (56px)
- Touch-optimized buttons
- Responsive form inputs

---

## Future Enhancements

Possible features for future versions:
- [ ] Quick add with banner upload
- [ ] Event templates (save and reuse)
- [ ] Duplicate event feature
- [ ] Quick edit from calendar
- [ ] Batch event creation
- [ ] Event categories/tags

---

## Support

If you encounter issues:

1. **Check Activity Log**
   - Go to Alumni Officer Dashboard
   - Check if event was logged

2. **Check Database**
   - Run: `test_alumni_officer_events.php`
   - Verify event count increased

3. **Check Browser Console**
   - Press F12
   - Look for JavaScript errors
   - Check Network tab for API responses

4. **Check Server Logs**
   - Location: `/logs/error.log`
   - Check for PHP errors

---

## Summary

✅ **Quick Add Event** is a time-saving feature for Alumni Officers

✅ Creates events in seconds without leaving current page

✅ Events automatically appear on public calendar

✅ Supports participant limits and basic details

✅ Mobile-friendly and accessible

✅ Secure with CSRF protection and validation

**Perfect for quick event creation on-the-go!**


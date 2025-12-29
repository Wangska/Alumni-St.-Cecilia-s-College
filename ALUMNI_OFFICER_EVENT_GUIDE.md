# Alumni Officer - Event Management Guide

## Overview

The Alumni Officer has the ability to create, manage, and publish events that automatically appear on the public event calendar for all alumni to see.

---

## How to Create an Event

### Step 1: Access the Event Management Page

1. **Login** to the alumni officer dashboard:
   ```
   http://localhost/scratch/login.php
   ```

2. Click **"Events & Activities"** in the left sidebar

3. You'll see:
   - Total events count
   - Upcoming events count
   - All existing events in a card grid

### Step 2: Create a New Event

1. Click the **"Create Event"** button (top right with + icon)

2. **Fill out the event form:**

   **Event Banner (Optional but Recommended):**
   - Click "Choose Banner" to upload an image
   - Supports: JPG, PNG, GIF, WEBP
   - Preview shows before uploading
   - Best size: 1200x630px

   **Event Title (Required):**
   - Enter a clear, descriptive title
   - Example: "Alumni Homecoming 2026"
   - Example: "Career Workshop: Digital Marketing"

   **Event Schedule (Required):**
   - Pick date and time using the datetime picker
   - Use 24-hour or 12-hour format
   - Events in the past can be created for historical records

   **Event Content (Required):**
   - Full description of the event
   - Include:
     - What the event is about
     - Who should attend
     - What to bring
     - Venue details
     - Any special instructions

   **Participant Limit (Optional):**
   - Leave blank for **unlimited** participants
   - Enter a number to set a maximum
   - When limit is reached, event shows as "FULL" on calendar

3. Click **"Create Event"** button at the bottom

4. You'll see a success message and be redirected to the events list

---

## Event Visibility

### Where Alumni Can See Events:

1. **Homepage Calendar**
   ```
   http://localhost/scratch/index.php#calendar
   ```
   - Shows all events in a calendar view
   - Green badge = Available
   - Red badge = Full (limit reached)
   - Click event to see details

2. **Events Page**
   ```
   http://localhost/scratch/events/
   ```
   - List view of all events
   - Shows participant count
   - Register button (if not full)

3. **Alumni Dashboard**
   - Upcoming events widget
   - Quick registration

---

## Managing Existing Events

### View Event Details

- Each event card shows:
  - Event banner image
  - Event date and time
  - Event title
  - Short description
  - Participant count / limit
  - Status badge (Upcoming/Past)

### Edit an Event

1. Find the event in the list
2. Click the **edit icon** (pencil) on the event card
3. Modify any field
4. Click **Update Event**

### View Participants

1. Click the **users icon** on the event card
2. See list of all registered alumni:
   - Name
   - Email
   - Registration date
   - Export to Excel option

### Delete an Event

1. Click the **trash icon** (red) on the event card
2. Confirm deletion in the popup
3. **Warning:** This will:
   - Permanently delete the event
   - Remove all participant registrations
   - Remove from calendar display

---

## Event Reminders (Automatic)

Your system has **automatic email reminders** that send:

- **3 days before** the event
- **2 days before** the event
- **1 day before** the event

**Requirements:**
- XAMPP must be running
- Windows Task Scheduler must be set up (see UPGRADE_TO_3DAY_REMINDERS.md)
- Participants will receive emails to their registered email addresses

---

## Best Practices

### Event Titles
✅ **Good:**
- "Alumni Homecoming 2026"
- "Career Workshop: Digital Marketing Strategies"
- "Monthly Alumni Meetup - January"

❌ **Avoid:**
- "event"
- "test"
- "ASAP!!!"

### Event Descriptions
Include:
- **What:** Brief overview
- **When:** Date, time, duration
- **Where:** Venue address or online link
- **Who:** Target audience
- **Why:** Purpose and benefits
- **Requirements:** What to bring, dress code, etc.

### Event Banners
- Use high-quality images
- Recommended size: 1200x630px (Facebook event cover size)
- Include event title in the image
- Use school colors (red/white)
- Make it eye-catching!

### Participant Limits
- Set limits for:
  - Physical venue capacity
  - Workshop/training sessions
  - Limited resource events
- Leave unlimited for:
  - Virtual events
  - Large gatherings
  - General announcements

---

## Troubleshooting

### Event Not Showing on Calendar?

1. **Check if event was saved:**
   - Go to Events page in alumni officer dashboard
   - Look for the event in the list

2. **Check event date:**
   - Calendar only shows events from 30 days ago onwards
   - Very old events won't appear

3. **Refresh the homepage:**
   - Clear browser cache (Ctrl+Shift+Delete)
   - Hard refresh (Ctrl+F5)

### Can't Upload Banner?

1. **Check file size:**
   - Must be under 5MB (typically)
   
2. **Check file type:**
   - Must be: JPG, JPEG, PNG, GIF, or WEBP
   
3. **Check permissions:**
   - `/uploads` folder must exist
   - Must have write permissions

### Participants Can't Register?

1. **Check participant limit:**
   - Event may be full
   - Edit event to increase limit or remove it

2. **Check event date:**
   - Past events can't accept registrations
   
3. **Check user login:**
   - Only logged-in alumni can register

---

## Quick Tips

### Creating Recurring Events

For monthly or regular events:
1. Create the first event
2. After it passes, edit it:
   - Update the date to next month
   - Update title if needed
   - All participants will be cleared automatically

### Promoting Events

1. **Announcements:**
   - Create an announcement linking to the event
   - Use the Announcements page

2. **Direct Link:**
   - Share event URL with alumni
   - Format: `http://localhost/scratch/events/?id=EVENT_ID`

3. **Social Media:**
   - Export event details
   - Share on Facebook, Twitter, etc.

### Event Statistics

To see event performance:
1. Go to **Reports & Statistics** page
2. View:
   - Total events created
   - Average participants per event
   - Most popular events
   - Registration trends

---

## Need Help?

If you encounter issues:

1. Check the logs:
   ```
   /logs/error.log
   /logs/activity.log
   ```

2. Verify database connection:
   - XAMPP must be running
   - MySQL service started

3. Check user permissions:
   - Must be logged in as Alumni Officer
   - Type must be 2 (Alumni Officer)

---

## Summary

✅ Alumni Officer can create events easily
✅ Events automatically appear on public calendar
✅ Alumni can view and register for events
✅ Automatic email reminders sent
✅ Full management (edit/delete)
✅ Participant tracking

**The system is fully functional and ready to use!**


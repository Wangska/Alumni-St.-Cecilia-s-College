# Admin Dashboard

## Overview
The admin dashboard provides a comprehensive interface for managing the St. Cecilia's Alumni Management System. It features a modern sidebar layout with easy navigation and statistics tracking.

## Features

### Dashboard
- **Statistics Cards**: View quick stats for alumni, events, announcements, and courses
- **Recent Announcements**: See the latest 5 announcements with quick edit access
- **Upcoming Events**: Calendar view of upcoming events
- **Progress Indicators**: Track verified vs pending alumni, active events

### Alumni Management
- View all registered alumni with their details
- Filter by verification status (Verified/Pending)
- Edit alumni information
- Delete alumni records
- Upload and manage alumni avatars

### Events Management
- Create, edit, and delete events
- View upcoming and past events
- Set event schedules with date and time
- Manage event content and descriptions

### Announcements Management
- Create, edit, and delete announcements
- View all announcements sorted by date
- Manage announcement content
- Quick access from dashboard

### Courses Management
- Add new courses with descriptions
- Edit existing courses
- Delete courses (with cascade handling)
- Modal-based editing for quick updates

## Access

### URL
`http://localhost/scratch/admin.php`

### Login Credentials
- **Username**: admin
- **Password**: admin123

**Note**: The admin account is auto-created on first login if it doesn't exist.

## Navigation

### Sidebar Menu
The sidebar provides quick access to all admin features:
- **Dashboard**: Overview and statistics
- **Alumni Management**: Manage alumni records
- **Events**: Create and manage events
- **Announcements**: Manage announcements
- **Courses**: Manage course listings
- **Gallery**: (Coming soon)
- **User Accounts**: (Coming soon)
- **Settings**: (Coming soon)
- **Logout**: End admin session

### Top Navbar
- Page title indicator
- Notification bell with badge counter
- User profile with avatar
- Quick access to user menu

## Design

### Color Scheme
- **Primary**: Red gradient (#dc3545 to #a71d2a) - Matches SCC branding
- **Secondary**: White backgrounds with subtle shadows
- **Accents**: Bootstrap colors for different stat types

### Layout
- **Sidebar**: Fixed 260px width, full height
- **Main Content**: Fluid width with responsive padding
- **Cards**: Modern design with hover effects
- **Tables**: Clean, hoverable rows with action buttons

### Responsive Design
- Mobile-friendly with collapsible sidebar
- Responsive statistics cards
- Touch-friendly buttons and controls

## Technical Details

### File Structure
```
views/
├── layouts/
│   └── admin.php          # Main admin layout with sidebar
└── admin/
    ├── dashboard.php      # Admin dashboard view
    ├── alumni.php         # Alumni management view
    ├── events.php         # Events management view
    ├── announcements.php  # Announcements management view
    └── courses.php        # Courses management view

app/
└── Controllers/
    └── AdminController.php # Admin controller logic

admin.php                  # Admin entry point
```

### Dependencies
- **Bootstrap 5.3.0**: UI framework
- **Font Awesome 6.4.0**: Icons
- **Google Fonts (Poppins)**: Typography
- **PHP 7.4+**: Server-side processing
- **MySQL**: Database

### Security
- CSRF token protection on all forms
- Session-based authentication
- Role-based access control (admin only)
- Input sanitization and validation
- XSS protection with `htmlspecialchars()`

## Usage

### Adding a New Alumni
1. Go to Alumni Management
2. Click "Add New Alumni"
3. Fill in the form with alumni details
4. Upload avatar (optional)
5. Submit to create

### Creating an Event
1. Go to Events
2. Click "Add New Event"
3. Set title, schedule, and content
4. Submit to publish

### Managing Courses
1. Go to Courses
2. Click "Add New Course" to add
3. Click edit icon to modify
4. Click delete icon to remove

### Publishing Announcements
1. Go to Announcements
2. Click "Add New Announcement"
3. Enter title and content
4. Submit to publish

## Statistics

The dashboard tracks:
- **Total Alumni**: All registered alumni
- **Verified Alumni**: Alumni with verified status
- **Pending Alumni**: Alumni awaiting verification
- **Upcoming Events**: Future scheduled events
- **Active Events**: Currently ongoing events
- **Total Announcements**: All published announcements
- **Total Courses**: Available courses

## Future Enhancements
- [ ] Gallery management
- [ ] User account management
- [ ] System settings
- [ ] Export data to CSV/PDF
- [ ] Advanced filtering and search
- [ ] Bulk operations
- [ ] Email notifications
- [ ] Activity logs
- [ ] Report generation


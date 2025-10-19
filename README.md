# St. Cecilia's Alumni Management System

A comprehensive alumni management web application built with PHP and MySQL, featuring a modern admin dashboard and public landing page.

## Prerequisites
- XAMPP (Apache + MariaDB/MySQL)
- PHP 7.4+
- Modern web browser

## Installation

### Database Setup
1. Start XAMPP (Apache + MySQL)
2. Import `alumni_db.sql` into MariaDB via phpMyAdmin
3. The script creates database `sccalumni_db` and loads seed data

### Configuration
- Edit `inc/config.php` if your DB credentials differ
- Default assumes user `root` with empty password on `127.0.0.1`

### Deployment
- Place this folder under `htdocs` as `scratch/`
- Ensure Apache and MySQL are running in XAMPP
- Access the application at `http://localhost/scratch/`

## Access Points

### Public Landing Page
**URL**: `http://localhost/scratch/`
- Modern landing page with hero carousel
- Login and registration modals
- Feature showcase
- Contact information

### Admin Dashboard
**URL**: `http://localhost/scratch/admin.php`
- **Username**: `admin`
- **Password**: `admin123`
- Full management interface with sidebar navigation
- Statistics and analytics
- Complete CRUD operations

### Alumni Dashboard
**URL**: `http://localhost/scratch/dashboard.php`
- For registered alumni users
- View announcements and events
- Access alumni directory

## Features

### Public Features
- ✅ Modern responsive landing page with carousel
- ✅ Modal-based login and registration
- ✅ User registration with avatar upload
- ✅ Course selection during registration

### Admin Features
- ✅ Modern sidebar dashboard with statistics
- ✅ **Alumni Management**: Full CRUD with verification status
- ✅ **Events Management**: Create and schedule events
- ✅ **Announcements Management**: Publish announcements
- ✅ **Courses Management**: Manage course listings
- ✅ Role-based access control
- ✅ Real-time statistics and progress tracking

### Security Features
- Session-based authentication
- CSRF token protection on all forms
- Role-based access control (Admin/Alumni)
- Input sanitization and XSS protection
- SQL injection prevention with prepared statements

## Architecture

### Traditional PHP Structure (Current)
```
scratch/
├── inc/                    # Config and auth helpers
│   ├── config.php         # Database connection
│   └── auth.php           # Authentication helpers
├── alumni/                # Alumni CRUD pages
├── announcements/         # Announcements CRUD pages
├── events/               # Events CRUD pages
├── courses/              # Courses pages
├── views/                # View templates
│   ├── layouts/
│   │   └── admin.php     # Admin layout with sidebar
│   └── admin/            # Admin views
│       ├── dashboard.php
│       ├── alumni.php
│       ├── events.php
│       ├── announcements.php
│       └── courses.php
├── index.php             # Public landing page
├── dashboard.php         # Alumni dashboard
├── admin.php            # Admin dashboard entry point
├── auth_login.php       # Login handler
├── auth_register.php    # Registration handler
└── logout.php          # Logout handler
```

### OOP Structure (In Development)
```
scratch/
├── app/
│   ├── Core/             # Core classes
│   │   ├── Database.php
│   │   ├── Model.php
│   │   ├── Controller.php
│   │   └── Router.php
│   ├── Models/           # Data models
│   │   ├── User.php
│   │   ├── Alumni.php
│   │   ├── Event.php
│   │   ├── Announcement.php
│   │   └── Course.php
│   └── Controllers/      # Business logic
│       ├── AuthController.php
│       ├── HomeController.php
│       ├── DashboardController.php
│       └── AdminController.php
├── config/
│   └── database.php     # Database configuration
├── routes/
│   └── web.php         # Route definitions
├── autoload.php        # PSR-4 autoloader
└── bootstrap.php       # Application bootstrap
```

## Credentials

### Admin Account
- **Username**: `admin`
- **Password**: `admin123`
- Auto-creates on first login if missing

### Test Alumni Account
Register through the landing page or use existing database users.

## File Upload Support
- Avatar uploads during registration
- Stored in `uploads/` directory
- Supported formats: JPG, PNG, GIF
- Automatic filename sanitization

## Notes

### Security
- Passwords use legacy MD5 (for compatibility with existing data)
- Auth system accepts both MD5 and plaintext
- For production: migrate to `password_hash()` with bcrypt

### Browser Support
- Modern browsers (Chrome, Firefox, Edge, Safari)
- Responsive design for mobile devices

### Development
- `.htaccess` blocks direct access to `inc/` directory
- CSRF protection on all POST requests
- Error reporting can be enabled in `inc/config.php`

## Documentation
- See `ADMIN_DASHBOARD.md` for detailed admin features
- See `OOP_STRUCTURE.md` for OOP architecture details

## Future Enhancements
- Gallery management
- User account management from admin
- System settings interface
- Email notifications
- Advanced search and filtering
- Export functionality (CSV/PDF)
- Activity logs and reporting


# How to Install Composer and PHPMailer

## Option 1: Install Composer (Recommended)

### Step 1: Download Composer
1. Go to [https://getcomposer.org/download/](https://getcomposer.org/download/)
2. Click on "Composer-Setup.exe" (for Windows)
3. Run the installer
4. Follow the installation wizard (it will auto-detect your PHP installation)
5. Restart your terminal/command prompt after installation

### Step 2: Install PHPMailer
Open Command Prompt or Git Bash and run:

```bash
cd C:\xampp\htdocs\scratch
composer install
```

This will install PHPMailer and all dependencies.

---

## Option 2: Manual Installation (If you can't install Composer)

If you cannot install Composer, follow these steps:

### Step 1: Download PHPMailer

1. Go to: [https://github.com/PHPMailer/PHPMailer/archive/refs/tags/v6.8.1.zip](https://github.com/PHPMailer/PHPMailer/archive/refs/tags/v6.8.1.zip)
2. Download the ZIP file
3. Extract it

### Step 2: Create vendor folder structure

1. In your `C:\xampp\htdocs\scratch` folder, create this structure:
   ```
   scratch/
   └── vendor/
       └── phpmailer/
           └── phpmailer/
               └── src/
   ```

2. Copy all files from the extracted PHPMailer folder to:
   `C:\xampp\htdocs\scratch\vendor\phpmailer\phpmailer\`

### Step 3: Create autoload.php

Create a file at `C:\xampp\htdocs\scratch\vendor\autoload.php` with this content:

```php
<?php
// Simple autoloader for PHPMailer
spl_autoload_register(function ($class) {
    // PHPMailer namespace
    $prefix = 'PHPMailer\\PHPMailer\\';
    $base_dir = __DIR__ . '/phpmailer/phpmailer/src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});
```

---

## Verify Installation

After installing (either method), create this test file:

**File: `C:\xampp\htdocs\scratch\test_phpmailer.php`**

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

try {
    $mail = new PHPMailer(true);
    echo "✅ PHPMailer installed successfully!\n";
    echo "Version: " . PHPMailer::VERSION . "\n";
} catch (Exception $e) {
    echo "❌ PHPMailer not found. Please check installation.\n";
    echo "Error: " . $e->getMessage() . "\n";
}
```

Open in browser: `http://localhost/scratch/test_phpmailer.php`

If you see "✅ PHPMailer installed successfully!" then you're good to go!

---

## Next Steps

After successful installation, proceed to **EMAIL_SETUP.md** to configure Mailtrap and test the email system.


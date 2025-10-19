# Alumni Management System - OOP Structure

## 📁 Directory Structure

```
scratch/
├── app/
│   ├── Core/              # Core framework classes
│   │   ├── Database.php   # Singleton database connection
│   │   ├── Model.php      # Base model class
│   │   ├── Controller.php # Base controller class
│   │   └── Router.php     # URL routing
│   ├── Models/            # Data models
│   │   ├── User.php
│   │   ├── Alumni.php
│   │   ├── Course.php
│   │   ├── Event.php
│   │   └── Announcement.php
│   └── Controllers/       # Business logic
│       ├── HomeController.php
│       ├── AuthController.php
│       └── DashboardController.php
├── config/                # Configuration files
│   └── database.php
├── routes/                # Route definitions
│   └── web.php
├── views/                 # View templates (to be created)
├── public/                # Web-accessible directory
│   └── index_new.php     # Entry point
├── uploads/               # User uploads
├── autoload.php           # PSR-4 autoloader
└── bootstrap.php          # Application bootstrap
```

## 🏗️ Architecture

### MVC Pattern
- **Models**: Handle database operations and business logic
- **Views**: Render HTML (currently using existing PHP files)
- **Controllers**: Handle HTTP requests and coordinate models/views

### Core Classes

#### Database (Singleton)
```php
$db = Database::getInstance();
$result = $db->fetch("SELECT * FROM users WHERE id = ?", [1]);
```

#### Model (Base Class)
All models extend this base class:
```php
class User extends Model {
    protected string $table = 'users';
}

$user = new User();
$user->find(1);              // Find by ID
$user->all();                // Get all
$user->where('email', $email); // Where clause
$user->create($data);        // Insert
$user->update($id, $data);   // Update
$user->delete($id);          // Delete
```

#### Controller (Base Class)
```php
class MyController extends Controller {
    public function index() {
        $this->view('home', ['data' => $data]);
        $this->redirect('/dashboard');
        $this->json(['success' => true]);
    }
}
```

#### Router
```php
$router->get('/path', Controller::class, 'method');
$router->post('/path', Controller::class, 'method');
```

## 🚀 Usage

### Creating a New Model
```php
namespace App\Models;
use App\Core\Model;

class MyModel extends Model {
    protected string $table = 'my_table';
    
    public function customMethod() {
        $sql = "SELECT * FROM {$this->table} WHERE ...";
        return $this->db->fetchAll($sql);
    }
}
```

### Creating a New Controller
```php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\MyModel;

class MyController extends Controller {
    public function index() {
        $model = new MyModel();
        $data = $model->all();
        
        $this->view('my-view', ['data' => $data]);
    }
}
```

### Adding Routes
In `routes/web.php`:
```php
$router->get('/my-path', MyController::class, 'index');
$router->post('/my-path/save', MyController::class, 'save');
```

## 🔄 Migration from Old Code

The old procedural code still works alongside the new OOP structure:
- Old: `index.php`, `login.php`, `dashboard.php`, etc.
- New: `public/index_new.php` (uses routing)

To fully migrate:
1. Move view logic to `views/` directory
2. Update `auth_login.php` → use `AuthController::login()`
3. Update `auth_register.php` → use `AuthController::register()`
4. Update all CRUD pages to use controllers
5. Use `.htaccess` to route all requests through `public/index_new.php`

## 🎯 Benefits

1. **Separation of Concerns**: Logic, data, and presentation are separated
2. **Reusability**: Models and controllers can be reused
3. **Maintainability**: Easier to find and fix bugs
4. **Scalability**: Easy to add new features
5. **Security**: Centralized validation and CSRF protection
6. **Testing**: Easier to write unit tests

## 📝 Next Steps

1. Create view templates in `views/` directory
2. Add middleware for authentication
3. Implement validation layer
4. Add error handling and logging
5. Create admin CRUD controllers
6. Add API endpoints for AJAX operations

## 🔒 Security Features

- CSRF token validation in base controller
- SQL injection prevention via prepared statements
- XSS protection via `htmlspecialchars()` helper
- Password hashing (MD5 legacy support, migrate to `password_hash()`)


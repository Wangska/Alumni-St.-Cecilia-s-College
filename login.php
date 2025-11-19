<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/logger.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (attempt_login($username, $password)) {
        // Log the login activity
        ActivityLogger::logLogin($username);
        
        // Redirect based on user type
        $user = current_user();
        $userType = (int)($user['type'] ?? 3); // Convert to integer
        
        if ($userType === 1) {
            // Admin
            header('Location: /scratch/admin.php');
        } elseif ($userType === 2) {
            // Alumni Officer
            header('Location: /scratch/alumni-officer.php');
        } else {
            // Regular alumni
        header('Location: /scratch/index.php');
        }
        exit;
    }
    $error = 'Invalid credentials';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Alumni</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h1 class="h4 mb-3">Login</h1>
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger py-2"><?php echo e($error); ?></div>
            <?php endif; ?>
            <form method="post">
              <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input class="form-control" name="username" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
              </div>
              <button class="btn btn-primary w-100">Sign in</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>



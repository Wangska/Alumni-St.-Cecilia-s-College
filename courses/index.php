<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();
$pdo = get_pdo();
$courses = $pdo->query('SELECT id, course, about FROM courses ORDER BY id')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Courses</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4">Courses</h1>
      <a class="btn btn-secondary" href="/scratch/index.php">Back</a>
    </div>
    <table class="table table-sm table-striped">
      <thead><tr><th>ID</th><th>Course</th><th>About</th></tr></thead>
      <tbody>
      <?php foreach ($courses as $c): ?>
        <tr>
          <td><?php echo (int)$c['id']; ?></td>
          <td><?php echo e($c['course']); ?></td>
          <td><?php echo e((string)$c['about']); ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>



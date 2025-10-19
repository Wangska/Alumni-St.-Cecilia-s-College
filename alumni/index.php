<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();
$pdo = get_pdo();
$rows = $pdo->query('SELECT a.id, a.firstname, a.lastname, a.email, a.batch, c.course FROM alumnus_bio a JOIN courses c ON c.id=a.course_id ORDER BY a.id')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alumni</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4">Alumni</h1>
      <div>
        <?php if ((current_user()['type'] ?? 3) === 1): ?>
          <a class="btn btn-secondary" href="/scratch/admin.php?page=alumni">Back to Admin</a>
          <a class="btn btn-primary" href="/scratch/alumni/new.php">Add</a>
        <?php else: ?>
          <a class="btn btn-secondary" href="/scratch/dashboard.php">Back</a>
        <?php endif; ?>
      </div>
    </div>
    <table class="table table-sm table-striped">
      <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Batch</th><th>Course</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?php echo (int)$r['id']; ?></td>
          <td><?php echo e($r['firstname'] . ' ' . $r['lastname']); ?></td>
          <td><?php echo e($r['email']); ?></td>
          <td><?php echo e((string)$r['batch']); ?></td>
          <td><?php echo e($r['course']); ?></td>
          <td class="text-end">
            <?php if ((current_user()['type'] ?? 3) === 1): ?>
              <a class="btn btn-sm btn-outline-primary" href="/scratch/alumni/edit.php?id=<?php echo (int)$r['id']; ?>">Edit</a>
              <a class="btn btn-sm btn-outline-danger" href="/scratch/alumni/delete.php?id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Delete this record?')">Delete</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>



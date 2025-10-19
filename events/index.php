<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();
$pdo = get_pdo();
$rows = $pdo->query('SELECT id, title, schedule, banner FROM events ORDER BY schedule DESC')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Events</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4">Events</h1>
      <div>
        <a class="btn btn-secondary" href="/scratch/index.php">Back</a>
        <?php if ((current_user()['type'] ?? 3) === 1): ?>
          <a class="btn btn-primary" href="/scratch/events/new.php">Add</a>
        <?php endif; ?>
      </div>
    </div>
    <table class="table table-sm table-striped">
      <thead><tr><th>ID</th><th>Title</th><th>Schedule</th><th>Banner</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?php echo (int)$r['id']; ?></td>
          <td><?php echo e($r['title']); ?></td>
          <td><?php echo e($r['schedule']); ?></td>
          <td><?php echo e((string)$r['banner']); ?></td>
          <td class="text-end">
            <?php if ((current_user()['type'] ?? 3) === 1): ?>
              <a class="btn btn-sm btn-outline-primary" href="/scratch/events/edit.php?id=<?php echo (int)$r['id']; ?>">Edit</a>
              <a class="btn btn-sm btn-outline-danger" href="/scratch/events/delete.php?id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Delete this event?')">Delete</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>



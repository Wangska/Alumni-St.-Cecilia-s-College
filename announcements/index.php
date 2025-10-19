<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();
$pdo = get_pdo();
$rows = $pdo->query('SELECT id, content, date_posted FROM announcements ORDER BY id DESC')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Announcements</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4">Announcements</h1>
      <div>
        <a class="btn btn-secondary" href="/scratch/index.php">Back</a>
        <?php if ((current_user()['type'] ?? 3) === 1): ?>
          <a class="btn btn-primary" href="/scratch/announcements/new.php">Add</a>
        <?php endif; ?>
      </div>
    </div>
    <div class="list-group">
      <?php foreach ($rows as $r): ?>
        <div class="list-group-item">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div><?php echo nl2br(e($r['content'])); ?></div>
              <small class="text-muted"><?php echo e($r['date_posted']); ?></small>
            </div>
            <?php if ((current_user()['type'] ?? 3) === 1): ?>
              <div class="ms-3">
                <a class="btn btn-sm btn-outline-primary" href="/scratch/announcements/edit.php?id=<?php echo (int)$r['id']; ?>">Edit</a>
                <a class="btn btn-sm btn-outline-danger" href="/scratch/announcements/delete.php?id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Delete this announcement?')">Delete</a>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>



<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_login();
$user = current_user();

$pdo = get_pdo();

// Check if user is admin
if (!isset($user['type']) || $user['type'] != 1) {
    echo "<h1>Access Denied</h1>";
    echo "<p>You need to be an admin to access this page.</p>";
    echo "<p>Your user type: " . ($user['type'] ?? 'not set') . "</p>";
    echo "<p><a href='/scratch/dashboard.php'>Back to Dashboard</a></p>";
    exit;
}

// Fetch all success stories
try {
    $stmt = $pdo->prepare('
        SELECT ss.*, u.username, ab.firstname, ab.lastname 
        FROM success_stories ss 
        LEFT JOIN users u ON ss.user_id = u.id 
        LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id 
        ORDER BY ss.created DESC
    ');
    $stmt->execute();
    $stories = $stmt->fetchAll();
} catch (Exception $e) {
    $stories = [];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Test - Success Stories</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="container mt-5">
    <h1>Success Stories Admin Test</h1>
    
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3>All Success Stories</h3>
            <p class="mb-0">Total Stories: <?= count($stories) ?></p>
          </div>
          <div class="card-body">
            <?php if (empty($stories)): ?>
              <p class="text-muted">No stories found.</p>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Title</th>
                      <th>Author</th>
                      <th>Status</th>
                      <th>Created</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($stories as $story): ?>
                      <tr>
                        <td><?= $story['id'] ?></td>
                        <td><?= htmlspecialchars($story['title']) ?></td>
                        <td><?= htmlspecialchars($story['firstname'] . ' ' . $story['lastname']) ?></td>
                        <td>
                          <?php if ($story['status']): ?>
                            <span class="badge bg-success">Approved</span>
                          <?php else: ?>
                            <span class="badge bg-warning">Pending</span>
                          <?php endif; ?>
                        </td>
                        <td><?= date('M d, Y H:i', strtotime($story['created'])) ?></td>
                        <td>
                          <?php if (!$story['status']): ?>
                            <a href="success-stories/admin.php" class="btn btn-success btn-sm">Approve</a>
                          <?php else: ?>
                            <a href="success-stories/admin.php" class="btn btn-warning btn-sm">Unapprove</a>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    
    <div class="mt-4">
      <a href="/scratch/dashboard.php" class="btn btn-primary">Back to Dashboard</a>
      <a href="success-stories/admin.php" class="btn btn-secondary">Go to Admin Panel</a>
    </div>
  </div>
</body>
</html>

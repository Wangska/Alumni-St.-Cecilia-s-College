<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_login();
$user = current_user();
$pageTitle = 'Navigation Test - SCC Alumni';
?>
<?php include __DIR__ . '/inc/header.php'; ?>

<div class="container mt-5">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3>Navigation Test</h3>
        </div>
        <div class="card-body">
          <p>This page uses the reusable header include to test navigation consistency.</p>
          <p><strong>User:</strong> <?= htmlspecialchars($user['name']) ?></p>
          <p><strong>User Type:</strong> <?= $user['type'] ?? 'not set' ?></p>
          <p><strong>Is Admin:</strong> <?= (isset($user['type']) && $user['type'] == 1) ? 'Yes' : 'No' ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/inc/footer.php'; ?>

<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pageTitle = 'News & Announcements - SCC Alumni';
$pdo = get_pdo();

try {
    $stmt = $pdo->prepare('SELECT id, title, content, image, date_created FROM announcements ORDER BY date_created DESC');
    $stmt->execute();
    $announcements = $stmt->fetchAll();
} catch (Exception $e) {
    $announcements = [];
}
?>
<?php include __DIR__ . '/../inc/header.php'; ?>

<style>
  .announce-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden; transition:transform .2s ease, box-shadow .2s ease; }
  .announce-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
  .cover { height:200px; background:#f3f4f6; display:flex; align-items:center; justify-content:center; overflow:hidden; }
  .cover img { width:100%; height:100%; object-fit:cover; }
  .title { color:#1f2937; font-weight:700; font-size:1.15rem; }
  .meta { color:#6b7280; font-size:.9rem; }
  .read-btn { background:#dc2626; color:#fff; border:none; padding:10px 14px; border-radius:10px; font-weight:600; }
</style>

<div class="container main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-1" style="color:#2d3142; font-weight:700;"><i class="fas fa-bullhorn me-2" style="color:#dc2626;"></i>News & Announcements</h2>
      <p class="text-muted mb-0">All the latest updates from the alumni community</p>
    </div>
  </div>

  <?php if (empty($announcements)): ?>
    <div class="text-center py-5" style="background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
      <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
        <i class="fas fa-bullhorn text-muted" style="font-size:2rem;"></i>
      </div>
      <h4 class="text-muted">No announcements yet</h4>
      <p class="text-muted">Check back later for updates</p>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($announcements as $a): ?>
        <div class="col-lg-4 col-md-6">
          <div class="announce-card h-100 d-flex flex-column">
            <div class="cover">
              <?php if (!empty($a['image'])): ?>
                <img src="/scratch/uploads/<?= htmlspecialchars($a['image']) ?>" alt="Announcement">
              <?php else: ?>
                <i class="fas fa-bullhorn text-muted" style="font-size:3rem;"></i>
              <?php endif; ?>
            </div>
            <div class="p-4 d-flex flex-column flex-grow-1">
              <div class="title mb-2"><?= htmlspecialchars($a['title'] ?? 'Announcement') ?></div>
              <div class="meta mb-3"><i class="fas fa-calendar me-1"></i><?= date('M d, Y', strtotime($a['date_created'])) ?></div>
              <p class="text-muted mb-4" style="line-height:1.6;">
                <?= htmlspecialchars(substr($a['content'] ?? '', 0, 140)) ?>...
              </p>
              <div class="mt-auto">
                <a href="#" class="read-btn w-100 d-inline-block text-center">Read More</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../inc/footer.php'; ?>



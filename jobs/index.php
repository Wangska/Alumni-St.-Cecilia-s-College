<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pageTitle = 'Available Jobs - SCC Alumni';
$pdo = get_pdo();

try {
    $stmt = $pdo->prepare('SELECT id, company, job_title AS title, description, location, date_created FROM careers ORDER BY date_created DESC');
    $stmt->execute();
    $jobs = $stmt->fetchAll();
} catch (Exception $e) {
    $jobs = [];
}
?>
<?php include __DIR__ . '/../inc/header.php'; ?>

<style>
  .job-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); overflow: hidden; transition: transform .2s ease, box-shadow .2s ease; }
  .job-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
  .job-cover { height: 180px; background: linear-gradient(135deg, #10b981, #059669); display:flex; align-items:center; justify-content:center; }
  .job-title { color:#1f2937; font-weight:700; font-size:1.1rem; }
  .meta { color:#6b7280; font-size:.9rem; }
  .apply-btn { background:#dc2626; color:#fff; border:none; padding:10px 14px; border-radius:10px; font-weight:600; }
</style>

<div class="container main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-1" style="color:#2d3142; font-weight:700;"><i class="fas fa-briefcase me-2" style="color:#10b981;"></i>Available Jobs</h2>
      <p class="text-muted mb-0">Browse all job opportunities posted for SCC alumni</p>
    </div>
  </div>

  <?php if (empty($jobs)): ?>
    <div class="text-center py-5" style="background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
      <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
        <i class="fas fa-briefcase text-muted" style="font-size:2rem;"></i>
      </div>
      <h4 class="text-muted">No job openings available</h4>
      <p class="text-muted">Check back later for new opportunities</p>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($jobs as $job): ?>
        <div class="col-lg-4 col-md-6">
          <div class="job-card h-100 d-flex flex-column">
            <div class="job-cover">
              <i class="fas fa-briefcase text-white" style="font-size:3rem;"></i>
            </div>
            <div class="p-4 d-flex flex-column flex-grow-1">
              <div class="job-title mb-2"><?= htmlspecialchars($job['title']) ?></div>
              <div class="meta d-flex justify-content-between mb-3">
                <span><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($job['location'] ?? 'Location not specified') ?></span>
                <span><i class="fas fa-calendar me-1"></i><?= date('M d, Y', strtotime($job['date_created'])) ?></span>
              </div>
              <p class="text-muted mb-4" style="line-height:1.6;">
                <?= htmlspecialchars(substr($job['description'] ?? '', 0, 140)) ?>...
              </p>
              <div class="mt-auto">
                <a href="#" class="apply-btn w-100 d-inline-block text-center">Apply Now</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../inc/footer.php'; ?>



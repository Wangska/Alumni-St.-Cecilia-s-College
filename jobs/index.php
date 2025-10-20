<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pageTitle = 'Available Jobs - SCC Alumni';
$pdo = get_pdo();

try {
    $stmt = $pdo->prepare('SELECT id, company, job_title AS title, description, location, company_logo, date_created FROM careers ORDER BY date_created DESC');
    $stmt->execute();
    $jobs = $stmt->fetchAll();
    // Fetch the current user's latest application status per job
    $appStmt = $pdo->prepare('SELECT job_id, status, applied_at FROM job_applications WHERE user_id = ? ORDER BY applied_at DESC');
    $appStmt->execute([$user['id']]);
    $latestStatusByJob = [];
    foreach ($appStmt->fetchAll() as $appRow) {
        $jobIdKey = (int)$appRow['job_id'];
        if (!isset($latestStatusByJob[$jobIdKey])) {
            $latestStatusByJob[$jobIdKey] = strtolower((string)$appRow['status']);
        }
    }
} catch (Exception $e) {
    $jobs = [];
}
?>
<?php include __DIR__ . '/../inc/header.php'; ?>

<!-- Application Success Notification -->
<?php if (isset($_SESSION['application_notification'])): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="<?= $_SESSION['application_notification']['duration'] ?? 5000 ?>">
            <div class="toast-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none;">
                <div class="me-2">
                    <i class="<?= $_SESSION['application_notification']['icon'] ?? 'fas fa-check-circle' ?>" style="font-size: 18px;"></i>
                </div>
                <strong class="me-auto"><?= $_SESSION['application_notification']['title'] ?? 'Success!' ?></strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" style="background: white; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px;">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-paper-plane text-white" style="font-size: 20px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-2" style="color: #2d3142; font-weight: 600;">Application Submitted!</h6>
                        <p class="mb-2" style="color: #6b7280; line-height: 1.5;">
                            <?= $_SESSION['application_notification']['message'] ?? 'Your application has been sent successfully.' ?>
                        </p>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="fas fa-clock me-1"></i>
                            <span>Review process: 2-3 business days</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['application_notification']); ?>
<?php endif; ?>

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
              <?php if (!empty($job['company_logo']) && file_exists(__DIR__ . '/../uploads/' . $job['company_logo'])): ?>
                <img src="/scratch/uploads/<?= htmlspecialchars($job['company_logo']) ?>" 
                     alt="<?= htmlspecialchars($job['company']) ?> Logo" 
                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
              <?php else: ?>
                <i class="fas fa-briefcase text-white" style="font-size:3rem;"></i>
              <?php endif; ?>
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
                <?php
                  $status = $latestStatusByJob[$job['id']] ?? null;
                  $canApply = !$status || $status === 'rejected';
                ?>
                <?php if ($canApply): ?>
                  <button class="apply-btn w-100 d-inline-block text-center" onclick="openApplicationModal(<?= $job['id'] ?>, '<?= htmlspecialchars($job['title']) ?>', '<?= htmlspecialchars($job['company']) ?>')">Apply Now</button>
                <?php else: ?>
                  <div class="w-100 text-center" style="padding:10px 14px; border-radius:10px; font-weight:600; background:#f3f4f6; color:#374151;">
                    <?php if ($status === 'pending' || $status === 'reviewed'): ?>
                      Application in review. Please wait for a decision.
                    <?php elseif ($status === 'accepted'): ?>
                      You have been accepted for this job.
                    <?php else: ?>
                      Application in progress.
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Job Application Modal -->
<div class="modal fade" id="applicationModal" tabindex="-1" aria-labelledby="applicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <!-- Gmail-style Header -->
            <div class="modal-header" style="background: #f8f9fa; border-bottom: 1px solid #e5e7eb; padding: 16px 24px; border-radius: 12px 12px 0 0;">
                <div class="d-flex align-items-center w-100">
                    <div class="me-3">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #dc3545, #c82333); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-paper-plane text-white" style="font-size: 18px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="modal-title mb-0" style="font-weight: 600; color: #2d3142;">Apply for Position</h5>
                        <small class="text-muted" id="applicationJobTitle">Job Title</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            
            <form id="applicationForm" method="POST" action="/scratch/jobs/apply.php" enctype="multipart/form-data" onsubmit="return validateApplicationForm()">
                <div class="modal-body" style="padding: 0;">
                    <!-- Gmail-style Compose Interface -->
                    <div class="compose-interface">
                        <!-- To Field -->
                        <div class="compose-field" style="border-bottom: 1px solid #e5e7eb; padding: 16px 24px;">
                            <div class="d-flex align-items-center">
                                <label class="compose-label" style="min-width: 60px; font-weight: 500; color: #6b7280;">To:</label>
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control border-0" value="SCC Alumni Portal" readonly style="background: transparent; font-weight: 500;">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Subject Field -->
                        <div class="compose-field" style="border-bottom: 1px solid #e5e7eb; padding: 16px 24px;">
                            <div class="d-flex align-items-center">
                                <label class="compose-label" style="min-width: 60px; font-weight: 500; color: #6b7280;">Subject:</label>
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control border-0" id="applicationSubject" readonly style="background: transparent; font-weight: 500;">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cover Letter -->
                        <div class="compose-field" style="padding: 24px;">
                            <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 12px;">
                                <i class="fas fa-edit me-2"></i>Cover Letter
                            </label>
                            <textarea name="cover_letter" class="form-control" rows="8" required 
                                      placeholder="Write your cover letter here... Explain why you're interested in this position and how your skills match the job requirements."
                                      style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; font-family: inherit; resize: vertical;"></textarea>
                        </div>
                        
                        <!-- Resume Upload -->
                        <div class="compose-field" style="border-top: 1px solid #e5e7eb; padding: 24px; background: #f8f9fa;">
                            <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 12px;">
                                <i class="fas fa-file-upload me-2"></i>Resume/CV Upload
                            </label>
                            <div class="resume-upload-area" style="border: 2px dashed #d1d5db; border-radius: 12px; padding: 24px; text-align: center; background: white; transition: all 0.3s ease;">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-3">Upload your resume or CV (PDF, DOC, DOCX)</p>
                                <input type="file" id="resumeInput" name="resume" accept=".pdf,.doc,.docx" style="display: none;">
                                <label for="resumeInput" class="btn btn-outline-primary">
                                    <i class="fas fa-plus me-2"></i>Choose File
                                </label>
                                <div id="resumePreview" class="mt-3" style="display: none;">
                                    <div class="d-flex align-items-center justify-content-center p-3" style="background: #e7f3ff; border-radius: 8px;">
                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                        <span id="resumeFileName" class="fw-bold"></span>
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeResume()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Maximum file size: 5MB. Supported formats: PDF, DOC, DOCX
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Gmail-style Footer -->
                <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #e5e7eb; padding: 16px 24px; border-radius: 0 0 12px 12px;">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Cancel
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="saveDraft()">
                                <i class="fas fa-save me-1"></i>Save Draft
                            </button>
                        </div>
                        <div>
                            <input type="hidden" name="job_id" id="applicationJobId">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #dc3545, #c82333); border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600;">
                                <i class="fas fa-paper-plane me-2"></i>Send Application
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openApplicationModal(jobId, jobTitle, company) {
    document.getElementById('applicationJobId').value = jobId;
    document.getElementById('applicationJobTitle').textContent = jobTitle + ' at ' + company;
    document.getElementById('applicationSubject').value = 'Application for ' + jobTitle + ' at ' + company;
    new bootstrap.Modal(document.getElementById('applicationModal')).show();
}

// Resume upload handling
document.getElementById('resumeInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('resumeFileName').textContent = file.name;
        document.getElementById('resumePreview').style.display = 'block';
    }
});

function removeResume() {
    document.getElementById('resumeInput').value = '';
    document.getElementById('resumePreview').style.display = 'none';
}

function saveDraft() {
    // Save draft functionality (can be implemented later)
    alert('Draft saved! (Feature coming soon)');
}

function validateApplicationForm() {
    const form = document.getElementById('applicationForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending Application...';
    submitBtn.disabled = true;
    
    // Validate form
    const coverLetter = form.querySelector('textarea[name="cover_letter"]').value.trim();
    if (!coverLetter) {
        alert('Please write a cover letter before submitting.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return false;
    }
    
    // Allow form to submit normally
    return true;
}

function handleApplicationSubmit(event) {
    // This function is no longer needed since we're using onsubmit
    return true;
}

// Enhanced notification system
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide notification after duration
    const notification = document.querySelector('.toast');
    if (notification) {
        const duration = notification.getAttribute('data-bs-delay') || 5000;
        setTimeout(() => {
            const toast = new bootstrap.Toast(notification);
            toast.hide();
        }, parseInt(duration));
        
        // Add slide-in animation
        notification.style.transform = 'translateX(100%)';
        notification.style.transition = 'transform 0.3s ease-out';
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
    }
});
</script>

<?php include __DIR__ . '/../inc/footer.php'; ?>



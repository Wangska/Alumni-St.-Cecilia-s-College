<?php
$currentPage = 'job-applications';
$pageTitle = 'Job Applications';
$title = 'Job Applications Management';

ob_start();
?>

<style>
.application-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 5px solid #0d6efd;
}

.application-card:hover {
    transform: translateX(8px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
}

.application-header {
    display: flex;
    gap: 20px;
    margin-bottom: 16px;
}

.application-icon {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.application-title {
    font-size: 20px;
    font-weight: 700;
    color: #2d3142;
    margin-bottom: 4px;
}

.application-job {
    font-size: 16px;
    color: #6c757d;
    font-weight: 500;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.status-reviewed {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.status-accepted {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.status-rejected {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

/* Enhanced Button Styling for Capstone */
.btn-outline-primary:hover {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(13, 110, 253, 0.3);
}

.btn-outline-success:hover {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
}

.btn-outline-danger:hover {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
}

.btn-outline-warning:hover {
    background: linear-gradient(135deg, #ffc107, #ff8f00);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 193, 7, 0.3);
}

/* Professional Button Animations */
.btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.resume-link {
    color: #0d6efd;
    text-decoration: none;
    font-weight: 600;
}

.resume-link:hover {
    color: #0a58ca;
    text-decoration: underline;
}
</style>

<!-- Stats & Actions Bar -->
<div class="search-filter-bar mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <div class="number"><?= count($applications ?? []) ?></div>
                    <div class="label">Total Applications</div>
                </div>
            </div>
        </div>
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon" style="background: linear-gradient(135deg, #ffc107, #ff8f00);">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <?php
                    $pending = array_filter($applications ?? [], fn($a) => $a['status'] === 'pending');
                    ?>
                    <div class="number"><?= count($pending) ?></div>
                    <div class="label">Pending Review</div>
                </div>
            </div>
        </div>
        <div class="col-md"></div>
        <div class="col-md-auto">
            <div class="d-flex align-items-center gap-2">
                <select id="statusFilter" class="form-select" style="min-width: 220px; border-radius: 12px;">
                    <option value="all">Show: All statuses</option>
                    <option value="pending">Show: Pending</option>
                    <option value="reviewed">Show: Reviewed</option>
                    <option value="accepted">Show: Accepted</option>
                    <option value="rejected">Show: Rejected</option>
                </select>
                <button class="btn btn-outline-secondary" id="clearFilterBtn" style="border-radius: 12px;">
                    <i class="fas fa-filter-circle-xmark me-1"></i>Clear
                </button>
            </div>
        </div>
        <div class="col-md-auto">
            <a href="/scratch/admin.php?page=careers" class="btn btn-outline-secondary" style="border-radius: 12px; padding: 12px 24px; font-weight: 600;">
                <i class="fas fa-arrow-left me-2"></i>Back to Jobs
            </a>
        </div>
    </div>
</div>

<style>
.search-filter-bar {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.stat-pill {
    background: white;
    padding: 16px 24px;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.stat-pill .icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.stat-pill .number {
    font-size: 24px;
    font-weight: 700;
    color: #2d3142;
}

.stat-pill .label {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
}
</style>

<!-- Applications List -->
<?php if (!empty($applications)): ?>
    <?php foreach ($applications as $application): ?>
        <div class="application-card" data-status="<?= htmlspecialchars($application['status']) ?>">
            <div class="application-header">
                <div class="application-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="application-title"><?= htmlspecialchars($application['applicant_name'] ?? 'Unknown Applicant') ?></h5>
                    <div class="application-job">Applied for: <?= htmlspecialchars($application['job_title'] ?? 'Unknown Position') ?></div>
                </div>
                <div class="text-end">
                    <span class="status-badge status-<?= $application['status'] ?>"><?= ucfirst($application['status']) ?></span>
                    <div class="text-muted small mt-1">
                        <?= date('M d, Y \a\t g:i A', strtotime($application['applied_at'])) ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <h6 class="fw-bold mb-2">Cover Letter:</h6>
                    <p class="text-muted" style="line-height: 1.6;">
                        <?= htmlspecialchars(substr($application['cover_letter'], 0, 200)) ?><?= strlen($application['cover_letter']) > 200 ? '...' : '' ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold mb-2">Resume/CV:</h6>
                    <?php if (!empty($application['resume_file'])): ?>
                        <a href="/scratch/uploads/<?= htmlspecialchars($application['resume_file']) ?>" 
                           target="_blank" class="resume-link">
                            <i class="fas fa-file-pdf me-1"></i>Download Resume
                        </a>
                    <?php else: ?>
                        <span class="text-muted">No resume uploaded</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2 mt-3 pt-3" style="border-top: 1px solid #f1f3f5;">
                <button class="btn btn-sm btn-outline-primary" onclick="viewApplication(<?= $application['id'] ?>, '<?= htmlspecialchars($application['applicant_name']) ?>', '<?= htmlspecialchars($application['job_title']) ?>', '<?= htmlspecialchars($application['company']) ?>', '<?= htmlspecialchars($application['cover_letter']) ?>', '<?= htmlspecialchars($application['resume_file'] ?? '') ?>', '<?= htmlspecialchars($application['status']) ?>', '<?= htmlspecialchars($application['notes'] ?? '') ?>', '<?= $application['applied_at'] ?>')" style="border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-eye me-1"></i>View Details
                </button>
                <?php if ($application['status'] === 'pending'): ?>
                    <button class="btn btn-sm btn-outline-success" onclick="showAcceptModal(<?= $application['id'] ?>, '<?= htmlspecialchars($application['applicant_name']) ?>', '<?= htmlspecialchars($application['job_title']) ?>')" style="border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-check me-1"></i>Accept
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="showRejectModal(<?= $application['id'] ?>, '<?= htmlspecialchars($application['applicant_name']) ?>', '<?= htmlspecialchars($application['job_title']) ?>')" style="border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-times me-1"></i>Reject
                    </button>
                    <button class="btn btn-sm btn-outline-warning" onclick="showReviewedModal(<?= $application['id'] ?>, '<?= htmlspecialchars($application['applicant_name']) ?>', '<?= htmlspecialchars($application['job_title']) ?>')" style="border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-eye me-1"></i>Mark Reviewed
                    </button>
                <?php elseif ($application['status'] === 'reviewed'): ?>
                    <button class="btn btn-sm btn-outline-success" onclick="showAcceptModal(<?= $application['id'] ?>, '<?= htmlspecialchars($application['applicant_name']) ?>', '<?= htmlspecialchars($application['job_title']) ?>')" style="border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-check me-1"></i>Accept
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="showRejectModal(<?= $application['id'] ?>, '<?= htmlspecialchars($application['applicant_name']) ?>', '<?= htmlspecialchars($application['job_title']) ?>')" style="border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-times me-1"></i>Reject
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="content-card text-center py-5">
        <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">No applications yet</h5>
        <p class="text-muted">Job applications will appear here when alumni apply for positions.</p>
    </div>
<?php endif; ?>

<!-- Application Details Modal -->
<div class="modal fade" id="applicationDetailsModal" tabindex="-1" aria-labelledby="applicationDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #0d6efd, #0a58ca); color: white; padding: 24px 30px; border: none; border-radius: 16px 16px 0 0;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-file-alt" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Application Details</h5>
                        <small id="modalApplicantName" style="opacity: 0.9;"></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3" style="color: #2d3142;">Applicant Information</h6>
                        <div class="mb-3">
                            <strong>Name:</strong> <span id="modalApplicantNameText"></span>
                        </div>
                        <div class="mb-3">
                            <strong>Applied for:</strong> <span id="modalJobTitle"></span>
                        </div>
                        <div class="mb-3">
                            <strong>Company:</strong> <span id="modalCompany"></span>
                        </div>
                        <div class="mb-3">
                            <strong>Applied on:</strong> <span id="modalAppliedDate"></span>
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong> <span id="modalStatus" class="status-badge"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3" style="color: #2d3142;">Resume/CV</h6>
                        <div id="modalResumeSection">
                            <!-- Resume content will be populated here -->
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6 class="fw-bold mb-3" style="color: #2d3142;">Cover Letter</h6>
                    <div id="modalCoverLetter" style="background: #f8f9fa; border-radius: 8px; padding: 20px; border-left: 4px solid #0d6efd; line-height: 1.6; white-space: pre-wrap;"></div>
                </div>
                
                <div class="mt-4">
                    <h6 class="fw-bold mb-3" style="color: #2d3142;">Admin Notes</h6>
                    <div id="modalNotes" style="background: #fff3cd; border-radius: 8px; padding: 15px; border-left: 4px solid #ffc107; min-height: 50px;">
                        <em class="text-muted">No notes added yet</em>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #e5e7eb; padding: 20px 30px; border-radius: 0 0 16px 16px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" onclick="openStatusUpdateModal()">
                    <i class="fas fa-edit me-1"></i>Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Accept Application Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 24px 30px; border: none; border-radius: 20px 20px 0 0;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-check" style="font-size: 24px;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Accept Application</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/admin.php?page=job-applications&action=update_status">
                <div class="modal-body" style="padding: 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="application_id" id="acceptApplicationId">
                    <input type="hidden" name="status" value="accepted">
                    
                    <div class="text-center mb-4">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05)); border: 3px solid rgba(16, 185, 129, 0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas fa-user-check" style="font-size: 36px; color: #10b981;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #2d3142; margin-bottom: 8px;">Accept This Application?</h5>
                        <p style="color: #6b7280; margin-bottom: 0;" id="acceptApplicantInfo">Are you sure you want to accept this application?</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Acceptance Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Add notes about why this application was accepted..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #e5e7eb; padding: 20px 30px; border-radius: 0 0 20px 20px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                        <i class="fas fa-check me-2"></i>Accept Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Application Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 24px 30px; border: none; border-radius: 20px 20px 0 0;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-times" style="font-size: 24px;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Reject Application</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/admin.php?page=job-applications&action=update_status">
                <div class="modal-body" style="padding: 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="application_id" id="rejectApplicationId">
                    <input type="hidden" name="status" value="rejected">
                    
                    <div class="text-center mb-4">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05)); border: 3px solid rgba(220, 53, 69, 0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas fa-user-times" style="font-size: 36px; color: #dc3545;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #2d3142; margin-bottom: 8px;">Reject This Application?</h5>
                        <p style="color: #6b7280; margin-bottom: 0;" id="rejectApplicantInfo">Are you sure you want to reject this application?</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rejection Reason (Required)</label>
                        <textarea name="notes" class="form-control" rows="3" required placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #e5e7eb; padding: 20px 30px; border-radius: 0 0 20px 20px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);">
                        <i class="fas fa-times me-2"></i>Reject Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mark Reviewed Modal -->
<div class="modal fade" id="reviewedModal" tabindex="-1" aria-labelledby="reviewedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #ffc107, #ff8f00); color: white; padding: 24px 30px; border: none; border-radius: 20px 20px 0 0;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-eye" style="font-size: 24px;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Mark as Reviewed</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/admin.php?page=job-applications&action=update_status">
                <div class="modal-body" style="padding: 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="application_id" id="reviewedApplicationId">
                    <input type="hidden" name="status" value="reviewed">
                    
                    <div class="text-center mb-4">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05)); border: 3px solid rgba(255, 193, 7, 0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas fa-clipboard-check" style="font-size: 36px; color: #ffc107;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #2d3142; margin-bottom: 8px;">Mark as Reviewed?</h5>
                        <p style="color: #6b7280; margin-bottom: 0;" id="reviewedApplicantInfo">Mark this application as reviewed?</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Review Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Add notes about your review..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #e5e7eb; padding: 20px 30px; border-radius: 0 0 20px 20px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #ffc107, #ff8f00); color: white; border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);">
                        <i class="fas fa-eye me-2"></i>Mark as Reviewed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #ffc107, #ff8f00); color: white; padding: 20px 25px; border: none; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title mb-0" style="font-weight: 700;">Update Application Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/admin.php?page=job-applications&action=update_status">
                <div class="modal-body" style="padding: 25px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="application_id" id="statusUpdateApplicationId">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="reviewed">Reviewed</option>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Admin Notes</label>
                        <textarea name="notes" class="form-control" rows="4" placeholder="Add notes about this application..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #e5e7eb; padding: 15px 25px; border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentApplicationId = null;

function viewApplication(id, applicantName, jobTitle, company, coverLetter, resumeFile, status, notes, appliedDate) {
    currentApplicationId = id;
    
    // Populate modal with data
    document.getElementById('modalApplicantName').textContent = applicantName;
    document.getElementById('modalApplicantNameText').textContent = applicantName;
    document.getElementById('modalJobTitle').textContent = jobTitle;
    document.getElementById('modalCompany').textContent = company;
    document.getElementById('modalCoverLetter').textContent = coverLetter;
    document.getElementById('modalAppliedDate').textContent = new Date(appliedDate).toLocaleString();
    
    // Set status badge
    const statusElement = document.getElementById('modalStatus');
    statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    statusElement.className = 'status-badge status-' + status;
    
    // Handle resume section
    const resumeSection = document.getElementById('modalResumeSection');
    if (resumeFile) {
        resumeSection.innerHTML = `
            <a href="/scratch/uploads/${resumeFile}" target="_blank" class="resume-link">
                <i class="fas fa-file-pdf me-1"></i>Download Resume/CV
            </a>
        `;
    } else {
        resumeSection.innerHTML = '<span class="text-muted">No resume uploaded</span>';
    }
    
    // Handle notes
    const notesElement = document.getElementById('modalNotes');
    if (notes && notes.trim()) {
        notesElement.innerHTML = notes;
        notesElement.style.background = '#d1ecf1';
        notesElement.style.borderLeftColor = '#0dcaf0';
    } else {
        notesElement.innerHTML = '<em class="text-muted">No notes added yet</em>';
    }
    
    new bootstrap.Modal(document.getElementById('applicationDetailsModal')).show();
}

function showAcceptModal(id, applicantName, jobTitle) {
    document.getElementById('acceptApplicationId').value = id;
    document.getElementById('acceptApplicantInfo').textContent = `Accept ${applicantName}'s application for ${jobTitle}?`;
    new bootstrap.Modal(document.getElementById('acceptModal')).show();
}

function showRejectModal(id, applicantName, jobTitle) {
    document.getElementById('rejectApplicationId').value = id;
    document.getElementById('rejectApplicantInfo').textContent = `Reject ${applicantName}'s application for ${jobTitle}?`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

function showReviewedModal(id, applicantName, jobTitle) {
    document.getElementById('reviewedApplicationId').value = id;
    document.getElementById('reviewedApplicantInfo').textContent = `Mark ${applicantName}'s application for ${jobTitle} as reviewed?`;
    new bootstrap.Modal(document.getElementById('reviewedModal')).show();
}

function updateApplicationStatus(id, status) {
    if (confirm(`Are you sure you want to ${status} this application?`)) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/scratch/admin.php?page=job-applications&action=update_status';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = 'csrf_token';
        csrfToken.value = '<?= csrf_token() ?>';
        
        const applicationId = document.createElement('input');
        applicationId.type = 'hidden';
        applicationId.name = 'application_id';
        applicationId.value = id;
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        
        form.appendChild(csrfToken);
        form.appendChild(applicationId);
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function openStatusUpdateModal() {
    if (currentApplicationId) {
        document.getElementById('statusUpdateApplicationId').value = currentApplicationId;
        new bootstrap.Modal(document.getElementById('statusUpdateModal')).show();
    }
}

// Client-side filter by status
document.addEventListener('DOMContentLoaded', function() {
    const filter = document.getElementById('statusFilter');
    const clearBtn = document.getElementById('clearFilterBtn');
    const cards = document.querySelectorAll('.application-card');

    function applyFilter(value) {
        cards.forEach(card => {
            const status = card.getAttribute('data-status');
            card.style.display = (value === 'all' || status === value) ? '' : 'none';
        });
    }

    if (filter) {
        filter.addEventListener('change', function() {
            applyFilter(this.value);
        });
    }
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            if (filter) filter.value = 'all';
            applyFilter('all');
        });
    }
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>

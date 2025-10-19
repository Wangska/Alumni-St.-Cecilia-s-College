<?php
$currentPage = 'careers';
$pageTitle = 'Job Postings';
$title = 'Job Postings Management';

ob_start();
?>

<style>
.job-card-modern {
    background: white;
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 5px solid #dc3545;
    position: relative;
}

.job-card-modern:hover {
    transform: translateX(8px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
}

.job-header {
    display: flex;
    gap: 20px;
    margin-bottom: 16px;
}

.job-icon {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.job-title-modern {
    font-size: 22px;
    font-weight: 700;
    color: #2d3142;
    margin-bottom: 4px;
}

.job-company {
    font-size: 16px;
    color: #6c757d;
    font-weight: 500;
}

.job-meta-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
}

.badge-location {
    padding: 8px 16px;
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    border-radius: 50px;
    font-weight: 600;
    font-size: 13px;
}

.badge-date {
    padding: 8px 16px;
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    border-radius: 50px;
    font-weight: 600;
    font-size: 13px;
}

.job-description-preview {
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 16px;
}

.job-footer {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding-top: 16px;
    border-top: 2px solid #f1f3f5;
}

.btn-action {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    font-size: 14px;
    position: relative;
    overflow: hidden;
}

.btn-action::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-action:hover::before {
    opacity: 1;
}

.btn-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
}

.btn-action.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    color: white;
}

.btn-action.btn-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}
</style>

<!-- Stats & Actions Bar -->
<div class="search-filter-bar mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div>
                    <div class="number"><?= count($careers ?? []) ?></div>
                    <div class="label">Total Job Postings</div>
                </div>
            </div>
        </div>
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <?php
                    $recent = array_filter($careers ?? [], fn($c) => strtotime($c['date_created'] ?? 'now') >= strtotime('-30 days'));
                    ?>
                    <div class="number"><?= count($recent) ?></div>
                    <div class="label">This Month</div>
                </div>
            </div>
        </div>
        <div class="col-md"></div>
        <div class="col-md-auto">
            <button class="btn-add-modern" data-bs-toggle="modal" data-bs-target="#addJobModal">
                <i class="fas fa-plus me-2"></i>Add New Job
            </button>
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

.btn-add-modern {
    padding: 12px 24px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    display: inline-flex;
    align-items: center;
    cursor: pointer;
}

.btn-add-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.6);
    background: linear-gradient(135deg, #b02a37, #991b1b);
    color: white;
}
</style>

<!-- Job Listings -->
<?php if (!empty($careers)): ?>
    <?php foreach ($careers as $career): ?>
        <div class="job-card-modern">
            <div class="job-header">
                <div class="job-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="job-title-modern"><?= htmlspecialchars($career['job_title'] ?? '') ?></h5>
                    <div class="job-company"><?= htmlspecialchars($career['company'] ?? '') ?></div>
                </div>
            </div>
            
            <div class="job-meta-badges">
                <span class="badge-location">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    <?= htmlspecialchars($career['location'] ?? '') ?>
                </span>
                <span class="badge-date">
                    <i class="far fa-calendar me-1"></i>
                    Posted <?= date('M d, Y', strtotime($career['date_created'] ?? 'now')) ?>
                </span>
            </div>
            
            <div class="job-description-preview">
                <?= htmlspecialchars(substr(strip_tags($career['description'] ?? ''), 0, 200)) ?>...
            </div>
            
            <div class="job-footer">
                <button style="background: linear-gradient(135deg, #17a2b8, #138496); color: white; border: none; border-radius: 10px; padding: 8px 20px; font-weight: 600; font-size: 13px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);" 
                        data-bs-toggle="modal" 
                        data-bs-target="#viewJobModal<?= $career['id'] ?>"
                        title="View Details"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(23, 162, 184, 0.4)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(23, 162, 184, 0.3)';">
                    <i class="fas fa-eye me-2"></i>View Details
                </button>
                <button class="btn-action btn-primary" 
                        onclick="editJob(<?= $career['id'] ?>, '<?= htmlspecialchars($career['job_title'], ENT_QUOTES) ?>', '<?= htmlspecialchars($career['company'], ENT_QUOTES) ?>', '<?= htmlspecialchars($career['location'], ENT_QUOTES) ?>', `<?= htmlspecialchars($career['description'], ENT_QUOTES) ?>`)"
                        title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-action btn-danger" 
                        onclick="deleteJob(<?= $career['id'] ?>, '<?= htmlspecialchars($career['job_title'], ENT_QUOTES) ?>')"
                        title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        
        <!-- View Job Modal -->
        <div class="modal fade" id="viewJobModal<?= $career['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                    <!-- Header -->
                    <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8, #138496); color: white; padding: 24px 30px; border: none;">
                        <div class="d-flex align-items-center">
                            <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                <i class="fas fa-briefcase" style="font-size: 24px;"></i>
                            </div>
                            <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Job Details</h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <!-- Body -->
                    <div class="modal-body" style="padding: 35px; max-height: 70vh; overflow-y: auto;">
                        <!-- Job Header -->
                        <div style="background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05)); border-left: 4px solid #dc3545; border-radius: 12px; padding: 24px; margin-bottom: 28px;">
                            <h3 style="color: #dc3545; font-weight: 700; margin-bottom: 8px; font-size: 24px;">
                                <?= htmlspecialchars($career['job_title']) ?>
                            </h3>
                            <h5 style="color: #6c757d; font-weight: 600; margin-bottom: 0; font-size: 18px;">
                                <i class="fas fa-building me-2"></i><?= htmlspecialchars($career['company']) ?>
                            </h5>
                        </div>
                        
                        <!-- Job Info Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div style="background: white; border: 2px solid #e9ecef; border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #dc3545, #c82333); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                            <i class="fas fa-map-marker-alt" style="color: white; font-size: 20px;"></i>
                                        </div>
                                        <div>
                                            <p style="color: #6c757d; font-size: 12px; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Location</p>
                                            <p style="color: #2d3142; font-size: 16px; font-weight: 600; margin: 0;">
                                                <?= htmlspecialchars($career['location']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div style="background: white; border: 2px solid #e9ecef; border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #17a2b8, #138496); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                            <i class="far fa-calendar" style="color: white; font-size: 20px;"></i>
                                        </div>
                                        <div>
                                            <p style="color: #6c757d; font-size: 12px; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Posted Date</p>
                                            <p style="color: #2d3142; font-size: 16px; font-weight: 600; margin: 0;">
                                                <?= date('F d, Y', strtotime($career['date_created'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Job Description -->
                        <div style="margin-top: 28px;">
                            <h6 style="color: #2d3142; font-weight: 700; margin-bottom: 16px; font-size: 16px; display: flex; align-items: center;">
                                <i class="fas fa-file-alt me-2" style="color: #17a2b8;"></i>
                                Job Description
                            </h6>
                            <div style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border: 2px solid #dee2e6; border-radius: 12px; padding: 24px; line-height: 1.8; color: #495057; font-size: 15px;">
                                <?= nl2br(htmlspecialchars(strip_tags($career['description']))) ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #f1f3f5; background: #f8f9fa;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600; border: 2px solid #6c757d;">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        #viewJobModal<?= $career['id'] ?> .modal-body::-webkit-scrollbar {
            width: 8px;
        }
        
        #viewJobModal<?= $career['id'] ?> .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        #viewJobModal<?= $career['id'] ?> .modal-body::-webkit-scrollbar-thumb {
            background: #17a2b8;
            border-radius: 10px;
        }
        
        #viewJobModal<?= $career['id'] ?> .modal-body::-webkit-scrollbar-thumb:hover {
            background: #138496;
        }
        </style>
    <?php endforeach; ?>
<?php else: ?>
    <div class="content-card text-center py-5">
        <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">No job postings yet</h5>
        <p class="text-muted">Add your first job posting to get started.</p>
        <button class="btn btn-modern btn-modern-primary mt-3" data-bs-toggle="modal" data-bs-target="#addJobModal">
            <i class="fas fa-plus me-2"></i>Add First Job
        </button>
    </div>
<?php endif; ?>

<!-- Add Job Modal -->
<div class="modal fade" id="addJobModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 24px 30px; border: none;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-briefcase" style="font-size: 24px;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Add New Job Posting</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/admin.php?page=careers&action=add">
                <div class="modal-body" style="padding: 30px; max-height: 70vh; overflow-y: auto;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <!-- Job Icon -->
                    <div class="text-center mb-4">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05)); border: 3px solid rgba(220, 53, 69, 0.2); border-radius: 20px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="fas fa-briefcase" style="font-size: 36px; color: #dc3545;"></i>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                                Job Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="job_title" class="form-control" required placeholder="e.g., Software Engineer" 
                                   style="border: 2px solid #e9ecef; border-radius: 12px; padding: 12px 18px;">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                                Company <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="company" class="form-control" required placeholder="e.g., Tech Corp" 
                                   style="border: 2px solid #e9ecef; border-radius: 12px; padding: 12px 18px;">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                                Location <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="border: 2px solid #e9ecef; border-right: none; border-radius: 12px 0 0 12px; background: #f8f9fa;">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                </span>
                                <input type="text" name="location" class="form-control" required placeholder="e.g., Cebu City, Philippines" 
                                       style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 12px 12px 0; padding: 12px 18px;">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                                Job Description <span class="text-danger">*</span>
                            </label>
                            <textarea name="description" class="form-control" rows="10" required placeholder="Enter detailed job description including:&#10;• Job responsibilities&#10;• Required qualifications&#10;• Skills and experience&#10;• Benefits and compensation&#10;• Application process" 
                                      style="border: 2px solid #e9ecef; border-radius: 12px; padding: 12px 18px; font-family: inherit;"></textarea>
                            <small class="text-muted">Be as detailed as possible to attract the right candidates</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #f1f3f5; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" 
                            style="border-radius: 12px; padding: 12px 28px; font-weight: 600; border: 2px solid #6c757d;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn" 
                            style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);">
                        <i class="fas fa-paper-plane me-2"></i>Post Job
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
#addJobModal .form-control:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
}

#addJobModal .btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

#addJobModal .input-group-text {
    border-color: #e9ecef;
}

#addJobModal .modal-body::-webkit-scrollbar {
    width: 8px;
}

#addJobModal .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#addJobModal .modal-body::-webkit-scrollbar-thumb {
    background: #dc3545;
    border-radius: 10px;
}

#addJobModal .modal-body::-webkit-scrollbar-thumb:hover {
    background: #c82333;
}
</style>

<!-- Edit Job Modal -->
<div class="modal fade" id="editJobModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #0d6efd, #0a58ca); color: white; padding: 24px 30px; border: none;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-edit" style="font-size: 22px;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Edit Job Posting</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/admin.php?page=careers&action=edit">
                <div class="modal-body" style="padding: 30px; max-height: 70vh; overflow-y: auto;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="id" id="edit_job_id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                                Job Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="job_title" id="edit_job_title" class="form-control" required 
                                   style="border: 2px solid #e9ecef; border-radius: 12px; padding: 12px 18px;">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                                Company <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="company" id="edit_company" class="form-control" required 
                                   style="border: 2px solid #e9ecef; border-radius: 12px; padding: 12px 18px;">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                                Location <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="border: 2px solid #e9ecef; border-right: none; border-radius: 12px 0 0 12px; background: #f8f9fa;">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </span>
                                <input type="text" name="location" id="edit_location" class="form-control" required 
                                       style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 12px 12px 0; padding: 12px 18px;">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                                Job Description <span class="text-danger">*</span>
                            </label>
                            <textarea name="description" id="edit_description" class="form-control" rows="10" required 
                                      style="border: 2px solid #e9ecef; border-radius: 12px; padding: 12px 18px; font-family: inherit;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #f1f3f5; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" 
                            style="border-radius: 12px; padding: 12px 28px; font-weight: 600; border: 2px solid #6c757d;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn" 
                            style="background: linear-gradient(135deg, #0d6efd, #0a58ca); color: white; border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);">
                        <i class="fas fa-save me-2"></i>Update Job
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
#editJobModal .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

#editJobModal .btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

#editJobModal .modal-body::-webkit-scrollbar {
    width: 8px;
}

#editJobModal .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#editJobModal .modal-body::-webkit-scrollbar-thumb {
    background: #0d6efd;
    border-radius: 10px;
}

#editJobModal .modal-body::-webkit-scrollbar-thumb:hover {
    background: #0a58ca;
}
</style>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteJobModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 24px 30px; border: none;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Confirm Deletion</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/admin.php?page=careers&action=delete">
                <div class="modal-body" style="padding: 40px 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="id" id="delete_job_id">
                    
                    <div class="text-center mb-4">
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05)); border: 3px solid rgba(220, 53, 69, 0.3); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                            <i class="fas fa-briefcase" style="font-size: 40px; color: #dc3545;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #2d3142; margin-bottom: 12px;">Delete Job Posting?</h5>
                        <p style="color: #6c757d; margin-bottom: 8px;">Are you sure you want to delete this job posting?</p>
                        <p style="font-weight: 600; color: #dc3545; font-size: 16px;" id="delete_job_title"></p>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #fff3cd, #fff8e1); border-left: 4px solid #ffc107; border-radius: 12px; padding: 16px; margin-top: 20px;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle" style="color: #ff9800; font-size: 20px; margin-right: 12px; margin-top: 2px;"></i>
                            <div>
                                <strong style="color: #856404; display: block; margin-bottom: 4px;">Warning</strong>
                                <span style="color: #856404; font-size: 14px;">This action cannot be undone. The job posting will be permanently removed.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #f1f3f5; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600; border: 2px solid #6c757d;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);">
                        <i class="fas fa-trash-alt me-2"></i>Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
#deleteJobModal .btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>

<script>
function editJob(id, title, company, location, description) {
    document.getElementById('edit_job_id').value = id;
    document.getElementById('edit_job_title').value = title;
    document.getElementById('edit_company').value = company;
    document.getElementById('edit_location').value = location;
    document.getElementById('edit_description').value = description;
    new bootstrap.Modal(document.getElementById('editJobModal')).show();
}

function deleteJob(id, title) {
    document.getElementById('delete_job_id').value = id;
    document.getElementById('delete_job_title').textContent = title;
    new bootstrap.Modal(document.getElementById('deleteJobModal')).show();
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>


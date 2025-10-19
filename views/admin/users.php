<?php
$currentPage = 'users';
$pageTitle = 'User Accounts';
$title = 'User Accounts';

ob_start();
?>

<style>
.verification-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 5px solid #ffc107;
}

.verification-card:hover {
    transform: translateX(8px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
}

.alumni-profile {
    display: flex;
    gap: 20px;
    align-items: start;
}

.profile-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    object-fit: cover;
    border: 3px solid #ffc107;
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    flex-shrink: 0;
}

.profile-info h5 {
    font-size: 20px;
    font-weight: 700;
    color: #2d3142;
    margin-bottom: 4px;
}

.profile-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 12px;
}

.meta-badge {
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
}

.verification-actions {
    display: flex;
    gap: 8px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 2px solid #f1f3f5;
}

.btn-approve {
    background: linear-gradient(135deg, #198754, #146c43);
    color: white;
    padding: 10px 24px;
    border-radius: 12px;
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(25, 135, 84, 0.4);
    color: white;
}

.btn-reject {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    padding: 10px 24px;
    border-radius: 12px;
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(220, 53, 69, 0.4);
    color: white;
}
</style>

<!-- Statistics Cards -->
<div class="search-filter-bar mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon" style="background: linear-gradient(135deg, #ffc107, #ffb300);">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="number"><?= number_format($stats['pending'] ?? 0) ?></div>
                    <div class="label">Pending Verification</div>
                </div>
            </div>
        </div>
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="number"><?= number_format($stats['verified'] ?? 0) ?></div>
                    <div class="label">Verified Alumni</div>
                </div>
            </div>
        </div>
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="number"><?= number_format($stats['total'] ?? 0) ?></div>
                    <div class="label">Total Alumni</div>
                </div>
            </div>
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

<!-- Pending Alumni List -->
<?php if (!empty($pendingAlumni)): ?>
    <?php foreach ($pendingAlumni as $alumni): ?>
        <div class="verification-card">
            <div class="alumni-profile">
                <?php
                $avatarPath = !empty($alumni['avatar']) ? '/scratch/uploads/' . basename($alumni['avatar']) : '/scratch/uploads/default-avatar.png';
                ?>
                <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Avatar" class="profile-avatar-large" onerror="this.src='/scratch/uploads/default-avatar.png'">
                
                <div class="flex-grow-1">
                    <h5><?= htmlspecialchars(($alumni['firstname'] ?? '') . ' ' . ($alumni['middlename'] ?? '') . ' ' . ($alumni['lastname'] ?? '')) ?></h5>
                    
                    <div class="profile-meta">
                        <span class="meta-badge bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-graduation-cap me-1"></i>
                            <?= htmlspecialchars($alumni['course'] ?? 'N/A') ?>
                        </span>
                        <span class="meta-badge bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-calendar me-1"></i>
                            Batch <?= htmlspecialchars($alumni['batch'] ?? 'N/A') ?>
                        </span>
                        <?php if (!empty($alumni['email'])): ?>
                            <span class="meta-badge bg-info bg-opacity-10 text-info">
                                <i class="fas fa-envelope me-1"></i>
                                <?= htmlspecialchars($alumni['email']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($alumni['currently_on'])): ?>
                        <p class="text-muted mt-2 mb-0 small">
                            <i class="fas fa-briefcase me-1"></i>
                            Currently: <?= htmlspecialchars($alumni['currently_on']) ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="verification-actions">
                        <button class="btn-approve" 
                                onclick="approveAlumni(<?= $alumni['id'] ?>, '<?= htmlspecialchars(($alumni['firstname'] ?? '') . ' ' . ($alumni['lastname'] ?? '')) ?>')">
                            <i class="fas fa-check me-2"></i>Approve
                        </button>
                        <button class="btn-reject" 
                                onclick="rejectAlumni(<?= $alumni['id'] ?>, '<?= htmlspecialchars(($alumni['firstname'] ?? '') . ' ' . ($alumni['lastname'] ?? '')) ?>')">
                            <i class="fas fa-times me-2"></i>Reject
                        </button>
                        <button class="btn btn-outline-info" 
                                onclick="viewDocuments(<?= $alumni['id'] ?>, '<?= htmlspecialchars(($alumni['firstname'] ?? '') . ' ' . ($alumni['lastname'] ?? '')) ?>')"
                                style="padding: 10px 24px; border-radius: 12px; font-weight: 600;">
                            <i class="fas fa-file-alt me-2"></i>View Documents
                        </button>
                        <a href="/scratch/alumni/edit.php?id=<?= $alumni['id'] ?>&from=users" 
                           class="btn btn-outline-secondary" 
                           style="padding: 10px 24px; border-radius: 12px; font-weight: 600;">
                            <i class="fas fa-eye me-2"></i>View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="content-card text-center py-5">
        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
        <h5 class="text-muted">No pending verifications</h5>
        <p class="text-muted">All alumni accounts have been verified!</p>
    </div>
<?php endif; ?>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Approve Alumni</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/admin.php?page=users&action=approve">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="alumni_id" id="approve_alumni_id">
                    <div class="text-center">
                        <i class="fas fa-user-check fa-4x text-success mb-3"></i>
                        <h5>Are you sure you want to approve this alumni?</h5>
                        <p class="text-muted mb-0" id="approve_alumni_name"></p>
                    </div>
                    <div class="alert alert-success mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        This will verify the alumni account and grant access to the system.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Yes, Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Reject Alumni</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/alumni/delete.php?from=users">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="id" id="reject_alumni_id">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                        <h5>Are you sure you want to reject this alumni?</h5>
                        <p class="text-muted mb-0" id="reject_alumni_name"></p>
                    </div>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This will permanently delete the alumni account!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Yes, Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Documents Modal -->
<div class="modal fade" id="documentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <!-- Header -->
            <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8, #138496); color: white; padding: 24px 30px; border: none;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-file-alt" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Verification Documents</h5>
                        <p class="mb-0" style="font-size: 14px; opacity: 0.9;">Review uploaded documents for <span id="documents_alumni_name" style="font-weight: 600;"></span></p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.2); border-radius: 8px; padding: 8px;"></button>
            </div>
            
            <!-- Body -->
            <div class="modal-body" style="padding: 30px; background: #f8f9fa;">
                <input type="hidden" id="documents_alumni_id">
                
                <!-- Loading State -->
                <div id="documentsLoading" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading documents...</p>
                </div>
                
                <!-- Documents Container -->
                <div id="documentsContainer" class="row g-4">
                    <!-- Documents will be loaded here -->
                </div>
            </div>
            
            <!-- Footer -->
            <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #e9ecef; background: white;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600; border: 2px solid #6c757d;">
                    <i class="fas fa-times me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Document Modal Styles */
.documents-container {
    max-height: 500px;
    overflow-y: auto;
    padding-right: 10px;
}

/* Custom Scrollbar */
.documents-container::-webkit-scrollbar {
    width: 8px;
}

.documents-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.documents-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #17a2b8, #138496);
    border-radius: 10px;
}

.documents-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #138496, #0f6674);
}

/* Document Card Styles */
.document-card {
    background: white;
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.document-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #17a2b8, #138496);
}

.document-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.document-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.document-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
    flex-shrink: 0;
}

.document-info {
    flex: 1;
}

.document-info h6 {
    margin: 0 0 8px 0;
    font-size: 16px;
    font-weight: 700;
    color: #2d3142;
    line-height: 1.3;
}

.document-info small {
    color: #6c757d;
    font-size: 13px;
    font-weight: 500;
    display: block;
    margin-bottom: 4px;
}

.document-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.document-actions .btn {
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 10px;
    transition: all 0.3s ease;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.document-actions .btn-primary {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
    box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
}

.document-actions .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
    background: linear-gradient(135deg, #138496, #0f6674);
}

.document-actions .btn-outline-primary {
    background: transparent;
    color: #17a2b8;
    border: 2px solid #17a2b8;
}

.document-actions .btn-outline-primary:hover {
    background: #17a2b8;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
}

/* Empty State */
.documents-empty {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.documents-empty i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.documents-empty h5 {
    font-weight: 600;
    margin-bottom: 10px;
}

.documents-empty p {
    font-size: 14px;
    margin: 0;
}

/* Error State */
.documents-error {
    text-align: center;
    padding: 60px 20px;
    color: #dc3545;
}

.documents-error i {
    font-size: 4rem;
    margin-bottom: 20px;
}

.documents-error h5 {
    font-weight: 600;
    margin-bottom: 10px;
}

.documents-error p {
    font-size: 14px;
    margin: 0;
}

/* Loading Animation */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.document-card.loading {
    animation: pulse 1.5s ease-in-out infinite;
}

/* Responsive Design */
@media (max-width: 768px) {
    .document-header {
        flex-direction: column;
        text-align: center;
    }
    
    .document-icon {
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .document-actions {
        flex-direction: column;
    }
    
    .document-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function approveAlumni(id, name) {
    document.getElementById('approve_alumni_id').value = id;
    document.getElementById('approve_alumni_name').textContent = name;
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}

function rejectAlumni(id, name) {
    document.getElementById('reject_alumni_id').value = id;
    document.getElementById('reject_alumni_name').textContent = name;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

function viewDocuments(id, name) {
    document.getElementById('documents_alumni_id').value = id;
    document.getElementById('documents_alumni_name').textContent = name;
    
    // Show loading state
    document.getElementById('documentsLoading').style.display = 'block';
    document.getElementById('documentsContainer').innerHTML = '';
    
    // Load documents via AJAX
    fetch(`/scratch/get_documents.php?alumni_id=${id}`, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Hide loading state
            document.getElementById('documentsLoading').style.display = 'none';
            const container = document.getElementById('documentsContainer');
            container.innerHTML = '';
            
            // Check for error in response
            if (data.error) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="documents-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h5>Error Loading Documents</h5>
                            <p>${data.error}</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            if (data.documents && data.documents.length > 0) {
                data.documents.forEach(doc => {
                    const docCard = document.createElement('div');
                    docCard.className = 'col-md-6';
                    docCard.innerHTML = `
                        <div class="document-card">
                            <div class="document-header">
                                <div class="document-icon">
                                    <i class="fas fa-file-${getFileIcon(doc.document_type)}"></i>
                                </div>
                                <div class="document-info">
                                    <h6>${doc.document_name}</h6>
                                    <small>${doc.document_type.toUpperCase()}</small>
                                    <small>${formatFileSize(doc.file_size)} • ${formatDate(doc.upload_date)}</small>
                                </div>
                            </div>
                            <div class="document-actions">
                                <a href="/scratch/uploads/${doc.file_path}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-eye"></i>View
                                </a>
                                <a href="/scratch/uploads/${doc.file_path}" download class="btn btn-outline-primary">
                                    <i class="fas fa-download"></i>Download
                                </a>
                            </div>
                        </div>
                    `;
                    container.appendChild(docCard);
                });
            } else {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="documents-empty">
                            <i class="fas fa-file-alt"></i>
                            <h5>No Documents Uploaded</h5>
                            <p>This user hasn't uploaded any verification documents yet.</p>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading documents:', error);
            console.error('Response status:', error.message);
            
            // Hide loading state
            document.getElementById('documentsLoading').style.display = 'none';
            
            document.getElementById('documentsContainer').innerHTML = `
                <div class="col-12">
                    <div class="documents-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h5>Connection Error</h5>
                        <p>Unable to load documents. Please check your connection and try again.</p>
                        <small>Error: ${error.message}</small>
                    </div>
                </div>
            `;
        });
    
    new bootstrap.Modal(document.getElementById('documentsModal')).show();
}

function getFileIcon(type) {
    const icons = {
        'tor': 'pdf',
        'diploma': 'pdf',
        'id_card': 'image',
        'other': 'alt'
    };
    return icons[type] || 'alt';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString();
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>

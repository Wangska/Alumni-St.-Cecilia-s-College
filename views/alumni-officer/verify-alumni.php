<!-- Pending Alumni Accounts -->
<div class="stat-card mb-4">
    <h5 class="mb-4" style="color: #7f1d1d; font-weight: 700; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-user-clock" style="color: #f59e0b;"></i>
        Pending Alumni Accounts (<?= count($pendingAlumni) ?>)
    </h5>
    
    <?php if (empty($pendingAlumni)): ?>
        <div class="text-center py-5">
            <i class="fas fa-check-circle" style="font-size: 48px; color: #10b981; margin-bottom: 15px;"></i>
            <p class="text-muted mb-0">No pending accounts to verify</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover" style="border-radius: 12px; overflow: hidden;">
                <thead style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white;">
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Batch</th>
                        <th>User ID</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingAlumni as $alumni): ?>
                        <tr>
                            <td style="font-weight: 600; color: #7f1d1d;">
                                <?= htmlspecialchars($alumni['firstname'] . ' ' . $alumni['lastname']) ?>
                            </td>
                            <td><?= htmlspecialchars($alumni['username']) ?></td>
                            <td><?= htmlspecialchars($alumni['email'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($alumni['course'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($alumni['batch'] ?? 'N/A') ?></td>
                            <td>User #<?= $alumni['id'] ?></td>
                            <td class="text-center" style="white-space: nowrap;">
                                <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                    <button type="button" class="btn btn-sm btn-info" 
                                            onclick="viewDocuments(<?= $alumni['alumnus_id'] ?>, '<?= htmlspecialchars($alumni['firstname'] . ' ' . $alumni['lastname']) ?>')"
                                            style="border-radius: 8px; padding: 6px 16px;">
                                        <i class="fas fa-file-alt"></i> Docs
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success" 
                                            onclick="approveAlumni(<?= $alumni['id'] ?>, '<?= htmlspecialchars($alumni['firstname'] . ' ' . $alumni['lastname']) ?>')"
                                            style="border-radius: 8px; padding: 6px 16px;">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="rejectAlumni(<?= $alumni['id'] ?>, '<?= htmlspecialchars($alumni['firstname'] . ' ' . $alumni['lastname']) ?>')"
                                            style="border-radius: 8px; padding: 6px 16px;">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Verified Alumni -->
<div class="stat-card">
    <h5 class="mb-4" style="color: #7f1d1d; font-weight: 700; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-user-check" style="color: #10b981;"></i>
        Verified Alumni (<?= count($verifiedAlumni) ?>)
    </h5>
    
    <?php if (empty($verifiedAlumni)): ?>
        <div class="text-center py-5">
            <i class="fas fa-users" style="font-size: 48px; color: #9ca3af; margin-bottom: 15px;"></i>
            <p class="text-muted mb-0">No verified alumni yet</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover" style="border-radius: 12px; overflow: hidden;">
                <thead style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Batch</th>
                        <th>User ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($verifiedAlumni as $alumni): ?>
                        <tr>
                            <td style="font-weight: 600; color: #7f1d1d;">
                                <?= htmlspecialchars($alumni['firstname'] . ' ' . $alumni['lastname']) ?>
                            </td>
                            <td><?= htmlspecialchars($alumni['username']) ?></td>
                            <td><?= htmlspecialchars($alumni['email'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($alumni['course'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($alumni['batch'] ?? 'N/A') ?></td>
                            <td>User #<?= $alumni['id'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 24px 30px; border: none;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Approve Alumni</h5>
                        <p class="mb-0" style="font-size: 14px; opacity: 0.9;">Verify and approve this alumni account</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.2); border-radius: 8px; padding: 8px;"></button>
            </div>
            <form method="POST" action="/scratch/alumni-officer.php?page=verify-alumni&action=approve" id="approveForm">
                <div class="modal-body" style="padding: 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="approve">
                    <input type="hidden" name="id" id="approve_alumni_id">
                    
                    <div class="text-center">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #d1fae5, #a7f3d0); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="fas fa-user-check" style="font-size: 40px; color: #059669;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #111827; margin-bottom: 10px;">Are you sure you want to approve?</h5>
                        <p style="color: #6b7280; margin-bottom: 20px;" id="approve_alumni_name"></p>
                        
                        <div style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); border-left: 4px solid #10b981; padding: 16px; border-radius: 12px; text-align: left;">
                            <div style="display: flex; align-items-start; gap: 12px;">
                                <i class="fas fa-info-circle" style="color: #059669; font-size: 20px; margin-top: 2px;"></i>
                                <div style="flex: 1;">
                                    <h6 style="color: #065f46; font-weight: 600; margin-bottom: 8px;">What happens next?</h6>
                                    <ul style="color: #047857; margin: 0; padding-left: 20px; font-size: 14px;">
                                        <li>Alumni account will be verified</li>
                                        <li>User will gain full access to the portal</li>
                                        <li>Alumni can participate in events and forums</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600; border: 2px solid #6c757d;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success" style="background: linear-gradient(135deg, #10b981, #059669); border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                        <i class="fas fa-check me-2"></i>Yes, Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; padding: 24px 30px; border: none;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Reject Alumni</h5>
                        <p class="mb-0" style="font-size: 14px; opacity: 0.9;">Reject and remove this alumni account</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.2); border-radius: 8px; padding: 8px;"></button>
            </div>
            <form method="POST" action="/scratch/alumni-officer.php?page=verify-alumni&action=reject" id="rejectForm">
                <div class="modal-body" style="padding: 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="reject">
                    <input type="hidden" name="id" id="reject_alumni_id">
                    
                    <div class="text-center">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #fee2e2, #fecaca); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="fas fa-user-times" style="font-size: 40px; color: #dc2626;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #111827; margin-bottom: 10px;">Are you sure you want to reject?</h5>
                        <p style="color: #6b7280; margin-bottom: 20px;" id="reject_alumni_name"></p>
                        
                        <div style="background: linear-gradient(135deg, #fee2e2, #fecaca); border-left: 4px solid #dc2626; padding: 16px; border-radius: 12px; text-align: left;">
                            <div style="display: flex; align-items-start; gap: 12px;">
                                <i class="fas fa-exclamation-triangle" style="color: #dc2626; font-size: 20px; margin-top: 2px;"></i>
                                <div style="flex: 1;">
                                    <h6 style="color: #991b1b; font-weight: 600; margin-bottom: 8px;">Warning: This action cannot be undone!</h6>
                                    <ul style="color: #b91c1c; margin: 0; padding-left: 20px; font-size: 14px;">
                                        <li>Alumni account will be permanently deleted</li>
                                        <li>All associated data will be removed</li>
                                        <li>User will need to register again</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600; border: 2px solid #6c757d;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #dc2626, #b91c1c); border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);">
                        <i class="fas fa-trash me-2"></i>Yes, Reject & Delete
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
            <div class="modal-header" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; padding: 24px 30px; border: none;">
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
                    <div class="spinner-border text-danger" role="status" style="width: 3rem; height: 3rem;">
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
.table {
    font-size: 14px;
}
.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 14px;
}
.table tbody td {
    padding: 14px;
    vertical-align: middle;
}
.table tbody tr {
    transition: all 0.3s ease;
}
.table tbody tr:hover {
    background-color: rgba(220, 38, 38, 0.05);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* Document Modal Styles */
.document-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.document-card:hover {
    border-color: #dc2626;
    box-shadow: 0 8px 24px rgba(220, 38, 38, 0.15);
    transform: translateY(-4px);
}

.document-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.document-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    margin-right: 15px;
}

.document-info h6 {
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
    font-size: 16px;
}

.document-info small {
    display: block;
    color: #6b7280;
    font-size: 13px;
}

.document-actions {
    display: flex;
    gap: 10px;
}

.document-actions .btn {
    flex: 1;
    border-radius: 10px;
    padding: 10px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
}

.document-actions .btn-primary {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    border: none;
}

.document-actions .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
}

.document-actions .btn-outline-primary {
    color: #dc2626;
    border: 2px solid #dc2626;
}

.document-actions .btn-outline-primary:hover {
    background: #dc2626;
    color: white;
    transform: translateY(-2px);
}

.documents-empty, .documents-error {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 16px;
    border: 2px dashed #e5e7eb;
}

.documents-empty i, .documents-error i {
    font-size: 64px;
    color: #d1d5db;
    margin-bottom: 20px;
}

.documents-empty h5, .documents-error h5 {
    font-weight: 700;
    color: #111827;
    margin-bottom: 10px;
}

.documents-empty p, .documents-error p {
    color: #6b7280;
    margin: 0;
}

.documents-error {
    border-color: #fecaca;
    background: #fef2f2;
}

.documents-error i {
    color: #dc2626;
}

/* Modal animations */
.modal.fade .modal-dialog {
    transform: scale(0.8);
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: scale(1);
}

.modal-backdrop.show {
    opacity: 0.6;
}

/* Button hover effects */
.btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.btn:active {
    transform: translateY(0);
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
    
    // Show the modal
    new bootstrap.Modal(document.getElementById('documentsModal')).show();
    
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
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="/scratch/uploads/${doc.file_path}" download class="btn btn-outline-primary">
                                    <i class="fas fa-download"></i> Download
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
            document.getElementById('documentsLoading').style.display = 'none';
            document.getElementById('documentsContainer').innerHTML = `
                <div class="col-12">
                    <div class="documents-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h5>Error Loading Documents</h5>
                        <p>${error.message}</p>
                    </div>
                </div>
            `;
        });
}

function getFileIcon(type) {
    const iconMap = {
        'photo': 'image',
        'id': 'id-card',
        'certificate': 'certificate',
        'transcript': 'file-alt',
        'resume': 'file-alt',
        'diploma': 'graduation-cap'
    };
    return iconMap[type.toLowerCase()] || 'file';
}

function formatFileSize(bytes) {
    if (!bytes) return 'Unknown size';
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    if (bytes === 0) return '0 Bytes';
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
}

function formatDate(dateString) {
    if (!dateString) return 'Unknown date';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}
</script>


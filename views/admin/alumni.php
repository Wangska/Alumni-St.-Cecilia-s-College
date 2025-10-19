<?php
$currentPage = 'alumni';
$pageTitle = 'Alumni Management';
$title = 'Alumni Management';

ob_start();
?>

<style>
.search-filter-bar {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.modern-table-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.table-modern {
    margin: 0;
}

.table-modern thead {
    background: linear-gradient(135deg, #2d3142 0%, #1a1d29 100%);
    color: white;
}

.table-modern thead th {
    padding: 18px 16px;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
}

.table-modern tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f5;
}

.table-modern tbody tr:hover {
    background: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.table-modern tbody td {
    padding: 18px 16px;
    vertical-align: middle;
}

.alumni-avatar {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid #dc3545;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.badge-modern {
    padding: 6px 14px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.action-btn-group {
    display: flex;
    gap: 8px;
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

.btn-action.btn-info {
    background: linear-gradient(135deg, #0dcaf0, #0aa2c0);
    color: white;
}

.stats-pills {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
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
    background: linear-gradient(135deg, #dc3545, #c82333);
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

<!-- Search & Filter -->
<div class="search-filter-bar">
    <div class="row g-3 align-items-center">
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="number"><?= count($alumni ?? []) ?></div>
                    <div class="label">Verified Alumni</div>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="input-group">
                <span class="input-group-text bg-light border-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-0 bg-light" placeholder="Search by name, course, or batch...">
            </div>
        </div>
        <div class="col-md-2">
            <select id="courseFilter" class="form-select border-0 bg-light">
                <option value="">All Courses</option>
                <?php
                $courses = array_unique(array_column($alumni ?? [], 'course'));
                foreach ($courses as $course):
                    if ($course):
                ?>
                    <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                <?php 
                    endif;
                endforeach; 
                ?>
            </select>
        </div>
        <div class="col-md-auto">
            <button class="btn btn-outline-secondary" onclick="resetFilters()">
                <i class="fas fa-redo me-2"></i>Reset
            </button>
        </div>
        <div class="col-md-auto">
            <a href="/scratch/alumni/new.php" class="btn-add-modern">
                <i class="fas fa-user-plus me-2"></i>Add Alumni
            </a>
        </div>
    </div>
</div>

<style>
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
    text-decoration: none;
}

.btn-add-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.6);
    background: linear-gradient(135deg, #b02a37, #991b1b);
    color: white;
}

.btn-add-modern i {
    font-size: 15px;
}
</style>

<!-- Alumni Table -->
<div class="modern-table-card">
    <?php if (!empty($alumni)): ?>
        <table class="table table-modern" id="alumniTable">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Batch</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumni as $alum): ?>
                    <tr>
                        <td>
                            <?php
                            $avatarPath = !empty($alum['avatar']) ? '/scratch/uploads/' . basename($alum['avatar']) : '/scratch/uploads/default-avatar.png';
                            ?>
                            <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Avatar" class="alumni-avatar" onerror="this.src='/scratch/uploads/default-avatar.png'">
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars(($alum['firstname'] ?? '') . ' ' . ($alum['lastname'] ?? '')) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($alum['email'] ?? 'N/A') ?></small>
                        </td>
                        <td>
                            <span class="badge-modern bg-danger bg-opacity-10 text-danger">
                                <?= htmlspecialchars($alum['course'] ?? 'N/A') ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge-modern bg-primary bg-opacity-10 text-primary">
                                <?= htmlspecialchars($alum['batch'] ?? 'N/A') ?>
                            </span>
                        </td>
                        <td>
                            <?php if (($alum['status'] ?? 0) == 1): ?>
                                <span class="badge-modern bg-success bg-opacity-10 text-success" style="display: inline-flex; align-items: center; gap: 6px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                    </svg>
                                    CONNECTED
                                </span>
                            <?php else: ?>
                                <span class="text-muted small">Not connected</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="action-btn-group justify-content-center">
                                <a href="/scratch/alumni/edit.php?id=<?= $alum['id'] ?>" 
                                   class="btn-action btn-primary" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteAlumni(<?= $alum['id'] ?>, '<?= htmlspecialchars(($alum['firstname'] ?? '') . ' ' . ($alum['lastname'] ?? '')) ?>')" 
                                        class="btn-action btn-danger" 
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-user-graduate fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No verified alumni yet</h5>
            <p class="text-muted">Add your first alumni member to get started.</p>
            <a href="/scratch/alumni/new.php" class="btn btn-modern btn-modern-primary mt-3">
                <i class="fas fa-plus me-2"></i>Add First Alumni
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
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
            <form method="POST" action="/scratch/alumni/delete.php">
                <div class="modal-body" style="padding: 40px 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="id" id="delete_alumni_id">
                    
                    <div class="text-center mb-4">
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05)); border: 3px solid rgba(220, 53, 69, 0.3); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                            <i class="fas fa-user-times" style="font-size: 40px; color: #dc3545;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #2d3142; margin-bottom: 12px;">Delete Alumni?</h5>
                        <p style="color: #6c757d; margin-bottom: 8px;">Are you sure you want to delete this alumni record?</p>
                        <p style="font-weight: 600; color: #dc3545; font-size: 16px;" id="delete_alumni_name"></p>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #fff3cd, #fff8e1); border-left: 4px solid #ffc107; border-radius: 12px; padding: 16px; margin-top: 20px;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle" style="color: #ff9800; font-size: 20px; margin-right: 12px; margin-top: 2px;"></i>
                            <div>
                                <strong style="color: #856404; display: block; margin-bottom: 4px;">Warning</strong>
                                <span style="color: #856404; font-size: 14px;">This action cannot be undone. All alumni data will be permanently removed.</span>
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
#deleteModal .btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>

<script>
function deleteAlumni(id, name) {
    document.getElementById('delete_alumni_id').value = id;
    document.getElementById('delete_alumni_name').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    filterTable();
});

document.getElementById('courseFilter').addEventListener('change', function() {
    filterTable();
});

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const courseFilter = document.getElementById('courseFilter').value.toLowerCase();
    const rows = document.querySelectorAll('#alumniTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const course = row.querySelector('.badge-modern.text-danger')?.textContent.toLowerCase() || '';
        
        const matchesSearch = text.includes(searchTerm);
        const matchesCourse = !courseFilter || course.includes(courseFilter);
        
        row.style.display = (matchesSearch && matchesCourse) ? '' : 'none';
    });
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('courseFilter').value = '';
    filterTable();
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>

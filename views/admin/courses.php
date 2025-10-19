<?php
$currentPage = 'courses';
$pageTitle = 'Courses Management';
$title = 'Courses Management';

ob_start();
?>

<style>
.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}

.course-card-modern {
    background: white;
    border-radius: 16px;
    padding: 0;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.05);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    min-height: 400px;
}

.course-card-modern::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.08), transparent);
    border-radius: 50%;
}

.course-card-modern:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 12px 32px rgba(0,0,0,0.15);
}

.course-image-container {
    width: 100%;
    height: 200px;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    position: relative;
    background: linear-gradient(135deg, #dc3545, #c82333);
    display: flex;
    align-items: center;
    justify-content: center;
}

.course-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.course-image-container:hover .course-image {
    transform: scale(1.05);
}

.course-image-placeholder {
    color: white;
    font-size: 48px;
}

.course-name {
    font-size: 18px;
    font-weight: 700;
    color: #2d3142;
    margin-bottom: 12px;
    line-height: 1.4;
}

.course-about {
    color: #6c757d;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 20px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.course-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-top: auto;
}

.event-actions {
    display: flex;
    gap: 10px;
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
                <div class="icon" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <div class="number"><?= count($courses ?? []) ?></div>
                    <div class="label">Total Courses</div>
                </div>
            </div>
        </div>
        <div class="col-md"></div>
        <div class="col-md-auto">
            <button class="btn-add-modern" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                <i class="fas fa-plus me-2"></i>Add Course
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

<!-- Courses Grid -->
<?php if (!empty($courses)): ?>
    <div class="courses-grid">
        <?php foreach ($courses as $course): ?>
            <div class="course-card-modern">
                <!-- Image Section -->
                <div class="course-image-container">
                    <?php if (!empty($course['image'])): ?>
                        <img src="/scratch/uploads/<?= htmlspecialchars($course['image']) ?>" 
                             alt="<?= htmlspecialchars($course['course'] ?? 'Course') ?>" 
                             class="course-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="course-image-placeholder" style="display: none;">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    <?php else: ?>
                        <div class="course-image-placeholder">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Content Section -->
                <div class="course-content" style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                    <h5 class="course-name"><?= htmlspecialchars($course['course'] ?? 'Unnamed Course') ?></h5>
                    
                    <?php if (!empty($course['about'])): ?>
                        <p class="course-about"><?= htmlspecialchars($course['about']) ?></p>
                    <?php else: ?>
                        <p class="course-about text-muted">No description available.</p>
                    <?php endif; ?>
                    
                    <!-- Action Buttons -->
                    <div class="course-footer" style="margin-top: auto; padding-top: 16px;">
                        <div class="event-actions">
                            <button onclick="editCourse(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course']) ?>', '<?= htmlspecialchars($course['about'] ?? '') ?>', '<?= htmlspecialchars($course['image'] ?? '') ?>')" 
                                    class="btn-action btn-primary" 
                                    title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteCourse(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course']) ?>')" 
                                    class="btn-action btn-danger" 
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="content-card text-center py-5">
        <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">No courses yet</h5>
        <p class="text-muted">Add your first course to get started.</p>
        <button class="btn btn-modern btn-modern-primary mt-3" data-bs-toggle="modal" data-bs-target="#addCourseModal">
            <i class="fas fa-plus me-2"></i>Add First Course
        </button>
    </div>
<?php endif; ?>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #6f42c1, #5a32a3); color: white; padding: 24px 30px; border: none;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-graduation-cap" style="font-size: 24px;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Add New Course</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/admin.php?page=courses&action=add" enctype="multipart/form-data">
                <div class="modal-body" style="padding: 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div class="mb-4">
                        <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                            Course Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="course" class="form-control" required placeholder="e.g., Bachelor of Science in Computer Science" 
                               style="border: 2px solid #e9ecef; border-radius: 12px; padding: 12px 18px; transition: all 0.3s ease;">
                        <small class="text-muted">Enter the full course title</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                            Course Image
                        </label>
                        <input type="file" name="image" class="form-control" accept="image/*" 
                               style="border: 2px solid #e9ecef; border-radius: 12px; padding: 12px 18px; transition: all 0.3s ease;"
                               onchange="previewCourseImage(this)">
                        <small class="text-muted">Upload a representative image for this course (optional)</small>
                        <div id="courseImagePreview" class="mt-3" style="display: none;">
                            <img id="previewImg" src="" alt="Course Preview" style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                            Course Description
                        </label>
                        <textarea name="about" class="form-control" rows="4" placeholder="Enter course description, objectives, or additional information..." 
                                  style="border: 2px solid #e9ecef; border-radius: 12px; padding: 12px 18px; transition: all 0.3s ease;"></textarea>
                        <small class="text-muted">Optional but recommended</small>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #f1f3f5; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" 
                            style="border-radius: 12px; padding: 12px 28px; font-weight: 600; border: 2px solid #6c757d;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn" 
                            style="background: linear-gradient(135deg, #6f42c1, #5a32a3); color: white; border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3);">
                        <i class="fas fa-plus me-2"></i>Add Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
#addCourseModal .form-control:focus {
    border-color: #6f42c1;
    box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.15);
}

#addCourseModal .btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #0d6efd, #0a58ca); color: white; padding: 24px 30px; border: none;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-edit" style="font-size: 22px;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Edit Course</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/admin.php?page=courses&action=edit" enctype="multipart/form-data">
                <div class="modal-body" style="padding: 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="id" id="edit_course_id">
                    
                    <div class="mb-4">
                        <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                            Course Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="course" id="edit_course_name" class="form-control" required 
                               style="border: 2px solid #e9ecef; border-radius: 12px; padding: 12px 18px; transition: all 0.3s ease;">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                            Course Image
                        </label>
                        <div class="mb-3" id="currentImageContainer" style="display: none;">
                            <label class="form-label" style="font-size: 14px; color: #6c757d; margin-bottom: 8px;">Current Image:</label>
                            <div class="d-flex align-items-center gap-3">
                                <img id="currentImage" src="" alt="Current Course Image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCurrentImage()">
                                        <i class="fas fa-trash me-1"></i>Remove
                                    </button>
                                    <input type="hidden" name="remove_image" id="remove_image" value="0">
                                </div>
                            </div>
                        </div>
                        <input type="file" name="image" class="form-control" accept="image/*" 
                               style="border: 2px solid #e9ecef; border-radius: 12px; padding: 12px 18px; transition: all 0.3s ease;"
                               onchange="previewEditCourseImage(this)">
                        <small class="text-muted">Upload a new image to replace the current one</small>
                        <div id="editCourseImagePreview" class="mt-3" style="display: none;">
                            <img id="editPreviewImg" src="" alt="Course Preview" style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #2d3142; margin-bottom: 10px;">
                            Course Description
                        </label>
                        <textarea name="about" id="edit_course_about" class="form-control" rows="4" 
                                  style="border: 2px solid #e9ecef; border-radius: 12px; padding: 12px 18px; transition: all 0.3s ease;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #f1f3f5; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" 
                            style="border-radius: 12px; padding: 12px 28px; font-weight: 600; border: 2px solid #6c757d;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn" 
                            style="background: linear-gradient(135deg, #0d6efd, #0a58ca); color: white; border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);">
                        <i class="fas fa-save me-2"></i>Update Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
#editCourseModal .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

#editCourseModal .btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>

<!-- Delete Modal -->
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
            <form method="POST" action="/scratch/admin.php?page=courses&action=delete">
                <div class="modal-body" style="padding: 40px 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="id" id="delete_course_id">
                    
                    <div class="text-center mb-4">
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05)); border: 3px solid rgba(220, 53, 69, 0.3); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                            <i class="fas fa-trash-alt" style="font-size: 40px; color: #dc3545;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #2d3142; margin-bottom: 12px;">Delete Course?</h5>
                        <p style="color: #6c757d; margin-bottom: 8px;">Are you sure you want to delete this course?</p>
                        <p style="font-weight: 600; color: #dc3545; font-size: 16px;" id="delete_course_name"></p>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #fff3cd, #fff8e1); border-left: 4px solid #ffc107; border-radius: 12px; padding: 16px; margin-top: 20px;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle" style="color: #ff9800; font-size: 20px; margin-right: 12px; margin-top: 2px;"></i>
                            <div>
                                <strong style="color: #856404; display: block; margin-bottom: 4px;">Warning</strong>
                                <span style="color: #856404; font-size: 14px;">This action cannot be undone. All associated data will be permanently removed.</span>
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
function editCourse(id, name, about, image = null) {
    document.getElementById('edit_course_id').value = id;
    document.getElementById('edit_course_name').value = name;
    document.getElementById('edit_course_about').value = about;
    
    // Handle current image display
    const currentImageContainer = document.getElementById('currentImageContainer');
    const currentImage = document.getElementById('currentImage');
    const removeImageInput = document.getElementById('remove_image');
    
    if (image && image.trim() !== '') {
        currentImage.src = '/scratch/uploads/' + image;
        currentImageContainer.style.display = 'block';
        removeImageInput.value = '0';
    } else {
        currentImageContainer.style.display = 'none';
    }
    
    // Reset preview
    document.getElementById('editCourseImagePreview').style.display = 'none';
    document.querySelector('#editCourseModal input[name="image"]').value = '';
    
    new bootstrap.Modal(document.getElementById('editCourseModal')).show();
}

function deleteCourse(id, name) {
    document.getElementById('delete_course_id').value = id;
    document.getElementById('delete_course_name').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function previewCourseImage(input) {
    const preview = document.getElementById('courseImagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

function previewEditCourseImage(input) {
    const preview = document.getElementById('editCourseImagePreview');
    const previewImg = document.getElementById('editPreviewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

function removeCurrentImage() {
    document.getElementById('remove_image').value = '1';
    document.getElementById('currentImageContainer').style.display = 'none';
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>

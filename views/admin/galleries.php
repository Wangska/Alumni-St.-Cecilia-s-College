<?php
$currentPage = 'galleries';
$pageTitle = 'Gallery Management';
$title = 'Gallery Management';

ob_start();
?>

<style>
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 24px;
}

.gallery-item-modern {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.gallery-item-modern:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 12px 32px rgba(0,0,0,0.15);
}

.gallery-image {
    width: 100%;
    height: 250px;
    object-fit: cover;
    cursor: pointer;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 60px;
    background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.7) 100%);
    opacity: 0;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-item-modern:hover .gallery-overlay {
    opacity: 1;
}

.gallery-actions-overlay {
    display: flex;
    gap: 12px;
}

.gallery-footer {
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.upload-zone {
    background: white;
    border-radius: 16px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    text-align: center;
    border: 3px dashed #dc3545;
    transition: all 0.3s ease;
}

.upload-zone:hover {
    border-color: #c82333;
    background: rgba(220, 53, 69, 0.02);
}

.upload-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    margin: 0 auto 20px;
    box-shadow: 0 8px 16px rgba(220, 53, 69, 0.3);
}
</style>

<!-- Success/Error Messages -->
<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= htmlspecialchars($_SESSION['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Stats Bar -->
<div class="search-filter-bar mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
                    <i class="fas fa-images"></i>
                </div>
                <div>
                    <div class="number"><?= count($galleries ?? []) ?></div>
                    <div class="label">Total Images</div>
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

<!-- Upload Form -->
<div class="upload-zone" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 2px dashed #cbd5e1; border-radius: 20px; padding: 40px; text-align: center; position: relative; overflow: hidden;">
    <!-- Background Pattern -->
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: linear-gradient(135deg, rgba(127, 29, 29, 0.05) 0%, rgba(153, 27, 27, 0.05) 100%); border-radius: 50%; z-index: 1;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: linear-gradient(135deg, rgba(127, 29, 29, 0.03) 0%, rgba(153, 27, 27, 0.03) 100%); border-radius: 50%; z-index: 1;"></div>
    
    <div style="position: relative; z-index: 2;">
        <form method="POST" action="/scratch/admin.php?page=galleries&action=upload" enctype="multipart/form-data" id="uploadForm" style="max-width: 600px; margin: 0 auto;">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            
            <!-- Upload Icon -->
            <div class="upload-icon mb-4" style="width: 80px; height: 80px; background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 8px 25px rgba(127, 29, 29, 0.3);">
                <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: white;"></i>
            </div>
            
            <h4 class="mb-2" style="font-size: 1.5rem; font-weight: 700; color: #1f2937; font-family: 'Inter', sans-serif;">Upload Gallery Images</h4>
            <p class="text-muted mb-4" style="font-size: 1rem;">Select multiple images and add descriptions for each</p>
            
            <div class="upload-input-wrapper mb-4" style="position: relative;">
                <input type="file" name="images[]" id="imageInput" class="form-control" accept="image/*" multiple required style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 15px; font-size: 1rem; background: white; transition: all 0.3s ease;" onchange="showImagePreviews(this)">
                <div class="upload-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(127, 29, 29, 0.1) 0%, rgba(153, 27, 27, 0.1) 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; pointer-events: none; opacity: 0; transition: all 0.3s ease;">
                    <i class="fas fa-upload me-2" style="color: #7f1d1d; font-size: 1.2rem;"></i>
                    <span style="color: #7f1d1d; font-weight: 600;">Click to select images</span>
                </div>
            </div>
            
            <div id="imagePreviewsContainer" class="mt-4" style="max-width: 800px; margin: 0 auto;"></div>
            
            <button type="submit" class="btn btn-modern btn-modern-primary mt-4" id="uploadButton" style="display: none; background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%); border: none; padding: 12px 30px; border-radius: 25px; color: white; font-weight: 600; font-size: 1rem; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(127, 29, 29, 0.3);">
                <i class="fas fa-upload me-2"></i>Upload Images
            </button>
        </form>
    </div>
</div>

<!-- Gallery Grid -->
<?php if (!empty($galleries)): ?>
    <div class="gallery-grid">
        <?php foreach ($galleries as $gallery): ?>
            <div class="gallery-item-modern">
                <img src="/scratch/uploads/<?= htmlspecialchars($gallery['image_path'] ?? '') ?>" 
                     alt="<?= htmlspecialchars($gallery['about'] ?? 'Gallery Image') ?>" 
                     class="gallery-image"
                     onclick="viewImage(this.src)"
                     onerror="this.src='/scratch/uploads/default-avatar.png'">
                
                <div class="gallery-overlay">
                    <div class="gallery-actions-overlay">
                        <button class="btn btn-light btn-lg" onclick="viewImage('/scratch/uploads/<?= htmlspecialchars($gallery['image_path'] ?? '') ?>')">
                            <i class="fas fa-search-plus"></i>
                        </button>
                        <button class="btn btn-danger btn-lg" onclick="deleteImage(<?= $gallery['id'] ?>, '<?= htmlspecialchars($gallery['image_path'] ?? '') ?>')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="gallery-footer" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                    <?php if (!empty($gallery['about'])): ?>
                        <div style="width: 100%; font-size: 13px; color: #2d3142; font-weight: 500; line-height: 1.4;">
                            <i class="fas fa-info-circle me-1" style="color: #6c757d;"></i>
                            <?= htmlspecialchars($gallery['about']) ?>
                        </div>
                    <?php endif; ?>
                    <div style="width: 100%; display: flex; justify-content: space-between; align-items: center;">
                        <small class="text-muted">
                            <i class="far fa-calendar me-1"></i>
                            <?= date('M d, Y', strtotime($gallery['created'] ?? 'now')) ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="content-card text-center py-5">
        <i class="fas fa-images fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">No images in gallery</h5>
        <p class="text-muted">Upload your first image to get started.</p>
    </div>
<?php endif; ?>

<!-- View Image Modal -->
<div class="modal fade" id="viewImageModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" style="z-index: 10;"></button>
                <img id="modalImage" src="" alt="Gallery Image" class="w-100 rounded">
            </div>
        </div>
    </div>
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
            <form method="POST" action="/scratch/admin.php?page=galleries&action=delete">
                <div class="modal-body" style="padding: 40px 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="id" id="delete_image_id">
                    <input type="hidden" name="image_file" id="delete_image_file">
                    
                    <div class="text-center mb-4">
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05)); border: 3px solid rgba(220, 53, 69, 0.3); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                            <i class="fas fa-image" style="font-size: 40px; color: #dc3545;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #2d3142; margin-bottom: 12px;">Delete Image?</h5>
                        <p style="color: #6c757d; margin-bottom: 8px;">Are you sure you want to delete this gallery image?</p>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #fff3cd, #fff8e1); border-left: 4px solid #ffc107; border-radius: 12px; padding: 16px; margin-top: 20px;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle" style="color: #ff9800; font-size: 20px; margin-right: 12px; margin-top: 2px;"></i>
                            <div>
                                <strong style="color: #856404; display: block; margin-bottom: 4px;">Warning</strong>
                                <span style="color: #856404; font-size: 14px;">This action cannot be undone. The image will be permanently removed from the gallery.</span>
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

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 30px; border: none; text-align: center;">
                <div class="success-icon" style="width: 80px; height: 80px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="fas fa-check" style="font-size: 2.5rem; color: white;"></i>
                </div>
                <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 1.5rem;">Upload Successful!</h5>
            </div>
            <div class="modal-body" style="padding: 30px; text-align: center;">
                <p class="mb-3" style="font-size: 1.1rem; color: #374151; line-height: 1.6;">
                    Your images have been uploaded successfully to the gallery.
                </p>
                <div class="success-details" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px; margin: 20px 0;">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-images me-2" style="color: #10b981; font-size: 1.2rem;"></i>
                        <span style="font-weight: 600; color: #065f46;">Gallery Updated</span>
                    </div>
                    <p class="mb-0" style="font-size: 0.95rem; color: #047857;">
                        The images are now visible in your gallery and can be viewed by visitors.
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #e5e7eb; background: #f9fafb;">
                <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px; font-weight: 600; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                    <i class="fas fa-check me-2"></i>Got It, Thanks!
                </button>
            </div>
        </div>
    </div>
</div>

<style>
#deleteModal .btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

/* Upload Input Hover */
.upload-input-wrapper:hover .upload-overlay {
    opacity: 1;
}

/* Upload Button Hover */
.btn-modern-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(127, 29, 29, 0.4) !important;
}

/* Success Modal Animation */
@keyframes successPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.success-icon {
    animation: successPulse 2s ease-in-out infinite;
}

/* Gallery Grid Responsive */
@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function viewImage(src) {
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('viewImageModal')).show();
}

function deleteImage(id, imageFile) {
    document.getElementById('delete_image_id').value = id;
    document.getElementById('delete_image_file').value = imageFile;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Image preview with description fields
function showImagePreviews(input) {
    const container = document.getElementById('imagePreviewsContainer');
    const uploadButton = document.getElementById('uploadButton');
    container.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        uploadButton.style.display = 'block';
        
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewCard = document.createElement('div');
                previewCard.style.cssText = 'background: white; border-radius: 16px; padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; transition: all 0.3s ease; text-align: left;';
                previewCard.innerHTML = `
                    <div style="display: flex; gap: 25px; align-items: start;">
                        <img src="${e.target.result}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; flex-shrink: 0; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <div style="flex-grow: 1;">
                            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i class="fas fa-file-image" style="color: white; font-size: 18px;"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #1f2937; font-size: 16px; margin-bottom: 2px;">${file.name}</div>
                                    <div style="font-size: 13px; color: #6b7280;">Image Preview</div>
                                </div>
                            </div>
                            <label style="font-size: 14px; color: #374151; margin-bottom: 8px; display: block; font-weight: 600;">
                                <i class="fas fa-edit me-2" style="color: #7f1d1d;"></i>Description/Caption *
                            </label>
                            <textarea name="descriptions[]" class="form-control" rows="3" required placeholder="Enter a description for this image (e.g., Class of 2010 Reunion, Alumni Award Ceremony, etc.)" style="border-radius: 12px; border: 2px solid #e5e7eb; font-size: 14px; padding: 12px; transition: all 0.3s ease; resize: vertical;"></textarea>
                            <small class="text-muted" style="font-size: 12px; margin-top: 5px; display: block;">This helps visitors understand what the image is about.</small>
                        </div>
                    </div>
                `;
                container.appendChild(previewCard);
            };
            
            reader.readAsDataURL(file);
        });
    } else {
        uploadButton.style.display = 'none';
    }
}

// Show success modal after upload
<?php if (isset($_SESSION['success']) && strpos($_SESSION['success'], 'uploaded successfully') !== false): ?>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('successModal')).show();
    });
<?php endif; ?>
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>

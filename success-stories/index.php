<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pageTitle = 'Success Stories - SCC Alumni';

$pdo = get_pdo();

// Fetch all approved success stories
try {
    $stmt = $pdo->prepare('
        SELECT ss.*, u.username, ab.firstname, ab.lastname 
        FROM success_stories ss 
        LEFT JOIN users u ON ss.user_id = u.id 
        LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id 
        WHERE ss.status = 1 
        ORDER BY ss.created DESC
    ');
    $stmt->execute();
    $stories = $stmt->fetchAll();
} catch (Exception $e) {
    $stories = [];
}

include __DIR__ . '/../inc/header.php';
?>

<style>
/* Success Stories Page Styles */
.success-stories-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 2rem;
}

.story-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.story-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.story-image {
    max-height: 300px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.story-header {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-bottom: 1px solid #e5e7eb;
    padding: 1.5rem;
}

.story-body {
    padding: 1.5rem;
}

.story-footer {
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
    padding: 1.5rem;
}

.btn-read-more {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.btn-read-more:hover {
    background: linear-gradient(135deg, #b91c1c, #991b1b);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
}

.btn-share-story {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-share-story:hover {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 16px;
    margin: 2rem 0;
}

.empty-state i {
    font-size: 4rem;
    color: #6b7280;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #374151;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 2rem;
}

/* Story Modal Styles */
.story-modal .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    overflow: hidden;
}

.story-modal .modal-header {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
    border-bottom: none;
    padding: 1.5rem 2rem;
}

.story-modal .modal-header .btn-close {
    filter: invert(1);
    opacity: 0.8;
}

.story-modal .modal-header .btn-close:hover {
    opacity: 1;
}

.story-modal .modal-body {
    padding: 2rem;
    background: #f8fafc;
    max-height: 70vh;
    overflow-y: auto;
}

.story-modal .modal-footer {
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
    padding: 1.5rem 2rem;
}

.story-modal-image {
    max-height: 400px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.story-modal-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.story-modal-meta {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid #dc2626;
}

.story-modal-author {
    font-size: 1.1rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.story-modal-date {
    color: #6b7280;
    font-size: 0.9rem;
}

.story-modal-content {
    color: #4b5563;
    line-height: 1.7;
    font-size: 1rem;
    text-align: justify;
}

.btn-modal-close {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-modal-close:hover {
    background: linear-gradient(135deg, #4b5563, #374151);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}

/* Animation for modal */
@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.8) translateY(-50px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.story-modal.show .modal-dialog {
    animation: modalSlideIn 0.3s ease-out;
}

/* Create Story Modal Styles */
.create-story-modal .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    overflow: hidden;
}

.create-story-modal .modal-header {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-bottom: none;
    padding: 1.5rem 2rem;
}

.create-story-modal .modal-header .btn-close {
    filter: invert(1);
    opacity: 0.8;
}

.create-story-modal .modal-header .btn-close:hover {
    opacity: 1;
}

.create-story-modal .modal-body {
    padding: 2rem;
    background: #f8fafc;
    max-height: 80vh;
    overflow-y: auto;
}

.create-story-modal .modal-footer {
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
    padding: 1.5rem 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-control {
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
}

.form-control.is-invalid {
    border-color: #ef4444;
}

.form-control.is-valid {
    border-color: #10b981;
}

.character-count {
    font-size: 0.8rem;
    color: #6b7280;
    text-align: right;
    margin-top: 0.25rem;
}

.image-preview {
    margin-top: 1rem;
    text-align: center;
}

.image-preview img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-submit {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-submit:hover {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.btn-cancel {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-cancel:hover {
    background: linear-gradient(135deg, #4b5563, #374151);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}

.alert-info {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    border: 1px solid #3b82f6;
    color: #1e40af;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.alert-info i {
    color: #3b82f6;
}
</style>

<div class="container main-content">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="success-stories-container">
                <div class="story-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1" style="color: #2d3142; font-weight: 700;">
                                <i class="fas fa-star me-2" style="color: #dc2626;"></i>Success Stories
                            </h2>
                            <p class="text-muted mb-0">Inspiring stories from our alumni community</p>
                        </div>
                        <div>
                            <button type="button" class="btn btn-share-story" data-bs-toggle="modal" data-bs-target="#createStoryModal">
                                <i class="fas fa-plus me-2"></i>Share Your Story
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (empty($stories)): ?>
            <div class="empty-state">
                <i class="fas fa-star"></i>
                <h3>No Success Stories Yet</h3>
                <p>Be the first to share your inspiring success story with the alumni community!</p>
            </div>
            <?php else: ?>
                <!-- Stories Grid -->
                <div class="row">
                    <?php foreach ($stories as $story): ?>
                        <div class="col-lg-6 mb-4">
                            <div class="story-card">
                                <div class="story-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1" style="color: #2d3142; font-weight: 700; font-size: 1.25rem;">
                                                <?= htmlspecialchars($story['title']) ?>
                                            </h5>
                                            <small class="text-muted" style="font-size: 0.9rem;">
                                                <i class="fas fa-user me-1"></i>
                                                <?= htmlspecialchars($story['firstname'] . ' ' . $story['lastname']) ?>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-calendar me-1"></i>
                                                <?= date('M d, Y', strtotime($story['created'])) ?>
                                            </small>
                                        </div>
                                        <div>
                                            <span class="badge" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                                Approved
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="story-body">
                                    <?php if (!empty($story['image'])): ?>
                                        <div class="mb-3">
                                            <img src="/scratch/<?= htmlspecialchars($story['image']) ?>" 
                                                 alt="<?= htmlspecialchars($story['title']) ?>" 
                                                 class="story-image" 
                                                 style="width: 100%;">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <p class="card-text" style="color: #4b5563; line-height: 1.6; font-size: 0.95rem;">
                                        <?= htmlspecialchars(substr($story['content'], 0, 200)) ?><?= strlen($story['content']) > 200 ? '...' : '' ?>
                                    </p>
                                </div>
                                
                                <div class="story-footer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i>
                                            Read the full story
                                        </small>
                                        <button type="button" class="btn btn-read-more" 
                                                data-bs-toggle="modal" data-bs-target="#storyModal" 
                                                data-story-id="<?= $story['id'] ?>" 
                                                data-story-title="<?= htmlspecialchars($story['title']) ?>"
                                                data-story-content="<?= htmlspecialchars($story['content']) ?>"
                                                data-story-image="<?= htmlspecialchars($story['image'] ?? '') ?>"
                                                data-story-author="<?= htmlspecialchars($story['firstname'] . ' ' . $story['lastname']) ?>"
                                                data-story-date="<?= date('M d, Y', strtotime($story['created'])) ?>">
                                            <i class="fas fa-arrow-right me-1"></i>Read More
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Story Modal -->
<div class="modal fade story-modal" id="storyModal" tabindex="-1" aria-labelledby="storyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="storyModalLabel">
                    <i class="fas fa-star me-2"></i>Success Story
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2 class="story-modal-title" id="modalStoryTitle"></h2>
                
                <div class="story-modal-meta">
                    <div class="story-modal-author" id="modalStoryAuthor"></div>
                    <div class="story-modal-date" id="modalStoryDate"></div>
                </div>
                
                <div id="modalStoryImageContainer" style="display: none;">
                    <img id="modalStoryImage" src="" alt="" class="story-modal-image" style="width: 100%;">
                </div>
                
                <div class="story-modal-content" id="modalStoryContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Story Modal -->
<div class="modal fade create-story-modal" id="createStoryModal" tabindex="-1" aria-labelledby="createStoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createStoryModalLabel">
                    <i class="fas fa-plus me-2"></i>Share Your Success Story
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createStoryForm" method="POST" action="create.php" enctype="multipart/form-data" onsubmit="return handleStorySubmission(event)">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Your story will be reviewed by administrators before being published. This helps maintain quality and appropriateness of content.
                    </div>
                    
                    <div class="form-group">
                        <label for="storyTitle" class="form-label">
                            <i class="fas fa-heading me-2" style="color: #10b981;"></i>Story Title
                        </label>
                        <input type="text" class="form-control" id="storyTitle" name="title" 
                               placeholder="Enter a compelling title for your success story" 
                               maxlength="255" required>
                        <div class="character-count">
                            <span id="titleCount">0</span>/255 characters
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="storyImage" class="form-label">
                            <i class="fas fa-image me-2" style="color: #10b981;"></i>Story Image (Optional)
                        </label>
                        <input type="file" class="form-control" id="storyImage" name="image" 
                               accept="image/jpeg,image/jpg,image/png,image/gif">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Upload a photo related to your success story (JPEG, PNG, or GIF, max 5MB)
                        </small>
                        <div id="imagePreview" class="image-preview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="storyContent" class="form-label">
                            <i class="fas fa-edit me-2" style="color: #10b981;"></i>Your Story
                        </label>
                        <textarea class="form-control" id="storyContent" name="content" rows="8" 
                                  placeholder="Share your journey, challenges overcome, achievements, and how SCC helped shape your success. Be detailed and inspiring!" 
                                  required minlength="50"></textarea>
                        <div class="character-count">
                            <span id="contentCount">0</span> characters (minimum 50 required)
                        </div>
                    </div>
                    
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-paper-plane me-1"></i>Submit Story
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Story Modal Event Handler
document.addEventListener('DOMContentLoaded', function() {
    const storyModal = document.getElementById('storyModal');
    if (storyModal) {
        storyModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            
            // Get story data from button attributes
            const storyId = button.getAttribute('data-story-id');
            const storyTitle = button.getAttribute('data-story-title');
            const storyContent = button.getAttribute('data-story-content');
            const storyImage = button.getAttribute('data-story-image');
            const storyAuthor = button.getAttribute('data-story-author');
            const storyDate = button.getAttribute('data-story-date');
            
            // Update modal content
            document.getElementById('modalStoryTitle').textContent = storyTitle;
            document.getElementById('modalStoryAuthor').textContent = storyAuthor;
            document.getElementById('modalStoryDate').textContent = storyDate;
            document.getElementById('modalStoryContent').textContent = storyContent;
            
            // Handle image
            const imageContainer = document.getElementById('modalStoryImageContainer');
            const imageElement = document.getElementById('modalStoryImage');
            
            if (storyImage && storyImage.trim() !== '') {
                imageElement.src = '/scratch/' + storyImage;
                imageElement.alt = storyTitle;
                imageContainer.style.display = 'block';
            } else {
                imageContainer.style.display = 'none';
            }
        });
    }
    
    // Create Story Modal Functionality
    const createStoryModal = document.getElementById('createStoryModal');
    const createStoryForm = document.getElementById('createStoryForm');
    const storyTitle = document.getElementById('storyTitle');
    const storyContent = document.getElementById('storyContent');
    const storyImage = document.getElementById('storyImage');
    const titleCount = document.getElementById('titleCount');
    const contentCount = document.getElementById('contentCount');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    // Character counting for title
    if (storyTitle) {
        storyTitle.addEventListener('input', function() {
            const count = this.value.length;
            titleCount.textContent = count;
            
            if (count > 255) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    }
    
    // Character counting for content
    if (storyContent) {
        storyContent.addEventListener('input', function() {
            const count = this.value.length;
            contentCount.textContent = count;
            
            if (count < 50) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    }
    
    // Image preview functionality
    if (storyImage) {
        storyImage.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Check file size (5MB limit)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size too large. Maximum 5MB allowed.');
                    this.value = '';
                    return;
                }
                
                // Check file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Invalid file type. Please upload a JPEG, PNG, or GIF image.');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }
    
    // Form submission with loading state
    if (createStoryForm) {
        createStoryForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Submitting...';
            submitBtn.disabled = true;
            
            // Re-enable button after 3 seconds (in case of error)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });
    }
    
    // Handle story submission
    window.handleStorySubmission = function(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Submitting...';
        submitBtn.disabled = true;
        
        // Submit form via AJAX
        fetch('create.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('createStoryModal'));
                modal.hide();
                
                // Show success notification
                showNotification('success', 'Story Submitted Successfully!', 'Your success story has been submitted and is now under review. You will be notified once it\'s approved.');
                
                // Reset form
                form.reset();
                document.getElementById('titleCount').textContent = '0';
                document.getElementById('contentCount').textContent = '0';
                document.getElementById('imagePreview').style.display = 'none';
                
                // Remove validation classes
                document.getElementById('storyTitle').classList.remove('is-valid', 'is-invalid');
                document.getElementById('storyContent').classList.remove('is-valid', 'is-invalid');
                
                // Reload page after a short delay to show the new story
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                // Show error notification
                showNotification('error', 'Submission Failed', data.message || 'There was an error submitting your story. Please try again.');
                
                // Re-enable button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Submission Failed', 'There was an error submitting your story. Please try again.');
            
            // Re-enable button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
        
        return false;
    }
    
    // Show notification function
    function showNotification(type, title, message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 350px; max-width: 500px; box-shadow: 0 8px 32px rgba(0,0,0,0.15);';
        
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" style="font-size: 1.5rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-bold">${title}</h6>
                    <p class="mb-0 small">${message}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
    
    // Reset form when modal is hidden
    if (createStoryModal) {
        createStoryModal.addEventListener('hidden.bs.modal', function() {
            createStoryForm.reset();
            titleCount.textContent = '0';
            contentCount.textContent = '0';
            imagePreview.style.display = 'none';
            
            // Remove validation classes
            storyTitle.classList.remove('is-valid', 'is-invalid');
            storyContent.classList.remove('is-valid', 'is-invalid');
        });
    }
});
</script>

<?php include __DIR__ . '/../inc/footer.php'; ?>

<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pageTitle = 'Testimonials - SCC Alumni';
$pdo = get_pdo();

// Fetch approved testimonials
try {
    $stmt = $pdo->prepare('
        SELECT t.*, u.username, ab.firstname, ab.lastname
        FROM testimonials t
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id
        WHERE t.status = 1
        ORDER BY t.created DESC
    ');
    $stmt->execute();
    $testimonials = $stmt->fetchAll();
} catch (Exception $e) {
    $testimonials = [];
    // Log error
}
?>
<?php include __DIR__ . '/../inc/header.php'; ?>

<style>
    /* General Styles */
    .main-content {
        padding-top: 3rem;
        padding-bottom: 3rem;
    }

    .section-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-header h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.75rem;
    }

    .section-header p {
        font-size: 1.1rem;
        color: #6b7280;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-share-testimonial {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-share-testimonial:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        color: white;
    }

    /* Testimonial Card Styles */
    .testimonial-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .testimonial-photo-container {
        height: 480px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .testimonial-photo-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .testimonial-photo-placeholder {
        font-size: 4rem;
        color: #cbd5e1;
    }

    .testimonial-content-area {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .testimonial-quote {
        font-size: 1.1rem;
        font-style: italic;
        color: #374151;
        line-height: 1.6;
        margin-bottom: 1rem;
        flex-grow: 1;
    }

    .testimonial-author {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .testimonial-details {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .testimonial-date {
        font-size: 0.8rem;
        color: #9ca3af;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-top: 2rem;
    }

    .empty-state i {
        font-size: 5rem;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: #374151;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #6b7280;
        margin-bottom: 2rem;
    }

    /* Create Testimonial Modal Styles */
    .create-testimonial-modal .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
    }

    .create-testimonial-modal .modal-header {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-bottom: none;
        padding: 1.5rem 2rem;
    }

    .create-testimonial-modal .modal-header .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }

    .create-testimonial-modal .modal-header .btn-close:hover {
        opacity: 1;
    }

    .create-testimonial-modal .modal-body {
        padding: 2rem;
        background: #f8fafc;
        max-height: 80vh;
        overflow-y: auto;
    }

    .create-testimonial-modal .modal-footer {
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

    .photo-preview {
        margin-top: 1rem;
        text-align: center;
    }

    .photo-preview img {
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1" style="color: #2d3142; font-weight: 700;">
                        <i class="fas fa-quote-left me-2" style="color: #10b981;"></i>Testimonials
                    </h2>
                    <p class="text-muted mb-0">Inspiring quotes and testimonials from our alumni community</p>
                </div>
                <div>
                    <button type="button" class="btn btn-share-testimonial" data-bs-toggle="modal" data-bs-target="#createTestimonialModal">
                        <i class="fas fa-plus me-2"></i>Share Your Testimonial
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($testimonials)): ?>
        <div class="empty-state">
            <i class="fas fa-quote-left"></i>
            <h3>No Testimonials Yet</h3>
            <p>Be the first to share your inspiring testimonial with the alumni community!</p>
        </div>
    <?php else: ?>
        <!-- Testimonials Grid -->
        <div class="row">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-photo-container">
                            <?php if (!empty($testimonial['graduation_photo'])): ?>
                                <img src="/scratch/<?= htmlspecialchars($testimonial['graduation_photo']) ?>" alt="<?= htmlspecialchars($testimonial['author_name']) ?>">
                            <?php else: ?>
                                <i class="fas fa-user-graduate testimonial-photo-placeholder"></i>
                            <?php endif; ?>
                        </div>
                        <div class="testimonial-content-area">
                            <p class="testimonial-quote">"<?= htmlspecialchars($testimonial['quote']) ?>"</p>
                            <div class="testimonial-author"><?= htmlspecialchars($testimonial['author_name']) ?></div>
                            <div class="testimonial-details">
                                <?= htmlspecialchars($testimonial['course']) ?> • Class of <?= (int)($testimonial['graduation_year'] ?? 0) ?>
                            </div>
                            <div class="testimonial-date">
                                <i class="fas fa-calendar me-1"></i><?= date('M d, Y', strtotime($testimonial['created'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Create Testimonial Modal -->
<div class="modal fade create-testimonial-modal" id="createTestimonialModal" tabindex="-1" aria-labelledby="createTestimonialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTestimonialModalLabel">
                    <i class="fas fa-plus me-2"></i>Share Your Testimonial
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createTestimonialForm" method="POST" action="create.php" enctype="multipart/form-data" onsubmit="return handleTestimonialSubmission(event)">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Your testimonial will be reviewed by administrators before being published. Please share your inspiring quote about your SCC experience.
                    </div>

                    <div class="form-group">
                        <label for="testimonialQuote" class="form-label">
                            <i class="fas fa-quote-left me-2" style="color: #10b981;"></i>Your Testimonial Quote
                        </label>
                        <textarea class="form-control" id="testimonialQuote" name="quote" rows="4"
                                  placeholder="Share your inspiring quote about your SCC experience, achievements, or how SCC helped shape your success..."
                                  required minlength="20" maxlength="500"></textarea>
                        <div class="character-count">
                            <span id="quoteCount">0</span>/500 characters (minimum 20 required)
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="authorName" class="form-label">
                            <i class="fas fa-user me-2" style="color: #10b981;"></i>Your Name
                        </label>
                        <input type="text" class="form-control" id="authorName" name="author_name"
                               placeholder="Enter your full name as you'd like it to appear"
                               required maxlength="255">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="graduationYear" class="form-label">
                                    <i class="fas fa-graduation-cap me-2" style="color: #10b981;"></i>Graduation Year
                                </label>
                                <select class="form-control" id="graduationYear" name="graduation_year" required>
                                    <option value="">Select graduation year</option>
                                    <?php for ($year = date('Y'); $year >= 1990; $year--): ?>
                                        <option value="<?= $year ?>"><?= $year ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course" class="form-label">
                                    <i class="fas fa-book me-2" style="color: #10b981;"></i>Course/Program
                                </label>
                                <input type="text" class="form-control" id="course" name="course"
                                       placeholder="e.g., Bachelor of Science in Computer Science"
                                       required maxlength="255">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="graduationPhoto" class="form-label">
                            <i class="fas fa-camera me-2" style="color: #10b981;"></i>Graduation Photo (Optional)
                        </label>
                        <input type="file" class="form-control" id="graduationPhoto" name="graduation_photo"
                               accept="image/jpeg,image/jpg,image/png,image/gif">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Upload your graduation photo (JPEG, PNG, or GIF, max 5MB)
                        </small>
                        <div id="photoPreview" class="photo-preview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview">
                        </div>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-paper-plane me-1"></i>Submit Testimonial
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Create Testimonial Modal Functionality
document.addEventListener('DOMContentLoaded', function() {
    const createTestimonialModal = document.getElementById('createTestimonialModal');
    const createTestimonialForm = document.getElementById('createTestimonialForm');
    const testimonialQuote = document.getElementById('testimonialQuote');
    const authorName = document.getElementById('authorName');
    const graduationPhoto = document.getElementById('graduationPhoto');
    const quoteCount = document.getElementById('quoteCount');
    const photoPreview = document.getElementById('photoPreview');
    const previewImg = document.getElementById('previewImg');

    // Character counting for quote
    if (testimonialQuote) {
        testimonialQuote.addEventListener('input', function() {
            const count = this.value.length;
            quoteCount.textContent = count;

            if (count < 20 || count > 500) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    }

    // Photo preview functionality
    if (graduationPhoto) {
        graduationPhoto.addEventListener('change', function(e) {
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
                    photoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                photoPreview.style.display = 'none';
            }
        });
    }

    // Form submission with loading state
    if (createTestimonialForm) {
        createTestimonialForm.addEventListener('submit', function(e) {
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

    // Handle testimonial submission
    window.handleTestimonialSubmission = function(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Submitting...';
        submitBtn.disabled = true;

        // Submit form via AJAX
        // Append debug flag temporarily to surface server errors while we stabilize
        fetch('create.php?debug=1', {
            method: 'POST',
            body: formData
        })
        .then(async (response) => {
            // Try to parse JSON; if it fails, surface raw text
            const text = await response.text();
            let data;
            try { data = JSON.parse(text); } catch (_) { data = { success: false, message: text || 'Unexpected server response.' }; }
            return data;
        })
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('createTestimonialModal'));
                modal.hide();

                // Show success notification
                showNotification('success', 'Testimonial Submitted Successfully!', 'Your testimonial has been submitted and is now under review. You will be notified once it\'s approved.');

                // Reset form
                form.reset();
                document.getElementById('quoteCount').textContent = '0';
                document.getElementById('photoPreview').style.display = 'none';

                // Remove validation classes
                document.getElementById('testimonialQuote').classList.remove('is-valid', 'is-invalid');

                // Reload page after a short delay to show the new testimonial
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                // Show error notification with message (may include diagnostic info in debug mode)
                showNotification('error', 'Submission Failed', (data && data.message) ? data.message : 'There was an error submitting your testimonial. Please try again.');

                // Re-enable button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Submission Failed', 'There was an error submitting your testimonial. Please try again.');

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
    if (createTestimonialModal) {
        createTestimonialModal.addEventListener('hidden.bs.modal', function() {
            createTestimonialForm.reset();
            quoteCount.textContent = '0';
            photoPreview.style.display = 'none';

            // Remove validation classes
            testimonialQuote.classList.remove('is-valid', 'is-invalid');
        });
    }
});
</script>

<?php include __DIR__ . '/../inc/footer.php'; ?>

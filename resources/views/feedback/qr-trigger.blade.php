<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Barangay Lumanglipa</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .feedback-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(42, 123, 196, 0.2);
            padding: 3rem;
            max-width: 500px;
            width: 90%;
        }
        
        .star {
            color: #ddd;
            transition: color 0.2s ease;
        }
        
        .star.active,
        .star:hover {
            color: #ffc107;
        }
        
        .rating-text {
            transition: all 0.3s ease;
        }
        
        .comment-section {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .comment-section.show {
            opacity: 1;
            max-height: 200px;
        }
        
        .btn {
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background-color: #2A7BC4;
            border-color: #2A7BC4;
        }
        
        .btn-primary:hover {
            background-color: #1e5f8c;
            border-color: #1e5f8c;
        }
    </style>
</head>
<body>
    <div class="feedback-container">
        <!-- Success Icon -->
        <div class="text-center mb-4">
            <div class="success-checkmark mb-3">
                <div class="check-icon" style="width: 80px; height: 80px; margin: 0 auto; position: relative;">
                    <span class="icon-line line-tip" style="position: absolute; width: 25px; height: 5px; background-color: #28a745; display: block; border-radius: 2px; left: 14px; top: 46px; transform: rotate(45deg);"></span>
                    <span class="icon-line line-long" style="position: absolute; width: 47px; height: 5px; background-color: #28a745; display: block; border-radius: 2px; right: 8px; top: 38px; transform: rotate(-45deg);"></span>
                    <div class="icon-circle" style="position: absolute; top: 0; left: 0; width: 80px; height: 80px; border-radius: 50%; border: 5px solid #28a745;"></div>
                </div>
            </div>
            @if($service_type === 'document_request')
                <h3 class="fw-bold mb-3">Document Request Survey</h3>
            @else
                <h3 class="fw-bold mb-3">Blotter/Complaint Survey</h3>
            @endif
        </div>

        <!-- Rating Section -->
        <div class="text-center mb-4">
            @if($service_type === 'document_request')
                <p class="text-muted mb-3">How was your experience with our document request service?</p>
            @else
                <p class="text-muted mb-3">How was your experience with our blotter/complaint service?</p>
            @endif
            <div class="stars-container mb-3" style="font-size: 3rem; cursor: pointer;">
                <i class="fas fa-star star" data-rating="1"></i>
                <i class="fas fa-star star" data-rating="2"></i>
                <i class="fas fa-star star" data-rating="3"></i>
                <i class="fas fa-star star" data-rating="4"></i>
                <i class="fas fa-star star" data-rating="5"></i>
            </div>
            <p class="rating-text fw-bold" style="color: #ffc107; font-size: 1.2rem; min-height: 30px;"></p>
        </div>

        <!-- Comment Section (Initially Hidden) -->
        <div class="comment-section mb-4">
            <label for="ratingComment" class="form-label text-muted">Tell us more (optional)</label>
            <textarea class="form-control" id="ratingComment" rows="3" placeholder="Share your thoughts..."></textarea>
        </div>

        <!-- Action Buttons -->
        <div class="d-grid gap-2">
            <button type="button" class="btn btn-primary btn-lg" id="submitRatingBtn" disabled style="border-radius: 8px;">
                <i class="fas fa-paper-plane me-2"></i>Submit Feedback
            </button>
            <button type="button" class="btn btn-outline-secondary" id="skipRatingBtn" style="border-radius: 8px;">
                Skip for now
            </button>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let selectedRating = 0;
        const ratingMessages = {
            1: "😞 We're sorry to hear that",
            2: "😔 We can do better",
            3: "🙂 Thank you for your feedback",
            4: "😊 Great! We're glad you're satisfied",
            5: "🌟 Excellent! Thank you for your trust"
        };

        // Star rating functionality
        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = parseInt(this.dataset.rating);
                updateStars();
                updateRatingText();
                showCommentSection();
                enableSubmitButton();
            });

            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                highlightStars(rating);
            });
        });

        document.querySelector('.stars-container').addEventListener('mouseleave', function() {
            updateStars();
        });

        function highlightStars(rating) {
            document.querySelectorAll('.star').forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        function updateStars() {
            highlightStars(selectedRating);
        }

        function updateRatingText() {
            const ratingText = document.querySelector('.rating-text');
            if (selectedRating > 0) {
                ratingText.textContent = ratingMessages[selectedRating];
                ratingText.style.opacity = '1';
            } else {
                ratingText.textContent = '';
                ratingText.style.opacity = '0';
            }
        }

        function showCommentSection() {
            const commentSection = document.querySelector('.comment-section');
            if (selectedRating > 0) {
                commentSection.classList.add('show');
                commentSection.style.display = 'block';
            }
        }

        function enableSubmitButton() {
            const submitBtn = document.getElementById('submitRatingBtn');
            if (selectedRating > 0) {
                submitBtn.disabled = false;
            }
        }

        // Submit feedback
        document.getElementById('submitRatingBtn').addEventListener('click', function() {
            if (selectedRating === 0) return;

            const comment = document.getElementById('ratingComment').value;
            const submitBtn = this;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            submitBtn.disabled = true;

            // Submit feedback
            fetch('/feedback/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    rating: selectedRating,
                    comment: comment,
                    service_type: '{{ $service_type }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    document.querySelector('.feedback-container').innerHTML = `
                        <div class="text-center">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Thank You!</h3>
                            <p class="text-muted mb-4">Your feedback has been submitted successfully. We appreciate your time and input.</p>
                            <button type="button" class="btn btn-primary" onclick="closeWindow()">Close</button>
                        </div>
                    `;
                } else {
                    throw new Error(data.message || 'Failed to submit feedback');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to submit feedback. Please try again.');
                
                // Reset button state
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Feedback';
                submitBtn.disabled = false;
            });
        });

        // Skip feedback
        document.getElementById('skipRatingBtn').addEventListener('click', function() {
            closeWindow();
        });
        
        // Close window function
        function closeWindow() {
            // Try to close the window
            if (window.opener) {
                window.close();
            } else {
                // If window.close() doesn't work (some browsers block it), show alternative
                document.querySelector('.feedback-container').innerHTML = `
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Thank You!</h3>
                        <p class="text-muted mb-4">You can now close this tab or window.</p>
                    </div>
                `;
            }
        }
    </script>
</body>
</html>
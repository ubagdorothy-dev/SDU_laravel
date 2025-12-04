<div class="container-fluid p-0">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4 review-card">
                <div class="card-header">
                    <h5 class="mb-0">Training Proof Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Staff Member</h6>
                            <p>{{ $trainingProof->user->full_name ?? 'N/A' }}</p>
                            
                            <h6>Email</h6>
                            <p>{{ $trainingProof->user->email ?? 'N/A' }}</p>
                            
                            <h6>Office</h6>
                            <p>{{ $trainingProof->user->office_code ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Training Title</h6>
                            <p>{{ $trainingProof->trainingRecord->title ?? 'N/A' }}</p>
                            
                            <h6>Training Date</h6>
                            <p>
                                {{ $trainingProof->trainingRecord->start_date->format('M d, Y') }} 
                                @if($trainingProof->trainingRecord->end_date)
                                    - {{ $trainingProof->trainingRecord->end_date->format('M d, Y') }}
                                @endif
                            </p>
                            
                            <h6>Submitted On</h6>
                            <p>{{ $trainingProof->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4 review-card">
                <div class="card-header">
                    <h5 class="mb-0">Review Actions</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('training_proofs.process_review', $trainingProof->id) }}" class="review-form">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks (Optional)</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Add any remarks for the staff member..."></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" name="action" value="approve" class="btn btn-approve">
                                <i class="fas fa-check-circle me-1"></i> Approve
                            </button>
                            <button type="submit" name="action" value="reject" class="btn btn-reject">
                                <i class="fas fa-times-circle me-1"></i> Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4 review-card">
                <div class="card-header">
                    <h5 class="mb-0">Training Proof File</h5>
                </div>
                <div class="card-body text-center">
                    @if(pathinfo($trainingProof->file_path, PATHINFO_EXTENSION) == 'pdf')
                        <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                        <p>PDF Document</p>
                    @else
                        <i class="fas fa-file-image fa-4x text-info mb-3"></i>
                        <p>Image File</p>
                    @endif
                    
                    <a href="{{ route('training_proofs.view', $trainingProof->id) }}" target="_blank" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-eye me-1"></i> View File
                    </a>
                    
                    <a href="{{ route('training_proofs.view', ['id' => $trainingProof->id, 'download' => 1]) }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-download me-1"></i> Download File
                    </a>
                </div>
            </div>
            
            <div class="card review-card">
                <div class="card-header">
                    <h5 class="mb-0">Status Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Status:</span>
                        <span class="badge bg-warning">Pending Review</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Proof ID:</span>
                        <span>{{ $trainingProof->id }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle form submission via AJAX to keep modal open and show success message
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.review-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const action = e.submitter.value;
                const url = this.action;
                
                // Get CSRF token from the parent document
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content') || 
                                  window.parent.document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal
                        const modalElement = document.getElementById('reviewModal');
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) {
                                modal.hide();
                            }
                        }
                        
                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                        alertDiv.style.zIndex = '9999';
                        alertDiv.innerHTML = `
                            <strong>Success!</strong> Training proof ${action}d successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.body.appendChild(alertDiv);
                        
                        // Remove alert after 5 seconds
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 5000);
                        
                        // Reload the page to update the table
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        // Show error message
                        alert('Error: ' + (data.message || 'Failed to process review'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing the review.');
                });
            });
        });
    });
</script>
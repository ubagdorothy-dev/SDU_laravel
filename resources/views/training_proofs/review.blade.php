<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Review Training Proof</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/unitdirector/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Training/proofs.css') }}">
</head>

<body id="body">
<input type="checkbox" id="sidebar-toggle-checkbox" style="display: none;">

<!-- Desktop Sidebar -->
<div class="sidebar-lg d-none d-lg-block">
  <div class="sidebar-header d-flex justify-content-between align-items-center px-3 mb-3">
    <div class="d-flex align-items-center">
      <img src="{{ asset('SDU_Logo.png') }}" class="sidebar-logo" alt="SDU">
      <h5 class="m-0 text-white">SDU UNIT DIRECTOR</h5>
    </div>
    <label for="sidebar-toggle-checkbox" class="btn btn-toggle" style="color:#fff;border:none;background:transparent"><i class="fas fa-bars"></i></label>
  </div>
  <div class="sidebar-content">
    <ul class="nav flex-column flex-grow-1">
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i><span> Dashboard</span></a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('directory_reports.index') ? 'active' : '' }}" href="{{ route('directory_reports.index') }}"><i class="fas fa-users me-2"></i><span> Directory & Reports</span></a></li>
       @if(in_array($user->role, ['unit director', 'unit_director']))
        <li class="nav-item"><a class="nav-link" href="{{ route('pending_approvals.index') }}"><i class="fas fa-clipboard-check me-2"></i>Pending Approvals <span class="badge bg-danger">{{ $pendingApprovalsCount ?? 0 }}</span></a></li>
        @endif
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('training_assignments.index') ? 'active' : '' }}" href="{{ route('training_assignments.index') }}"><i class="fas fa-tasks me-2"></i> <span> Training Assignments</span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('training_proofs.review_index') }}"><i class="fas fa-file-alt me-2"></i> <span> Review Training Proofs</span></a></li>
    </ul>
    <ul class="nav flex-column sidebar-footer">
      <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fas fa-user-circle me-2"></i> <span> Profile</span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i><span> Logout</span></a></li>
    </ul>
  </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<div class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Review Training Proof</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('training_proofs.review_index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    
    <div class="modern-card">
        <div class="card-header">
            <h2>Training Proof Review</h2>
            <p class="text-muted mb-0">Review and evaluate the submitted training proof</p>
        </div>
            
        <div class="proof-details-grid">
            <div class="proof-detail-card">
                <h6><i class="fas fa-user me-2"></i>Staff Member</h6>
                <p>{{ $trainingProof->user->full_name ?? 'N/A' }}</p>
                
                <h6><i class="fas fa-envelope me-2"></i>Email</h6>
                <p>{{ $trainingProof->user->email ?? 'N/A' }}</p>
                
                <h6><i class="fas fa-building me-2"></i>Office</h6>
                <p>{{ $trainingProof->user->office_code ?? 'N/A' }}</p>
            </div>
            
            <div class="proof-detail-card">
                <h6><i class="fas fa-graduation-cap me-2"></i>Training Title</h6>
                <p>{{ $trainingProof->trainingRecord->title ?? 'N/A' }}</p>
                
                <h6><i class="fas fa-calendar me-2"></i>Training Date</h6>
                <p>
                    {{ $trainingProof->trainingRecord->start_date->format('M d, Y') }} 
                    @if($trainingProof->trainingRecord->end_date)
                        - {{ $trainingProof->trainingRecord->end_date->format('M d, Y') }}
                    @endif
                </p>
                
                <h6><i class="fas fa-clock me-2"></i>Submitted On</h6>
                <p>{{ $trainingProof->created_at->format('M d, Y H:i') }}</p>
            </div>
                    
        </div>
        
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">Review Actions</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('training_proofs.process_review', $trainingProof->id) }}" class="review-form">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="remarks" class="form-label">Remarks (Optional)</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Add any remarks for the staff member..."></textarea>
                    </div>
                    
                    <div class="review-actions">
                        <button type="submit" name="action" value="approve" class="btn btn-success flex-grow-1">
                            <i class="fas fa-check-circle me-1"></i> Approve Training Proof
                        </button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger flex-grow-1">
                            <i class="fas fa-times-circle me-1"></i> Reject Training Proof
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">Training Proof File</h5>
            </div>
            <div class="card-body">
                <div class="file-preview-container">
                    @if(pathinfo($trainingProof->file_path, PATHINFO_EXTENSION) == 'pdf')
                        <i class="fas fa-file-pdf fa-4x file-preview-icon pdf mb-3"></i>
                        <h6>PDF Document</h6>
                        <p class="text-muted">Training Certificate/Proof</p>
                    @else
                        <i class="fas fa-file-image fa-4x file-preview-icon image mb-3"></i>
                        <h6>Image File</h6>
                        <p class="text-muted">Training Certificate/Proof</p>
                    @endif
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('training_proofs.view', $trainingProof->id) }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-eye me-1"></i> View File
                    </a>
                    
                    <a href="{{ route('training_proofs.view', ['id' => $trainingProof->id, 'download' => 1]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-download me-1"></i> Download File
                    </a>
                </div>
            </div>
        </div>
        
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">Status Information</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Status:</span>
                    <span class="status-indicator status-pending">
                        <i class="fas fa-clock me-1"></i> Pending Review
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Proof ID:</span>
                    <span class="fw-bold">{{ $trainingProof->id }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Staff Profile Modal -->
@include('staff.partials.profile_notification_modals')

@include('staff.partials.modal_scripts')

</body>
</html>
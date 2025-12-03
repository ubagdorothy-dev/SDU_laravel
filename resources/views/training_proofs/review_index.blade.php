<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Review Training Proofs - SDU Unit Director</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/unitdirector/dashboard.css') }}">
</head>
<body id="body">
<input type="checkbox" id="sidebar-toggle-checkbox" style="display: none;">

<!-- Desktop Sidebar -->
<div class="sidebar-lg d-none d-lg-block">
  <div class="d-flex justify-content-between align-items-center px-3 mb-3">
    <div class="d-flex align-items-center">
      <img src="{{ asset('SDU_Logo.png') }}" class="sidebar-logo" alt="SDU">
      <h5 class="m-0 text-white">SDU UNIT DIRECTOR</h5>
    </div>
    <label for="sidebar-toggle-checkbox" class="btn btn-toggle" style="color:#fff;border:none;background:transparent"><i class="fas fa-bars"></i></label>
  </div>
  <ul class="nav flex-column">
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i><span> Dashboard</span></a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('directory_reports.index') ? 'active' : '' }}" href="{{ route('directory_reports.index') }}"><i class="fas fa-users me-2"></i><span> Directory & Reports</span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('pending_approvals.index') }}"><i class="fas fa-clipboard-check me-2"></i>Pending Approvals <span class="badge bg-danger">{{ $pendingApprovalsCount ?? 0 }}</span></a></li>
     
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('training_assignments.index') ? 'active' : '' }}" href="{{ route('training_assignments.index') }}"><i class="fas fa-tasks me-2"></i> <span> Training Assignments</span></a></li>
    <li class="nav-item mt-auto"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i><span> Logout</span></a></li>
  </ul>
</div>

<div class="main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Review Training Proofs</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if($pendingProofs->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h4>No Pending Training Proofs</h4>
                    <p class="text-muted">There are currently no training proofs awaiting review.</p>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Pending Training Proofs</h5>
                        <p class="text-muted mb-0">Review and approve/reject training proofs submitted by staff members</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Training Title</th>
                                        <th>Office</th>
                                        <th>Submitted On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingProofs as $proof)
                                        <tr>
                                            <td>{{ $proof->user->full_name ?? 'N/A' }}</td>
                                            <td>{{ $proof->trainingRecord->title ?? 'N/A' }}</td>
                                            <td>{{ $proof->user->office_code ?? 'N/A' }}</td>
                                            <td>{{ $proof->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('training_proofs.review', $proof->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i> Review
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $pendingProofs->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
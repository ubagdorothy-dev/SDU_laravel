<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Training Proofs - SDU Unit Director</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/unitdirector/dashboard.css') }}">

</head>
<body>
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
  <div class="sidebar-content d-flex flex-column flex-grow-1">
    <ul class="nav flex-column flex-grow-1">
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i><span> Dashboard</span></a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('directory_reports.index') ? 'active' : '' }}" href="{{ route('directory_reports.index') }}"><i class="fas fa-users me-2"></i><span> Directory & Reports</span></a></li>
       @if(in_array($user->role, ['unit director', 'unit_director']))
        <li class="nav-item"><a class="nav-link" href="{{ route('pending_approvals.index') }}"><i class="fas fa-clipboard-check me-2"></i><span> Pending Approvals</span> <span class="badge bg-danger">{{ $pending_approvals ?? 0 }}</span></a></li>
        @endif
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('training_assignments.index') ? 'active' : '' }}" href="{{ route('training_assignments.index') }}"><i class="fas fa-tasks me-2"></i><span> Training Assignments</span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('training_proofs.review_index') }}"><i class="fas fa-file-alt me-2"></i><span> Review Training Proofs</span></a></li>
    </ul>
    <ul class="nav flex-column sidebar-footer">
      <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fas fa-user-circle me-2"></i><span> Profile</span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i><span> Logout</span></a></li>
    </ul>
  </div>
</div>

<!-- MAIN CONTENT -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<div class="main-content">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
        <div class="card-header bg-light">
            <h5 class="mb-1">Pending Training Proofs</h5>
            <small class="text-muted">Review and approve/reject training proofs submitted by staff members</small>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
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
                                    <a href="{{ route('training_proofs.review', $proof->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i> Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $pendingProofs->links() }}
            </div>
        </div>
    </div>

    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Staff Profile Modal -->
@include('staff.partials.profile_notification_modals')
@include('staff.partials.modal_scripts')

</body>
</html>

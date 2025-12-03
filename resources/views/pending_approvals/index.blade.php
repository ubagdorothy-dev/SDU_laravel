<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Unit Director - Pending Approvals</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/unitdirector/pending_approvals.css') }}">
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
    @if(in_array($user->role, ['unit director', 'unit_director']))
      <li class="nav-item"><a class="nav-link" href="{{ route('pending_approvals.index') }}"><i class="fas fa-clipboard-check me-2"></i>Pending Approvals <span class="badge bg-danger">{{ $pendingApprovalsCount ?? 0 }}</span></a></li>
      @endif
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('training_assignments.index') ? 'active' : '' }}" href="{{ route('training_assignments.index') }}"><i class="fas fa-tasks me-2"></i> <span> Training Assignments</span></a></li>
    <li class="nav-item mt-auto"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i><span> Logout</span></a></li>
  </ul>
</div>

<!-- Main Content Area -->
<div class="main-content">
    <button class="btn btn-primary d-lg-none mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
        <i class="fas fa-bars"></i> Menu
    </button>

    <div class="header mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="fw-bold mb-2" style="color: #1e293b;"><i class="fas fa-clipboard-check me-2"></i> Pending Approvals</h1>
                <p class="mb-0" style="color: #6b7280;">Review and approve or reject pending account requests.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Back to Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-cards mb-4">
        <div class="card"><h3>Pending Accounts</h3><p>{{ $pendingUsers->count() }}</p></div>
        <div class="card">
            <h3>Total Users</h3>
            <p>
                {{ \App\Models\User::whereNotIn('role', ['unit_director', 'unit director'])->count() }}
            </p>
        </div>
        <div class="card">
            <h3>Pending Rate</h3>
            <p>
                @php
                    $totalUsers = \App\Models\User::whereNotIn('role', ['unit_director', 'unit director'])->count();
                    $pendingCount = $pendingUsers->count();
                    $pendingRate = $totalUsers > 0 ? round(($pendingCount / max(1, $totalUsers)) * 100) : 0;
                @endphp
                {{ $pendingRate }}%
            </p>
        </div>
    </div>

    <div class="content-box">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Pending Users List -->
        <div class="pending-users-box">
            <h2><i class="fas fa-hourglass-half"></i> Pending Accounts</h2>

            @if($pendingUsers->count() == 0)
                <div class="no-pending">
                    <i class="fas fa-check-circle"></i>
                    <h4>All accounts approved!</h4>
                    <p>There are no pending accounts at the moment.</p>
                </div>
            @else
                @foreach($pendingUsers as $user)
                    <div class="pending-item">
                        <div class="pending-info">
                            <h4>{{ $user->full_name }}</h4>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Office:</strong> {{ $user->office_code ?? 'Not assigned' }}</p>
                            <p><strong>Registered:</strong> {{ $user->created_at ? date('M d, Y @ h:i A', strtotime($user->created_at)) : 'N/A' }}</p>
                            <span class="role-badge {{ strtolower($user->role) }}">
                                <i class="fas fa-badge"></i> {{ strtoupper($user->role) }}
                            </span>
                        </div>
                        <div class="pending-actions">
                            <form method="POST" action="{{ route('pending_approvals.approve', $user->user_id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Approve this account?');">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('pending_approvals.reject', $user->user_id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Reject and remove this account?');">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Synchronize the hidden checkbox with the body.toggled class.
    var sidebarCheckbox = document.getElementById('sidebar-toggle-checkbox');
    if (sidebarCheckbox) {
        // When checkbox changes, reflect state on body for backwards compatibility
        sidebarCheckbox.addEventListener('change', function(){
            var b = document.getElementById('body') || document.body;
            if (sidebarCheckbox.checked) {
                b.classList.add('toggled');
                b.setAttribute('data-sidebar-collapsed', '1');
            } else {
                b.classList.remove('toggled');
                b.setAttribute('data-sidebar-collapsed', '0');
            }
        });
        // Initialize from current state
        if (sidebarCheckbox.checked) {
            var b = document.getElementById('body') || document.body;
            b.classList.add('toggled');
            b.setAttribute('data-sidebar-collapsed', '1');
        } else {
            document.getElementById('body').setAttribute('data-sidebar-collapsed', '0');
        }
    }

    // Keep the label click behavior simple: label toggles the checkbox automatically.
    // Remove direct click-based toggling to avoid double-toggle conflicts.
    // Ensure clicks on the label or its icon always toggle the checkbox (cover edge cases).
    var sidebarLabel = document.getElementById('sidebar-toggle');
    if (sidebarLabel && sidebarCheckbox) {
        sidebarLabel.addEventListener('click', function (e) {
            // toggle checkbox programmatically to avoid label quirks
            sidebarCheckbox.checked = !sidebarCheckbox.checked;
            // dispatch change event so other handlers respond
            var ev = new Event('change', { bubbles: true });
            sidebarCheckbox.dispatchEvent(ev);
            e.preventDefault();
        });
    }
});
</script>
</body>
</html>
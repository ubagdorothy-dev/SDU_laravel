<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Unit Director - Pending Approvals</title>
<!-- Bootstrap & Font Awesome Links -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap');

body { 
    font-family: 'Montserrat', sans-serif;
    display: flex; 
    background-color: #f0f2f5;
}

.main-content { 
    flex-grow: 1; 
    padding: 2rem; 
    transition: margin-left 0.3s ease-in-out; 
}

.sidebar-lg { 
    transition: width 0.3s ease-in-out; 
}

@media (min-width: 992px) {
    .sidebar-lg { 
        width: 250px; 
        background-color: #1a237e; 
        color: white; 
        height: 100vh; 
        position: fixed; 
        padding-top: 2rem; 
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .sidebar-lg .d-flex.justify-content-between { 
        padding: 0.75rem 1rem; 
    }
    .main-content { margin-left: 250px; }
}

/* Sidebar collapse toggle */
#sidebar-toggle-checkbox:checked ~ .sidebar-lg { 
    width: 80px; 
    padding-top: 1rem; 
}
#sidebar-toggle-checkbox:checked ~ .sidebar-lg .nav-link span,
#sidebar-toggle-checkbox:checked ~ .sidebar-lg .logo-text,
#sidebar-toggle-checkbox:checked ~ .sidebar-lg h5 { 
    display: none; 
}
#sidebar-toggle-checkbox:checked ~ .main-content { 
    margin-left: 80px; 
}
#sidebar-toggle-checkbox:checked ~ .sidebar-lg .nav-link { 
    text-align: center; 
    padding: 12px 0; 
}
#sidebar-toggle-checkbox:checked ~ .sidebar-lg .d-flex.justify-content-between { 
    padding-left: 0.25rem !important; 
    padding-right: 0.25rem !important; 
    margin-bottom: 1rem !important; 
}

/* Consistent sidebar styling */
.sidebar-lg .d-flex h5 { 
    font-weight: 700; 
    margin-right: 0 !important; 
    font-size: 1rem;
}
.sidebar-lg .nav-link { 
    color: #ffffff !important; 
    padding: 12px 20px; 
    border-radius: 5px; 
    margin: 5px 15px; 
    transition: background-color 0.2s; 
    white-space: nowrap; 
    overflow: hidden; 
    display: flex;
    align-items: center;
}
.sidebar-lg .nav-link:hover, 
.sidebar-lg .nav-link.active { 
    background-color: #3f51b5; 
    color: #ffffff !important; 
}
.sidebar-lg .nav-link i {
    min-width: 20px;
    text-align: center;
    margin-right: 15px;
}
#sidebar-toggle-checkbox:checked ~ .sidebar-lg .nav-link i {
    margin-right: 0;
}

.sidebar-lg .btn-toggle { 
    background-color: transparent; 
    border: none; 
    color: #ffffff; 
    padding: 6px 10px; 
    cursor: pointer; 
}
.sidebar-lg .btn-toggle:focus { 
    box-shadow: none; 
}

.sidebar-logo { 
    height: 30px; 
    width: auto; 
    margin-right: 8px; 
}

/* Offcanvas sidebar for mobile */
.offcanvas {
    background-color: #1a237e;
}
.offcanvas .nav-link {
    color: #ffffff !important;
    padding: 12px 20px;
    border-radius: 5px;
    margin: 5px 0;
    transition: background-color 0.2s;
}
.offcanvas .nav-link:hover,
.offcanvas .nav-link.active {
    background-color: #3f51b5;
    color: #ffffff !important;
}
.offcanvas .nav-link i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

/* Responsive adjustments */
@media (max-width: 991.98px) { 
    .main-content { 
        margin-left: 0 !important; 
        padding: 1rem; 
    } 
}

.stats-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.card { background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.12); padding: 2rem 1.5rem; text-align: center; border: none; transition: all 0.3s ease; position: relative; overflow: hidden; }
.card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--card-color), var(--card-color-light)); }
.card h3 { margin: 0 0 1rem; color: var(--card-color); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
.card p { font-size: 2.5rem; font-weight: 900; margin: 0; color: var(--card-color); }
.card:nth-child(1) { --card-color: #6366f1; --card-color-light: #a5b4fc; }
.card:nth-child(2) { --card-color: #10b981; --card-color-light: #6ee7b7; }
.card:nth-child(3) { --card-color: #f59e0b; --card-color-light: #fbbf24; }

.content-box { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); padding: 2rem; border: 1px solid rgba(255, 255, 255, 0.2); }
.content-box h2 { color: #1e293b; border-bottom: 3px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 25px; font-weight: 700; font-size: 1.5rem; }

.pending-users-box { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
.pending-users-box h2 { color: #1a237e; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #667eea; font-weight: 700; }
.pending-item { background: #f8f9fa; border-left: 4px solid #667eea; border-radius: 8px; padding: 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease; }
.pending-item:hover { box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2); }
.pending-info h4 { color: #1a237e; margin: 0 0 8px; font-weight: 700; }
.role-badge { display: inline-block; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-top: 8px; }
.role-badge.staff { background-color: #cfe2ff; color: #084298; }
.role-badge.head { background-color: #cff4fc; color: #055160; }

.no-pending { text-align: center; padding: 2rem; }
.no-pending i { font-size: 3rem; color: #10b981; margin-bottom: 1rem; }
.no-pending h4 { color: #1a237e; margin-bottom: 0.5rem; }
.no-pending p { color: #6b7280; }

.alert { border-radius: 8px; }

@media (max-width: 768px) { 
    .main-content { padding: 1rem; } 
    .stats-cards { grid-template-columns: 1fr; } 
    .pending-item { flex-direction: column; align-items: flex-start; }
    .pending-actions { margin-top: 15px; }
    .card { padding: 1.5rem 1rem; }
    .card p { font-size: 2rem; }
    .content-box { padding: 1.5rem; border-radius: 16px; }
    .pending-users-box { padding: 20px; }
}
</style>
</head>
<body id="body">
<input type="checkbox" id="sidebar-toggle-checkbox" style="display: none;">

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-start bg-dark" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header text-white">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">SDU Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i> <span>Dashboard</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('directory_reports.index') }}"><i class="fas fa-users me-2"></i> <span>Directory & Reports</span></a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('pending_approvals.index') }}"><i class="fas fa-clipboard-check me-2"></i> <span>Pending Approvals <span class="badge bg-danger">{{ $pendingUsers->count() }}</span></span></a></li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Desktop Sidebar -->
<div class="sidebar-lg d-none d-lg-block">
    <div class="d-flex justify-content-between align-items-center px-3 mb-3">
        <div class="d-flex align-items-center">
            <img src="{{ asset('SDU_Logo.png') }}" alt="SDU Logo" class="sidebar-logo">
            <h5 class="m-0 text-white"><span class="logo-text">SDU UNIT DIRECTOR</span></h5>
        </div>
        <label for="sidebar-toggle-checkbox" id="sidebar-toggle" class="btn btn-toggle"><i class="fas fa-bars"></i></label>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i> <span>Dashboard</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('directory_reports.index') }}"><i class="fas fa-users me-2"></i> <span>Directory & Reports</span></a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ route('pending_approvals.index') }}"><i class="fas fa-clipboard-check me-2"></i> <span>Pending Approvals <span class="badge bg-danger">{{ $pendingUsers->count() }}</span></span></a></li>
        <li class="nav-item mt-auto">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i> <span>Logout</span>
            </a>
        </li>
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
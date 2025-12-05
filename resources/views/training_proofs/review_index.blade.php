<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Training Proofs - SDU Unit Director</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* =========================================================
   GLOBAL
   ========================================================= */
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap');

body {
    font-family: 'Montserrat', sans-serif;
    background-color: #f3f5fa;
    margin: 0;
    display: flex;
    min-height: 100vh;
}

/* =========================================================
   SIDEBAR
   ========================================================= */
.sidebar-lg { transition: width 0.3s ease-in-out; }

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
        overflow: hidden;
    }
    .main-content { margin-left: 250px; }
}

.sidebar-header {
    padding: 0 15px 1rem 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-logo {
    width: 40px;
    height: auto;
    margin-right: 10px;
    display: inline-block;
    vertical-align: middle;
}

.sidebar-footer {
    margin-top: auto;
    padding: 15px 0 10px 0;
    position: relative;
    min-height: 80px;
}

.sidebar-lg .nav-link {
    color: white; 
    padding: 12px 20px; 
    border-radius: 0; 
    margin: 0; 
    transition: background-color 0.2s; 
    display: flex;
    align-items: center;
    text-decoration: none;
}

.sidebar-lg .nav-link:hover { 
    background-color: #3f51b5; 
}

.sidebar-lg .nav-link.active { 
    background-color: #303f9f; 
    border-left: 3px solid #ffffff;
}

.sidebar-lg .nav-link i {
    width: 24px;
    text-align: center;
    margin-right: 12px;
}

#sidebar-toggle-checkbox:checked ~ .sidebar-lg { width: 80px; padding-top: 1rem; }
#sidebar-toggle-checkbox:checked ~ .sidebar-lg .nav-link span,
#sidebar-toggle-checkbox:checked ~ .sidebar-lg .logo-text,
#sidebar-toggle-checkbox:checked ~ .sidebar-lg h5 { display: none; }
#sidebar-toggle-checkbox:checked ~ .main-content { margin-left: 80px; }

.sidebar-lg .btn-toggle { background-color: transparent; border: none; color: #ffffff; padding: 6px 10px; cursor: pointer; }
.sidebar-lg .btn-toggle:focus { box-shadow: none; }

/* =========================================================
   CONTAINER
   ========================================================= */
.container {
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    padding: 2rem;
    min-height: 100vh;
    background-color: #f3f5fa;
}

/* =========================================================
   PAGE HEADER
   ========================================================= */
.review-header {
    background: linear-gradient(120deg, #3b82f6 0%, #1e40af 100%);
    padding: 2rem;
    border-radius: 14px;
    color: white;
    margin-bottom: 2rem;
}

.review-header h1 {
    font-weight: 700;
}

/* =========================================================
   CARDS
   ========================================================= */
.card {
    border-radius: 14px;
    border: none;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    background: #ffffff;
    display: flex;
    flex-direction: column;
}

.card-header {
    background: #eef2ff;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e0e3f1;
}

.card-header h5 {
    margin: 0;
    font-weight: 700;
    color: #1e2a78;
}

.card-body {
    padding: 1.5rem;
    flex: 1;
}

/* =========================================================
   TABLE STYLING ENHANCED
   ========================================================= */
.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.table thead th {
    background-color: #0d1b6f !important;
    color: #ffffff !important;
    font-weight: 700;
    padding: 16px 12px;
    text-align: left;
    border-bottom: 2px solid #e0e3f1;
}

.table tbody td {
    vertical-align: middle;
    padding: 14px 12px;
    border-bottom: 1px solid #e9ecef;
    color: #1e2a78;
}

.table tbody tr:hover {
    background-color: #f1f4ff;
}

.table-hover tbody tr:hover {
    cursor: pointer;
}

.table td .btn {
    padding: 0.45rem 0.85rem;
    font-size: 0.85rem;
}

.table-responsive {
    overflow-x: auto;
}

/* =========================================================
   BUTTONS
   ========================================================= */
.btn-primary {
    background-color: #1e40af !important;
    border: none;
    border-radius: 10px;
    font-weight: 600;
}

.btn-primary:hover {
    background-color: #1a2f92 !important;
}

/* =========================================================
   FORM ELEMENTS
   ========================================================= */
textarea.form-control {
    border-radius: 10px;
    padding: 0.75rem;
    resize: vertical;
}

label.form-label {
    font-weight: 600;
    color: #1e2a78;
}

/* =========================================================
   RESPONSIVE
   ========================================================= */
@media (max-width: 992px) {
    .main-content {
        margin-left: 0;
        padding: 1rem;
    }

    .sidebar-lg {
        display: none;
    }

    .review-header {
        text-align: center;
        padding: 1.5rem;
    }
}

</style>

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
    <label for="sidebar-toggle-checkbox" class="btn btn-toggle"><i class="fas fa-bars"></i></label>
  </div>
  <div class="sidebar-content d-flex flex-column flex-grow-1">
    <ul class="nav flex-column flex-grow-1">
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i><span> Dashboard</span></a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('directory_reports.index') ? 'active' : '' }}" href="{{ route('directory_reports.index') }}"><i class="fas fa-users me-2"></i><span> Directory & Reports</span></a></li>
       @if(in_array($user->role, ['unit director', 'unit_director']))
        <li class="nav-item"><a class="nav-link" href="{{ route('pending_approvals.index') }}"><i class="fas fa-clipboard-check me-2"></i>Pending Approvals <span class="badge bg-danger">{{ $pendingApprovalsCount ?? 0 }}</span></a></li>
        @endif
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('training_assignments.index') ? 'active' : '' }}" href="{{ route('training_assignments.index') }}"><i class="fas fa-tasks me-2"></i> <span> Training Assignments</span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('training_proofs.review_index') }}"><i class="fas fa-file-alt me-2"></i> <span> Review Training Proofs</span></a></li>
    </ul>
    <div class="footer">
      <ul class="nav flex-column sidebar-footer">
        <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fas fa-user-circle me-2"></i> <span> Profile</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i><span> Logout</span></a></li>
      </ul>
    </div>
  </div>
</div>

<!-- MAIN CONTENT -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<div class="container">

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
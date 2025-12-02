<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Staff Directory & Training Reports</title>
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

.content-box{background:rgba(255,255,255,.98);border-radius:12px;padding:2rem;box-shadow:0 8px 32px rgba(0,0,0,.08);border:1px solid rgba(0,0,0,.03)}
.content-box h2{font-weight:800;color:#2b3742;margin-bottom:1rem}
.table thead th{background:#f1f5f9;color:#374151;font-weight:700}
.table tbody td{vertical-align:middle}
.badge-complete{background:#16a34a;color:#fff}
.badge-ongoing{background:#2563eb;color:#fff}

.modal-body .form-label, .modal-body .form-control-plaintext { color: #1e293b !important; }
.modal-dialog { display: flex; align-items: center; min-height: calc(100vh - 1rem); }

/* Master-detail table styles (lightweight) */
.master-table{ width:100%; border-collapse:collapse; min-width:720px; }
.master-table thead th{ text-align:left; font-size:0.85rem; padding:12px 10px; color:#6b7280; border-bottom:1px solid #eef2f7; font-weight:700; }
.master-table td{ padding:12px 10px; vertical-align:middle; border-bottom:1px solid #f1f5f9; font-size:0.95rem; }
.col-toggle{ width:44px; text-align:center; }
.toggle-indicator{ display:inline-block; width:18px; height:18px; transition:transform 160ms ease; font-size:12px; }
.toggle-indicator.open{ transform:rotate(90deg); }
.detail-row{ background:#fbfdff; }
.detail-cell{ padding:10px 12px; }
.detail-table{ width:100%; border-collapse:collapse; margin-top:6px; }
.detail-table th{ font-size:0.8rem; padding:8px 10px; text-align:left; color:#6b7280; border-bottom:1px solid #eef2f7; font-weight:700; }
.detail-table td{ padding:8px 10px; border-bottom:1px dashed #f1f5f9; font-size:0.9rem; }
.badge{ display:inline-block; padding:4px 8px; font-size:0.78rem; border-radius:999px; color:#fff; font-weight:700; }
.badge.completed{ background:#10b981; }
.badge.ongoing{ background:#1f6feb; }
.badge.cancelled{ background:#ef4444; }

@media (max-width: 768px) {
    .main-content { padding: 1rem; }
    .content-box { padding: 1.5rem; }
    .d-flex.flex-wrap { flex-direction: column; }
    .me-2 { margin-bottom: 1rem; }
}
</style>
</head>
<body>
    
<input type="checkbox" id="sidebar-toggle-checkbox" style="display:none;">

<!-- Offcanvas Mobile -->
<div class="offcanvas offcanvas-start bg-dark" tabindex="-1" id="offcanvasNavbar">
  <div class="offcanvas-header text-white">
    <h5 class="offcanvas-title">SDU Menu</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i>Dashboard</a></li>
      <li class="nav-item"><a class="nav-link active" href="{{ route('directory_reports.index') }}"><i class="fas fa-users me-2"></i>Directory & Reports</a></li>
      @if(in_array($user->role, ['unit director', 'unit_director']))
      <li class="nav-item"><a class="nav-link" href="{{ route('pending_approvals.index') }}"><i class="fas fa-clipboard-check me-2"></i>Pending Approvals <span class="badge bg-danger">{{ $pendingApprovalsCount ?? 0 }}</span></a></li>
      @endif
      <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a>
      </li>
    </ul>
  </div>
</div>

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
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}" ><i class="fas fa-chart-line me-2"></i><span> Dashboard</span></a></li>
    <li class="nav-item"><a class="nav-link active" href="{{ route('directory_reports.index') }}" ><i class="fas fa-users me-2"></i><span> Directory & Reports</span></a></li>
    @if(in_array($user->role, ['unit director', 'unit_director']))
    <li class="nav-item"><a class="nav-link" href="{{ route('pending_approvals.index') }}"><i class="fas fa-clipboard-check me-2"></i> <span> Pending Approvals <span class="badge bg-danger">{{ $pendingApprovalsCount ?? 0 }}</span></span></a></li>
    @endif
    <li class="nav-item mt-auto">
        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt me-2"></i><span> Logout</span>
        </a>
    </li>
  </ul>
</div>

<!-- Main -->
<div class="main-content">
  <button class="btn btn-primary d-lg-none mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"><i class="fas fa-bars"></i> Menu</button>

  <div class="content-box">
    <h2>Staff Directory & Training Reports</h2>
    <hr style="border-color:#e6edf3;margin-top:-6px;margin-bottom:1.5rem">

    <!-- Filters & Actions -->
    <div class="d-flex flex-wrap align-items-center mb-3 gap-2">
      <div class="me-2">
        <label class="form-label small text-muted mb-1">Offices</label>
        <select id="filter-office" class="form-select form-select-sm">
          <option value="all">All</option>
          @foreach($offices as $office)
              <option value="{{ $office->code }}" {{ (request()->get('office') == $office->code) ? 'selected' : '' }}>
                  {{ $office->name }} ({{ $office->code }})
              </option>
          @endforeach
        </select>
      </div>
      <div class="me-2">
        <label class="form-label small text-muted mb-1">Role</label>
        <select id="filter-role" class="form-select form-select-sm">
          <option value="all">All</option>
          <option value="staff" {{ (request()->get('role') == 'staff') ? 'selected' : '' }}>Staff</option>
          <option value="head" {{ (request()->get('role') == 'head') ? 'selected' : '' }}>Head</option>
        </select>
      </div>
      <div class="me-2">
        <label class="form-label small text-muted mb-1">Period</label>
        <select id="filter-period" class="form-select form-select-sm">
          <option value="all">All</option>
          @php
              $currentYear = date('Y');
          @endphp
          @for ($y = $currentYear; $y >= $currentYear - 5; $y--)
              <option value="{{ $y }}" {{ (request()->get('period') == (string)$y) ? 'selected' : '' }}>{{ $y }}</option>
          @endfor
        </select>
      </div>
      <div class="me-2">
        <label class="d-block small text-muted mb-1">&nbsp;</label>
        <button id="applyFilters" class="btn btn-primary btn-sm">Apply Filters</button>
      </div>

      <div class="ms-auto d-flex gap-2">
        <button id="printBtn" class="btn btn-outline-secondary btn-sm"><i class="fa fa-print"></i> Print</button>
        <div class="btn-group">
          <button class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Export</button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#" id="exportCsv">Export CSV</a></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Trainings</h5>
                    <p class="card-text display-4">{{ $trainingStats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <p class="card-text display-4">{{ $trainingStats['completed'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Upcoming</h5>
                    <p class="card-text display-4">{{ $trainingStats['upcoming'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Ongoing</h5>
                    <p class="card-text display-4">{{ $trainingStats['ongoing'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Office Statistics -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Office Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Office</th>
                                    <th>Code</th>
                                    <th>Staff Count</th>
                                    <th>Total Trainings</th>
                                    <th>Completed Trainings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($officeStats as $stat)
                                <tr>
                                    <td>{{ $stat['office']->name }}</td>
                                    <td>{{ $stat['office']->code }}</td>
                                    <td>{{ $stat['staff_count'] }}</td>
                                    <td>{{ $stat['training_count'] }}</td>
                                    <td>{{ $stat['completed_training_count'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Directory -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>User Directory</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="staffTable">
                            <thead>
                                <tr>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Office</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Program</th>
                                    <th scope="col">Job Function</th>
                                    <th scope="col">Degree Attained</th>
                                    <th scope="col">Approved</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="staffBody">
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->office ? $user->office->name : 'N/A' }}</td>
                                    <td>{{ $user->staffDetail ? $user->staffDetail->position : 'N/A' }}</td>
                                    <td>{{ $user->staffDetail ? $user->staffDetail->program : 'N/A' }}</td>
                                    <td>{{ $user->staffDetail ? $user->staffDetail->job_function : 'N/A' }}</td>
                                    <td>{{ $user->staffDetail ? $user->staffDetail->degree_attained : 'N/A' }}</td>
                                    <td>
                                        @if($user->is_approved)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-warning">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info btn-view-trainings" 
                                                data-user-id="{{ $user->user_id }}" 
                                                data-user-name="{{ $user->full_name }}">
                                            View Trainings
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Trainings Modal -->
<div class="modal fade" id="trainingsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Staff Trainings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4 id="trainingsModalTitle">Training Records</h4>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="table-dark">
              <tr>
                <th>Training Title</th>
                <th>Description</th>
                <th>Dates</th>
                <th>Venue</th>
                <th>Nature</th>
                <th>Scope</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="trainingsModalBody">
              <!-- populated dynamically -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// CSV export helper
function tableToCSV(filename) {
  const rows = document.querySelectorAll('table tr');
  const csv = [];
  rows.forEach(row => {
    const cols = row.querySelectorAll('th, td');
    const rowData = [];
    cols.forEach(col => {
      let txt = col.innerText.replace(/\n/g,' ').replace(/\s+/g,' ').trim();
      txt = '"' + txt.replace(/"/g,'""') + '"';
      rowData.push(txt);
    });
    csv.push(rowData.join(','));
  });
  const csvStr = csv.join('\n');
  const blob = new Blob([csvStr], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = filename;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

document.getElementById('exportCsv').addEventListener('click', function(e){
  e.preventDefault();
  tableToCSV('staff-training-reports.csv');
});

document.getElementById('printBtn').addEventListener('click', function(){
  window.print();
});

// Filtering logic (client-side simple)
document.getElementById('applyFilters').addEventListener('click', function(){
  const office = encodeURIComponent(document.getElementById('filter-office').value || 'all');
  const role = encodeURIComponent(document.getElementById('filter-role').value || 'all');
  const period = encodeURIComponent(document.getElementById('filter-period').value || 'all');
  // Redirect to same page with query parameters so results come from SQL
  const qs = `?office=${office}&role=${role}&period=${period}`;
  window.location.href = window.location.pathname + qs;
});

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

// Server-provided data for trainings
const trainingsByUser = {};
const staffList = [];

// Populate trainingsByUser and staffList from the database
@foreach($users as $user)
    // Add staff to staffList
    staffList.push({
        id: {{ $user->user_id }},
        name: "{{ $user->full_name }}",
        role: "{{ $user->role }}",
        email: "{{ $user->email }}",
        office: "{{ $user->office ? $user->office->name : '' }}",
        position: "{{ $user->staffDetail ? $user->staffDetail->position : '' }}",
        program: "{{ $user->staffDetail ? $user->staffDetail->program : '' }}",
        job_function: "{{ $user->staffDetail ? $user->staffDetail->job_function : '' }}",
        degree_attained: "{{ $user->staffDetail ? $user->staffDetail->degree_attained : '' }}"
    });
    
    // Add trainings for this user
    trainingsByUser[{{ $user->user_id }}] = [
        @foreach($user->trainingRecords as $training)
        {
            training_id: {{ $training->id }},
            title: "{{ $training->title }}",
            description: "{{ $training->description }}",
            start_date: "{{ $training->start_date }}",
            end_date: "{{ $training->end_date }}",
            venue: "{{ $training->venue }}",
            status: "{{ $training->status }}",
            nature: "{{ $training->nature }}",
            scope: "{{ $training->scope }}"
        },
        @endforeach
    ];
@endforeach

// Helper functions
function escapeHtml(unsafe) {
    if (unsafe === null || unsafe === undefined) return '';
    return String(unsafe).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}

function formatDateClient(dStr) {
    if (!dStr) return '-';
    const d = new Date(dStr);
    if (isNaN(d)) return dStr;
    return d.toLocaleDateString();
}

function renderStatusBadgeClient(status) {
    const s = (status || '').toLowerCase();
    if (s === 'completed') return '<span class="badge completed">Completed</span>';
    if (s === 'ongoing') return '<span class="badge ongoing">Ongoing</span>';
    if (s === 'cancelled') return '<span class="badge cancelled">Cancelled</span>';
    return '<span class="badge ongoing">' + escapeHtml(status || 'Unknown') + '</span>';
}

// showTrainingsFor: populate modal from trainingsByUser and show
function showTrainingsFor(userId, userName) {
    const modalTitle = document.getElementById('trainingsModalTitle');
    const body = document.getElementById('trainingsModalBody');
    modalTitle.innerHTML = `Training Records for ${escapeHtml(userName)}`;
    body.innerHTML = '';
    const arr = trainingsByUser[userId] || [];
    if (arr.length === 0) {
        body.innerHTML = '<tr><td colspan="8" class="text-muted">No training records found.</td></tr>';
    } else {
        arr.forEach(t => {
            const tr = document.createElement('tr');
            const statusHtml = renderStatusBadgeClient(t.status || '');
            const actions = t.training_id ? 
                `<button class="btn btn-sm btn-outline-primary" onclick="onViewTraining(${t.training_id}, this)">View</button> 
                 <button class="btn btn-sm btn-outline-secondary" onclick="onUploadProof(${t.training_id}, this)">Upload Proof</button>` + 
                (( (t.status||'').toLowerCase() !== 'completed') ? 
                    ` <button class="btn btn-sm btn-success" onclick="onMarkCompleted(${t.training_id}, this)">Mark Completed</button>` : 
                    '') : 
                '<span class="text-muted">No actions</span>';
            const dates = (t.start_date || t.end_date) ? 
                `${t.start_date ? formatDateClient(t.start_date) : ''}${t.start_date && t.end_date ? ' - ' : ''}${t.end_date ? formatDateClient(t.end_date) : ''}` : 
                '-';
            const nature = t.nature || '';
            const scope = t.scope || '';
            tr.innerHTML = `
                <td>${escapeHtml(t.title||'')}</td>
                <td>${escapeHtml(t.description||'')}</td>
                <td>${escapeHtml(dates)}</td>
                <td>${escapeHtml(t.venue||'')}</td>
                <td>${escapeHtml(nature)}</td>
                <td>${escapeHtml(scope)}</td>
                <td>${statusHtml}</td>
                <td>${actions}</td>
            `;
            body.appendChild(tr);
        });
    }
    const mdl = new bootstrap.Modal(document.getElementById('trainingsModal'));
    mdl.show();
}

// Delegate view trainings clicks
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-view-trainings');
    if (!btn) return;
    const uid = btn.getAttribute('data-user-id');
    const uname = btn.getAttribute('data-user-name');
    showTrainingsFor(uid, uname);
});

/* --- Action handlers --- */
function onViewTraining(trainingId, btn) {
    // find row and show a minimal detail dialog (alert for now)
    const row = btn.closest('tr');
    if (!row) return;
    // columns: 0=name,1=role,2=office,3=title,4=date,5=venue,6=status,7=actions
    const title = row.children[0] ? row.children[0].innerText : '';
    const date = row.children[2] ? row.children[2].innerText : '';
    const venue = row.children[3] ? row.children[3].innerText : '';
    const status = row.children[6] ? row.children[6].innerText : '';
    alert(`Training:
${title}
Date: ${date}
Venue: ${venue}
Status: ${status}`);
}

function onUploadProof(trainingId, btn) {
    if (!trainingId) { 
        alert('No training ID available for upload.'); 
        return; 
    }
    // create a temporary file input
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.pdf,image/*';
    input.addEventListener('change', function(){
        const file = input.files[0];
        if (!file) return;
        const fd = new FormData();
        fd.append('training_id', trainingId);
        fd.append('proof', file);
        btn.disabled = true;
        fetch('/training_proofs/' + trainingId + '/upload', { 
            method: 'POST', 
            body: fd, 
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(r => r.json())
        .then(j => {
            if (j.success) {
                alert('Proof uploaded successfully.');
            } else {
                alert('Upload failed: ' + (j.error || 'Unknown'));
            }
        })
        .catch(err => { 
            console.error(err); 
            alert('Upload failed.'); 
        })
        .finally(() => { 
            btn.disabled = false; 
            input.remove(); 
        });
    });
    // trigger click
    input.click();
}

function onMarkCompleted(trainingId, btn) {
    if (!trainingId) { 
        alert('No training ID available.'); 
        return; 
    }
    if (!confirm('Mark this training as completed?')) return;
    btn.disabled = true;
    fetch('/training_records/' + trainingId + '/status', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: 'completed' })
    })
    .then(r => r.json())
    .then(j => {
        if (j.success) {
            // update status cell in the same row
            const row = btn.closest('tr');
            if (row) {
                const statusCell = row.querySelector('td:nth-child(7)');
                if (statusCell) statusCell.innerHTML = '<span class="badge completed">Completed</span>';
                // remove the complete button
                const compBtn = row.querySelector('.btn-success'); 
                if (compBtn) compBtn.remove();
            }
        } else {
            alert('Failed to update status: ' + (j.error || j.message || 'Unknown'));
        }
    })
    .catch(err => { 
        console.error(err); 
        alert('Request failed.'); 
    })
    .finally(() => { 
        btn.disabled = false; 
    });
}
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Directory & Training Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/unitdirector/directoryreports.css') }}">
</head>
<body>
    
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
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('pending_approvals.index') ? 'active' : '' }}" href="{{ route('pending_approvals.index') }}"><i class="fas fa-clipboard-check me-2"></i>Pending Approvals <span class="badge bg-danger">{{ $pendingApprovalsCount ?? 0 }}</span></a></li>
    @endif
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('training_assignments.index') ? 'active' : '' }}" href="{{ route('training_assignments.index') }}"><i class="fas fa-tasks me-2"></i> <span> Training Assignments</span></a></li>
    <li class="nav-item mt-auto"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i><span> Logout</span></a></li>
  </ul>
</div>

<!-- Main -->
<div class="main-content">
  <div class="content-box">
    <h2>Staff Directory & Training Reports</h2>

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
    <div class="stats-cards">
        <div class="card">
            <h3>Total Trainings</h3>
            <p>{{ $trainingStats['total'] }}</p>
        </div>
        <div class="card">
            <h3>Completed</h3>
            <p>{{ $trainingStats['completed'] }}</p>
        </div>
        <div class="card">
            <h3>Upcoming</h3>
            <p>{{ $trainingStats['upcoming'] }}</p>
        </div>
        <div class="card">
            <h3>Ongoing</h3>
            <p>{{ $trainingStats['ongoing'] }}</p>
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
            <div class="content-box">
                <h2>User Directory</h2>
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
                                <th scope="col">Employment Status</th>
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
                                <td>{{ $user->office ? $user->office->code : 'N/A' }}</td>
                                <td>{{ $user->staffDetail ? $user->staffDetail->position : 'N/A' }}</td>
                                <td>{{ $user->staffDetail ? $user->staffDetail->program : 'N/A' }}</td>
                                <td>{{ $user->staffDetail ? $user->staffDetail->job_function : 'N/A' }}</td>
                                <td>{{ $user->staffDetail ? ucfirst(str_replace('_', ' ', $user->staffDetail->employment_status)) : 'N/A' }}</td>
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

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Trainings Modal -->
<div class="modal fade" id="trainingsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #1a237e; color: white;">
        <h5 class="modal-title">Staff Trainings</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4 id="trainingsModalTitle" style="color: #1a237e;">Training Records</h4>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead style="background-color: #1a237e; color: white;">
              <tr>
                <th>Training Title</th>
                <th>Description</th>
                <th>Dates</th>
                <th>Venue</th>
                <th>Nature</th>
                <th>Scope</th>
                <th>Status</th>
                <th>Proof</th>
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

<!-- Proof Preview Modal -->
<div class="modal fade" id="proofPreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #1a237e; color: white;">
        <h5 class="modal-title">Proof Preview</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center p-0" id="proofPreviewContent">
        <div class="d-flex justify-content-center align-items-center" style="min-height: 500px;">
          <div>
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading proof...</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a href="#" id="downloadProofLink" class="btn btn-primary" download>Download</a>
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
const applyFiltersBtn = document.getElementById('applyFilters');
if (applyFiltersBtn) {
  applyFiltersBtn.addEventListener('click', function(){
    try {
      const officeFilter = document.getElementById('filter-office');
      const roleFilter = document.getElementById('filter-role');
      const periodFilter = document.getElementById('filter-period');
      
      if (!officeFilter || !roleFilter || !periodFilter) {
        console.error('Filter elements not found');
        return;
      }
      
      const office = encodeURIComponent(officeFilter.value || 'all');
      const role = encodeURIComponent(roleFilter.value || 'all');
      const period = encodeURIComponent(periodFilter.value || 'all');
      
      // Redirect to same page with query parameters so results come from SQL
      const qs = `?office=${office}&role=${role}&period=${period}`;
      window.location.href = window.location.pathname + qs;
    } catch (error) {
      console.error('Error applying filters:', error);
      alert('Error applying filters. Please try again.');
    }
  });
} else {
  console.warn('Apply filters button not found');
}

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
        employment_status: "{{ $user->staffDetail ? ucfirst(str_replace('_', ' ', $user->staffDetail->employment_status)) : '' }}",
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
            nature: "{{ $training->nature_of_training }}",
            scope: "{{ $training->scope }}",
            proof_document: "{{ $training->proof ? addslashes($training->proof->file_path) : '' }}",
            proof_id: {{ $training->proof ? $training->proof->id : 'null' }},
            proof_status: "{{ $training->proof ? $training->proof->status : '' }}"
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
            // Add proof preview if available
            const proofPreview = t.proof_document ? 
                `<button class="btn btn-sm btn-info" onclick="previewProof('/storage/${t.proof_document}', ${t.proof_id}, '${t.proof_status}')">Preview</button>` : 
                '<span class="text-muted">No proof</span>';
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
                <td>${proofPreview}</td>
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
// Preview proof in modal with approval functionality
function previewProof(filePath, proofId, proofStatus) {
    // Decode the file path in case it's URL encoded
    const decodedPath = decodeURIComponent(filePath);
    
    const modal = document.getElementById('proofPreviewModal');
    const content = document.getElementById('proofPreviewContent');
    const downloadLink = document.getElementById('downloadProofLink');
    
    // Set download link
    downloadLink.href = decodedPath;
    
    // Show loading state
    content.innerHTML = `
        <div class="d-flex justify-content-center align-items-center" style="min-height: 500px;">
            <div>
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading proof...</p>
            </div>
        </div>
    `;
    
    // Show modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    // Load content based on file type
    const fileExtension = decodedPath.split('.').pop().toLowerCase();
    
    // Add a fallback mechanism for file loading errors
    setTimeout(() => {
        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
            // Image file
            const img = document.createElement('img');
            img.src = decodedPath;
            img.className = 'img-fluid';
            img.style.cssText = 'max-height: 80vh; max-width: 100%; object-fit: contain;';
            img.alt = 'Proof Document';
            
            // Handle image load error
            img.onerror = function() {
                content.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 500px;">
                        <div>
                            <i class="fas fa-exclamation-triangle fa-5x mb-3 text-warning"></i>
                            <h5>File Not Found</h5>
                            <p class="text-muted">The requested file could not be found or accessed.</p>
                            <p class="small text-muted">Path: ${decodedPath}</p>
                            <a href="${decodedPath}" class="btn btn-primary" download>Try Direct Download</a>
                        </div>
                    </div>
                `;
            };
            
            // Handle successful image load
            img.onload = function() {
                displayProofContent(decodedPath, proofId, proofStatus);
            };
            
            // Initially show the image (will trigger onload or onerror)
            content.innerHTML = `
                <div class="d-flex justify-content-center align-items-center" style="min-height: 500px;">
                    <img src="${decodedPath}" class="img-fluid" style="max-height: 80vh; max-width: 100%; object-fit: contain;" alt="Proof Document">
                </div>
            `;
        } else if (fileExtension === 'pdf') {
            // PDF file
            displayProofContent(decodedPath, proofId, proofStatus);
        } else {
            // Other file types - provide download link
            displayProofContent(decodedPath, proofId, proofStatus);
        }
    }, 500); // Small delay to show loading state
}

// Display proof content with approval functionality
function displayProofContent(filePath, proofId, proofStatus) {
    const content = document.getElementById('proofPreviewContent');
    const fileExtension = filePath.split('.').pop().toLowerCase();
    
    // Check if proof is already approved/rejected
    let approvalSection = '';
    if (proofStatus === 'pending') {
        approvalSection = `
            <div class="approval-section mt-3 p-3 border-top">
                <h5>Review & Approval</h5>
                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks (Optional)</label>
                    <textarea class="form-control" id="remarks" rows="2" placeholder="Add any remarks..."></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success" onclick="processReview(${proofId}, 'approve')">
                        <i class="fas fa-check-circle me-1"></i> Approve
                    </button>
                    <button class="btn btn-danger" onclick="processReview(${proofId}, 'reject')">
                        <i class="fas fa-times-circle me-1"></i> Reject
                    </button>
                </div>
            </div>
        `;
    } else if (proofStatus === 'approved') {
        approvalSection = `
            <div class="alert alert-success mt-3">
                <i class="fas fa-check-circle me-1"></i> This proof has been <strong>approved</strong>.
            </div>
        `;
    } else if (proofStatus === 'rejected') {
        approvalSection = `
            <div class="alert alert-danger mt-3">
                <i class="fas fa-times-circle me-1"></i> This proof has been <strong>rejected</strong>.
            </div>
        `;
    }
    
    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
        // Image file
        content.innerHTML = `
            <div class="d-flex justify-content-center align-items-center" style="min-height: 500px;">
                <img src="${filePath}" class="img-fluid" style="max-height: 60vh; max-width: 100%; object-fit: contain;" alt="Proof Document">
            </div>
            ${approvalSection}
        `;
    } else if (fileExtension === 'pdf') {
        // PDF file
        content.innerHTML = `
            <div style="height: 60vh;">
                <iframe src="${filePath}" class="w-100 h-100" frameborder="0"></iframe>
            </div>
            ${approvalSection}
        `;
    } else {
        // Other file types
        content.innerHTML = `
            <div class="d-flex justify-content-center align-items-center" style="min-height: 500px;">
                <div>
                    <i class="fas fa-file fa-5x mb-3 text-muted"></i>
                    <h5>File Preview Not Available</h5>
                    <p class="text-muted">This file type cannot be previewed directly.</p>
                    <a href="${filePath}" class="btn btn-primary" download>Download File</a>
                </div>
            </div>
            ${approvalSection}
        `;
    }
}

// Process review (approve/reject)
function processReview(proofId, action) {
    const remarks = document.getElementById('remarks') ? document.getElementById('remarks').value : '';
    
    // Confirm action
    const actionText = action === 'approve' ? 'approve' : 'reject';
    if (!confirm(`Are you sure you want to ${actionText} this training proof?`)) {
        return;
    }
    
    // Send request to server
    fetch(`/training_proofs/${proofId}/process-review`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            action: action,
            remarks: remarks
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(`Training proof ${actionText}d successfully.`);
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('proofPreviewModal'));
            if (modal) modal.hide();
            // Reload page to reflect changes
            location.reload();
        } else {
            alert('Error processing review: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error processing review. Please try again.');
    });
}



</script>
</body>
</html>
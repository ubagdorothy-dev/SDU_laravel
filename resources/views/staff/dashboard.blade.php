<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/staff/dashboard.css') }}">
</head>
<body id="body">
<input type="checkbox" id="sidebar-toggle-checkbox" style="display: none;">

    <div class="sidebar">
        <div class="sidebar-header d-flex justify-content-between align-items-center px-3 mb-3">
            <div class="d-flex align-items-center">
                <img src="{{ asset('SDU_Logo.png') }}" alt="SDU Logo" class="sidebar-logo">
                <h5 class="m-0 text-white"><span class="logo-text">SDU STAFF</span></h5>
            </div>
            <label for="sidebar-toggle-checkbox" id="sidebar-toggle" class="btn btn-toggle"><i class="fas fa-bars"></i></label>
        </div>
        <div class="sidebar-content d-flex flex-column flex-grow-1">
            <ul class="nav flex-column flex-grow-1">
                <li class="nav-item">
                    <a class="nav-link {{ !request()->has('view') || request()->get('view') === 'overview' ? 'active' : '' }}" href="{{ route('staff.dashboard') }}?view=overview">
                        <i class="fas fa-chart-line me-2"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (request()->get('view') === 'training-records' || request()->get('view') === 'assigned-trainings' || request()->get('view') === 'uploaded-files') ? 'active' : '' }}" href="#trainingSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ (request()->get('view') === 'training-records' || request()->get('view') === 'assigned-trainings' || request()->get('view') === 'uploaded-files') ? 'true' : 'false' }}" aria-controls="trainingSubmenu">
                        <i class="fas fa-book-open me-2"></i> <span>Training</span>
                    </a>
                    <div class="collapse {{ (request()->get('view') === 'training-records' || request()->get('view') === 'assigned-trainings' || request()->get('view') === 'uploaded-files') ? 'show' : '' }}" id="trainingSubmenu">
                        <ul class="nav flex-column ms-4">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->get('view') === 'training-records' ? 'active' : '' }}" href="{{ route('staff.dashboard') }}?view=training-records">
                                    <i class="fas fa-list me-2"></i> <span>My Records</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->get('view') === 'assigned-trainings' ? 'active' : '' }}" href="{{ route('staff.dashboard') }}?view=assigned-trainings">
                                    <i class="fas fa-tasks me-2"></i> <span>Assigned Trainings</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->get('view') === 'uploaded-files' ? 'active' : '' }}" href="{{ route('staff.dashboard') }}?view=uploaded-files">
                                    <i class="fas fa-file-upload me-2"></i> <span>Uploaded Files</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="fas fa-user-circle me-2"></i> <span>Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        @if (!request()->has('view') || request()->get('view') === 'overview')
            <div class="header mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h1 class="text-dark fw-bold mb-2">Welcome, {{ $staff_username }}!</h1>
                        @if (!empty($office_display))
                            <p class="mb-1 text-muted small">{{ $office_display }}</p>
                        @endif
                    </div>
                    <div class="d-flex gap-2 flex-wrap">                       
                        <button class="btn btn-primary position-relative" data-bs-toggle="modal" data-bs-target="#notificationsModal">
                            <i class="fas fa-bell me-2"></i> Notifications
                            <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;"></span>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="stats-cards">
                <div class="card">
                    <h3>Trainings Completed</h3>
                    <p>{{ $trainings_completed }}</p>
                </div>
                <div class="card">
                    <h3>Ongoing Trainings</h3>
                    <p>{{ $trainings_ongoing }}</p>
                </div>
                <div class="card">
                    <h3>Upcoming Trainings</h3>
                    <p>{{ $trainings_upcoming }}</p>
                </div>
            </div>

            <div class="quick-actions">
                <div class="action-card" data-bs-toggle="modal" data-bs-target="#addTrainingModal" style="cursor:pointer;">
                    <i class="fas fa-plus-circle"></i>
                    <h4>Add Training</h4>
                    <p>Record a new training</p>
                </div>
                <div class="action-card" data-bs-toggle="modal" data-bs-target="#profileModal" style="cursor:pointer;">
                    <i class="fas fa-user"></i>
                    <h4>View Profile</h4>
                    <p>Manage your information</p>
                </div>
            </div>
            
            @if (count($ongoing_rows) > 0)
            <div class="content-box mt-4">
                <h2>Ongoing Trainings</h2>
                <div class="list-group">
                    @foreach($ongoing_rows as $ongoing)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $ongoing->title }}</h6>
                                <small class="text-muted">In progress: {{ date('M d, Y', strtotime($ongoing->start_date)) }} — {{ date('M d, Y', strtotime($ongoing->end_date)) }}</small>
                                @if (!empty($ongoing->nature) || !empty($ongoing->scope))
                                    <small class="d-block mt-1">
                                        @if (!empty($ongoing->nature))
                                            <span class="badge bg-info me-1">{{ $ongoing->nature }}</span>
                                        @endif
                                        @if (!empty($ongoing->scope))
                                            <span class="badge bg-secondary">{{ $ongoing->scope }}</span>
                                        @endif
                                    </small>
                                @endif
                            </div>
                            <span class="badge bg-primary rounded-pill">Ongoing</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (count($upcoming_rows) > 0)
            <div class="content-box mt-4">
                <h2>Upcoming Trainings</h2>
                <div class="list-group">
                    @foreach($upcoming_rows as $upcoming)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $upcoming->title }}</h6>
                                <small class="text-muted">Scheduled for {{ date('M d, Y', strtotime($upcoming->start_date)) }} — {{ date('M d, Y', strtotime($upcoming->end_date)) }}</small>
                                @if (!empty($upcoming->nature) || !empty($upcoming->scope))
                                    <small class="d-block mt-1">
                                        @if (!empty($upcoming->nature))
                                            <span class="badge bg-info me-1">{{ $upcoming->nature }}</span>
                                        @endif
                                        @if (!empty($upcoming->scope))
                                            <span class="badge bg-secondary">{{ $upcoming->scope }}</span>
                                        @endif
                                    </small>
                                @endif
                            </div>
                            <span class="badge bg-warning rounded-pill">Upcoming</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="content-box mt-4">
                <h2>Recent Activity</h2>
                @if (count($activity_rows) > 0)
                    <div class="list-group">
                        @foreach($activity_rows as $activity)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $activity->title }}</h6>
                                    <small class="text-muted">
                                        @if ($activity->status === 'completed')
                                            Completed on {{ date('M d, Y', strtotime($activity->end_date)) }}
                                        @else
                                            Added on {{ date('M d, Y', strtotime($activity->created_at)) }} - Scheduled for {{ date('M d, Y', strtotime($activity->start_date)) }} — {{ date('M d, Y', strtotime($activity->end_date)) }}
                                        @endif
                                    </small>
                                    @if (!empty($activity->nature) || !empty($activity->scope))
                                        <small class="d-block mt-1">
                                            @if (!empty($activity->nature))
                                                <span class="badge bg-info me-1">{{ $activity->nature }}</span>
                                            @endif
                                            @if (!empty($activity->scope))
                                                <span class="badge bg-secondary">{{ $activity->scope }}</span>
                                            @endif
                                        </small>
                                    @endif
                                </div>
                                <span class="badge {{ $activity->status === 'completed' ? 'bg-success' : 'bg-warning' }} rounded-pill">
                                    {{ ucfirst($activity->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Training Activities Yet</h5>
                        <p class="text-muted">Start by adding your first training record!</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainingModal">
                            <i class="fas fa-plus-circle me-1"></i> Add Your First Training
                        </button>
                    </div>
                @endif
            </div>
        @elseif (request()->get('view') === 'training-records')
            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>My Training Records</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainingModal">
                        <i class="fas fa-plus-circle me-1"></i> Add Training
                    </button>
                </div>
                
                @if (count($record_rows) > 0)
                    <table class="table table-striped mt-4">
                        <thead>
                            <tr>
                                <th scope="col">Training Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Venue</th>
                                <th scope="col">Nature of Training</th>
                                <th scope="col">Scope</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($record_rows as $row)
                                <tr>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->description }}</td>
                                    <td>{{ $row->start_date }}</td>
                                    <td>{{ $row->end_date }}</td>
                                    <td>{{ $row->venue ?? '' }}</td>
                                    <td>{{ $row->nature_of_training ?? '' }}</td>
                                    <td>{{ $row->scope ?? '' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($row->status == 'completed') bg-success
                                            @elseif($row->status == 'upcoming') bg-warning text-dark
                                            @elseif($row->status == 'ongoing') bg-primary
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($row->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editTrainingModal"
                                                data-training-id="{{ $row->id }}"
                                                data-title="{{ $row->title }}"
                                                data-description="{{ $row->description }}"
                                                data-start-date="{{ $row->start_date }}"
                                                data-end-date="{{ $row->end_date }}"
                                                data-venue="{{ $row->venue ?? '' }}"
                                                data-nature="{{ $row->nature_of_training ?? '' }}"
                                                data-scope="{{ $row->scope ?? '' }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-training-id="{{ $row->id }}">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                            @if ($row->status === 'completed' && empty($row->proof_uploaded))
                                                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadProofModal" data-training-id="{{ $row->id }}"> 
                                                    <i class="fas fa-upload"></i> Upload Proof
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info mt-4" role="alert">
                        You have not completed any trainings yet.
                    </div>
                @endif
            </div>
        @elseif (request()->get('view') === 'assigned-trainings')
            <div class="content-box">
                <h2>My Assigned Trainings</h2>
                <p class="text-muted">Trainings assigned to you by your Unit Director.</p>
                
                @php
                    // Get assigned trainings for the current user
                    $assignedTrainings = \App\Models\TrainingAssignment::with(['training', 'assignedBy'])
                        ->where('staff_id', Auth::user()->user_id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                @endphp
                
                @if($assignedTrainings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped mt-4">
                            <thead>
                                <tr>
                                    <th>Training</th>
                                    <th>Assigned By</th>
                                    <th>Assigned Date</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignedTrainings as $assignment)
                                    <tr>
                                        <td>{{ $assignment->training->title }}</td>
                                        <td>{{ $assignment->assignedBy->full_name ?? 'N/A' }}</td>
                                        <td>{{ $assignment->assigned_date->format('M d, Y') }}</td>
                                        <td>{{ $assignment->deadline->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($assignment->status == 'completed') bg-success
                                                @elseif($assignment->status == 'pending') bg-warning text-dark
                                                @elseif($assignment->status == 'overdue') bg-primary
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst($assignment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                        <h5>No Assigned Trainings</h5>
                        <p class="text-muted">You don't have any trainings assigned to you right now.</p>
                    </div>
                @endif
            </div>
        @elseif (request()->get('view') === 'uploaded-files')
            <div class="content-box">
                <h2>My Uploaded Files</h2>
                <p class="text-muted">Files you have uploaded as proof of completed trainings.</p>
                
                @php
                    // Get uploaded files for the current user
                    $uploadedFiles = \App\Models\TrainingProof::with(['trainingRecord'])
                        ->where('user_id', Auth::user()->user_id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                @endphp
                
                @if($uploadedFiles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped mt-4">
                            <thead>
                                <tr>
                                    <th>Training</th>
                                    <th>File Name</th>
                                    <th>Upload Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($uploadedFiles as $file)
                                    <tr>
                                        <td>{{ $file->trainingRecord->title }}</td>
                                        <td>{{ basename($file->file_path) }}</td>
                                        <td>{{ $file->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($file->status == 'approved') bg-success
                                                @elseif($file->status == 'pending') bg-warning text-dark
                                                @elseif($file->status == 'rejected') bg-primary
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst($file->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('training_proofs.view', $file->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                            <a href="{{ route('training_proofs.view', $file->id) }}?download=1" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-download me-1"></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                        <h5>No Uploaded Files</h5>
                        <p class="text-muted">You haven't uploaded any files as proof of completed trainings yet.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

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
                    console.log('sidebar-toggle-checkbox change, checked=', sidebarCheckbox.checked);
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

            // Setup for edit training form
            var editTrainingModal = document.getElementById('editTrainingModal');
            if (editTrainingModal) {
                editTrainingModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    if (!button) return;
                    var form = document.getElementById('editTrainingForm');
                    form.elements['id'].value = button.getAttribute('data-training-id');
                    form.elements['title'].value = button.getAttribute('data-title');
                    // handle start_date / end_date fields
                    if (form.elements['start_date']) form.elements['start_date'].value = button.getAttribute('data-start-date') || '';
                    if (form.elements['end_date']) form.elements['end_date'].value = button.getAttribute('data-end-date') || '';
                    // Populate description
                    if (form.elements['description']) {
                        form.elements['description'].value = button.getAttribute('data-description') || '';
                    }

                    if (form.elements['venue']) form.elements['venue'].value = button.getAttribute('data-venue') || '';
                    if (form.elements['nature']) form.elements['nature'].value = button.getAttribute('data-nature') || '';
                    if (form.elements['scope']) form.elements['scope'].value = button.getAttribute('data-scope') || '';
                });
            }

            // Handle URL parameters to auto-open modals
            const urlParams = new URLSearchParams(window.location.search);
            const action = urlParams.get('action');
            const trainingId = urlParams.get('id');
            const view = urlParams.get('view');
            
            if (action === 'create') {
                // Auto-open add training modal
                const addModal = new bootstrap.Modal(document.getElementById('addTrainingModal'));
                addModal.show();
            } else if (action === 'edit' && trainingId) {
                // Auto-open edit training modal with training data
                fetch(`/training_records/${trainingId}/edit-ajax`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const editModalElement = document.getElementById('editTrainingModal');
                            const form = document.getElementById('editTrainingForm');
                            
                            // Populate form fields
                            form.elements['id'].value = data.training_record.id;
                            form.elements['title'].value = data.training_record.title;
                            if (form.elements['description']) {
                                form.elements['description'].value = data.training_record.description || '';
                            }
                            if (form.elements['start_date']) {
                                form.elements['start_date'].value = data.training_record.start_date || '';
                            }
                            if (form.elements['end_date']) {
                                form.elements['end_date'].value = data.training_record.end_date || '';
                            }
                            if (form.elements['venue']) {
                                form.elements['venue'].value = data.training_record.venue || '';
                            }
                            if (form.elements['nature_of_training']) {
                                form.elements['nature_of_training'].value = data.training_record.nature_of_training || '';
                            }
                            if (form.elements['scope']) {
                                form.elements['scope'].value = data.training_record.scope || '';
                            }
                            
                            // Show the modal
                            const editModal = new bootstrap.Modal(editModalElement);
                            editModal.show();
                        }
                    })
                    .catch(error => console.error('Error fetching training record:', error));
            }

            // Setup for add training form
            var addForm = document.getElementById('addTrainingForm');
            if (addForm) {
                addForm.addEventListener('submit', function(e){
                    e.preventDefault();
                    
                    // Log form data for debugging
                    var fd = new FormData(addForm);
                    console.log('Form data being sent:');
                    for (var pair of fd.entries()) {
                        console.log(pair[0] + ': ' + pair[1]);
                    }
                    
                    // Add CSRF token to form data
                    var csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        console.log('CSRF Token found:', csrfToken.getAttribute('content'));
                        fd.append('_token', csrfToken.getAttribute('content'));
                    } else {
                        console.error('CSRF Token not found!');
                    }
                    
                    fetch('/training_records', { 
                        method: 'POST', 
                        body: fd, 
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(function(r){ 
                            // Check if response is OK
                            if (!r.ok) {
                                throw new Error('HTTP error! status: ' + r.status);
                            }
                            
                            // Log the raw response text for debugging
                            return r.clone().text().then(function(text) {
                                console.log('Raw response text (add):', text);
                                return r.json().catch(function(e){ 
                                    console.error('JSON parse error:', e);
                                    return { 
                                        success: false, 
                                        error: 'Invalid JSON response from server', 
                                        rawStatus: r.status,
                                        rawText: text
                                    }; 
                                });
                            }); 
                        })
                        .then(function(data){
                            var fb = document.getElementById('addTrainingFeedback');
                            console.log('training_records.store response:', data);
                            if (data.success) {
                                fb.innerHTML = '<div class="alert alert-success">Training added!</div>';
                                setTimeout(function(){ window.location.reload(); }, 600);
                            } else {
                                var msg = data.error || 'Failed';
                                
                                // Handle validation errors
                                if (data.errors) {
                                    msg = 'Validation failed:<br>';
                                    for (var field in data.errors) {
                                        msg += '<strong>' + field + '</strong>: ' + data.errors[field].join(', ') + '<br>';
                                    }
                                } else if (data.rawText) {
                                    msg += ' (raw response: ' + data.rawText + ')';
                                } else if (data.raw) {
                                    msg += ' (raw:' + JSON.stringify(data.raw) + ')';
                                }
                                
                                fb.innerHTML = '<div class="alert alert-danger">' + msg + '</div>';
                            }
                        })
                        .catch(function(err){
                            var fb = document.getElementById('addTrainingFeedback');
                            console.error('Fetch error on create:', err);
                            fb.innerHTML = '<div class="alert alert-danger">Request failed: ' + (err && err.message ? err.message : 'network error') + '</div>';
                        });
                });
            }
            
            // Handle delete confirmation modal
            var deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget; // Button that triggered the modal
                    var trainingId = button.getAttribute('data-training-id');
                    var modal = this;
                    modal.querySelector('#deleteTrainingId').value = trainingId;
                });

                document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                    var trainingId = document.getElementById('deleteTrainingId').value;
                    
                    // Show loading state on delete button
                    var deleteBtn = this;
                    var originalText = deleteBtn.innerHTML;
                    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
                    deleteBtn.disabled = true;
                    
                    // Debugging: log the training ID and CSRF token
                    console.log('Deleting training record with ID:', trainingId);
                    console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                                    
                    // Make AJAX request to delete the training record
                    fetch('/training_records/' + trainingId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        console.log('Delete response status:', response.status);
                        console.log('Delete response ok:', response.ok);
                                        
                        if (response.ok) {
                            // Close delete confirmation modal
                            var modal = bootstrap.Modal.getInstance(deleteModal);
                            if (modal) {
                                modal.hide();
                            }
                            // Show success message in modal
                            var successModal = new bootstrap.Modal(document.getElementById('successMessageModal'));
                            document.getElementById('successMessageText').textContent = 'Training record deleted successfully!';
                            successModal.show();
                            
                            // Reload page after a short delay
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            return response.json().then(data => {
                                console.log('Delete error data:', data);
                                throw new Error(data.message || 'Delete failed with status: ' + response.status);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Close delete confirmation modal
                        var modal = bootstrap.Modal.getInstance(deleteModal);
                        if (modal) {
                            modal.hide();
                        }
                        // Show error message in modal
                        var successModal = new bootstrap.Modal(document.getElementById('successMessageModal'));
                        document.getElementById('successMessageText').textContent = 'Failed to delete training record: ' + error.message;
                        successModal.show();
                    })
                    .finally(() => {
                        // Reset delete button state
                        deleteBtn.innerHTML = originalText;
                        deleteBtn.disabled = false;
                    });
                });
            }
            
            // Handle delete buttons in dynamically loaded content
            document.addEventListener('click', function(event) {
                if (event.target.matches('[data-bs-target="#deleteConfirmationModal"]')) {
                    var button = event.target;
                    var trainingId = button.getAttribute('data-training-id');
                    
                    // If we can't get the training ID from the button itself, try the closest button
                    if (!trainingId && button.tagName !== 'BUTTON') {
                        var parentButton = button.closest('button');
                        if (parentButton) {
                            trainingId = parentButton.getAttribute('data-training-id');
                        }
                    }
                    
                    // Set the training ID in the modal
                    if (trainingId && deleteModal) {
                        deleteModal.querySelector('#deleteTrainingId').value = trainingId;
                    }
                }
            });

            // Setup for edit training form
            var editForm = document.getElementById('editTrainingForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e){
                    e.preventDefault();
                    
                    // Get the training ID from the form
                    var trainingId = editForm.elements['id'].value;
                    console.log('Submitting edit form for training ID:', trainingId);
                    
                    // Validate that we have a training ID
                    if (!trainingId) {
                        console.error('No training ID found in form');
                        var fb = document.getElementById('editTrainingFeedback');
                        fb.innerHTML = '<div class="alert alert-danger">Error: No training ID found. Please try again.</div>';
                        return;
                    }
                    
                    // Log form data for debugging
                    var fd = new FormData(editForm);
                    console.log('Edit form data being sent:');
                    for (var pair of fd.entries()) {
                        console.log(pair[0] + ': ' + pair[1]);
                    }
                    
                    // Also log all form elements
                    console.log('All form elements:');
                    for (var i = 0; i < editForm.elements.length; i++) {
                        var element = editForm.elements[i];
                        if (element.name) {
                            console.log(element.name + ': ' + element.value);
                        }
                    }
                    
                    // Add CSRF token and method override to form data
                    var csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        console.log('CSRF Token found:', csrfToken.getAttribute('content'));
                        fd.append('_token', csrfToken.getAttribute('content'));
                    } else {
                        console.error('CSRF Token not found!');
                    }
                    fd.append('_method', 'PUT');
                    
                    var url = '/training_records/' + trainingId;
                    console.log('Sending request to:', url);
                    
                    // Proceed with the actual request
                    fetch(url, { 
                        method: 'POST', 
                        body: fd, 
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(function(r){ 
                            console.log('Response received:', r.status, r.statusText);
                            // Check if response is OK
                            if (!r.ok) {
                                throw new Error('HTTP error! status: ' + r.status);
                            }
                            
                            // Log the raw response text for debugging
                            return r.clone().text().then(function(text) {
                                console.log('Raw response text:', text);
                                return r.json().catch(function(e){ 
                                    console.error('JSON parse error:', e);
                                    return { 
                                        success: false, 
                                        error: 'Invalid JSON response from server', 
                                        rawStatus: r.status,
                                        rawText: text
                                    }; 
                                });
                            }); 
                        })
                        .then(function(data){
                            var fb = document.getElementById('editTrainingFeedback');
                            console.log('training_records.update response:', data);
                            if (data.success) {
                                fb.innerHTML = '<div class="alert alert-success">Training updated!</div>';
                                setTimeout(function(){ window.location.reload(); }, 600);
                            } else {
                                var msg = data.error || 'Failed';
                                
                                // Handle validation errors
                                if (data.errors) {
                                    msg = 'Validation failed:<br>';
                                    for (var field in data.errors) {
                                        msg += '<strong>' + field + '</strong>: ' + data.errors[field].join(', ') + '<br>';
                                    }
                                } else if (data.rawText) {
                                    msg += ' (raw response: ' + data.rawText + ')';
                                } else if (data.raw) {
                                    msg += ' (raw:' + JSON.stringify(data.raw) + ')';
                                }
                                
                                fb.innerHTML = '<div class="alert alert-danger">' + msg + '</div>';
                            }
                        })
                        .catch(function(err){
                            var fb = document.getElementById('editTrainingFeedback');
                            console.error('Fetch error on update:', err);
                            fb.innerHTML = '<div class="alert alert-danger">Request failed: ' + (err && err.message ? err.message : 'network error') + '</div>';
                        });
                });
            }

            // Setup for upload proof form
            var uploadForm = document.getElementById('uploadProofForm');
            if (uploadForm) {
                uploadForm.addEventListener('submit', function(e){
                    e.preventDefault();
                    
                    // Get the training ID from the form
                    var trainingId = uploadForm.elements['training_id'].value;
                    
                    var fd = new FormData(uploadForm);
                    // Add CSRF token to form data
                    var csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        console.log('CSRF Token found:', csrfToken.getAttribute('content'));
                        fd.append('_token', csrfToken.getAttribute('content'));
                    } else {
                        console.error('CSRF Token not found!');
                    }
                    // Rename 'proof' to 'proof_file' to match the controller expectation
                    if (fd.has('proof')) {
                        var proofFile = fd.get('proof');
                        fd.delete('proof');
                        fd.append('proof_file', proofFile);
                    }
                    
                    fetch('/training_records/' + trainingId + '/upload-proof', { 
                        method: 'POST', 
                        body: fd, 
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(function(r){ 
                            // Check if response is OK
                            if (!r.ok) {
                                throw new Error('HTTP error! status: ' + r.status);
                            }
                            
                            // Log the raw response text for debugging
                            return r.clone().text().then(function(text) {
                                console.log('Raw response text (upload):', text);
                                return r.json().catch(function(e){ 
                                    console.error('JSON parse error:', e);
                                    return { 
                                        success: false, 
                                        error: 'Invalid JSON response from server', 
                                        rawStatus: r.status,
                                        rawText: text
                                    }; 
                                });
                            }); 
                        })
                        .then(function(data){
                            var fb = document.getElementById('uploadProofFeedback');
                            console.log('training_proofs.upload response:', data);
                            if (data.success) {
                                fb.innerHTML = '<div class="alert alert-success">Proof uploaded and sent for review.</div>';
                                setTimeout(function(){ window.location.reload(); }, 900);
                            } else {
                                var msg = data.error || 'Failed';
                                
                                // Handle validation errors
                                if (data.errors) {
                                    msg = 'Validation failed:<br>';
                                    for (var field in data.errors) {
                                        msg += '<strong>' + field + '</strong>: ' + data.errors[field].join(', ') + '<br>';
                                    }
                                } else if (data.rawText) {
                                    msg += ' (raw response: ' + data.rawText + ')';
                                } else if (data.raw) {
                                    msg += ' (raw:' + JSON.stringify(data.raw) + ')';
                                }
                                
                                fb.innerHTML = '<div class="alert alert-danger">' + msg + '</div>';
                            }
                        })
                        .catch(function(err){
                            var fb = document.getElementById('uploadProofFeedback');
                            console.error('Fetch error on upload:', err);
                            fb.innerHTML = '<div class="alert alert-danger">Request failed: ' + (err && err.message ? err.message : 'network error') + '</div>';
                        });
                });
            }
            
            // Setup for upload proof modal to populate training ID
            var uploadProofModal = document.getElementById('uploadProofModal');
            if (uploadProofModal) {
                uploadProofModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget; // Button that triggered the modal
                    var trainingId = button.getAttribute('data-training-id');
                    var modal = this;
                    // Set the training ID in the hidden input field
                    modal.querySelector('input[name="training_id"]').value = trainingId;
                });
            }
        });

        // Delete functionality now handled by modal
    </script>

    @include('staff.partials.modals')

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this training record? This action cannot be undone.</p>
                    <input type="hidden" id="deleteTrainingId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    @include('staff.partials.profile_notification_modals')

    @include('staff.partials.modal_scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Success Message Modal -->
    <div class="modal fade" id="successMessageModal" tabindex="-1" aria-labelledby="successMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successMessageModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="successMessageText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
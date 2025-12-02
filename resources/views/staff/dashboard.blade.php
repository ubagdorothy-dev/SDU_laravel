<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            display: flex;
            background: white;
            background-attachment: fixed;
        }
        
        @media (min-width: 992px) {
            body.toggled .sidebar { width: 80px; }
            body.toggled .main-content { margin-left: 80px; }
            .sidebar .nav-link { transition: all 0.2s; white-space: nowrap; overflow: hidden; }
            body.toggled .sidebar .nav-link { text-align: center; padding: 12px 0; }
            body.toggled .sidebar .nav-link i { margin-right: 0; }
            body.toggled .sidebar .nav-link span { display: none; }
            body.toggled .sidebar h3 { display: none; }
        }
        
        .main-content {
            flex-grow: 1;
            padding: 2rem;
            margin-left: 250px; 
            transition: margin-left 0.3s ease-in-out;
        }
        
        .sidebar {
            width: 250px;
            background-color: #1a237e;
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 2rem;
            transition: width 0.3s ease-in-out;
        }
        
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            margin: 5px 15px;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 0.75rem;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #3f51b5;
        }
        
        .content-box { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(10px);
            border-radius: 20px; 
            box-shadow: 0 8px 32px rgba(0,0,0,0.1); 
            padding: 2rem; 
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .content-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        
        /* Transparent sidebar toggle like admin */
        .sidebar .btn-toggle {
            background-color: transparent;
            border: none;
            color: #ffffff;
            padding: 6px 10px;
            cursor: pointer;
        }
        
        .sidebar .btn-toggle:focus { box-shadow: none; }
        .sidebar .btn-toggle:hover { background-color: transparent; }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            padding: 2rem 1.5rem;
            text-align: center;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        
        .card h3 {
            margin: 0 0 1rem;
            color: var(--card-color);
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .card p {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--card-color);
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stats-cards .card:nth-child(1) { 
            --card-color: #10b981;
            --card-color-light: #6ee7b7;
        }
        
        .stats-cards .card:nth-child(2) { 
            --card-color: #f59e0b;
            --card-color-light: #fbbf24;
        }
        
        .stats-cards .card:nth-child(3) { 
            --card-color: #31d0e6;
            --card-color-light: #16c6e6;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .action-card {
            background: #1a237e;
            color: white;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
        }
        
        .action-card a {
            color: white;
            text-decoration: none;
            display: block;
        }
        
        .action-card i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
                margin-left: 0 !important;
            }
            
            .stats-cards {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .card {
                padding: 1.5rem 1rem;
            }
            
            .card p {
                font-size: 2rem;
            }
            
            .content-box {
                padding: 1.5rem;
                border-radius: 16px;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            .header p {
                font-size: 0.9rem;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .main-content {
                padding: 0.5rem;
            }
            
            .card {
                padding: 1rem;
            }
            
            .content-box {
                padding: 1rem;
            }
            
            .header h1 {
                font-size: 1.25rem;
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .btn-group {
                flex-direction: column;
                gap: 0.25rem;
            }
        }
        
        /* Checkbox-controlled sidebar toggle (mirror admin_dashboard behavior) */
        #sidebar-toggle-checkbox:checked ~ .sidebar { width: 80px; padding-top: 1rem; }
        #sidebar-toggle-checkbox:checked ~ .sidebar .nav-link span,
        #sidebar-toggle-checkbox:checked ~ .sidebar h3,
        #sidebar-toggle-checkbox:checked ~ .sidebar .logo-text { display: none; }
        #sidebar-toggle-checkbox:checked ~ .main-content { margin-left: 80px; }
        #sidebar-toggle-checkbox:checked ~ .sidebar .nav-link { text-align: center; padding: 12px 0; }
        #sidebar-toggle-checkbox:checked ~ .sidebar .d-flex.justify-content-between { padding-left: 0.25rem !important; padding-right: 0.25rem !important; margin-bottom: 1rem !important; }
        
        /* Keep existing JS-driven toggle (body.toggled) compatible with checkbox approach */
        @media (min-width: 992px) {
            #sidebar-toggle-checkbox:checked ~ .sidebar .nav-link { justify-content: center; }
        }
        
        /* Logo in sidebar */
        .sidebar-logo { height: 30px; width: auto; margin-right: 8px; }
        .sidebar .d-flex h5 { font-weight: 700; margin-right: 0 !important; }
        
        /* Disable sidebar/main transitions so expand/collapse is instant (match other dashboards) */
        .sidebar,
        .main-content,
        .sidebar .nav-link,
        .sidebar-logo,
        .logo-text {
            transition: none !important;
            -webkit-transition: none !important;
        }
        
        @media (min-width: 992px) {
            body.toggled .sidebar { transition: none !important; }
            body.toggled .main-content { transition: none !important; }
        }
    </style>
</head>
<body id="body">
<input type="checkbox" id="sidebar-toggle-checkbox" style="display: none;">

    <div class="sidebar">
        <div class="d-flex justify-content-between align-items-center px-3 mb-3">
            <div class="d-flex align-items-center">
                <img src="{{ asset('SDU_Logo.png') }}" alt="SDU Logo" class="sidebar-logo">
                <h5 class="m-0 text-white"><span class="logo-text">SDU STAFF</span></h5>
            </div>
            <label for="sidebar-toggle-checkbox" id="sidebar-toggle" class="btn btn-toggle"><i class="fas fa-bars"></i></label>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ !request()->has('view') || request()->get('view') === 'overview' ? 'active' : '' }}" href="{{ route('staff.dashboard') }}?view=overview">
                    <i class="fas fa-chart-line me-2"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->get('view') === 'training-records' ? 'active' : '' }}" href="{{ route('staff.dashboard') }}?view=training-records">
                    <i class="fas fa-book-open me-2"></i> <span>Training Records</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                    <i class="fas fa-user-circle me-2"></i> <span>Profile</span>
                </a>
            </li>
            <li class="nav-item mt-auto">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
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
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#profileModal">
                            <i class="fas fa-user-circle me-2"></i> Profile
                        </button>
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
                <div class="action-card" data-bs-toggle="modal" data-bs-target="#trainingRecordsModal" style="cursor:pointer;">
                    <i class="fas fa-book-open"></i>
                    <h4>Training Records</h4>
                    <p>Review your records instantly</p>
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
                                <th scope="col">Nature</th>
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
                                    <td>{{ $row->nature ?? '' }}</td>
                                    <td>{{ $row->scope ?? '' }}</td>
                                    <td>
                                        <span class="badge {{ $row->status === 'completed' ? 'bg-success' : 'bg-warning' }}">
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
                                                data-nature="{{ $row->nature ?? '' }}"
                                                data-scope="{{ $row->scope ?? '' }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <a href="#" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $row->id }})">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
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
                                        msg += field + ': ' + data.errors[field].join(', ') + '<br>';
                                    }
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

            // Training records modal loader
            const trainingRecordsModal = document.getElementById('trainingRecordsModal');
            if (trainingRecordsModal) {
                trainingRecordsModal.addEventListener('show.bs.modal', function () {
                    const container = document.getElementById('trainingRecordsContent');
                    container.innerHTML = '<div class="text-center py-4"><div class="spinner-border" role="status"></div></div>';
                    // In Laravel, we would need to create an API endpoint for this
                    container.innerHTML = '<div class="alert alert-info">Training records loading is not implemented yet.</div>';
                });
            }

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
                                        msg += field + ': ' + data.errors[field].join(', ') + '<br>';
                                    }
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
                                        msg += field + ': ' + data.errors[field].join(', ') + '<br>';
                                    }
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
        });

        function confirmDelete(trainingId) {
            if (confirm('Are you sure you want to delete this training record?')) {
                // In a real implementation, you would make an AJAX call to delete the record
                alert('Delete functionality would be implemented here. Training ID: ' + trainingId);
            }
        }
    </script>

    <!-- Training Records Modal -->
    <div class="modal fade" id="trainingRecordsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-book-reader me-2"></i>Your Training Records</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="trainingRecordsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border" role="status"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <small class="text-muted">Need more space? <a href="{{ route('staff.dashboard') }}?view=training-records">Open the full page</a></small>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Modal -->
    <div class="modal fade" id="notificationsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-bell me-2"></i>Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="notificationsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border" role="status"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> {{ $user->full_name ?? $user->email ?? '' }}</p>
                    <p><strong>Email:</strong> {{ $user->email ?? '' }}</p>
                    <p><strong>Role:</strong> {{ $user->role ?? '' }}</p>
                    <p class="text-muted">Profile editing functionality would be implemented here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- STATIC: Add Training Modal -->
    <div class="modal fade" id="addTrainingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Training</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addTrainingForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Training Title</label>
                            <input type="text" name="title" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" required />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Venue</label>
                            <input type="text" name="venue" class="form-control" />
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nature</label>
                                <select name="nature" class="form-control">
                                    <option value="">Select Nature</option>
                                    <option value="Internal">Internal</option>
                                    <option value="External">External</option>
                                    <option value="Online">Online</option>
                                    <option value="Workshop">Workshop</option>
                                    <option value="Seminar">Seminar</option>
                                    <option value="Conference">Conference</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Scope</label>
                                <select name="scope" class="form-control">
                                    <option value="">Select Scope</option>
                                    <option value="Local">Local</option>
                                    <option value="Regional">Regional</option>
                                    <option value="National">National</option>
                                    <option value="International">International</option>
                                </select>
                            </div>
                        </div>
                        <div id="addTrainingFeedback"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- STATIC: Edit Training Modal -->
    <div class="modal fade" id="editTrainingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Training</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editTrainingForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" />
                        <div class="mb-3">
                            <label class="form-label">Training Title</label>
                            <input type="text" name="title" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" required />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Venue</label>
                            <input type="text" name="venue" class="form-control" />
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nature</label>
                                <select name="nature" class="form-control">
                                    <option value="">Select Nature</option>
                                    <option value="Internal">Internal</option>
                                    <option value="External">External</option>
                                    <option value="Online">Online</option>
                                    <option value="Workshop">Workshop</option>
                                    <option value="Seminar">Seminar</option>
                                    <option value="Conference">Conference</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Scope</label>
                                <select name="scope" class="form-control">
                                    <option value="">Select Scope</option>
                                    <option value="Local">Local</option>
                                    <option value="Regional">Regional</option>
                                    <option value="National">National</option>
                                    <option value="International">International</option>
                                </select>
                            </div>
                        </div>
                        <div id="editTrainingFeedback"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- STATIC: Upload Proof Modal -->
    <div class="modal fade" id="uploadProofModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Proof of Completion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadProofForm">
                    <div class="modal-body">
                        <input type="hidden" name="training_id" />
                        <div class="mb-3">
                            <label class="form-label">Select file (photo or certificate)</label>
                            <input type="file" name="proof" class="form-control" accept="image/*,.pdf" required />
                        </div>
                        <div id="uploadProofFeedback"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
</html>
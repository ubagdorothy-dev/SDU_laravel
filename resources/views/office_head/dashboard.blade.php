<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Office Head Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/officehead/dashboard.css') }}">
</head>

<body>
    <!-- Desktop Sidebar -->
    <div class="sidebar d-none d-lg-block">
      <div class="sidebar-header d-flex justify-content-between align-items-center px-3 mb-3">
        <div class="d-flex align-items-center">
          <img src="{{ asset('images/SDU_Logo.png') }}" class="sidebar-logo" alt="SDU">
          <h5 class="m-0 text-white">SDU OFFICE HEAD</h5>
        </div>
        <button class="btn btn-toggle" type="button" onclick="document.body.classList.toggle('toggled')" style="color:#fff;border:none;background:transparent">
            <i class="fas fa-bars"></i>
        </button>
      </div>
    <div class="sidebar-content d-flex flex-column flex-grow-1">
        <ul class="nav flex-column flex-grow-1">
          <li class="nav-item">
              <a class="nav-link {{ !request()->has('view') || request()->get('view') === 'overview' ? 'active' : '' }}" href="{{ route('office_head.dashboard') }}?view=overview">
                  <i class="fas fa-chart-line me-2"></i> <span>Dashboard</span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link {{ (request()->get('view') === 'training-records' || request()->get('view') === 'assigned-trainings') ? 'active' : '' }}" href="#trainingSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ (request()->get('view') === 'training-records' || request()->get('view') === 'assigned-trainings') ? 'true' : 'false' }}" aria-controls="trainingSubmenu">
                  <i class="fas fa-book-open me-2"></i> <span>Training</span>
              </a>
              <div class="collapse {{ (request()->get('view') === 'training-records' || request()->get('view') === 'assigned-trainings') ? 'show' : '' }}" id="trainingSubmenu">
                  <ul class="nav flex-column ms-4">
                      <li class="nav-item">
                          <a class="nav-link {{ request()->get('view') === 'training-records' ? 'active' : '' }}" href="{{ route('office_head.dashboard') }}?view=training-records">
                              <i class="fas fa-list me-2"></i> <span>My Records</span>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link {{ request()->get('view') === 'assigned-trainings' ? 'active' : '' }}" href="{{ route('office_head.dashboard') }}?view=assigned-trainings">
                              <i class="fas fa-tasks me-2"></i> <span>Assigned Trainings</span>
                          </a>
                      </li>
                  </ul>
              </div>
          </li>
          <li class="nav-item">
              <a class="nav-link {{ request()->get('view') === 'office-directory' ? 'active' : '' }}" href="{{ route('office_head.dashboard') }}?view=office-directory">
                  <i class="fas fa-users me-2"></i> <span>Office Directory</span>
              </a>
          </li>
        </ul>
        <ul class="nav flex-column sidebar-footer">
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
        <button class="btn btn-primary d-lg-none mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <i class="fas fa-bars"></i> Menu
        </button>
        
        @if (!request()->has('view') || request()->get('view') === 'overview')
        <div class="header mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h1 class="fw-bold mb-2" style="color: #1e293b;">Welcome, {{ $user->full_name ?? 'Office Head' }}! </h1>
                    <p class="mb-1" style="color: #6b7280; font-size: 0.95rem; margin-bottom: .25rem;">
                        @if(optional($user->office)->name)
                            {{ $user->office->name }}
                            @if(optional($user->office)->code)
                                ({{ $user->office->code }})
                            @endif
                        @elseif(!empty($user->office_code))
                            ({{ $user->office_code }})
                        @else
                            Assigned Office
                        @endif
                    </p>
                </div>
                <div class="ms-auto d-flex align-items-center gap-2">
                    <button class="btn btn-primary position-relative" type="button" data-bs-toggle="modal" data-bs-target="#notificationsModal">
                        <i class="fas fa-bell me-2"></i> Notifications
                        <span id="notificationBadgeOffice" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;"></span>
                    </button>
                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#broadcastModal">
                        <i class="fas fa-bullhorn me-2"></i> Send Notification
                    </button>
                </div>
            </div>
        </div>
        @endif
        
        
        @if (!request()->has('view') || request()->get('view') === 'overview')
            <div class="stats-cards">
                <div class="card"><h3>Staff in Office</h3><p>{{ $total_staff_in_office }}</p></div>
                <div class="card"><h3>Trainings Completed</h3><p>{{ $trainings_completed_in_office }}</p></div>
                <div class="card"><h3>Trainings Upcoming</h3><p>{{ $trainings_upcoming_in_office }}</p></div>
                <div class="card"><h3>Trainings Ongoing</h3><p>{{ $trainings_ongoing_in_office }}</p></div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-lg-6">
                    <div class="content-box chart-card">
                        <h2>My Training Status</h2>
                        <div class="chart-wrapper">
                            <canvas id="trainingStatusChart" class="chart-canvas" height="220"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="content-box scrollable-content-box">
                        <h2>Staff in Office</h2>
                        <div class="table-responsive" style="flex: 1; overflow-y: auto;">
                            @if($office_staff->count() > 0)
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Job Function</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($office_staff as $staff)
                                            <tr>
                                                <td>{{ $staff->full_name }}</td>
                                                <td>{{ $staff->position ?? 'N/A' }}</td>
                                                <td>{{ $staff->job_function ?? 'N/A' }}</td>
                                                <td>
                                                    <button class="btn btn-info btn-sm btn-view-trainings" 
                                                        data-user-id="{{ $staff->user_id }}" 
                                                        data-user-name="{{ $staff->full_name }}">
                                                        <i class="fas fa-graduation-cap"></i> View Trainings
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">No staff members in your office.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="content-box non-scrollable">
                        <h2>Recent Activity</h2>
                        <div class="table-responsive">
                            @if(count($result_head_activities) > 0)
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($result_head_activities as $activity)
                                            <tr>
                                                <td>{{ $activity->title }}</td>
                                                <td>
                                                    <span class="badge 
                                                        @if($activity->status == 'completed') bg-success
                                                        @elseif($activity->status == 'upcoming') bg-warning
                                                        @elseif($activity->status == 'ongoing') bg-info
                                                        @else bg-secondary
                                                        @endif">
                                                        {{ ucfirst($activity->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $activity->start_date }}</td>
                                                <td>{{ $activity->end_date }}</td>
                                                <td>{{ date('M j, Y', strtotime($activity->created_at)) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">No recent activities.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @elseif (request()->get('view') === 'training-records')
            <div class="content-box non-scrollable">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>My Training Records</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainingModal">
                        <i class="fas fa-plus me-1"></i> Add Training
                    </button>
                </div>
                
                @if (count($training_records) > 0)
                    <div class="table-responsive">
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
                                @foreach($training_records as $record)
                                    <tr>
                                        <td>{{ $record->title }}</td>
                                        <td>{{ $record->description }}</td>
                                        <td>{{ $record->start_date }}</td>
                                        <td>{{ $record->end_date }}</td>
                                        <td>{{ $record->venue ?? '' }}</td>
                                        <td>{{ $record->nature ?? '' }}</td>
                                        <td>{{ $record->scope ?? '' }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($record->status == 'completed') bg-success
                                                @elseif($record->status == 'upcoming') bg-warning text-dark
                                                @elseif($record->status == 'ongoing') bg-primary
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst($record->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editTrainingModal"
                                                    data-training-id="{{ $record->id }}"
                                                    data-title="{{ $record->title }}"
                                                    data-description="{{ $record->description }}"
                                                    data-start-date="{{ $record->start_date }}"
                                                    data-end-date="{{ $record->end_date }}"
                                                    data-venue="{{ $record->venue ?? '' }}"
                                                    data-nature="{{ $record->nature ?? '' }}"
                                                    data-scope="{{ $record->scope ?? '' }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-training-id="{{ $record->id }}">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                                @if ($record->status === 'completed' && empty($record->proof_uploaded))
                                                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadProofModal" data-training-id="{{ $record->id }}"> 
                                                        <i class="fas fa-upload"></i> Upload Proof
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mt-4" role="alert">
                        You have not completed any trainings yet.
                    </div>
                @endif
            </div>
        @elseif (request()->get('view') === 'assigned-trainings')
            <div class="content-box non-scrollable">
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
        @elseif (request()->get('view') === 'office-directory')
            <div class="content-box non-scrollable">
                <h2>Office Directory</h2>
                <p class="text-muted">Staff members in your office.</p>
                
                @if($office_staff->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped mt-4">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Program</th>
                                    <th>Employment Status</th>
                                    <th>Degree Attained</th>
                                    <th>Job Function</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($office_staff as $staff)
                                    <tr>
                                        <td>{{ $staff->full_name }}</td>
                                        <td>{{ $staff->email }}</td>
                                        <td>{{ $staff->position ?? 'N/A' }}</td>
                                        <td>{{ $staff->program ?? 'N/A' }}</td>
                                        <td>{{ $staff->employment_status ?? 'N/A' }}</td>
                                        <td>{{ $staff->degree_attained ?? 'N/A' }}</td>
                                        <td>{{ $staff->job_function ?? 'N/A' }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm btn-view-trainings" 
                                                data-user-id="{{ $staff->user_id }}" 
                                                data-user-name="{{ $staff->full_name }}">
                                                <i class="fas fa-graduation-cap"></i> View Trainings
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5>No Staff Members</h5>
                        <p class="text-muted">There are no staff members in your office yet.</p>
                    </div>
                @endif
            </div>
        @endif
        
        <!-- Modals -->
    @include('office_head.partials.modals', [
        'office_display' => $office_display,
        'user' => $user
    ])
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Debug: Check if logout button exists
        document.addEventListener('DOMContentLoaded', function() {
            const logoutButton = document.querySelector('.sidebar-footer .nav-link[href*="logout"]');
            if (logoutButton) {
                console.log('Logout button found in DOM');
                // Add a temporary alert to confirm it exists
                // alert('Logout button exists in DOM - check console for details');
            } else {
                console.log('ERROR: Logout button NOT found in DOM');
                alert('ERROR: Logout button NOT found in DOM');
            }
        });
    </script>
    

    
<div class="modal fade" id="broadcastModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="broadcastForm">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-bullhorn me-2"></i>Send Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Audience</label>
                            <select class="form-select" name="audience" required>
                                <option value="office_staff">Staff of Assigned Office</option>
                                <option value="unit_director">Unit Director</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">Subject (optional)</label>
                            <input type="text" class="form-control" name="subject" placeholder="Optional subject line">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" name="message" rows="5" placeholder="Share updates, reminders, or announcements" required></textarea>
                    </div>
                    <div id="broadcastFeedback" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
    function renderOfficeNotifications(notifications) {
        const listDiv = document.getElementById('notificationsList');
        if (!listDiv) return;
        if (!notifications || notifications.length === 0) {
            listDiv.innerHTML = `<div class="text-center py-5"><i class="fas fa-inbox fa-3x mb-3 text-muted"></i><h5>No notifications</h5><p class="text-muted">You don't have any notifications at the moment.</p></div>`;
            return;
        }
        // Deduplicate notifications by id in case multiple fetches returned overlapping results
        const unique = {};
        const deduped = [];
        notifications.forEach(n => {
            if (!n || !n.id) return;
            if (!unique[n.id]) { unique[n.id] = true; deduped.push(n); }
        });

        let html = '<div class="list-group">';
        deduped.forEach(n => {
            const isUnreadClass = !n.is_read ? 'list-group-item-warning unread' : '';
            const unreadIndicator = !n.is_read ? '<span class="badge bg-warning me-2">NEW</span>' : '';
            let senderInfo = '';
            if (n.sender_name && n.sender_role) senderInfo = `<small class="text-muted d-block mb-1">From: ${n.sender_name} (${n.sender_role})</small>`;
            html += `<div class="list-group-item ${isUnreadClass}" data-notification-id="${n.id}"><div class="d-flex w-100 justify-content-between"><h6 class="mb-1">${unreadIndicator}${n.title || 'Notification'}</h6><small class="text-muted">${timeAgo(new Date(n.created_at))}</small></div>${senderInfo}<p class="mb-1">${n.message || ''}</p></div>`;
        });
        html += '</div>';
        listDiv.innerHTML = html;
    }

    // Helper function to format time ago
    function timeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = Math.floor(seconds / 31536000);
        if (interval > 1) return interval + " years ago";
        interval = Math.floor(seconds / 2592000);
        if (interval > 1) return interval + " months ago";
        interval = Math.floor(seconds / 86400);
        if (interval > 1) return interval + " days ago";
        interval = Math.floor(seconds / 3600);
        if (interval > 1) return interval + " hours ago";
        interval = Math.floor(seconds / 60);
        if (interval > 1) return interval + " minutes ago";
        return Math.floor(seconds) + " seconds ago";
    }

    function initializeOfficeNotificationActions() {
        const markAllReadBtn = document.getElementById('markAllReadBtn');
        if (markAllReadBtn) markAllReadBtn.addEventListener('click', markAllOfficeNotificationsAsRead);
        const deleteAllBtn = document.getElementById('deleteAllBtn');
        if (deleteAllBtn) deleteAllBtn.addEventListener('click', deleteAllOfficeNotifications);
    }

    function markAllOfficeNotificationsAsRead() {
        fetch("{{ route('notifications.mark_read') }}", { method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept':'application/json' }, body: JSON.stringify({ ids: 'all' }) })
            .then(r => r.json()).then(data => { if (data.success) { fetchOfficeNotifications(); updateOfficeUnreadCount(); } else console.error(data); }).catch(console.error);
    }

    function deleteAllOfficeNotifications() {
        if (!confirm('Are you sure you want to delete all notifications?')) return;
        fetch("{{ route('notifications.delete') }}", { method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept':'application/json' }, body: JSON.stringify({ ids: 'all' }) })
            .then(r => r.json()).then(data => { if (data.success) { fetchOfficeNotifications(); updateOfficeUnreadCount(); } else console.error(data); }).catch(console.error);
    }

    function updateOfficeUnreadCount() {
        // fetch all notifications and count unread excluding office_head senders
        fetch("{{ route('notifications.get') }}", { method: 'GET', headers: { 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                const badge = document.getElementById('notificationBadgeOffice');
                if (!badge) return;
                if (data.success && data.notifications) {
                    // Deduplicate by id first to avoid double-counting
                    const seen = {};
                    const unique = [];
                    data.notifications.forEach(n => { if (n && n.id && !seen[n.id]) { seen[n.id] = true; unique.push(n); } });
                    const count = unique.filter(n => !n.is_read && (n.sender_role || '').toLowerCase() !== 'office_head').length;
                    if (count > 0) { badge.textContent = count; badge.style.display = 'block'; } else { badge.style.display = 'none'; }
                }
            }).catch(err => console.error('Error updating unread count:', err));
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Training Status Chart (Bar Chart)
        const statusCtx = document.getElementById('trainingStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: ['Completed', 'Pending', 'Ongoing'],
                datasets: [{
                    label: 'Training Status Distribution',
                    data: [{{ $trainings_completed_in_office }}, {{ $trainings_upcoming_in_office }}, {{ $trainings_ongoing_in_office }}],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#17a2b8'
                    ],
                    borderColor: [
                        '#1e7e34',
                        '#e0a800',
                        '#117a8b'
                    ],
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });

// Office notification form submit
    (function(){
        const broadcastForm = document.getElementById('broadcastForm');
        const broadcastFeedback = document.getElementById('broadcastFeedback');

       if (!broadcastForm) return;
            broadcastForm.addEventListener('submit', function(e) {
            
            e.preventDefault();
            

            console.log('Submitting broadcast form');
            const formData = new FormData(this);
            // Log form data for debugging
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            // Log specifically the audience value
            console.log('Audience value:', formData.get('audience'));
            const sendButton = this.querySelector('button[type="submit"]');
            const originalButtonText = sendButton.innerHTML;

            // Disable button and show loading state
            sendButton.disabled = true;
            sendButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
            broadcastFeedback.innerHTML = ''; // Clear previous feedback

            fetch('{{ route('notifications.office_broadcast') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response received:', response);
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                if (data.success) {
                    broadcastFeedback.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    
                    // Show additional confirmation based on audience
                    if (data.audience) {
                        let additionalInfo = '';
                        if (data.audience === 'unit_director') {
                            additionalInfo = '<div class="mt-2"><small class="text-muted">Note: Your message has been sent to the Unit Director(s).</small></div>';
                        } else if (data.audience === 'office_staff') {
                            additionalInfo = '<div class="mt-2"><small class="text-muted">Note: Your message has been sent to staff members in your office.</small></div>';
                        }
                        broadcastFeedback.innerHTML += additionalInfo;
                    }
                    
                    broadcastForm.reset(); // Clear form on success
                    setTimeout(() => {
                        const modalEl = document.getElementById('broadcastModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);

                        if (modal) modal.hide();
                
                    }, 1500);
                } else {
                broadcastFeedback.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error('Error sending notification:', error);
                broadcastFeedback.innerHTML = `<div class="alert alert-danger">An unexpected error occurred. Please try again.</div>`;
            })
            .finally(() => {
                // Re-enable button
                sendButton.disabled = false;
                sendButton.innerHTML = originalButtonText;
            });
        });
    })();
    </script>
    
    @include('office_head.partials.modals', [
        'office_display' => $office_display,
        'user' => $user
    ])
    
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
    
    <script>
    // Handle view trainings clicks
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-view-trainings');
            if (!btn) return;
            console.log('View trainings button clicked');
            const uid = btn.getAttribute('data-user-id');
            const uname = btn.getAttribute('data-user-name');
            console.log('User ID:', uid, 'User Name:', uname);
            showTrainingsFor(uid, uname);
        });
    });
    
    // Server-provided data for trainings
    const trainingsByUser = {};
    
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
        if (s === 'completed') return '<span class="badge bg-success">Completed</span>';
        if (s === 'ongoing') return '<span class="badge bg-info">Ongoing</span>';
        if (s === 'upcoming') return '<span class="badge bg-warning">Upcoming</span>';
        if (s === 'cancelled') return '<span class="badge bg-danger">Cancelled</span>';
        return '<span class="badge bg-secondary">' + escapeHtml(status || 'Unknown') + '</span>';
    }

    // showTrainingsFor: populate modal from trainingsByUser and show
    function showTrainingsFor(userId, userName) {
        console.log('Fetching trainings for user:', userId, userName);
        // First, fetch training records for this user via AJAX
        fetch(`/office-head/staff/${userId}/trainings`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response received:', response);
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            if (data.success) {
                // Update modal title
                const modalTitle = document.getElementById('trainingsModalTitle');
                modalTitle.innerHTML = `Training Records for ${escapeHtml(userName)}`;
                
                // Clear and populate modal body
                const body = document.getElementById('trainingsModalBody');
                body.innerHTML = '';
                
                const trainings = data.trainings || [];
                if (trainings.length === 0) {
                    body.innerHTML = '<tr><td colspan="8" class="text-muted text-center">No training records found.</td></tr>';
                } else {
                    trainings.forEach(t => {
                        const tr = document.createElement('tr');
                        const statusHtml = renderStatusBadgeClient(t.status || '');
                        
                        // Format dates
                        const dates = (t.start_date || t.end_date) ? 
                            `${t.start_date ? formatDateClient(t.start_date) : ''}${t.start_date && t.end_date ? ' - ' : ''}${t.end_date ? formatDateClient(t.end_date) : ''}` : 
                            '-';
                        
                        const nature = t.nature_of_training || '';
                        const scope = t.scope || '';
                        
                        // Proof preview (simplified for Office Head)
                        const proofPreview = t.proof_document ? 
                            '<span class="text-success">Available</span>' : 
                            '<span class="text-muted">Not provided</span>';
                        
                        tr.innerHTML = `
                            <td>${escapeHtml(t.title || '')}</td>
                            <td>${escapeHtml(t.description || '')}</td>
                            <td>${escapeHtml(dates)}</td>
                            <td>${escapeHtml(t.venue || '')}</td>
                            <td>${escapeHtml(nature)}</td>
                            <td>${escapeHtml(scope)}</td>
                            <td>${statusHtml}</td>
                            <td>${proofPreview}</td>
                        `;
                        body.appendChild(tr);
                    });
                }
                
                // Show modal
                console.log('Showing modal');
                const mdl = new bootstrap.Modal(document.getElementById('trainingsModal'));
                mdl.show();
                console.log('Modal shown');
            } else {
                alert('Error loading training records: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading training records. Please try again.');
        });
    }
    
    // Handle edit training modal
    document.addEventListener('DOMContentLoaded', function() {
        // Populate edit training modal with data
        const editTrainingModal = document.getElementById('editTrainingModal');
        if (editTrainingModal) {
            editTrainingModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Button that triggered the modal
                
                // Extract info from data-* attributes
                const trainingId = button.getAttribute('data-training-id');
                const title = button.getAttribute('data-title');
                const description = button.getAttribute('data-description');
                const startDate = button.getAttribute('data-start-date');
                const endDate = button.getAttribute('data-end-date');
                const venue = button.getAttribute('data-venue');
                const nature = button.getAttribute('data-nature');
                const scope = button.getAttribute('data-scope');
                
                // Update the modal's content
                const modal = this;
                modal.querySelector('input[name="id"]').value = trainingId;
                modal.querySelector('input[name="title"]').value = title;
                modal.querySelector('textarea[name="description"]').value = description;
                modal.querySelector('input[name="start_date"]').value = startDate;
                modal.querySelector('input[name="end_date"]').value = endDate;
                modal.querySelector('input[name="venue"]').value = venue;
                
                // Set nature of training select
                const natureSelect = modal.querySelector('select[name="nature_of_training"]');
                if (natureSelect) {
                    natureSelect.value = nature;
                }
                
                // Set scope select
                const scopeSelect = modal.querySelector('select[name="scope"]');
                if (scopeSelect) {
                    scopeSelect.value = scope;
                }
            });
        }
        
        // Handle edit training form submission
        const editTrainingForm = document.getElementById('editTrainingForm');
        if (editTrainingForm) {
            editTrainingForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                const feedbackDiv = document.getElementById('editTrainingFeedback');
                
                // Show loading state
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
                submitBtn.disabled = true;
                feedbackDiv.innerHTML = '';
                
                // Get form data
                const formData = new FormData(this);
                const trainingId = formData.get('id');
                
                // Submit form via AJAX
                fetch('/training_records/' + trainingId, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-HTTP-Method-Override': 'PUT' // Laravel convention for PUT requests
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        feedbackDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                        
                        // Close modal after delay
                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('editTrainingModal'));
                            if (modal) modal.hide();
                            
                            // Reload page to show updated training record
                            location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        let errorMsg = data.message || 'Error updating training record.';
                        if (data.errors) {
                            errorMsg += '<br><ul class="mb-0">';
                            for (const field in data.errors) {
                                errorMsg += '<li>' + data.errors[field][0] + '</li>';
                            }
                            errorMsg += '</ul>';
                        }
                        feedbackDiv.innerHTML = '<div class="alert alert-danger">' + errorMsg + '</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    feedbackDiv.innerHTML = '<div class="alert alert-danger">An error occurred while updating the training record.</div>';
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
        
        // Handle upload proof modal
        const uploadProofModal = document.getElementById('uploadProofModal');
        if (uploadProofModal) {
            uploadProofModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Button that triggered the modal
                const trainingId = button.getAttribute('data-training-id');
                
                // Update the modal's content
                const modal = this;
                modal.querySelector('input[name="training_id"]').value = trainingId;
            });
        }
        
        // Handle upload proof form submission
        const uploadProofForm = document.getElementById('uploadProofForm');
        if (uploadProofForm) {
            uploadProofForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                const feedbackDiv = document.getElementById('uploadProofFeedback');
                
                // Show loading state
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
                submitBtn.disabled = true;
                feedbackDiv.innerHTML = '';
                
                // Get form data
                const formData = new FormData(this);
                
                // Get training ID from the hidden input field
                const trainingId = this.querySelector('input[name="training_id"]').value;
                
                // Add CSRF token to form data
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                // Rename 'proof' to 'proof_file' to match the controller expectation
                if (formData.has('proof')) {
                    const proofFile = formData.get('proof');
                    formData.delete('proof');
                    formData.append('proof_file', proofFile);
                }
                
                // Submit form via AJAX
                fetch('{{ route('training_proofs.upload', ['training_record' => '_ID_']) }}'.replace('_ID_', trainingId), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        feedbackDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                        
                        // Reset form
                        uploadProofForm.reset();
                        
                        // Close modal after delay
                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('uploadProofModal'));
                            if (modal) modal.hide();
                            
                            // Reload page to show updated training record
                            location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        let errorMsg = data.message || 'Error uploading proof.';
                        if (data.errors) {
                            errorMsg += '<br><ul class="mb-0">';
                            for (const field in data.errors) {
                                errorMsg += '<li>' + data.errors[field][0] + '</li>';
                            }
                            errorMsg += '</ul>';
                        }
                        feedbackDiv.innerHTML = '<div class="alert alert-danger">' + errorMsg + '</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    feedbackDiv.innerHTML = '<div class="alert alert-danger">An error occurred while uploading the proof.</div>';
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
    
    // Handle delete confirmation
        // Set up delete button event listeners
        const deleteButtons = document.querySelectorAll('[data-bs-target="#deleteConfirmationModal"]');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const trainingId = this.getAttribute('data-training-id');
                document.getElementById('deleteTrainingId').value = trainingId;
            });
        });
        
        // Handle confirm delete button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            const trainingId = document.getElementById('deleteTrainingId').value;
            
            // Show loading state on delete button
            const deleteBtn = this;
            const originalText = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
            deleteBtn.disabled = true;
            
            // Debugging: log the training ID and CSRF token
            console.log('Deleting training record with ID:', trainingId);
            console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
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
                    const deleteModal = document.getElementById('deleteConfirmationModal');
                    const modal = bootstrap.Modal.getInstance(deleteModal);
                    if (modal) {
                        modal.hide();
                    }
                    // Show success message in modal
                    const successModal = new bootstrap.Modal(document.getElementById('successMessageModal'));
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
                    }).catch(e => {
                        // If we can't parse JSON, use the status text
                        if (e instanceof SyntaxError) {
                            throw new Error('Delete failed with status: ' + response.status + ' - ' + response.statusText);
                        }
                        throw e;
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Close delete confirmation modal
                const deleteModal = document.getElementById('deleteConfirmationModal');
                const modal = bootstrap.Modal.getInstance(deleteModal);
                if (modal) {
                    modal.hide();
                }
                // Show error message in modal
                const successModal = new bootstrap.Modal(document.getElementById('successMessageModal'));
                document.getElementById('successMessageText').textContent = 'Failed to delete training record: ' + error.message;
                successModal.show();
            })
            .finally(() => {
                // Reset delete button state
                deleteBtn.innerHTML = originalText;
                deleteBtn.disabled = false;
            });
        });
        
        // Handle Add Training form submission
        const addTrainingForm = document.getElementById('addTrainingForm');
        if (addTrainingForm) {
            addTrainingForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                const feedbackDiv = document.getElementById('addTrainingFeedback');
                
                // Show loading state
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
                submitBtn.disabled = true;
                feedbackDiv.innerHTML = '';
                
                // Get form data
                const formData = new FormData(this);
                
                // Submit form via AJAX
                fetch('{{ route('training_records.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        feedbackDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                        
                        // Reset form
                        addTrainingForm.reset();
                        
                        // Close modal after delay
                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('addTrainingModal'));
                            if (modal) modal.hide();
                            
                            // Reload page to show new training record
                            location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        let errorMsg = data.message || 'Error saving training record.';
                        if (data.errors) {
                            errorMsg += '<br><ul class="mb-0">';
                            for (const field in data.errors) {
                                errorMsg += '<li>' + data.errors[field][0] + '</li>';
                            }
                            errorMsg += '</ul>';
                        }
                        feedbackDiv.innerHTML = '<div class="alert alert-danger">' + errorMsg + '</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    feedbackDiv.innerHTML = '<div class="alert alert-danger">An error occurred while saving the training record.</div>';
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
    });
    </script>
    
    <!-- Staff Modal Scripts (for profile modal) -->
    @include('staff.partials.modal_scripts')
    
</body>
</html>
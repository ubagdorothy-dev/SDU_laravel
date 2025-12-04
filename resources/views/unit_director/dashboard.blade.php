<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Unit Director - Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/unitdirector/dashboard.css') }}">

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
    <label for="sidebar-toggle-checkbox" class="btn btn-toggle" style="color:#fff;border:none;background:transparent"><i class="fas fa-bars"></i></label>
  </div>
  <div class="sidebar-content">
    <ul class="nav flex-column flex-grow-1">
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i><span> Dashboard</span></a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('directory_reports.index') ? 'active' : '' }}" href="{{ route('directory_reports.index') }}"><i class="fas fa-users me-2"></i><span> Directory & Reports</span></a></li>
       @if(in_array($user->role, ['unit director', 'unit_director']))
        <li class="nav-item"><a class="nav-link" href="{{ route('pending_approvals.index') }}"><i class="fas fa-clipboard-check me-2"></i>Pending Approvals <span class="badge bg-danger">{{ $pendingApprovalsCount ?? 0 }}</span></a></li>
        @endif
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('training_assignments.index') ? 'active' : '' }}" href="{{ route('training_assignments.index') }}"><i class="fas fa-tasks me-2"></i> <span> Training Assignments</span></a></li>
    </ul>
    <ul class="nav flex-column sidebar-footer">
      <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fas fa-user-circle me-2"></i> <span> Profile</span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i><span> Logout</span></a></li>
    </ul>
  </div>
</div>

<div class="main-content">
    <button class="btn btn-primary d-lg-none mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
        <i class="fas fa-bars"></i> Menu
    </button>

    <div class="header mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="fw-bold mb-2" style="color: #1e293b;">Welcome, {{ $user->full_name ?? 'SDU Director' }}! </h1>
                <p class="mb-0" style="color: #6b7280;">Here's what's happening with your organization today.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-primary position-relative" data-bs-toggle="modal" data-bs-target="#inboxModal">
                    <i class="fas fa-bell me-2"></i> Inbox
                    <span id="inboxCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;">0</span>
                </button>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bullhorn me-2"></i> Send Notification
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#broadcastModal">
                            <i class="fas fa-users me-2"></i> Broadcast to All
                        </a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#officeStaffBroadcastModal">
                            <i class="fas fa-building me-2"></i> To Specific Offices
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="stats-cards">
        <div class="card"><h3>Total Staff</h3><p>{{ $total_staff }}</p></div>
        <div class="card"><h3>Total Heads</h3><p>{{ $total_heads }}</p></div>
        <div class="card"><h3>Trainings Completed</h3><p>{{ $training_completion_percentage }}%</p></div>
        <div class="card"><h3>Active Offices</h3><p>{{ $active_offices }}</p></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="content-box chart-card">
                <h2>Total Attendance</h2>
                <p class="text-muted">Completed trainings across the last 6 months</p>
                <div class="chart-wrapper"><canvas id="areaChart" class="chart-canvas" height="220"></canvas></div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="content-box chart-card">
                <h2>Training Statistics</h2>
                <p class="text-muted">Overview of training activity and completion trends</p>

                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-4">
                        <div class="card" style="padding:1rem; border-radius:12px;">
                            <h3 style="font-size:0.8rem;">Most Attended</h3>
                            <p style="font-size:1.3rem; font-weight:800; color:#6366f1; margin:0;">{{ $most_attended_title }}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card" style="padding:1rem; border-radius:12px;">
                            <h3 style="font-size:0.8rem;">Least Attended</h3>
                            <p style="font-size:1.3rem; font-weight:800; color:#10b981; margin:0;">{{ $least_attended_title }}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card" style="padding:1rem; border-radius:12px;">
                            <h3 style="font-size:0.8rem;">This Month</h3>
                            <p style="font-size:1.3rem; font-weight:800; color:#f59e0b; margin:0;">{{ $this_month_count }} Trainings</p>
                        </div>
                    </div>
                </div>

                <div class="chart-wrapper"><canvas id="horizontalBarChart" class="chart-canvas" height="220"></canvas></div>
            </div>
        </div>
    </div>
    
    <!-- Recent Training Completions -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="content-box">
                <h2>Recent Training Completions</h2>
                <p class="text-muted">Latest training completions by staff members</p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Staff Name</th>
                                <th>Training Title</th>
                                <th>Completion Date</th>
                                <th>Office</th>
                            </tr>
                        </thead>
                        <tbody id="recentTrainings">
                            @forelse($recent_trainings as $training)
                                <tr>
                                    <td>{{ $training->full_name }}</td>
                                    <td>{{ $training->title }}</td>
                                    <td>{{ $training->completion_date }}</td>
                                    <td>{{ $training->office_code }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No recent training completions</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Office-wise Training Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="content-box">
                <h2>Office-wise Training Statistics</h2>
                <p class="text-muted">Training completion rates by office</p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Office</th>
                                <th>Total Staff</th>
                                <th>Completed Trainings</th>
                                <th>Completion Rate</th>
                            </tr>
                        </thead>
                        <tbody id="officeStats">
                            @forelse($office_stats as $office)
                                @php
                                    $completionRate = $office['total_staff'] > 0 ? round(($office['completed_trainings'] / $office['total_staff']) * 100) : 0;
                                @endphp
                                <tr>
                                    <td>{{ $office['office_name'] }}</td>
                                    <td>{{ $office['total_staff'] }}</td>
                                    <td>{{ $office['completed_trainings'] }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $completionRate }}%" aria-valuenow="{{ $completionRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span>{{ $completionRate }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No office statistics available</td>
                                </tr>
                            @endforelse
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

<!-- Inbox Modal -->
<div class="modal fade" id="inboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-inbox me-2"></i>Notifications Inbox</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <button id="markAllReadBtn" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-check-double me-1"></i>Mark All Read
                        </button>
                        <button id="deleteAllBtn" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash-alt me-1"></i>Delete All
                        </button>
                    </div>
                    <div>
                        <span id="notificationCount" class="text-muted small">Loading notifications...</span>
                    </div>
                </div>
                <div id="notificationsList">
                    <div class="text-center py-4">
                        <div class="spinner-border" role="status"></div>
                        <p class="mt-2">Loading notifications...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Broadcast Modal -->
<div class="modal fade" id="broadcastModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
                                <option value="all">All Users</option>
                                <option value="staff">Staff Only</option>
                                <option value="heads">Office Heads Only</option>
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

<!-- Office Staff Broadcast Modal -->
<div class="modal fade" id="officeStaffBroadcastModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="officeStaffBroadcastForm">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-building me-2"></i>Send Notification to Office Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Offices</label>
                        <div id="officesContainer" class="border rounded p-3 bg-light">
                            <div class="text-center py-3">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                <span class="ms-2">Loading offices...</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject (optional)</label>
                        <input type="text" class="form-control" name="subject" placeholder="Optional subject line">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" name="message" rows="5" placeholder="Share updates, reminders, or announcements" required></textarea>
                    </div>
                    <div id="officeStaffBroadcastFeedback" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send to Selected Offices</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // --- Area Chart (Total Attendance) ---
    const areaCtx = document.getElementById('areaChart').getContext('2d');
    const areaGradient = areaCtx.createLinearGradient(0, 0, 0, 220);
    areaGradient.addColorStop(0, 'rgba(99,102,241,0.16)');
    areaGradient.addColorStop(1, 'rgba(99,102,241,0.02)');

    new Chart(areaCtx, {
        type: 'line',
        data: {
            labels: @json($attendance_labels),
            datasets: [{
                label: 'Completed trainings',
                data: @json($attendance_data),
                borderColor: '#1e3a8a',
                backgroundColor: areaGradient,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#1e3a8a',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: { x: { grid: { display: false } }, y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { legend: { display: false } }
        }
    });

    // --- Horizontal Bar Chart (Training completion trends) ---
    const hbarCtx = document.getElementById('horizontalBarChart').getContext('2d');
    new Chart(hbarCtx, {
        type: 'bar',
        data: {
            indexAxis: 'y',
            labels: @json($completion_labels),
            datasets: [
                {
                    label: 'Trainings Completed',
                    data: @json($completion_data),
                    backgroundColor: ['#7841f7ff','#7d4af5ff','#7d4decff','#7642f0ff','#8b5cf6','#7d4ceeff'],
                    borderRadius: 6
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1 } },
                y: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
    
    // --- Broadcast Modal Form Submission Logic ---
    const broadcastForm = document.getElementById('broadcastForm');
    const broadcastFeedback = document.getElementById('broadcastFeedback');

    if (broadcastForm) {
        broadcastForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const sendButton = this.querySelector('button[type="submit"]');
            const originalButtonText = sendButton.innerHTML;

            // Disable button and show loading state
            sendButton.disabled = true;
            sendButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
            broadcastFeedback.innerHTML = ''; // Clear previous feedback

            fetch('{{ route('notifications.broadcast') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    broadcastFeedback.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    broadcastForm.reset(); // Clear form on success
                    // Optionally close the modal after a brief moment
                    setTimeout(() => {
                        const modalEl = document.getElementById('broadcastModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }, 2000);
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
    }

    // Load count initially and when modal opened
    fetchInboxCount();
    var inboxModal = document.getElementById('inboxModal');
    if (inboxModal) inboxModal.addEventListener('show.bs.modal', loadInboxList);
    
    // Handle broadcast form submission
    document.getElementById('broadcastForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const feedback = document.getElementById('broadcastFeedback');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
        
        // Clear previous feedback
        feedback.innerHTML = '';
        
        fetch('{{ route('notifications.broadcast') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(Object.fromEntries(new FormData(form)))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                feedback.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                form.reset();
                
                // Close modal after 2 seconds
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('broadcastModal')).hide();
                }, 2000);
            } else {
                feedback.innerHTML = `<div class="alert alert-danger">Error: ${data.message || 'Failed to send notification'}</div>`;
            }
        })
        .catch(error => {
            console.error('Error sending notification:', error);
            feedback.innerHTML = '<div class="alert alert-danger">Error sending notification. Please try again.</div>';
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Send';
        });
    });
});

// Fetch inbox count
function fetchInboxCount() {
    fetch('{{ route('notifications.unread_count') }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const unreadCount = data.count;
                const inboxCountElement = document.getElementById('inboxCount');
                if (inboxCountElement) {
                    if (unreadCount > 0) {
                        inboxCountElement.textContent = unreadCount;
                        inboxCountElement.style.display = 'inline';
                    } else {
                        inboxCountElement.style.display = 'none';
                    }
                }
            }
        })
        .catch(error => console.error('Error fetching inbox count:', error));
}

// Load inbox list
function loadInboxList() {
    fetch('{{ route('notifications.get') }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderNotifications(data.notifications);
            } else {
                document.getElementById('notificationsList').innerHTML = 
                    '<div class="alert alert-danger">Failed to load notifications: ' + (data.message || 'Unknown error') + '</div>';
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            document.getElementById('notificationsList').innerHTML = 
                '<div class="alert alert-danger">Error loading notifications: ' + error.message + '</div>';
        });
}

// Render notifications
function renderNotifications(notifications) {
    const container = document.getElementById('notificationsList');
    const countElement = document.getElementById('notificationCount');
    
    if (countElement) {
        countElement.textContent = `${notifications.length} notifications`;
    }
    
    if (!notifications || notifications.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                <h5>No notifications</h5>
                <p class="text-muted">You don't have any notifications at the moment.</p>
            </div>`;
        return;
    }
    
    // Deduplicate notifications by id in case multiple fetches returned overlapping results
    const unique = {};
    const deduped = [];
    notifications.forEach(n => {
        if (!n || !n.id) return;
        if (!unique[n.id]) { unique[n.id] = true; deduped.push(n); }
    });
    
    if (deduped.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                <h5>No notifications</h5>
                <p class="text-muted">You don't have any notifications at the moment.</p>
            </div>`;
        return;
    }
    
    if (countElement) {
        countElement.textContent = `${deduped.length} notifications`;
    }
    
    let html = '<div class="list-group">';
    deduped.forEach(notification => {
        const isReadClass = notification.is_read ? '' : 'list-group-item-warning';
        const formattedDate = new Date(notification.created_at).toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        
        // Get sender information
        let senderInfo = '';
        if (notification.sender_name && notification.sender_role) {
            senderInfo = `<small class="text-muted d-block mb-1">From: ${notification.sender_name} (${notification.sender_role})</small>`;
        } else if (notification.sender_name) {
            senderInfo = `<small class="text-muted d-block mb-1">From: ${notification.sender_name}</small>`;
        }
        
        html += `
            <div class="list-group-item ${isReadClass} mb-2" data-notification-id="${notification.id}">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${notification.title || 'Notification'}</h6>
                    <small>${formattedDate}</small>
                </div>
                ${senderInfo}
                <p class="mb-1">${notification.message}</p>
                <div class="d-flex justify-content-end gap-2 mt-2">
                    ${!notification.is_read ? 
                        `<button class="btn btn-sm btn-outline-primary mark-read-btn" data-id="${notification.id}">
                            <i class="fas fa-check"></i> Mark Read
                        </button>` : ''}
                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${notification.id}">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>`;
    });
    html += '</div>';
    
    container.innerHTML = html;
    
    // Add event listeners for mark read and delete buttons
    document.querySelectorAll('.mark-read-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            markNotificationAsRead(id, this);
        });
    });
    
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            deleteNotification(id, this.closest('.list-group-item'));
        });
    });
}

// Mark notification as read
function markNotificationAsRead(id, button) {
    fetch('{{ route('notifications.mark_read') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.closest('.list-group-item').classList.remove('list-group-item-warning');
            button.remove();
            fetchInboxCount(); // Refresh count
        } else {
            alert('Failed to mark notification as read');
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        alert('Error marking notification as read');
    });
}

// Delete notification
function deleteNotification(id, element) {
    if (!confirm('Are you sure you want to delete this notification?')) return;
    
    fetch('{{ route('notifications.delete') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            element.remove();
            fetchInboxCount(); // Refresh count
        } else {
            alert('Failed to delete notification');
        }
    })
    .catch(error => {
        console.error('Error deleting notification:', error);
        alert('Error deleting notification');
    });
}

// Mark all as read
document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
    fetch('{{ route('notifications.mark_read') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ mark_all: true })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.mark-read-btn').forEach(btn => btn.closest('.list-group-item').remove());
            fetchInboxCount(); // Refresh count
        } else {
            alert('Failed to mark all notifications as read');
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
        alert('Error marking all notifications as read');
    });
});

// Delete all notifications
document.getElementById('deleteAllBtn')?.addEventListener('click', function() {
    if (!confirm('Are you sure you want to delete all notifications?')) return;
    
    fetch('{{ route('notifications.delete') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ delete_all: true })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('notificationsList').innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                    <h5>No notifications</h5>
                    <p class="text-muted">You don't have any notifications at the moment.</p>
                </div>`;
            fetchInboxCount(); // Refresh count
        } else {
            alert('Failed to delete all notifications');
        }
    })
    .catch(error => {
        console.error('Error deleting all notifications:', error);
        alert('Error deleting all notifications');
    });
});

// Load offices when office staff broadcast modal is shown
var officeStaffBroadcastModal = document.getElementById('officeStaffBroadcastModal');
if (officeStaffBroadcastModal) {
    officeStaffBroadcastModal.addEventListener('show.bs.modal', function () {
        loadOfficesForBroadcast();
    });
}

// Load offices for broadcast
function loadOfficesForBroadcast() {
    const container = document.getElementById('officesContainer');
    
    fetch('{{ route('notifications.offices') }}')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.offices.length > 0) {
                let html = '<div class="row">';
                data.offices.forEach(office => {
                    html += `
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="offices[]" value="${office.code}" id="office_${office.code}">
                                <label class="form-check-label" for="office_${office.code}">
                                    ${office.name} (${office.code})
                                </label>
                            </div>
                        </div>`;
                });
                html += '</div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<div class="text-center text-muted">No offices found</div>';
            }
        })
        .catch(error => {
            console.error('Error loading offices:', error);
            container.innerHTML = '<div class="alert alert-danger">Error loading offices. Please try again.</div>';
        });
}

// Handle office staff broadcast form submission
document.getElementById('officeStaffBroadcastForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const feedback = document.getElementById('officeStaffBroadcastFeedback');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Get selected offices
    const selectedOffices = Array.from(form.querySelectorAll('input[name="offices[]"]:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedOffices.length === 0) {
        feedback.innerHTML = '<div class="alert alert-warning">Please select at least one office.</div>';
        return;
    }
    
    // Get form data
    const formData = new FormData(form);
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
    
    // Clear previous feedback
    feedback.innerHTML = '';
    
    fetch('{{ route('notifications.office_staff_broadcast') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            feedback.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            form.reset();
            
            // Close modal after 2 seconds
            setTimeout(() => {
                bootstrap.Modal.getInstance(officeStaffBroadcastModal).hide();
            }, 2000);
        } else {
            feedback.innerHTML = `<div class="alert alert-danger">Error: ${data.message || 'Failed to send notification'}</div>`;
        }
    })
    .catch(error => {
        console.error('Error sending notification:', error);
        feedback.innerHTML = '<div class="alert alert-danger">Error sending notification. Please try again.</div>';
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Send to Selected Offices';
    });
});

</script>

<!-- Staff Profile Modal -->
@include('staff.partials.profile_notification_modals')
@include('staff.partials.modal_scripts')

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Head Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles/officehead.css') }}">
</head>
<body>
  
    <!-- Desktop Sidebar -->
    <div class="sidebar d-none d-lg-block">
      <div class="d-flex justify-content-between align-items-center px-3 mb-3">
        <div class="d-flex align-items-center">
          <img src="{{ asset('images/SDU_Logo.png') }}" class="sidebar-logo" alt="SDU">
          <h5 class="m-0 text-white">SDU OFFICE HEAD</h5>
        </div>
        <button class="btn btn-toggle" type="button" onclick="document.body.classList.toggle('toggled')" style="color:#fff;border:none;background:transparent">
            <i class="fas fa-bars"></i>
        </button>
      </div>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link active" href="{{ route('office_head.dashboard') }}" ><i class="fas fa-chart-line me-2"></i><span> Dashboard</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('office_head.dashboard') }}?view=training-records" ><i class="fas fa-book-open me-2"></i><span> Training Records</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('office_head.dashboard') }}?view=office-directory" ><i class="fas fa-users me-2"></i><span> Office Directory</span></a></li>
        <li class="nav-item mt-auto"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i><span> Logout</span></a></li>
      </ul>
    </div>

    <div class="main-content">
        <button class="btn btn-primary d-lg-none mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <i class="fas fa-bars"></i> Menu
        </button>

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
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#officeNotificationModal">
                        <i class="fas fa-paper-plane me-2"></i>Send Notification
                    </button>
                </div>
            </div>
        </div>
        
        <div class="stats-cards">
            <div class="card"><h3>Trainings Completed</h3><p>{{ $head_trainings_completed }}</p></div>
            <div class="card"><h3>Upcoming Trainings</h3><p>{{ $head_trainings_upcoming }}</p></div>
            <div class="card"><h3>Staff in Office</h3><p>{{ $total_staff_in_office }}</p></div>
            <div class="card"><h3>Completed in Office</h3><p>{{ $completed_trainings_in_office }}</p></div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="content-box chart-card">
                    <h2>Training Status</h2>
                    <div class="chart-wrapper"><canvas id="trainingStatusChart" class="chart-canvas" height="220"></canvas></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="content-box">
                    <h2>Upcoming Trainings</h2>
                    <div class="table-responsive">
                        @if(count($result_head_upcoming_list) > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Nature</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result_head_upcoming_list as $training)
                                <tr>
                                    <td>{{ $training['title'] }}</td>
                                    <td>{{ $training['start_date'] }}</td>
                                    <td>{{ $training['end_date'] }}</td>
                                    <td>{{ $training['nature'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-muted">No upcoming trainings.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="content-box">
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
                                    <td>{{ $activity['title'] }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($activity['status'] == 'completed') bg-success
                                            @elseif($activity['status'] == 'upcoming') bg-warning
                                            @elseif($activity['status'] == 'ongoing') bg-info
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($activity['status']) }}
                                        </span>
                                    </td>
                                    <td>{{ $activity['start_date'] }}</td>
                                    <td>{{ $activity['end_date'] }}</td>
                                    <td>{{ date('M j, Y', strtotime($activity['created_at'])) }}</td>
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
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Office Notification Modal -->
        <div class="modal fade" id="officeNotificationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Send Notification to Office</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="officeNotificationForm">
                        <div class="modal-body">
                            @csrf
                            <div class="mb-3">
                                <label for="notifSubject" class="form-label">Subject (optional)</label>
                                <input type="text" class="form-control" id="notifSubject" name="subject" maxlength="255">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Audience</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="audience" id="audienceOffice" value="office_staff" checked>
                                        <label class="form-check-label" for="audienceOffice">Staff of Assigned Office</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="audience" id="audienceDirector" value="unit_director">
                                        <label class="form-check-label" for="audienceDirector">Unit Director</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="notifMessage" class="form-label">Message</label>
                                <textarea class="form-control" id="notifMessage" name="message" rows="4" required maxlength="1000"></textarea>
                            </div>
                            <div id="notifAlert" style="display:none;"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Training Status Chart
        const statusCtx = document.getElementById('trainingStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Ongoing'],
                datasets: [{
                    data: [{{ $training_completed }}, {{ $training_pending }}, {{ $training_overdue }}],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#17a2b8'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });

// Office notification form submit
    (function(){
        const form = document.getElementById('officeNotificationForm');
        const alertEl = document.getElementById('notifAlert');

        if (!form) return;

            form.addEventListener('submit', function(e){
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;

            const subject = document.getElementById('notifSubject').value;
            const message = document.getElementById('notifMessage').value;
            const audience = form.querySelector('input[name="audience"]:checked')?.value || 'office_staff';

            fetch("{{ route('notifications.office_broadcast') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ subject: subject, message: message, audience: audience })
            }).then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                if (data.success) {
                    alertEl.className = 'alert alert-success';
                    alertEl.innerText = data.message || 'Notification sent.';
                    alertEl.style.display = 'block';
                    // Close modal after short delay
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('officeNotificationModal'));
                        if (modal) modal.hide();
                        alertEl.style.display = 'none';
                        form.reset();
                    }, 900);
                } else {
                    alertEl.className = 'alert alert-danger';
                    alertEl.innerText = data.message || 'Failed to send notification.';
                    alertEl.style.display = 'block';
                }
            }).catch(err => {
                submitBtn.disabled = false;
                alertEl.className = 'alert alert-danger';
                alertEl.innerText = err?.message || 'An error occurred.';
                alertEl.style.display = 'block';
            });
        });
    })();
    
    </script>
</body>
</html>
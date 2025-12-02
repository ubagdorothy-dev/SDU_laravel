<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Head Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            display: flex;
            background-color: #f0f2f5;
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
            color: #ffffff;
            height: 100vh;
            position: fixed;
            padding-top: 2rem;
            transition: width 0.3s ease-in-out;
        }
        .sidebar .nav-link { color: white; padding: 12px 20px; border-radius: 5px; margin: 5px 15px; transition: background-color 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #3f51b5; }
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
        .content-box h2 {
            margin-top: 0;
            color: #1e293b;
            border-bottom: 3px solid #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 25px;
            font-weight: 700;
            font-size: 1.5rem;
        }
        /* Transparent sidebar toggle like admin */
        .sidebar .btn-toggle { background-color: transparent; border: none; color: #ffffff; padding: 6px 10px; }
        .sidebar .btn-toggle:focus { box-shadow: none; }
        .sidebar .btn-toggle:hover { background-color: transparent; }
        @media (min-width: 992px) {
            body.toggled .sidebar { width: 80px; }
            body.toggled .main-content { margin-left: 80px; }
            .sidebar .nav-link { transition: all 0.2s; }
            body.toggled .sidebar .nav-link { text-align: center; padding: 12px 0; }
            body.toggled .sidebar .nav-link i { margin-right: 0; }
            body.toggled .sidebar .nav-link span { display: none; }
            /* Hide header logo text when collapsed and tighten logo spacing */
            body.toggled .sidebar .logo-text { display: none; }
            body.toggled .sidebar h3 { display: none; }
            body.toggled .sidebar .sidebar-logo { margin-right: 0; }
        }
        .header h1 { 
            font-size: 2rem; 
            font-weight: 800; 
            margin-bottom: .25rem; 
            color: #1e293b;
        }
        .header p { 
            color: #6b7280; 
            font-size: .95rem; 
            margin: 0; 
        }

        .stats-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
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
            text-transform: uppercase; 
            letter-spacing: 1px; 
            font-weight: 600; 
        }
        .card p { 
            font-size: 2.5rem; 
            font-weight: 900; 
            margin: 0; 
            color: var(--card-color); 
        }
        .card:nth-child(1) { --card-color: #6366f1; --card-color-light: #a5b4fc; }
        .card:nth-child(2) { --card-color: #10b981; --card-color-light: #6ee7b7; }
        .card:nth-child(3) { --card-color: #f59e0b; --card-color-light: #fbbf24; }
        .card:nth-child(4) { --card-color: #8b5cf6; --card-color-light: #c4b5fd; }
        
        .sidebar-logo { height: 30px; width: auto; margin-right: 8px; }
        
        @media (max-width: 991.98px) { 
            .main-content { margin-left: 0 !important; } 
        }
        @media (max-width: 768px) { 
            .main-content { padding: 1rem; } 
            .stats-cards { grid-template-columns: 1fr; } 
        }
        
        /* Chart styling */
        .chart-card { padding: 1rem; }
        .chart-wrapper { overflow: hidden; max-height: 260px; }
        .chart-canvas { width: 100%; height: auto; display: block; }
        @media (min-width: 1200px) { .chart-wrapper { max-height: 320px; } }
        @media (max-width: 767px) { .chart-wrapper { max-height: 200px; } }
        
        /* Progress bar styling */
        .progress { 
            border-radius: 10px; 
            background-color: #e9ecef; 
        }
        .progress-bar { 
            border-radius: 10px; 
            background-color: #6366f1; 
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
    </style>
</head>
<body>
    <!-- Offcanvas Mobile -->
    <div class="offcanvas offcanvas-start bg-dark" tabindex="-1" id="offcanvasNavbar">
      <div class="offcanvas-header text-white">
        <h5 class="offcanvas-title">SDU Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link active" href="{{ route('office_head.dashboard') }}"><i class="fas fa-chart-line me-2"></i>Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('office_head.dashboard') }}?view=training-records"><i class="fas fa-book-open me-2"></i>Training Records</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('office_head.dashboard') }}?view=office-directory"><i class="fas fa-users me-2"></i>Office Directory</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
      </div>
    </div>

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
                    <h1 class="fw-bold mb-2" style="color: #1e293b;">Welcome {{ $user->full_name ?? 'Office Head' }}! </h1>
                    <p class="mb-0" style="color: #6b7280;">Here's what's happening in your office today.</p>
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
    </script>
</body>
</html>
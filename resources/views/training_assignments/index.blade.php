<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Training Assignments - SDU Unit Director</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/unitdirector/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/Training/index.css') }}">
</head>
<body id="body">

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
      <li class="nav-item"><a class="nav-link" href="{{ route('pending_approvals.index') }}"><i class="fas fa-clipboard-check me-2"></i>Pending Approvals <span class="badge bg-danger">{{ $pendingApprovalsCount ?? 0 }}</span></a></li>
    @endif
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('training_assignments.index') ? 'active' : '' }}" href="{{ route('training_assignments.index') }}"><i class="fas fa-tasks me-2"></i> <span> Training Assignments</span></a></li>
    <li class="nav-item mt-auto"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i><span> Logout</span></a></li>
  </ul>
</div>

<div class="main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Training Assignments</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('training_assignments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Assign Training
                    </a>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="modern-card">
                <div class="card-header header-gradient">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="mb-2 fw-bold text-primary"><i class="fas fa-tasks me-3"></i>Training Assignments</h2>
                            <p class="text-muted mb-0">Manage and track assigned training programs</p>
                        </div>
                        <div class="d-flex gap-3 flex-wrap">
                            <div class="bg-primary bg-opacity-10 px-4 py-3 rounded-pill d-flex align-items-center">
                                <i class="fas fa-list-check me-2 text-primary"></i>
                                <span class="fw-bold text-primary fs-5">{{ $assignments->count() }} Total</span>
                            </div>
                            <a href="{{ route('training_assignments.create') }}" class="btn btn-primary px-4 py-3 fw-bold">
                                <i class="fas fa-plus-circle me-2"></i> New Assignment
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-0">
                    @if($assignments->count() > 0)
                        <div class="table-container">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="25%">Training Program</th>
                                        <th width="20%">Staff Member</th>
                                        <th width="15%">Assigned By</th>
                                        <th width="15%">Assigned On</th>
                                        <th width="15%">Deadline</th>
                                        <th width="10%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignments as $assignment)
                                        <tr>
                                            <td>
                                                <div class="fw-medium data-highlight">{{ $assignment->training->title }}</div>
                                                @if($assignment->training->description)
                                                    <small class="text-muted d-block mt-1">{{ Str::limit($assignment->training->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-medium text-dark">{{ $assignment->staff->full_name ?? 'N/A' }}</div>
                                                @if($assignment->staff->office_code)
                                                    <small class="text-muted d-block mt-1"><i class="fas fa-building me-1"></i>{{ $assignment->staff->office_code }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-dark">{{ $assignment->assignedBy->full_name ?? 'N/A' }}</div>
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-user me-1"></i>{{ ucfirst($assignment->assignedBy->role ?? 'N/A') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="text-dark">{{ $assignment->assigned_date->format('M d, Y') }}</div>
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-calendar me-1"></i>{{ $assignment->assigned_date->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="text-dark">{{ $assignment->deadline->format('M d, Y') }}</div>
                                                @php
                                                    $daysUntilDeadline = now()->startOfDay()->diffInDays($assignment->deadline->startOfDay(), false);
                                                    $isUrgent = $daysUntilDeadline <= 3 && $daysUntilDeadline >= 0;
                                                    $isOverdue = $daysUntilDeadline < 0;
                                                @endphp
                                                @if($isOverdue)
                                                    <small class="text-danger d-block mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>{{ abs($daysUntilDeadline) }} days overdue
                                                    </small>
                                                @elseif($isUrgent)
                                                    <small class="text-warning d-block mt-1">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $daysUntilDeadline }} days left
                                                    </small>
                                                @else
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="fas fa-hourglass-half me-1"></i>{{ $daysUntilDeadline }} days left
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge status-badge rounded-pill 
                                                    @if($assignment->status == 'completed') bg-success
                                                    @elseif($assignment->status == 'pending') bg-warning text-dark
                                                    @else bg-danger
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
                        <div class="empty-state">
                            <div class="empty-state-icon mb-4">
                                <i class="fas fa-tasks fa-3x"></i>
                            </div>
                            <h5 class="mb-3">No Training Assignments Found</h5>
                            <p class="text-muted mb-4">Get started by assigning training programs to staff members. Create your first assignment to begin tracking progress.</p>
                            <a href="{{ route('training_assignments.create') }}" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-plus-circle me-2"></i> Create First Assignment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
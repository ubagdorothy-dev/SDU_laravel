<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assign Training - SDU Unit Director</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/unitdirector/dashboard.css') }}">
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
                <h1 class="h2">Assign Training</h1>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h5>Select Training and Staff</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('training_assignments.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="training_id" class="form-label">Select Training:</label>
                            <select class="form-select" id="training_id" name="training_id" required>
                                <option value="">Choose a training...</option>
                                @foreach($trainings as $training)
                                    <option value="{{ $training->id }}">{{ $training->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Select Staff Members:</label>
                            <div class="row">
                                @foreach($staff as $member)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="staff_ids[]" value="{{ $member->user_id }}" id="staff_{{ $member->user_id }}">
                                            <label class="form-check-label" for="staff_{{ $member->user_id }}">
                                                {{ $member->full_name ?? 'N/A' }} 
                                                @if($member->office_code)
                                                    ({{ $member->office_code }})
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="deadline" class="form-label">Deadline:</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Assign Training</button>
                        <a href="{{ route('training_assignments.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
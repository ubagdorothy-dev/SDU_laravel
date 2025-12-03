<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assign Training</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/unitdirector/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/Training/assignment.css') }}">

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
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="modern-card">
                <div class="card-header header-gradient">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="mb-2 fw-bold text-primary"><i class="fas fa-user-friends me-3"></i>Assign Training</h2>
                            <p class="text-muted mb-0">Assign training programs to staff members and office heads</p>
                        </div>
                        <div class="bg-primary bg-opacity-10 px-4 py-3 rounded-pill d-flex align-items-center">
                            <i class="fas fa-user-friends me-2 text-primary"></i>
                            <span class="fw-bold text-primary fs-5">New Assignment</span>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <form action="{{ route('training_assignments.store') }}" method="POST" id="assignmentForm">
                        @csrf
                        
                        <div class="form-group-spacing">
                            <label for="training_id" class="control-label">Select Training Program</label>
                            <div class="input-group">
                                <select class="form-select" id="training_id" name="training_id" required>
                                    <option value="">Choose a training program...</option>
                                    @foreach($trainings as $training)
                                        <option value="{{ $training->id }}">{{ $training->title }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-primary" type="button" id="addCustomTraining">
                                    <i class="fas fa-plus-circle"></i> Custom
                                </button>
                            </div>
                            <div class="custom-training-input" id="customTrainingContainer">
                                <input type="text" class="form-control mt-2" id="custom_training_title" name="custom_training_title" placeholder="Enter custom training title">
                                <div class="form-text text-muted mt-1">
                                    <i class="fas fa-info-circle me-1"></i> Create a new training program not in the list
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group-spacing">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="control-label mb-0">Select Staff Members and Office Heads</label>
                                <div class="d-flex gap-2">
                                    <div class="input-group input-group-sm" style="width: auto;">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search"></i></span>
                                        <select class="form-select border-start-0" id="officeFilter">
                                            <option value="all">All Offices</option>
                                            @foreach($staff as $officeCode => $members)
                                                @if($members->first() && $members->first()->office)
                                                    <option value="{{ $officeCode }}">{{ $members->first()->office->name }} ({{ $officeCode }})</option>
                                                @else
                                                    <option value="{{ $officeCode }}">{{ $officeCode }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllStaff">
                                        <i class="fas fa-check-double me-1"></i>Select All Visible
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="clearAllStaff">
                                        <i class="fas fa-ban me-1"></i>Clear Selection
                                    </button>
                                </div>
                            </div>
                            
                            <div class="staff-list-container bg-white">
                                <div id="staffList">
                                    @foreach($staff as $officeCode => $members)
                                        <div class="office-section" data-office="{{ $officeCode }}">
                                            <div class="section-header d-flex justify-content-between align-items-center mb-3 py-2">
                                                <h6 class="mb-0 fw-bold text-primary d-flex align-items-center">
                                                    @if($members->first() && $members->first()->office)
                                                        <i class="fas fa-building me-2"></i>{{ $members->first()->office->name }}
                                                    @else
                                                        <i class="fas fa-building me-2"></i>{{ $officeCode }}
                                                    @endif
                                                </h6>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ $members->count() }} staff</span>
                                                    <button type="button" class="btn btn-sm btn-outline-primary select-all-office" data-office="{{ $officeCode }}">
                                                        <i class="fas fa-check me-1"></i>Select All
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                @foreach($members as $member)
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="form-check highlight-on-hover">
                                                            <input class="form-check-input staff-checkbox" type="checkbox" name="staff_ids[]" value="{{ $member->user_id }}" id="staff_{{ $member->user_id }}" data-office="{{ $officeCode }}">
                                                            <label class="form-check-label d-block staff-checkbox-card" for="staff_{{ $member->user_id }}">
                                                                <div class="d-flex align-items-center mb-1">
                                                                    <div class="me-2">
                                                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                            <i class="fas fa-user text-primary"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <span class="fw-medium text-dark">{{ $member->full_name ?? 'N/A' }}</span>
                                                                    </div>
                                                                </div>
                                                                @if($member->office_code)
                                                                    <small class="d-block text-muted ms-1">
                                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $member->office_code }}
                                                                    </small>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group-spacing">
                            <label for="deadline" class="control-label">Completion Deadline</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="date" class="form-control" id="deadline_date" name="deadline_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="time" class="form-control" id="deadline_time" name="deadline_time" value="23:59">
                                </div>
                            </div>
                            <div class="form-text text-muted mt-1">
                                <i class="fas fa-calendar-alt me-1"></i> Select the deadline date and time for completing this training
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3 pt-2">
                            <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i> Assign Training
                            </button>
                            <a href="{{ route('training_assignments.index') }}" class="btn btn-light border px-4">
                                <i class="fas fa-arrow-left me-2"></i> Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Office filter functionality
        const officeFilter = document.getElementById('officeFilter');
        const officeSections = document.querySelectorAll('.office-section');
        
        officeFilter.addEventListener('change', function() {
            const selectedOffice = this.value;
            
            officeSections.forEach(section => {
                if (selectedOffice === 'all' || section.dataset.office === selectedOffice) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });
        
        // Select/Deselect all staff
        const selectAllBtn = document.getElementById('selectAllStaff');
        const clearAllBtn = document.getElementById('clearAllStaff');
        
        selectAllBtn.addEventListener('click', function() {
            const visibleCheckboxes = document.querySelectorAll('.staff-checkbox:not([disabled])');
            const allVisibleChecked = Array.from(visibleCheckboxes).every(checkbox => {
                const section = checkbox.closest('.office-section');
                return section && section.style.display !== 'none' && checkbox.checked;
            });
            
            visibleCheckboxes.forEach(checkbox => {
                // Only toggle visible checkboxes
                const section = checkbox.closest('.office-section');
                if (section && section.style.display !== 'none') {
                    checkbox.checked = !allVisibleChecked;
                    // Toggle selected class on card
                    const card = checkbox.nextElementSibling;
                    if (card && card.classList.contains('staff-checkbox-card')) {
                        card.classList.toggle('selected', !allVisibleChecked);
                    }
                }
            });
        });
        
        clearAllBtn.addEventListener('click', function() {
            const visibleCheckboxes = document.querySelectorAll('.staff-checkbox:not([disabled])');
            visibleCheckboxes.forEach(checkbox => {
                // Only uncheck visible checkboxes
                const section = checkbox.closest('.office-section');
                if (section && section.style.display !== 'none') {
                    checkbox.checked = false;
                    // Remove selected class from card
                    const card = checkbox.nextElementSibling;
                    if (card && card.classList.contains('staff-checkbox-card')) {
                        card.classList.remove('selected');
                    }
                }
            });
        });
        
        // Add click handler for staff cards
        document.querySelectorAll('.staff-checkbox-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Don't trigger if clicking on the checkbox itself
                if (e.target.type === 'checkbox') return;
                
                const checkbox = this.previousElementSibling;
                if (checkbox && checkbox.type === 'checkbox') {
                    checkbox.checked = !checkbox.checked;
                    this.classList.toggle('selected', checkbox.checked);
                }
            });
        });
        
        // Update card style when checkbox state changes
        document.querySelectorAll('.staff-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const card = this.nextElementSibling;
                if (card && card.classList.contains('staff-checkbox-card')) {
                    card.classList.toggle('selected', this.checked);
                }
            });
        });
        
        // Select all staff in specific office
        document.querySelectorAll('.select-all-office').forEach(button => {
            button.addEventListener('click', function() {
                const officeCode = this.getAttribute('data-office');
                const officeSection = document.querySelector(`.office-section[data-office="${officeCode}"]`);
                
                if (officeSection) {
                    const checkboxes = officeSection.querySelectorAll('.staff-checkbox:not(:disabled)');
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = !allChecked;
                        const card = checkbox.nextElementSibling;
                        if (card && card.classList.contains('staff-checkbox-card')) {
                            card.classList.toggle('selected', !allChecked);
                        }
                    });
                }
            });
        });
        
        // Custom training functionality
        const addCustomTrainingBtn = document.getElementById('addCustomTraining');
        const customTrainingContainer = document.getElementById('customTrainingContainer');
        const trainingSelect = document.getElementById('training_id');
        const customTrainingInput = document.getElementById('custom_training_title');
        
        addCustomTrainingBtn.addEventListener('click', function() {
            if (customTrainingContainer.style.display === 'none') {
                customTrainingContainer.style.display = 'block';
                trainingSelect.disabled = true;
                trainingSelect.required = false;
                customTrainingInput.required = true;
                this.innerHTML = '<i class="fas fa-list"></i> Select from List';
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');
            } else {
                customTrainingContainer.style.display = 'none';
                trainingSelect.disabled = false;
                trainingSelect.required = true;
                customTrainingInput.required = false;
                customTrainingInput.value = '';
                this.innerHTML = '<i class="fas fa-plus-circle"></i> Custom';
                this.classList.remove('btn-primary');
                this.classList.add('btn-outline-primary');
            }
        });
        
        // Form submission handling
        const assignmentForm = document.getElementById('assignmentForm');
        const submitBtn = document.getElementById('submitBtn');
        const deadlineDateInput = document.getElementById('deadline_date');
        const deadlineTimeInput = document.getElementById('deadline_time');
        
        assignmentForm.addEventListener('submit', function(e) {
            // Check if at least one staff member is selected
            const selectedStaff = document.querySelectorAll('input[name="staff_ids[]"]:checked');
            
            if (selectedStaff.length === 0) {
                e.preventDefault();
                alert('Please select at least one staff member.');
                return;
            }
            
            // Validate deadline inputs
            if (!deadlineDateInput.value) {
                e.preventDefault();
                alert('Please select a deadline date.');
                return;
            }
            
            // Combine date and time into a single datetime value
            const deadlineDateTime = deadlineDateInput.value + ' ' + (deadlineTimeInput.value || '23:59');
            // Create a hidden input to send the combined datetime
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'deadline';
            hiddenInput.value = deadlineDateTime;
            assignmentForm.appendChild(hiddenInput);
            
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Assigning...';
        });
    });
</script>
</body>
</html>
@extends('layouts.app')

@section('title', 'Edit Profile')

@section('styles')
<style>
    .profile-card {
        border-radius: 15px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
        margin-bottom: 1.5rem;
    }
    
    .profile-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        font-weight: 600;
        border-radius: 15px 15px 0 0 !important;
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.75rem;
        border: 1px solid #ced4da;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .btn-primary, .btn-secondary {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        margin-right: 0.5rem;
    }
    
    .alert-danger {
        border-radius: 8px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>SDU SYSTEM</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.dashboard') }}">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('training_records.index') }}">
                            <i class="fas fa-book-open"></i> Training Records
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('profile.show') }}">
                            <i class="fas fa-user-circle"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit Profile</h1>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="profile-card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Personal Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" id="profile-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <!-- Job Functions -->
                        <div class="mb-3">
                            <label for="job_function" class="form-label">Job Function</label>
                            <select class="form-select" id="job_function" name="job_function">
                                <option value="">Select Job Function</option>
                                <option value="Director/Office Head" {{ old('job_function', $staffDetail->job_function ?? '') == 'Director/Office Head' ? 'selected' : '' }}>Director/Office Head</option>
                                <option value="Program Officer" {{ old('job_function', $staffDetail->job_function ?? '') == 'Program Officer' ? 'selected' : '' }}>Program Officer</option>
                                <option value="Admin Officer" {{ old('job_function', $staffDetail->job_function ?? '') == 'Admin Officer' ? 'selected' : '' }}>Admin Officer</option>
                            </select>
                        </div>

                        <!-- Employment Status -->
                        <div class="mb-3">
                            <label for="employment_status" class="form-label">Employment Status</label>
                            <select class="form-select" id="employment_status" name="employment_status">
                                <option value="">Select Employment Status</option>
                                <option value="Regular or Permanent Employment" {{ old('employment_status', $staffDetail->employment_status ?? '') == 'Regular or Permanent Employment' ? 'selected' : '' }}>Regular or Permanent Employment</option>
                                <option value="Probationary Employment" {{ old('employment_status', $staffDetail->employment_status ?? '') == 'Probationary Employment' ? 'selected' : '' }}>Probationary Employment</option>
                                <option value="Contractual and Fixed-Term Employment" {{ old('employment_status', $staffDetail->employment_status ?? '') == 'Contractual and Fixed-Term Employment' ? 'selected' : '' }}>Contractual and Fixed-Term Employment</option>
                            </select>
                        </div>

                        <!-- Degree Attained -->
                        <div class="mb-3">
                            <label for="degree_attained" class="form-label">Degree Attained</label>
                            <select class="form-select" id="degree_attained" name="degree_attained">
                                <option value="">Select Degree</option>
                                <option value="Bachelors" {{ old('degree_attained', $staffDetail->degree_attained ?? '') == 'Bachelors' ? 'selected' : '' }}>Bachelors</option>
                                <option value="Masters" {{ old('degree_attained', $staffDetail->degree_attained ?? '') == 'Masters' ? 'selected' : '' }}>Master's</option>
                                <option value="Doctorate" {{ old('degree_attained', $staffDetail->degree_attained ?? '') == 'Doctorate' ? 'selected' : '' }}>Doctorate / PhD</option>
                                <option value="Other" {{ old('degree_attained', $staffDetail->degree_attained ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <!-- Other Degree Specification -->
                        <div class="mb-3" id="other_degree_group" style="display: none;">
                            <label for="degree_other" class="form-label">Please specify</label>
                            <input type="text" class="form-control" id="degree_other" name="degree_other" value="{{ old('degree_other', $staffDetail->degree_other ?? '') }}">
                        </div>

                        <!-- Office -->
                        <div class="mb-3">
                            <label for="office" class="form-label">Office</label>
                            <select class="form-select" id="office" name="office" disabled>
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->code }}" {{ old('office', $staffDetail->office ?? '') == $office->code ? 'selected' : '' }}>{{ $office->name }} ({{ $office->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Programs based on Office -->
                        <div class="mb-3">
                            <label for="program" class="form-label">Program</label>
                            <select class="form-select" id="program" name="program">
                                <option value="">Select Program</option>
                                <!-- Options will be populated by JavaScript based on office selection -->
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    // Program options based on office
    const programOptions = {
        'ACCA': [
            'Artejos program',
            'Aguila program (with 4 student led-organization teatro ateneo, ateneo blue vigors, ateneo concert band, ateneo glee club)'
        ],
        'ACES': [
            'ALERTO',
            'Inigo program',
            'Ecological solid waste management',
            'Adopt-a-watershed'
        ],
        'ACLG': [
            'Leadership and governance program',
            'Engaged citizenship & democracy building program'
        ],
        'ALTEC': [
            'SUGPAT PROGRAM',
            'Emerge program',
            'Teach anywhere program'
        ],
        'APC': [
            'Peace education program',
            'Peace advocacy program',
            'Interreligious dialogue program'
        ],
        'CCES': [
            'Health program',
            'Livelihood program',
            'Education program'
        ]
    };

    // Set the current office based on staff detail
    const currentOffice = "{{ $staffDetail->office ?? '' }}";
    
    // Populate programs based on office
    function populatePrograms(officeCode) {
        const programSelect = document.getElementById('program');
        programSelect.innerHTML = '<option value="">Select Program</option>';
        
        if (officeCode && programOptions[officeCode]) {
            programOptions[officeCode].forEach(function(program) {
                const option = document.createElement('option');
                option.value = program;
                option.text = program;
                // Check if this option should be selected
                if (program === "{{ old('program', $staffDetail->program ?? '') }}") {
                    option.selected = true;
                }
                programSelect.appendChild(option);
            });
        }
    }

    // Initialize programs on page load
    document.addEventListener('DOMContentLoaded', function() {
        if (currentOffice) {
            populatePrograms(currentOffice);
            // Set the selected program if it exists
            const programValue = "{{ old('program', $staffDetail->program ?? '') }}";
            if (programValue) {
                document.getElementById('program').value = programValue;
            }
        }

        // Handle degree attained dropdown change
        const degreeSelect = document.getElementById('degree_attained');
        const otherDegreeGroup = document.getElementById('other_degree_group');
        const otherDegreeInput = document.getElementById('degree_other');
        
        function toggleOtherDegreeField() {
            if (degreeSelect.value === 'Other') {
                otherDegreeGroup.style.display = 'block';
            } else {
                otherDegreeGroup.style.display = 'none';
                otherDegreeInput.value = '';
            }
        }
        
        degreeSelect.addEventListener('change', toggleOtherDegreeField);
        
        // Initialize on page load
        toggleOtherDegreeField();
    });
</script>
@endsection
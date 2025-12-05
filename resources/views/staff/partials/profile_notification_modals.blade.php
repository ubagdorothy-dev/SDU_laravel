<!-- Training Records Modal -->
<div class="modal fade" id="trainingRecordsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-book-reader me-2"></i>Your Training Records</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="trainingRecordsContent">
                <div class="text-center py-4">
                    <div class="spinner-border" role="status"></div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <small class="text-muted">Need more space? <a href="{{ route('staff.dashboard') }}?view=training-records">Open the full page</a></small>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Modal -->
<div class="modal fade" id="notificationsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-bell me-2"></i>Notifications</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="notificationsContent">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <button id="markAllReadBtn" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-check-double me-1"></i>Mark All Read
                        </button>
                        <button id="deleteAllBtn" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash-alt me-1"></i>Delete All
                        </button>
                    </div>
                    <div class="d-flex align-items-center">
                        <label for="notificationFilter" class="me-2">Filter:</label>
                        <select id="notificationFilter" class="form-select form-select-sm" style="width: auto;">
                            <option value="all">All Notifications</option>
                            <option value="unit_director">From Unit Director</option>
                            <option value="office_head">From Office Head</option>
                            <option value="system">System Notifications</option>
                        </select>
                    </div>
                </div>
                <div class="text-center py-4" id="notificationsLoader">
                    <div class="spinner-border" role="status"></div>
                </div>
                <div id="notificationsList" class="scrollable-notifications"></div>
                
                <!-- Sent Items Tab (for roles that can send notifications) -->
                <div id="sentItemsTab" style="display: none;">
                    <div class="text-center py-4" id="sentItemsLoader" style="display: none;">
                        <div class="spinner-border" role="status"></div>
                    </div>
                    <div id="sentItemsList" class="scrollable-notifications"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true" data-office-code="{{ $user->office_code ?? ($user->office->code ?? '') }}" data-program="{{ $user->staffDetail->program ?? '' }}" data-role="{{ $user->role ?? '' }}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-circle me-2"></i>
                    @if(in_array($user->role ?? '', ['unit_director', 'unit director']))
                        Profile Details
                    @else
                    Edit Profile
                    @endif
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <style>
                #profileModal .modal-content {
                    border-radius: 12px;
                    box-shadow: 0 10px 30px rgba(2, 0, 0, 0.15);
                    border: none;
                }
                
                #profileModal .modal-header {
                    border-top-left-radius: 12px;
                    border-top-right-radius: 12px;
                    background: linear-gradient(120deg, #2563eb 0%, #1e40af 100%);
                    border: none;
                }
                
                #profileModal .modal-title {
                    font-weight: 600;
                    font-size: 1.25rem;
                }
                
                #profileModal .form-label {
                    font-weight: 600;
                    margin-bottom: 0.35rem;
                    color: #374151;
                    font-size: 0.9rem;
                }
                
                #profileModal .form-control, #profileModal .form-select {
                    border-radius: 8px;
                    padding: 0.6rem 0.85rem;
                    font-size: 0.95rem;
                    border: 1px solid #d1d5db;
                    transition: all 0.2s ease;
                }
                
                #profileModal .form-control:focus, #profileModal .form-select:focus {
                    border-color: #3b82f6;
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
                    outline: none;
                }
                
                #profileModal .form-control:disabled, #profileModal .form-select:disabled {
                    background-color: #f9fafb;
                    cursor: not-allowed;
                }
                
                #profileModal .badge {
                    font-weight: 600;
                    font-size: 0.75rem;
                    padding: 0.3em 0.7em;
                    border-radius: 20px;
                }
                
                #profileModal .alert {
                    border-radius: 8px;
                    padding: 0.85rem 1.1rem;
                    font-size: 0.9rem;
                    border: none;
                }
                
                #profileModal .alert-info {
                    background-color: #dbeafe;
                    color: #1e40af;
                }
                
                #profileModal .readonly-field {
                    background-color: #f9fafb;
                    color: #6b7280;
                }
                
                #profileModal .profile-section {
                    background-color: #f8fafc;
                    border-radius: 10px;
                    padding: 1.25rem;
                    margin-bottom: 1rem;
                    border: 1px solid #e5e7eb;
                }
                
                #profileModal .section-title {
                    font-weight: 600;
                    color: #1f2937;
                    margin-bottom: 1rem;
                    padding-bottom: 0.5rem;
                    border-bottom: 1px solid #e5e7eb;
                }
                
                #profileModal .modal-footer {
                    background-color: #f9fafb;
                    border-top: 1px solid #e5e7eb;
                    padding: 1rem 1.5rem;
                }
                
                @media (max-width: 768px) {
                    #profileModal .modal-dialog {
                        margin: 1rem;
                        max-width: calc(100% - 2rem);
                    }
                    
                    #profileModal .profile-section {
                        padding: 1rem;
                    }
                }
            </style>
            <div class="modal-body">
                @if(in_array($user->role ?? '', ['unit_director', 'unit director']))
                    <div class="alert alert-info mb-4 py-3 px-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="alert-heading mb-1">Profile Management</h5>
                                <p class="mb-0">As a Unit Director, your profile information is managed by the system administrator. Contact your administrator if you need to update any information.</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <form id="profileEditForm" method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="profile-section">
                        <h5 class="section-title">Personal Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="profile_full_name" class="form-label mb-1">Full Name *</label>
                                    <input type="text" class="form-control @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) readonly-field @endif" id="profile_full_name" name="full_name" value="{{ $user->full_name ?? '' }}" required 
                                        @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) readonly @endif>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="profile_email" class="form-label mb-1">Email Address *</label>
                                    <input type="email" class="form-control @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) readonly-field @endif" id="profile_email" name="email" value="{{ $user->email ?? '' }}" required 
                                        @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) readonly @endif>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label mb-1">Role</label>
                                    <p class="mb-0">
                                        @if($user->role === 'staff')
                                            <span class="badge bg-info">Staff Member</span>
                                        @elseif($user->role === 'head')
                                            <span class="badge bg-success">Office Head</span>
                                        @elseif($user->role === 'unit_director' || $user->role === 'unit director')
                                            <span class="badge bg-warning text-dark">Unit Director</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($user->role ?? '') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            @if($user->role === 'head' && $user->office)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label mb-1">Office</label>
                                    <p class="mb-0">{{ $user->office->name }} ({{ $user->office->code }})</p>
                                </div>
                            </div>
                            @elseif(!empty($office_display))
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label mb-1">Office</label>
                                    <p class="mb-0">{{ $office_display }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="profile-section">
                        <h5 class="section-title">Professional Information</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="profile_job_function" class="form-label mb-1">Job Function *</label>
                                    @if($user->role === 'head')
                                        <!-- For Office Heads: Auto-assigned based on office, non-editable -->
                                        @php
                                            $autoAssignedJobFunction = 'Director/Office Head - ' . ($user->office->name ?? 'Unknown Office');
                                        @endphp
                                        <input type="text" class="form-control" id="profile_job_function" name="job_function" value="{{ $autoAssignedJobFunction }}" readonly>
                                        <input type="hidden" name="job_function" value="{{ $autoAssignedJobFunction }}">
                                        <small class="form-text text-muted">Job function is automatically assigned based on your office and cannot be changed.</small>
                                    @else
                                        <!-- For Staff: Choose between Program Officer and Admin Officer -->
                                        <select class="form-select @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) readonly-field @endif" id="profile_job_function" name="job_function" required 
                                            @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) disabled @endif>
                                            <option value="">Select Job Function</option>
                                            <option value="Program Officer" {{ old('job_function', $user->staffDetail->job_function ?? $user->job_function ?? '') == 'Program Officer' ? 'selected' : '' }}>Program Officer</option>
                                            <option value="Admin Officer" {{ old('job_function', $user->staffDetail->job_function ?? $user->job_function ?? '') == 'Admin Officer' ? 'selected' : '' }}>Admin Officer</option>
                                        </select>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="profile_employment_status" class="form-label mb-1">Employment Status *</label>
                                    @if($user->role === 'head')
                                        <!-- For Office Heads, show a readonly field -->
                                        <input type="text" class="form-control" value="Not Applicable for Office Heads" readonly>
                                        <input type="hidden" name="employment_status" value="">
                                    @else
                                    <select class="form-select @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) readonly-field @endif" id="profile_employment_status" name="employment_status" required
                                        @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) disabled @endif>
                                        <option value="">Select Employment Status</option>
                                        <option value="Regular or Permanent Employment" {{ old('employment_status', $user->staffDetail->employment_status ?? $user->employment_status ?? '') == 'Regular or Permanent Employment' ? 'selected' : '' }}>Regular or Permanent Employment</option>
                                        <option value="Probationary Employment" {{ old('employment_status', $user->staffDetail->employment_status ?? $user->employment_status ?? '') == 'Probationary Employment' ? 'selected' : '' }}>Probationary Employment</option>
                                        <option value="Contractual and Fixed-Term Employment" {{ old('employment_status', $user->staffDetail->employment_status ?? $user->employment_status ?? '') == 'Contractual and Fixed-Term Employment' ? 'selected' : '' }}>Contractual and Fixed-Term Employment</option>
                                    </select>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="profile_degree_attained" class="form-label mb-1">Degree Attained *</label>
                                    @if($user->role === 'head')
                                        <!-- For Office Heads, show a readonly field -->
                                        <input type="text" class="form-control" value="Not Applicable for Office Heads" readonly>
                                        <input type="hidden" name="degree_attained" value="">
                                    @else
                                    <select class="form-select @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) readonly-field @endif" id="profile_degree_attained" name="degree_attained" required
                                        @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) disabled @endif>
                                        <option value="">Select Degree</option>
                                        <option value="Bachelors" {{ old('degree_attained', $user->staffDetail->degree_attained ?? $user->degree_attained ?? '') == 'Bachelors' ? 'selected' : '' }}>Bachelors</option>
                                        <option value="Masters" {{ old('degree_attained', $user->staffDetail->degree_attained ?? $user->degree_attained ?? '') == 'Masters' ? 'selected' : '' }}>Master's</option>
                                        <option value="Doctorate" {{ old('degree_attained', $user->staffDetail->degree_attained ?? $user->degree_attained ?? '') == 'Doctorate' ? 'selected' : '' }}>Doctorate / PhD</option>
                                        <option value="Other" {{ old('degree_attained', $user->staffDetail->degree_attained ?? $user->degree_attained ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3" id="profile_other_degree_group" style="display: none;">
                                    <label for="profile_degree_other" class="form-label mb-1">Please specify *</label>
                                    @if($user->role === 'head')
                                        <!-- For Office Heads, show a readonly field -->
                                        <input type="text" class="form-control" value="Not Applicable for Office Heads" readonly>
                                        <input type="hidden" name="degree_other" value="">
                                    @else
                                    <input type="text" class="form-control @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) readonly-field @endif" id="profile_degree_other" name="degree_other" value="{{ old('degree_other', $user->staffDetail->degree_other ?? $user->degree_other ?? '') }}" 
                                        @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) readonly @endif required>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="profile_program" class="form-label mb-1">Program *</label>
                                    @if($user->role === 'head')
                                        <!-- For Office Heads, show a readonly field -->
                                        <input type="text" class="form-control" value="Not Applicable for Office Heads" readonly>
                                        <input type="hidden" name="program" value="">
                                    @else
                                    <select class="form-select @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) readonly-field @endif" id="profile_program" name="program" required
                                        @if(in_array($user->role ?? '', ['unit_director', 'unit director'])) disabled @endif>
                                        <option value="">Select Program</option>
                                        <option value="Undergraduate" {{ old('program', $user->staffDetail->program ?? $user->program ?? '') == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                        <option value="Graduate" {{ old('program', $user->staffDetail->program ?? $user->program ?? '') == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                        <option value="Post Graduate" {{ old('program', $user->staffDetail->program ?? $user->program ?? '') == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                        <option value="Non-Degree" {{ old('program', $user->staffDetail->program ?? $user->program ?? '') == 'Non-Degree' ? 'selected' : '' }}>Non-Degree</option>
                                    </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                @if(!in_array($user->role ?? '', ['unit_director', 'unit director']))
                    <button type="button" class="btn btn-primary" id="saveProfileBtn">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                @endif
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
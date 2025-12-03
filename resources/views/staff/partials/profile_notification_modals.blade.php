<!-- Training Records Modal -->
<div class="modal fade" id="trainingRecordsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
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
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
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
                <div id="notificationsList"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true" data-office-code="{{ $user->office_code ?? '' }}" data-program="{{ $user->staffDetail->program ?? '' }}">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-circle me-2"></i>Edit Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="profileEditForm" method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="profile_full_name" class="form-label fw-bold text-primary">Full Name *</label>
                                <input type="text" class="form-control" id="profile_full_name" name="full_name" value="{{ $user->full_name ?? '' }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="profile_email" class="form-label fw-bold text-primary">Email Address *</label>
                                <input type="email" class="form-control" id="profile_email" name="email" value="{{ $user->email ?? '' }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-primary">Role:</label>
                                <p class="mb-2">
                                    @if($user->role === 'staff')
                                        <span class="badge bg-info">Staff Member</span>
                                    @elseif($user->role === 'head')
                                        <span class="badge bg-success">Office Head</span>
                                    @elseif($user->role === 'unit_director' || $user->role === 'unit director')
                                        <span class="badge bg-warning">Unit Director</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($user->role ?? '') }}</span>
                                    @endif
                                </p>
                            </div>
                            
                            @if(!empty($office_display))
                            <div class="mb-3">
                                <label class="form-label fw-bold text-primary">Office:</label>
                                <p class="mb-2">{{ $office_display }}</p>
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            @if($user->staffDetail)
                            <div class="mb-3">
                                <label for="profile_job_function" class="form-label fw-bold text-primary">Job Function:</label>
                                <select class="form-select" id="profile_job_function" name="job_function">
                                    <option value="">Select Job Function</option>
                                    <option value="Director/Office Head" {{ old('job_function', $user->staffDetail->job_function ?? '') == 'Director/Office Head' ? 'selected' : '' }}>Director/Office Head</option>
                                    <option value="Program Officer" {{ old('job_function', $user->staffDetail->job_function ?? '') == 'Program Officer' ? 'selected' : '' }}>Program Officer</option>
                                    <option value="Admin Officer" {{ old('job_function', $user->staffDetail->job_function ?? '') == 'Admin Officer' ? 'selected' : '' }}>Admin Officer</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="profile_employment_status" class="form-label fw-bold text-primary">Employment Status:</label>
                                <select class="form-select" id="profile_employment_status" name="employment_status">
                                    <option value="">Select Employment Status</option>
                                    <option value="Regular or Permanent Employment" {{ old('employment_status', $user->staffDetail->employment_status ?? '') == 'Regular or Permanent Employment' ? 'selected' : '' }}>Regular or Permanent Employment</option>
                                    <option value="Probationary Employment" {{ old('employment_status', $user->staffDetail->employment_status ?? '') == 'Probationary Employment' ? 'selected' : '' }}>Probationary Employment</option>
                                    <option value="Contractual and Fixed-Term Employment" {{ old('employment_status', $user->staffDetail->employment_status ?? '') == 'Contractual and Fixed-Term Employment' ? 'selected' : '' }}>Contractual and Fixed-Term Employment</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="profile_degree_attained" class="form-label fw-bold text-primary">Degree Attained:</label>
                                <select class="form-select" id="profile_degree_attained" name="degree_attained">
                                    <option value="">Select Degree</option>
                                    <option value="Bachelors" {{ old('degree_attained', $user->staffDetail->degree_attained ?? '') == 'Bachelors' ? 'selected' : '' }}>Bachelors</option>
                                    <option value="Masters" {{ old('degree_attained', $user->staffDetail->degree_attained ?? '') == 'Masters' ? 'selected' : '' }}>Master's</option>
                                    <option value="Doctorate" {{ old('degree_attained', $user->staffDetail->degree_attained ?? '') == 'Doctorate' ? 'selected' : '' }}>Doctorate / PhD</option>
                                    <option value="Other" {{ old('degree_attained', $user->staffDetail->degree_attained ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            
                            <div class="mb-3" id="profile_other_degree_group" style="display: none;">
                                <label for="profile_degree_other" class="form-label fw-bold text-primary">Please specify:</label>
                                <input type="text" class="form-control" id="profile_degree_other" name="degree_other" value="{{ old('degree_other', $user->staffDetail->degree_other ?? '') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="profile_program" class="form-label fw-bold text-primary">Program:</label>
                                <select class="form-select" id="profile_program" name="program">
                                    <option value="">Select Program</option>
                                    <!-- Options will be populated by JavaScript -->
                                </select>
                            </div>
                            @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>No additional profile information available.
                            </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="saveProfileBtn">
                    <i class="fas fa-save me-1"></i> Save Changes
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
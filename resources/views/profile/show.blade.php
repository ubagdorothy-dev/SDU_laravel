@extends('layouts.app')

@section('title', 'Profile')

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
    
    .profile-info-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.25rem;
    }
    
    .profile-info-value {
        color: #212529;
        margin-bottom: 1rem;
    }
    
    .badge-success {
        background-color: #28a745;
    }
    
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    
    .btn-primary, .btn-secondary {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        margin-right: 0.5rem;
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
                <h1 class="h2">Profile</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="profile-card">
                        <div class="card-header">
                            <h5 class="mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="profile-info-label">Name:</div>
                            <div class="profile-info-value">{{ $user->full_name }}</div>
                            
                            <div class="profile-info-label">Email:</div>
                            <div class="profile-info-value">{{ $user->email }}</div>
                            
                            <div class="profile-info-label">Role:</div>
                            <div class="profile-info-value">{{ ucfirst($user->role) }}</div>
                            
                            <div class="profile-info-label">Office:</div>
                            <div class="profile-info-value">{{ $user->office ? $user->office->name : 'N/A' }}</div>
                            
                            <div class="profile-info-label">Account Status:</div>
                            <div class="profile-info-value">
                                @if($user->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending Approval</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                            <a href="{{ route('profile.change_password') }}" class="btn btn-secondary">Change Password</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="profile-card">
                        <div class="card-header">
                            <h5 class="mb-0">Professional Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="profile-info-label">Job Function:</div>
                            <div class="profile-info-value">{{ $user->staffDetail->job_function ?? 'N/A' }}</div>
                            
                            <div class="profile-info-label">Employment Status:</div>
                            <div class="profile-info-value">{{ $user->staffDetail->employment_status ?? 'N/A' }}</div>
                            
                            <div class="profile-info-label">Degree Attained:</div>
                            <div class="profile-info-value">{{ $user->staffDetail->degree_attained ?? 'N/A' }}</div>
                            
                            <div class="profile-info-label">Program:</div>
                            <div class="profile-info-value">{{ $user->staffDetail->program ?? 'N/A' }}</div>
                        </div>
                    </div>
                    
                    <div class="profile-card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Account Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="profile-info-label">Member Since:</div>
                            <div class="profile-info-value">{{ $user->created_at->format('M d, Y') }}</div>
                            
                            <div class="profile-info-label">Last Updated:</div>
                            <div class="profile-info-value">{{ $user->updated_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection
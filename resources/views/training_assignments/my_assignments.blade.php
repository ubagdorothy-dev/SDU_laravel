@extends('layouts.app')

@section('title', 'My Assigned Trainings')

@section('content')
<input type="checkbox" id="sidebar-toggle-checkbox" style="display: none;">

    <div class="sidebar">
        <div class="d-flex justify-content-between align-items-center px-3 mb-3">
            <div class="d-flex align-items-center">
                <img src="{{ asset('SDU_Logo.png') }}" alt="SDU Logo" class="sidebar-logo">
                <h5 class="m-0 text-white"><span class="logo-text">SDU STAFF</span></h5>
            </div>
            <label for="sidebar-toggle-checkbox" id="sidebar-toggle" class="btn btn-toggle"><i class="fas fa-bars"></i></label>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ !request()->has('view') || request()->get('view') === 'overview' ? 'active' : '' }}" href="{{ route('staff.dashboard') }}?view=overview">
                    <i class="fas fa-chart-line me-2"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ (request()->get('view') === 'training-records' || request()->get('view') === 'assigned-trainings') ? 'active' : '' }}" href="#trainingSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ (request()->get('view') === 'training-records' || request()->get('view') === 'assigned-trainings') ? 'true' : 'false' }}" aria-controls="trainingSubmenu">
                    <i class="fas fa-book-open me-2"></i> <span>Training</span>
                </a>
                <div class="collapse {{ (request()->get('view') === 'training-records' || request()->get('view') === 'assigned-trainings') ? 'show' : '' }}" id="trainingSubmenu">
                    <ul class="nav flex-column ms-4">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->get('view') === 'training-records' ? 'active' : '' }}" href="{{ route('staff.dashboard') }}?view=training-records">
                                <i class="fas fa-list me-2"></i> <span>My Records</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('training_assignments.my_assignments') ? 'active' : '' }}" href="{{ route('training_assignments.my_assignments') }}">
                                <i class="fas fa-tasks me-2"></i> <span>Assigned Trainings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->get('view') === 'uploaded-files' ? 'active' : '' }}" href="{{ route('staff.dashboard') }}?view=uploaded-files">
                                <i class="fas fa-file-upload me-2"></i> <span>Uploaded Files</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <ul class="nav flex-column mt-auto" style="padding-bottom: 20px;">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

<div class="main-content">
    <button class="btn btn-primary d-lg-none mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
        <i class="fas fa-bars"></i> Menu
    </button>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">My Assigned Trainings</h1>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h5>Trainings Assigned to Me</h5>
                </div>
                <div class="card-body">
                    @if($assignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Training</th>
                                        <th>Assigned By</th>
                                        <th>Assigned Date</th>
                                        <th>Deadline</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignments as $assignment)
                                        <tr>
                                            <td>{{ $assignment->training->title }}</td>
                                            <td>{{ $assignment->assignedBy->full_name ?? 'N/A' }}</td>
                                            <td>{{ $assignment->assigned_date->format('M d, Y') }}</td>
                                            <td>{{ $assignment->deadline->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($assignment->status == 'completed') bg-success
                                                    @elseif($assignment->status == 'pending') bg-warning text-dark
                                                    @elseif($assignment->status == 'overdue') bg-primary
                                                    @else bg-secondary
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
                        <div class="text-center py-5">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h5>No assigned trainings</h5>
                            <p class="text-muted">You don't have any trainings assigned to you right now.</p>
                        </div>
                    @endif
                </div>
            </div>
</div>
@endsection
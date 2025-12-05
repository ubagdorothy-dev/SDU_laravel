<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Unit Director Dashboard')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/unitdirector/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Training/proofs.css') }}">

    @yield('styles')
</head>
<body class="font-sans antialiased">

<input type="checkbox" id="sidebar-toggle-checkbox" style="display: none;">

<!-- Desktop Sidebar -->
<div class="sidebar-lg d-none d-lg-flex flex-column">
    <div class="sidebar-header d-flex justify-content-between align-items-center px-3 mb-3">
        <div class="d-flex align-items-center">
            <img src="{{ asset('SDU_Logo.png') }}" class="sidebar-logo" alt="SDU">
            <h5 class="m-0 text-white">SDU UNIT DIRECTOR</h5>
        </div>
        <label for="sidebar-toggle-checkbox" class="btn btn-toggle" style="color:#fff;border:none;background:transparent">
            <i class="fas fa-bars"></i>
        </label>
    </div>

    <div class="sidebar-content d-flex flex-column flex-grow-1">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-chart-line me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('directory_reports.index') ? 'active' : '' }}" href="{{ route('directory_reports.index') }}">
                    <i class="fas fa-users me-2"></i> Directory & Reports
                </a>
            </li>
            @if(auth()->check() && in_array(auth()->user()->role, ['unit director', 'unit_director']))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('pending_approvals.index') }}">
                    <i class="fas fa-clipboard-check me-2"></i> Pending Approvals
                    <span class="badge bg-danger">{{ $pendingApprovalsCount ?? 0 }}</span>
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('training_assignments.index') ? 'active' : '' }}" href="{{ route('training_assignments.index') }}">
                    <i class="fas fa-tasks me-2"></i> Training Assignments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('training_proofs.review_index') }}">
                    <i class="fas fa-file-alt me-2"></i> Review Training Proofs
                </a>
            </li>
        </ul>

        <ul class="nav flex-column sidebar-footer">
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                    <i class="fas fa-user-circle me-2"></i> Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" 
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">

    @yield('content')

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@yield('scripts')
</body>
</html>

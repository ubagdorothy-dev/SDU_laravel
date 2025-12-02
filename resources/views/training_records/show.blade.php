@extends('layouts.app')

@section('title', 'View Training Record')

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
                        <a class="nav-link" href="{{ route('profile.show') }}">
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

        <main role="main" class="col-md-10 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">View Training Record</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5>{{ $trainingRecord->title }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Description:</strong> {{ $trainingRecord->description ?? 'N/A' }}</p>
                            <p><strong>Venue:</strong> {{ $trainingRecord->venue ?? 'N/A' }}</p>
                            <p><strong>Nature:</strong> {{ $trainingRecord->nature ?? 'N/A' }}</p>
                            <p><strong>Scope:</strong> {{ $trainingRecord->scope ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Start Date:</strong> {{ $trainingRecord->start_date }}</p>
                            <p><strong>End Date:</strong> {{ $trainingRecord->end_date }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge 
                                    @if($trainingRecord->status == 'completed') badge-success
                                    @elseif($trainingRecord->status == 'upcoming') badge-warning
                                    @elseif($trainingRecord->status == 'ongoing') badge-info
                                    @else badge-secondary
                                    @endif">
                                    {{ ucfirst($trainingRecord->status) }}
                                </span>
                            </p>
                            <p><strong>Proof Uploaded:</strong> 
                                @if($trainingRecord->proof_uploaded)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-warning">No</span>
                                @endif
                            </p>
                            <p><strong>Created At:</strong> {{ $trainingRecord->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <!-- Proof Upload Section -->
                    @if($trainingRecord->status == 'completed' && !$trainingRecord->proof_uploaded)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Upload Proof of Completion</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('training_proofs.upload', $trainingRecord->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="proof_file">Proof Document (PDF, JPG, PNG - Max 2MB)</label>
                                    <input type="file" class="form-control-file" id="proof_file" name="proof_file" required>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Upload Proof</button>
                            </form>
                        </div>
                    </div>
                    @elseif($trainingRecord->proof_uploaded)
                    <div class="alert alert-info mt-4">
                        <h5>Proof of Completion Uploaded</h5>
                        <p>Your proof of completion has been uploaded and is pending review.</p>
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ route('training_records.edit', $trainingRecord->id) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('training_records.index') }}" class="btn btn-secondary">Back to List</a>
                        
                        <form method="POST" action="{{ route('training_records.destroy', $trainingRecord->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                        </form>
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
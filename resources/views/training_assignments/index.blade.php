@extends('layouts.app')

@section('title', 'Training Assignments')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('unit_director.sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Training Assignments</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('training_assignments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Assign Training
                    </a>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h5>All Training Assignments</h5>
                </div>
                <div class="card-body">
                    @if($assignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Training</th>
                                        <th>Staff Member</th>
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
                                            <td>{{ $assignment->staff->full_name ?? 'N/A' }}</td>
                                            <td>{{ $assignment->assignedBy->full_name ?? 'N/A' }}</td>
                                            <td>{{ $assignment->assigned_date->format('M d, Y') }}</td>
                                            <td>{{ $assignment->deadline->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($assignment->status == 'completed') bg-success
                                                    @elseif($assignment->status == 'pending') bg-warning
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
                        <div class="text-center py-5">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h5>No training assignments found</h5>
                            <p class="text-muted">Assign trainings to staff members to track their progress.</p>
                            <a href="{{ route('training_assignments.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-1"></i> Assign Training Now
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
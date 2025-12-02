@extends('layouts.app')

@section('title', 'Training Records')

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
                        <a class="nav-link active" href="{{ route('training_records.index') }}">
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
                <h1 class="h2">Training Records</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('training_records.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add New Record
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($trainingRecords->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5>All Training Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Nature of Training</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trainingRecords as $record)
                                <tr>
                                    <td>{{ $record->title }}</td>
                                    <td>{{ Str::limit($record->description, 50) }}</td>
                                    <td>{{ $record->nature_of_training }}</td>
                                    <td>{{ $record->start_date }}</td>
                                    <td>{{ $record->end_date }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($record->status == 'completed') badge-success
                                            @elseif($record->status == 'upcoming') badge-warning
                                            @elseif($record->status == 'ongoing') badge-info
                                            @else badge-secondary
                                            @endif">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('training_records.show', $record->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('training_records.edit', $record->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <!-- Delete button that triggers modal -->
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-training-id="{{ $record->id }}">Delete</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-info">
                <h4>No Training Records</h4>
                <p>You don't have any training records yet.</p>
                <a href="{{ route('training_records.create') }}" class="btn btn-primary">Add Your First Record</a>
            </div>
            @endif
        </main>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this training record? This action cannot be undone.</p>
                <input type="hidden" id="deleteTrainingId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle delete confirmation modal
    document.addEventListener('DOMContentLoaded', function() {
        var deleteModal = document.getElementById('deleteConfirmationModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var trainingId = button.getAttribute('data-training-id');
                var modal = this;
                modal.querySelector('#deleteTrainingId').value = trainingId;
            });

            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                var trainingId = document.getElementById('deleteTrainingId').value;
                
                // Make AJAX request to delete the training record
                fetch('/training_records/' + trainingId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Close modal and reload page
                        var modal = bootstrap.Modal.getInstance(deleteModal);
                        modal.hide();
                        window.location.reload();
                    } else {
                        throw new Error('Delete failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete training record. Please try again.');
                });
            });
        }
    });
</script>
@endsection
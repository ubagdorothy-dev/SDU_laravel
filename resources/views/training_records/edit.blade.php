@extends('layouts.app')

@section('title', 'Edit Training Record')

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
                <h1 class="h2">Edit Training Record</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Edit Training Record</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('training_records.update', $trainingRecord->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="title">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $trainingRecord->title) }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $trainingRecord->description) }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_date">Start Date *</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $trainingRecord->start_date) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_date">End Date *</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $trainingRecord->end_date) }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="venue">Venue *</label>
                            <input type="text" class="form-control" id="venue" name="venue" value="{{ old('venue', $trainingRecord->venue) }}" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nature_of_training">Nature of Training</label>
                                    <select class="form-control" id="nature_of_training" name="nature_of_training">
                                        <option value="">Select Nature of Training</option>
                                        <option value="Leadership training" {{ old('nature_of_training', $trainingRecord->nature_of_training) == 'Leadership training' ? 'selected' : '' }}>Leadership training</option>
                                        <option value="Technical skills training" {{ old('nature_of_training', $trainingRecord->nature_of_training) == 'Technical skills training' ? 'selected' : '' }}>Technical skills training</option>
                                        <option value="Management training" {{ old('nature_of_training', $trainingRecord->nature_of_training) == 'Management training' ? 'selected' : '' }}>Management training</option>
                                        <option value="Community development training" {{ old('nature_of_training', $trainingRecord->nature_of_training) == 'Community development training' ? 'selected' : '' }}>Community development training</option>
                                        <option value="Advocacy-related training" {{ old('nature_of_training', $trainingRecord->nature_of_training) == 'Advocacy-related training' ? 'selected' : '' }}>Advocacy-related training</option>
                                        <option value="Other" {{ old('nature_of_training', $trainingRecord->nature_of_training) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3" id="other_nature_group" style="display: none;">
                                    <label for="nature_of_training_other">Please specify</label>
                                    <input type="text" class="form-control" id="nature_of_training_other" name="nature_of_training_other" value="{{ old('nature_of_training_other', $trainingRecord->nature_of_training_other) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="scope">Scope *</label>
                                    <select class="form-control" id="scope" name="scope" required>
                                        <option value="">Select Scope</option>
                                        <option value="Local" {{ old('scope', $trainingRecord->scope) == 'Local' ? 'selected' : '' }}>Local</option>
                                        <option value="Regional" {{ old('scope', $trainingRecord->scope) == 'Regional' ? 'selected' : '' }}>Regional</option>
                                        <option value="National" {{ old('scope', $trainingRecord->scope) == 'National' ? 'selected' : '' }}>National</option>
                                        <option value="International" {{ old('scope', $trainingRecord->scope) == 'International' ? 'selected' : '' }}>International</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="status">Status *</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="upcoming" {{ old('status', $trainingRecord->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ old('status', $trainingRecord->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status', $trainingRecord->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Training Record</button>
                        <a href="{{ route('training_records.index') }}" class="btn btn-secondary">Cancel</a>
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
    document.addEventListener('DOMContentLoaded', function() {
        const natureSelect = document.getElementById('nature_of_training');
        const otherGroup = document.getElementById('other_nature_group');
        const otherInput = document.getElementById('nature_of_training_other');
        
        function toggleOtherField() {
            if (natureSelect.value === 'Other') {
                otherGroup.style.display = 'block';
            } else {
                otherGroup.style.display = 'none';
                otherInput.value = '';
            }
        }
        
        natureSelect.addEventListener('change', toggleOtherField);
        
        // Initialize on page load
        toggleOtherField();
    });
</script>
@endsection
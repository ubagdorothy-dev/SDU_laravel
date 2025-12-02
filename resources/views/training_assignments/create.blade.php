@extends('layouts.app')

@section('title', 'Assign Training')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('unit_director.sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Assign Training</h1>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h5>Select Training and Staff</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('training_assignments.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="training_id" class="form-label">Select Training:</label>
                            <select class="form-select" id="training_id" name="training_id" required>
                                <option value="">Choose a training...</option>
                                @foreach($trainings as $training)
                                    <option value="{{ $training->id }}">{{ $training->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Select Staff Members:</label>
                            <div class="row">
                                @foreach($staff as $member)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="staff_ids[]" value="{{ $member->user_id }}" id="staff_{{ $member->user_id }}">
                                            <label class="form-check-label" for="staff_{{ $member->user_id }}">
                                                {{ $member->full_name ?? 'N/A' }} 
                                                @if($member->office_code)
                                                    ({{ $member->office_code }})
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="deadline" class="form-label">Deadline:</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Assign Training</button>
                        <a href="{{ route('training_assignments.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Review Training Proof - SDU')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-9 col-lg-10 ml-sm-auto px-4 main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Review Training Proof</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('training_proofs.review_index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Training Proof Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Staff Member</h6>
                                    <p>{{ $trainingProof->user->full_name ?? 'N/A' }}</p>
                                    
                                    <h6>Email</h6>
                                    <p>{{ $trainingProof->user->email ?? 'N/A' }}</p>
                                    
                                    <h6>Office</h6>
                                    <p>{{ $trainingProof->user->office_code ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Training Title</h6>
                                    <p>{{ $trainingProof->trainingRecord->title ?? 'N/A' }}</p>
                                    
                                    <h6>Training Date</h6>
                                    <p>
                                        {{ $trainingProof->trainingRecord->start_date->format('M d, Y') }} 
                                        @if($trainingProof->trainingRecord->end_date)
                                            - {{ $trainingProof->trainingRecord->end_date->format('M d, Y') }}
                                        @endif
                                    </p>
                                    
                                    <h6>Submitted On</h6>
                                    <p>{{ $trainingProof->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Review Actions</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('training_proofs.process_review', $trainingProof->id) }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks (Optional)</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Add any remarks for the staff member..."></textarea>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" name="action" value="approve" class="btn btn-success">
                                        <i class="fas fa-check-circle me-1"></i> Approve
                                    </button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger">
                                        <i class="fas fa-times-circle me-1"></i> Reject
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Training Proof File</h5>
                        </div>
                        <div class="card-body text-center">
                            @if(pathinfo($trainingProof->file_path, PATHINFO_EXTENSION) == 'pdf')
                                <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                                <p>PDF Document</p>
                            @else
                                <i class="fas fa-file-image fa-4x text-info mb-3"></i>
                                <p>Image File</p>
                            @endif
                            
                            <a href="{{ route('training_proofs.view', $trainingProof->id) }}" target="_blank" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-eye me-1"></i> View File
                            </a>
                            
                            <a href="{{ route('training_proofs.view', ['id' => $trainingProof->id, 'download' => 1]) }}" class="btn btn-secondary w-100">
                                <i class="fas fa-download me-1"></i> Download File
                            </a>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Status Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Status:</span>
                                <span class="badge bg-warning">Pending Review</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Proof ID:</span>
                                <span>{{ $trainingProof->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
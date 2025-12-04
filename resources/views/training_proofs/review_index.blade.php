@extends('layouts.app')

@section('title', 'Review Training Proofs - SDU')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .review-header {
        background: linear-gradient(120deg, #3b82f6 0%, #1e40af 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 12px;
    }
    
    .review-card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: none;
        margin-bottom: 1.5rem;
    }
    
    .review-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
        padding: 1rem 1.5rem;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
    }
    
    .btn-refresh {
        background: linear-gradient(120deg, #3b82f6 0%, #1e40af 100%);
        border: none;
        color: white;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-refresh:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="review-header mb-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-0">Review Training Proofs</h1>
                    <p class="mb-0 text-muted">Review and approve/reject training proofs submitted by staff members</p>
                </div>
                <button class="btn btn-outline-light" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>
    
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if($pendingProofs->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h4>No Pending Training Proofs</h4>
                <p class="text-muted">There are currently no training proofs awaiting review.</p>
            </div>
        @else
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pending Training Proofs</h5>
                    <p class="text-muted mb-0">Review and approve/reject training proofs submitted by staff members</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Staff Member</th>
                                    <th>Training Title</th>
                                    <th>Office</th>
                                    <th>Submitted On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingProofs as $proof)
                                    <tr>
                                        <td>{{ $proof->user->full_name ?? 'N/A' }}</td>
                                        <td>{{ $proof->trainingRecord->title ?? 'N/A' }}</td>
                                        <td>{{ $proof->user->office_code ?? 'N/A' }}</td>
                                        <td>{{ $proof->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('training_proofs.review', $proof->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i> Review
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $pendingProofs->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
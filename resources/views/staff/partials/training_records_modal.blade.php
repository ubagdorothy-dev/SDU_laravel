<div class="modal fade" id="trainingRecordsModal" tabindex="-1" aria-labelledby="trainingRecordsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="trainingRecordsModalLabel">My Training Records</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="trainingRecordsContent">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainingModal">
                        <i class="fas fa-plus-circle me-1"></i> Add Training
                    </button>
                </div>
                
                @if (count($record_rows) > 0)
                    <table class="table table-striped mt-4">
                        <thead>
                            <tr>
                                <th scope="col">Training Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Venue</th>
                                <th scope="col">Nature of Training</th>
                                <th scope="col">Scope</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($record_rows as $row)
                                <tr>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->description }}</td>
                                    <td>{{ $row->start_date }}</td>
                                    <td>{{ $row->end_date }}</td>
                                    <td>{{ $row->venue ?? '' }}</td>
                                    <td>{{ $row->nature_of_training ?? '' }}</td>
                                    <td>{{ $row->scope ?? '' }}</td>
                                    <td>
                                        <span class="badge {{ $row->status === 'completed' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($row->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editTrainingModal"
                                                data-training-id="{{ $row->id }}"
                                                data-title="{{ $row->title }}"
                                                data-description="{{ $row->description }}"
                                                data-start-date="{{ $row->start_date }}"
                                                data-end-date="{{ $row->end_date }}"
                                                data-venue="{{ $row->venue ?? '' }}"
                                                data-nature="{{ $row->nature_of_training ?? '' }}"
                                                data-scope="{{ $row->scope ?? '' }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-training-id="{{ $row->id }}">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                            @if ($row->status === 'completed' && empty($row->proof_uploaded))
                                                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadProofModal" data-training-id="{{ $row->id }}"> 
                                                    <i class="fas fa-upload"></i> Upload Proof
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info mt-4" role="alert">
                        You have not completed any trainings yet.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
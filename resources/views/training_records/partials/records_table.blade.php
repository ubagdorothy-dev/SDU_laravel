<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
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
            @forelse($trainingRecords as $record)
                <tr>
                    <td>{{ $record->title }}</td>
                    <td>{{ Str::limit($record->description, 50) }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->start_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->end_date)->format('M d, Y') }}</td>
                    <td>{{ Str::limit($record->venue, 20) }}</td>
                    <td>{{ Str::limit($record->nature_of_training, 20) }}</td>
                    <td>{{ Str::limit($record->scope, 20) }}</td>
                    <td>
                        <span class="badge 
                            @if($record->status == 'completed') bg-success
                            @elseif($record->status == 'upcoming') bg-warning text-dark
                            @elseif($record->status == 'ongoing') bg-info text-dark
                            @else bg-secondary
                            @endif">
                            {{ ucfirst($record->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('training_records.show', $record->id) }}" class="btn btn-outline-info">View</a>
                            <a href="{{ route('training_records.edit', $record->id) }}" class="btn btn-outline-primary">Edit</a>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-training-id="{{ $record->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>No training records found.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
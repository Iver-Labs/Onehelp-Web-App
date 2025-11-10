@extends('layouts.admin-app')

@section('title', 'Manage Organizations')
@section('page-title', 'Organization Management')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-building me-2"></i>All Organizations</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Verified</th>
                        <th>Events</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizations as $org)
                        <tr>
                            <td>{{ $org->organization_id }}</td>
                            <td>{{ $org->organization_name }}</td>
                            <td>{{ $org->user->email ?? 'N/A' }}</td>
                            <td>{{ $org->organization_type ?? 'N/A' }}</td>
                            <td>
                                @if($org->is_verified)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $org->events->count() }} events</span>
                            </td>
                            <td>{{ $org->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('reports.organization.summary', $org->organization_id) }}" 
                                       class="btn btn-info" 
                                       title="Generate Report">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <button type="button" class="btn btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#orgModal{{ $org->organization_id }}"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal for organization details -->
                        <div class="modal fade" id="orgModal{{ $org->organization_id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ $org->organization_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Email:</strong> {{ $org->user->email ?? 'N/A' }}</p>
                                                <p><strong>Type:</strong> {{ $org->organization_type ?? 'N/A' }}</p>
                                                <p><strong>Registration Number:</strong> {{ $org->registration_number ?? 'N/A' }}</p>
                                                <p><strong>Phone:</strong> {{ $org->phone ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Address:</strong> {{ $org->address ?? 'N/A' }}</p>
                                                <p><strong>Website:</strong> {{ $org->website ?? 'N/A' }}</p>
                                                <p><strong>Verified:</strong> {{ $org->is_verified ? 'Yes' : 'No' }}</p>
                                                <p><strong>Joined:</strong> {{ $org->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        @if($org->description)
                                            <hr>
                                            <p><strong>Description:</strong></p>
                                            <p>{{ $org->description }}</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p>No organizations found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $organizations->links() }}
        </div>
    </div>
</div>
@endsection

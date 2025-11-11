@extends('layouts.admin-app')

@section('title', 'Organization Verifications')
@section('page-title', 'Organization Verifications')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Verification Requests</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Organization</th>
                        <th>Registration Number</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($verifications as $verification)
                        <tr>
                            <td>{{ $verification->verification_id }}</td>
                            <td>{{ $verification->organization->organization_name ?? 'N/A' }}</td>
                            <td>{{ $verification->registration_number ?? 'N/A' }}</td>
                            <td>
                                @if($verification->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($verification->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $verification->created_at->format('M d, Y') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#verificationModal{{ $verification->verification_id }}">
                                    <i class="fas fa-eye"></i> Review
                                </button>
                            </td>
                        </tr>

                        <!-- Modal for verification details -->
                        <div class="modal fade" id="verificationModal{{ $verification->verification_id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Verification Request - {{ $verification->organization->organization_name ?? 'N/A' }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.verifications.update', $verification->verification_id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <p><strong>Organization Name:</strong><br>
                                                       {{ $verification->organization->organization_name ?? 'N/A' }}
                                                    </p>
                                                    <p><strong>Email:</strong><br>
                                                       {{ $verification->organization->user->email ?? 'N/A' }}
                                                    </p>
                                                    <p><strong>Registration Number:</strong><br>
                                                       {{ $verification->registration_number ?? 'N/A' }}
                                                    </p>
                                                    <p><strong>Organization Type:</strong><br>
                                                       {{ $verification->organization->organization_type ?? 'N/A' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Phone:</strong><br>
                                                       {{ $verification->organization->phone ?? 'N/A' }}
                                                    </p>
                                                    <p><strong>Address:</strong><br>
                                                       {{ $verification->organization->address ?? 'N/A' }}
                                                    </p>
                                                    <p><strong>Website:</strong><br>
                                                       {{ $verification->organization->website ?? 'N/A' }}
                                                    </p>
                                                    <p><strong>Requested On:</strong><br>
                                                       {{ $verification->created_at->format('F d, Y') }}
                                                    </p>
                                                </div>
                                            </div>

                                            @if($verification->organization->description)
                                                <div class="mb-3">
                                                    <strong>Description:</strong>
                                                    <p>{{ $verification->organization->description }}</p>
                                                </div>
                                            @endif

                                            @if($verification->documents_url)
                                                <div class="mb-3">
                                                    <strong>Documents:</strong><br>
                                                    <a href="{{ $verification->documents_url }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-file-download"></i> View Documents
                                                    </a>
                                                </div>
                                            @endif

                                            <hr>

                                            <div class="mb-3">
                                                <label for="status" class="form-label"><strong>Decision:</strong></label>
                                                <select name="status" id="status" class="form-select" required>
                                                    <option value="pending" {{ $verification->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="approved" {{ $verification->status === 'approved' ? 'selected' : '' }}>Approve</option>
                                                    <option value="rejected" {{ $verification->status === 'rejected' ? 'selected' : '' }}>Reject</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="admin_notes" class="form-label">Admin Notes (Optional):</label>
                                                <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" 
                                                          placeholder="Add any notes about this verification...">{{ $verification->admin_notes }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Save Decision
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p>No verification requests found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $verifications->links() }}
        </div>
    </div>
</div>
@endsection

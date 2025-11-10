@extends('layouts.admin-app')

@section('title', 'Manage Users')
@section('page-title', 'User Management')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-users me-2"></i>All Users</h5>
        <a href="{{ route('reports.export.users') }}" class="btn btn-sm btn-success">
            <i class="fas fa-file-excel"></i> Export to Excel
        </a>
    </div>
    <div class="card-body">
        <!-- Filter and Search -->
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All User Types</option>
                        <option value="volunteer" {{ request('type') == 'volunteer' ? 'selected' : '' }}>Volunteers</option>
                        <option value="organization" {{ request('type') == 'organization' ? 'selected' : '' }}>Organizations</option>
                        <option value="admin" {{ request('type') == 'admin' ? 'selected' : '' }}>Admins</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by email or name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Verified</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->user_id }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->user_type === 'volunteer' && $user->volunteer)
                                    {{ $user->volunteer->first_name }} {{ $user->volunteer->last_name }}
                                @elseif($user->user_type === 'organization' && $user->organization)
                                    {{ $user->organization->organization_name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->user_type === 'admin' ? 'danger' : ($user->user_type === 'organization' ? 'primary' : 'success') }}">
                                    {{ ucfirst($user->user_type) }}
                                </span>
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_verified)
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger"></i>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user->user_id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }}" 
                                                title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>
                                    
                                    @if($user->user_id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.delete', $user->user_id) }}" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p>No users found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

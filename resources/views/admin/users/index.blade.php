@extends('layouts.app')
@section('title', 'Users Management')

@section('sidebar')
    <span class="sidebar-label">Main</span>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <span class="sidebar-label">Management</span>
    <a href="#" class="sidebar-link">
        <i class="bi bi-hospital"></i> Hospitals
        <span class="s-badge warn">{{ $pendingHospitals }}</span>
    </a>
    <a href="#" class="sidebar-link">
        <i class="bi bi-droplet-half"></i> Blood Units
    </a>
    <a href="#" class="sidebar-link">
        <i class="bi bi-clipboard-pulse"></i> Blood Requests
        <span class="s-badge">{{ $pendingRequests }}</span>
    </a>
    <a href="#" class="sidebar-link">
        <i class="bi bi-people"></i> Donors
    </a>
    <a href="{{ route('roles.index') }}" class="sidebar-link">
        <i class="bi bi-people"></i> Roles & Permissions
    </a>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link active">
        <i class="bi bi-person-lines-fill"></i> Users
    </a>

    <div class="sidebar-divider"></div>

    <span class="sidebar-label">Reports</span>
    <a href="#" class="sidebar-link">
        <i class="bi bi-bar-chart-line"></i> Analytics
    </a>
    <a href="#" class="sidebar-link">
        <i class="bi bi-file-earmark-text"></i> Reports
    </a>

    <div class="sidebar-divider"></div>

    <span class="sidebar-label">System</span>
    <a href="#" class="sidebar-link">
        <i class="bi bi-gear"></i> Settings
    </a>
@endsection

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Users</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h4>Users Management</h4>
            <p class="text-muted mb-0">Manage user accounts and assign roles.</p>
        </div>
        <div class="d-flex gap-2">
            <input type="text" id="searchUsers" class="form-control form-control-sm" placeholder="Search users..." style="width:250px">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Add User
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User Info</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="fw-600">{{ $user->name }}</div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </td>
                                <td>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles->take(2) as $role)
                                            <span class="badge bg-primary rounded-pill me-1 mb-1">{{ $role->name }}</span>
                                        @endforeach
                                        @if($user->roles->count() > 2)
                                            <span class="badge bg-secondary rounded-pill">{{ $user->roles->count() - 2 }} more...</span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-dark rounded-pill">No Role</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">{{ $user->created_at->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-success border-0 p-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger border-0 p-1" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top bg-light">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-person-plus display-1 opacity-25 d-block mb-3"></i>
                <div class="h5 mb-1">No users found</div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary mt-2">Create first user</a>
            </div>
        @endif
    </div>
</div>

<script>
document.getElementById('searchUsers').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
});
</script>
@endsection


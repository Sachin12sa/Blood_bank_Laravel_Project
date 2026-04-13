@extends('layouts.app')

@section('title', 'Roles')
@section('sidebar')
    <span class="sidebar-label">Main</span>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link active">
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

    <div class="sidebar-divider"></div>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link">
        <i class="bi bi-person-lines-fill"></i> Users
    </a>

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
            <li class="breadcrumb-item active">Roles</li>
        </ol>
    </nav>
    <h4>Roles Management</h4>
    <p class="text-muted">View and manage user roles and permissions.</p>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pb-0">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="mb-1 fw-700">{{ $roles->count() }} Roles</h6>
                <small class="text-muted">Showing all available roles</small>
            </div>
            <div class="d-flex gap-2">
                <input type="text" id="searchRoles" class="form-control form-control-sm" placeholder="Search roles..." style="width:250px">
                <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Add Role
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        {{-- <th>Guard</th> --}}
                        <th>Users</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-600">{{ $role->name }}</div>
                            {{-- <small class="text-muted">{{ $role->description ?? 'No description' }}</small> --}}
                        </td>
                        <td>
                            @if($role->permissions->count() > 0)
                                <div class="mb-1">
                                    @foreach($role->permissions->take(3) as $perm)
                                        <span class="badge bg-light text-dark border rounded-pill me-1 mb-1 fs-2xs">{{ $perm->name }}</span>
                                    @endforeach
                                    @if($role->permissions->count() > 3)
                                        <span class="badge bg-secondary rounded-pill fs-2xs">{{ $role->permissions->count() - 3 }} more...</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted fs-2xs">No permissions</span>
                            @endif
                        </td>
                        {{-- <td>
                            <span class="badge bg-{{ $role->guard_name == 'web' ? 'primary' : 'secondary' }} rounded-pill">{{ $role->guard_name }}</span>
                        </td> --}}
                        <td>
                            <span class="fw-600">{{ $role->users_count }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $role->created_at->format('M d, Y') }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                {{-- <a href="{{ route('roles.show', $role) }}" class="btn btn-outline-primary border-0 p-1" title="View">
                                    <i class="bi bi-eye"></i>
                                </a> --}}
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-success border-0 p-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger border-0 p-1" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox display-1 opacity-25 d-block mb-3"></i>
                            <div>No roles found</div>
                            <a href="{{ route('roles.create') }}" class="btn btn-primary mt-2">Create first role</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchRoles');
    const rows = document.querySelectorAll('tbody tr:not(.empty-state)');

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
});
</script>
@endsection

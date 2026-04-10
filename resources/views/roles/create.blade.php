@extends('layouts.app')

@section('title', 'Create Role')

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
    <a href="{{ route('admin.users.index') }}" class="sidebar-link">
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
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active">Create Role</li>
        </ol>
    </nav>
    <h4>Create New Role</h4>
    <p class="text-muted">Define role name, description, and assign permissions.</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="mb-0 fw-600">Role Information</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label fw-600">Role Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="mb-4">
                        <label for="description" class="form-label fw-600">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Optional role description...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    <div class="mb-4">
                        <label class="form-label fw-600">Permissions</label>
                        <div class="card border p-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">Select permissions to assign to this role</small>
                                <div class="position-relative">
                                    <input type="text" id="permissionSearch" class="form-control form-control-sm" placeholder="Search permissions..." style="width:200px">
                                </div>
                            </div>
                            <div class="row" id="permissionsList">
                                @php $permissions = \Spatie\Permission\Models\Permission::all(); @endphp
                                @foreach($permissions as $permission)
                                    <div class="col-sm-6 col-md-4 col-lg-3 mb-2 permission-item" data-name="{{ strtolower($permission->name) }}">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('permissions')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">No permissions selected (0)</small>
                            <span id="selectedCount">0</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-check-lg"></i> Create Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('permissionSearch');
    const permissionItems = document.querySelectorAll('.permission-item');
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    const selectedCount = document.getElementById('selectedCount');

    function updateCount() {
        const count = document.querySelectorAll('.permission-checkbox:checked').length;
        selectedCount.textContent = count;
    }

    // Search
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        permissionItems.forEach(item => {
            const name = item.dataset.name;
            item.style.display = name.includes(query) || query === '' ? '' : 'none';
        });
    });

    // Count & select all logic
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateCount);
    });
    updateCount();
});
</script>
@endsection


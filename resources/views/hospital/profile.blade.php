@extends('layouts.app')
@section('title', 'Hospital Profile')

@section('sidebar')
    <span class="sidebar-label">My Hospital</span>
    <a href="{{ route('hospital.dashboard') }}" class="sidebar-link">
        <i class="bi bi-house-fill"></i> Dashboard
    </a>
    <a href="{{ route('hospital.profile') }}" class="sidebar-link active">
        <i class="bi bi-building"></i> Hospital Profile
    </a>
    <div class="sidebar-divider"></div>
    <span class="sidebar-label">Blood Requests</span>
    <a href="{{ route('hospital.requests.create') }}" class="sidebar-link">
        <i class="bi bi-plus-circle-fill"></i> New Blood Request
    </a>
    <a href="{{ route('hospital.requests.index') }}" class="sidebar-link">
        <i class="bi bi-clipboard-pulse"></i> My Requests
    </a>
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('hospital.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Hospital Profile</li>
            </ol>
        </nav>
        <h4>Hospital Profile</h4>
    </div>

    <div class="card border-0">
        <div class="card-body p-4">
            <form action="{{ route('hospital.profile.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Hospital Name</label>
                        <input type="text" name="hospital_name" class="form-control" value="{{ old('hospital_name', $hospital->hospital_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">License Number</label>
                        <input type="text" class="form-control" value="{{ $hospital->license_number }}" disabled>
                        <small class="text-muted">License cannot be changed.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $hospital->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <input type="text" class="form-control" value="{{ ucfirst($hospital->status) }}" disabled>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $hospital->address) }}">
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn" style="background:var(--red);color:#fff;border-radius:8px">
                        <i class="bi bi-check-lg me-1"></i> Save Changes
                    </button>
                    <a href="{{ route('hospital.dashboard') }}" class="btn btn-outline-secondary" style="border-radius:8px">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.app')
@section('title', 'Hospital Dashboard')

@section('sidebar')
    <span class="sidebar-label">My Hospital</span>
    <a href="{{ route('hospital.dashboard') }}" class="sidebar-link active">
        <i class="bi bi-house-fill"></i> Dashboard
    </a>
    <a href="#" class="sidebar-link">
        <i class="bi bi-plus-circle-fill"></i> New Blood Request
    </a>
    <a href="#" class="sidebar-link">
        <i class="bi bi-clipboard-pulse"></i> My Requests
    </a>
    <div class="sidebar-divider"></div>
    <span class="sidebar-label">Account</span>
    <a href="#" class="sidebar-link">
        <i class="bi bi-building"></i> Hospital Profile
    </a>
@endsection

@section('content')

    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
        <h4>{{ $hospital->hospital_name ?? 'Hospital Dashboard' }}</h4>
        <p>Manage your blood requests and track fulfillment status.</p>
    </div>

    {{-- Approval Status --}}
    @if ($hospital)
        @if ($hospital->status === 'pending')
            <div class="alert alert-warning d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-hourglass-split fs-5"></i>
                <div>
                    <strong>Pending Approval</strong> — Your hospital registration is under review by
                    the admin. You'll be notified once approved.
                </div>
            </div>
        @elseif($hospital->status === 'rejected')
            <div class="alert alert-danger d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-x-octagon-fill fs-5"></i>
                <div>
                    <strong>Registration Rejected</strong> — Please contact the admin for more details.
                </div>
            </div>
        @else
            <div class="alert alert-success d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>
                    <strong>Hospital Approved</strong> — You can now submit blood requests.
                </div>
                <a href="#" class="btn btn-sm ms-auto"
                    style="background:var(--red);color:#fff;border-radius:8px;white-space:nowrap">
                    <i class="bi bi-plus-lg me-1"></i> New Request
                </a>
            </div>

            {{-- Stats --}}
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="bi bi-clipboard-data"></i></div>
                        <div class="stat-value">0</div>
                        <div class="stat-label">Total Requests</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon orange"><i class="bi bi-hourglass"></i></div>
                        <div class="stat-value">0</div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="bi bi-truck"></i></div>
                        <div class="stat-value">0</div>
                        <div class="stat-label">Dispatched</div>
                    </div>
                </div>
            </div>
        @endif
    @endif

@endsection

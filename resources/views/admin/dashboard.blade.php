@extends('layouts.app')
@section('title', 'Admin Dashboard')

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

    {{-- Page Header --}}
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
        <h4>Admin Dashboard</h4>
        <p>Welcome back. Here's what's happening today.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon red"><i class="bi bi-heart-pulse-fill"></i></div>
                <div class="stat-value">{{ $totalDonors }}</div>
                <div class="stat-label">Total Donors</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-hospital-fill"></i></div>
                <div class="stat-value">{{ $totalHospitals }}</div>
                <div class="stat-label">Approved Hospitals</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-value">{{ $pendingHospitals }}</div>
                <div class="stat-label">Pending Approval</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-clipboard-check"></i></div>
                <div class="stat-value">{{ $pendingRequests }}</div>
                <div class="stat-label">Pending Requests</div>
            </div>
        </div>
    </div>

    {{-- Blood Inventory Grid --}}
    <div class="card border-0 mb-4">
        <div class="card-body p-0">
            <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                <div>
                    <h6 class="mb-0 fw-700" style="font-weight:700">Blood Inventory</h6>
                    <small class="text-muted">Current stock per blood group</small>
                </div>
                <a href="#" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                    <i class="bi bi-plus-lg me-1"></i> Add Units
                </a>
            </div>
            <div class="row g-0">
                @foreach ($inventory as $inv)
                    @php
                        $pct =
                            $inv->total_units > 0
                                ? min(100, round(($inv->available_units / max($inv->total_units, 1)) * 100))
                                : 0;
                        $color = $inv->isBelowThreshold() ? '#e02020' : ($pct > 60 ? '#22c55e' : '#f59e0b');
                    @endphp
                    <div class="col-6 col-md-3 p-3 border-end border-bottom">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge-blood">{{ $inv->blood_group }}</span>
                            @if ($inv->isBelowThreshold())
                                <span class="badge rounded-pill" style="background:#fef2f2;color:#e02020;font-size:.7rem">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Low
                                </span>
                            @endif
                        </div>
                        <div style="font-size:1.6rem;font-weight:800;letter-spacing:-.5px">
                            {{ $inv->available_units }}
                            <span style="font-size:.8rem;font-weight:500;color:var(--gray-500)">units</span>
                        </div>
                        <div class="progress mt-2" style="height:5px;border-radius:10px;background:var(--gray-200)">
                            <div class="progress-bar" role="progressbar"
                                style="width:{{ $pct }}%;background:{{ $color }};border-radius:10px">
                            </div>
                        </div>
                        <div style="font-size:.7rem;color:var(--gray-500);margin-top:4px">
                            Threshold: {{ $inv->threshold }} units
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection

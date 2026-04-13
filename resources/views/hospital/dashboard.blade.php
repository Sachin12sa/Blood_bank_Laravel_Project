@extends('layouts.app')
@section('title', 'Hospital Dashboard')

@section('sidebar')
    <span class="sidebar-label">My Hospital</span>
    <a href="{{ route('hospital.dashboard') }}" class="sidebar-link active">
        <i class="bi bi-house-fill"></i> Dashboard
    </a>
    <a href="{{ route('hospital.profile') }}" class="sidebar-link">
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
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
        <h4>{{ $hospital->hospital_name ?? 'Hospital Dashboard' }}</h4>
        <p>Manage your blood requests and track fulfillment status.</p>
    </div>

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
                <a href="{{ route('hospital.requests.create') }}" class="btn btn-sm ms-auto"
                    style="background:var(--red);color:#fff;border-radius:8px;white-space:nowrap">
                    <i class="bi bi-plus-lg me-1"></i> New Request
                </a>
            </div>

            {{-- Stats --}}
            @if($stats)
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="bi bi-clipboard-data"></i></div>
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Requests</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon orange"><i class="bi bi-hourglass"></i></div>
                        <div class="stat-value">{{ $stats['pending'] }}</div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="bi bi-truck"></i></div>
                        <div class="stat-value">{{ $stats['dispatched'] }}</div>
                        <div class="stat-label">Dispatched</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon cyan"><i class="bi bi-check2-square"></i></div>
                        <div class="stat-value">{{ $stats['received'] ?? 0 }}</div>
                        <div class="stat-label">Received</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon red"><i class="bi bi-x-circle"></i></div>
                        <div class="stat-value">{{ $stats['rejected'] }}</div>
                        <div class="stat-label">Rejected</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Recent Requests --}}
            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Recent Requests</h6>
                        <a href="{{ route('hospital.requests.index') }}" class="btn btn-sm btn-outline-danger rounded-pill">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>ID</th><th>Urgency</th><th>Status</th><th>Date</th><th></th></tr></thead>
                            <tbody>
                                @forelse($recentRequests as $r)
                                <tr>
                                    <td>#{{ $r->id }}</td>
                                    <td>
                                        @if($r->urgency === 'critical')
                                            <span class="badge rounded-pill" style="background:#fef2f2;color:#e02020;font-size:.72rem">Critical</span>
                                        @else
                                            <span class="badge rounded-pill" style="background:#f0fdf4;color:#22c55e;font-size:.72rem">Normal</span>
                                        @endif
                                    </td>
                                    <td>
@php
                                            $sc = ['pending' => ['#fffbeb','#b45309'], 'approved' => ['#eff6ff','#3b82f6'], 'dispatched' => ['#f0fdf4','#22c55e'], 'received' => ['#f0f9ff','#0ea5e9'], 'rejected' => ['#fef2f2','#e02020']];
                                            $c = $sc[$r->status] ?? ['#f1f3f7','#3a3f52'];
                                        @endphp
                                        <span class="badge rounded-pill" style="background:{{ $c[0] }};color:{{ $c[1] }};font-size:.72rem">{{ ucfirst($r->status) }}</span>
                                    </td>
                                    <td>{{ $r->created_at->format('M d, Y') }}</td>
                                    <td><a href="{{ route('hospital.requests.show', $r) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:6px"><i class="bi bi-eye"></i></a></td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">No requests yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endsection

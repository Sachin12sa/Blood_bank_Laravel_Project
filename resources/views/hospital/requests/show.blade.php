@extends('layouts.app')
@section('title', 'Request Details')

@section('sidebar')
    <span class="sidebar-label">My Hospital</span>
    <a href="{{ route('hospital.dashboard') }}" class="sidebar-link">
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
    <a href="{{ route('hospital.requests.index') }}" class="sidebar-link active">
        <i class="bi bi-clipboard-pulse"></i> My Requests
    </a>
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('hospital.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hospital.requests.index') }}">My Requests</a></li>
                <li class="breadcrumb-item active">Request #{{ $bloodRequest->id }}</li>
            </ol>
        </nav>
        <h4>Request #{{ $bloodRequest->id }}</h4>
    </div>

    {{-- Status & Info --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card text-center">
@php
                    $sc = ['pending'=>['#fffbeb','#b45309'],'approved'=>['#eff6ff','#3b82f6'],'dispatched'=>['#f0fdf4','#22c55e'],'received'=>['#f0f9ff','#0ea5e9'],'rejected'=>['#fef2f2','#e02020']];
                    $c = $sc[$bloodRequest->status] ?? ['#f1f3f7','#3a3f52'];
                @endphp
                <span class="badge rounded-pill" style="background:{{ $c[0] }};color:{{ $c[1] }};font-size:1rem;padding:8px 20px">{{ ucfirst($bloodRequest->status) }}</span>
                <div class="stat-label mt-2">Status</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div style="font-size:1.4rem;font-weight:700">
                    @if($bloodRequest->urgency === 'critical')
                        <span style="color:#e02020">🔴 Critical</span>
                    @else
                        <span style="color:#22c55e">Normal</span>
                    @endif
                </div>
                <div class="stat-label mt-2">Urgency</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div style="font-size:1.1rem;font-weight:600">{{ $bloodRequest->created_at->format('M d, Y') }}</div>
                <div class="stat-label mt-2">Submitted</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div style="font-size:1.1rem;font-weight:600">{{ $bloodRequest->received_at?->format('M d, Y') ?? '—' }}</div>
                <div class="stat-label mt-2">Received</div>
            </div>
        </div>
    </div>

    @if($bloodRequest->status === 'dispatched')
        <div class="alert alert-success d-flex align-items-center gap-3 mb-4">
            <i class="bi bi-truck fs-5"></i>
            <div>
                <strong>Blood Request Dispatched</strong> — Please confirm when the blood units have been received at your hospital.
            </div>
            <form method="POST" action="{{ route('hospital.requests.mark-received', $bloodRequest) }}" class="ms-auto d-inline" onsubmit="return confirm('Mark this request as received?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm" style="background:#22c55e;color:#fff;border-radius:8px">
                    <i class="bi bi-check-circle me-1"></i> Mark Received
                </button>
            </form>
        </div>
    @endif

    @if($bloodRequest->notes)
        <div class="alert alert-info mb-4">
            <i class="bi bi-chat-left-text me-2"></i> <strong>Notes:</strong> {{ $bloodRequest->notes }}
        </div>
    @endif

    {{-- Items --}}
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="px-4 py-3 border-bottom"><h6 class="mb-0 fw-bold">Requested Items</h6></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Blood Group</th><th>Requested</th><th>Fulfilled</th><th>Progress</th></tr></thead>
                    <tbody>
                        @foreach($bloodRequest->requestItems as $item)
                        <tr>
                            <td><span class="badge-blood">{{ $item->blood_group }}</span></td>
                            <td>{{ $item->units_requested }} units</td>
                            <td>{{ $item->units_fulfilled }} units</td>
                            <td style="width:200px">
                                @php $pct = $item->units_requested > 0 ? min(100, round($item->units_fulfilled / $item->units_requested * 100)) : 0; @endphp
                                <div class="progress" style="height:6px;border-radius:10px;background:var(--gray-200)">
                                    <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $pct >= 100 ? '#22c55e' : '#3b82f6' }};border-radius:10px"></div>
                                </div>
                                <small class="text-muted" style="font-size:.72rem">{{ $pct }}%</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')
@section('title', 'My Requests')

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
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('hospital.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Requests</li>
                    </ol>
                </nav>
                <h4>My Blood Requests</h4>
                <p>Track status of all your blood requests.</p>
            </div>
            <a href="{{ route('hospital.requests.create') }}" class="btn btn-sm" style="background:var(--red);color:#fff;border-radius:8px">
                <i class="bi bi-plus-lg me-1"></i> New Request
            </a>
        </div>
    </div>

    <div class="card border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr><th>ID</th><th>Items</th><th>Urgency</th><th>Status</th><th>Date</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $r)
                        <tr>
                            <td>#{{ $r->id }}</td>
                            <td>
                                @foreach($r->requestItems as $item)
                                    <span class="badge-blood me-1" style="font-size:.65rem">{{ $item->blood_group }}</span>
                                    <small class="text-muted">×{{ $item->units_requested }}</small>
                                @endforeach
                            </td>
                            <td>
                                @if($r->urgency === 'critical')
                                    <span class="badge rounded-pill" style="background:#fef2f2;color:#e02020;font-size:.72rem">🔴 Critical</span>
                                @else
                                    <span class="badge rounded-pill" style="background:#f0fdf4;color:#22c55e;font-size:.72rem">Normal</span>
                                @endif
                            </td>
                            <td>
@php
                                    $sc = ['pending'=>['#fffbeb','#b45309'],'approved'=>['#eff6ff','#3b82f6'],'dispatched'=>['#f0fdf4','#22c55e'],'received'=>['#f0f9ff','#0ea5e9'],'rejected'=>['#fef2f2','#e02020']];
                                    $c = $sc[$r->status] ?? ['#f1f3f7','#3a3f52'];
                                @endphp
                                <span class="badge rounded-pill" style="background:{{ $c[0] }};color:{{ $c[1] }};font-size:.72rem">{{ ucfirst($r->status) }}</span>
                            </td>
                            <td>{{ $r->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('hospital.requests.show', $r) }}" class="btn btn-sm btn-outline-secondary me-1" style="border-radius:6px"><i class="bi bi-eye"></i></a>
                                @if($r->status === 'dispatched')
                                    <form method="POST" action="{{ route('hospital.requests.mark-received', $r) }}" class="d-inline" onsubmit="return confirm('Mark #{{ $r->id }} as received?')" style="margin-left:-4px">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm" style="background:#22c55e;color:#fff;border-radius:6px;font-size:.8rem;padding:4px 8px"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No requests yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $requests->links() }}
@endsection

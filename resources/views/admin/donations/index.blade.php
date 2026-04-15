@extends('layouts.app')

@section('title', 'Donation Requests')
@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'donations'])
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">
            <i class="bi bi-droplet-half text-danger me-2"></i>
            @if($status) {{ ucfirst($status) }} Donations @else All Donations @endif ({{ $donations->total() }})
        </h4>
        <nav>
            <div class="nav nav-pills">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.donations.index') }}">All</a>
                <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('admin.donations.index', ['status' => 'pending']) }}">Pending</a>
                <a class="nav-link {{ request('status') == 'approved' ? 'active' : '' }}" href="{{ route('admin.donations.index', ['status' => 'approved']) }}">Approved</a>
                <a class="nav-link {{ request('status') == 'donated' ? 'active' : '' }}" href="{{ route('admin.donations.index', ['status' => 'donated']) }}">Donated</a>
                <a class="nav-link {{ request('status') == 'rejected' ? 'active' : '' }}" href="{{ route('admin.donations.index', ['status' => 'rejected']) }}">Rejected</a>
            </div>
        </nav>
    </div>
    {{-- <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
    </a> --}}
</div>

@if ($donations->count())
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Donor</th>
                        <th>Blood Group</th>
                        <th>Date Requested</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $donation)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar avatar-sm bg-light text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:42px;height:42px">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $donation->donor->user->name }}</div>
                                    <small class="text-muted">{{ $donation->donor->user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-blood fs-6 px-3 py-2 rounded-pill">{{ $donation->donor->blood_group }}</span></td>
                        <td>{{ $donation->donated_at->format('M d, Y') }}</td>
                        <td>
                            @php
                                $sc = ['pending' => ['#fffbeb', '#b45309'], 'approved' => ['#eff6ff', '#3b82f6'], 'donated' => ['#f0fdf4', '#22c55e'], 'rejected' => ['#fef2f2', '#e02020']];
                                $c = $sc[$donation->status] ?? ['#f1f3f7', '#3a3f52'];
                            @endphp
                            <span class="badge rounded-pill" style="background:{{ $c[0] }};color:{{ $c[1] }};font-size:.8rem">
                                {{ ucfirst($donation->status) }}
                            </span>
                        </td>
                        <td>
                            @if($donation->status === 'pending')
                                <div class="btn-group" role="group">
                                    <form action="{{ route('admin.donations.approve', $donation) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve & process donation?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.donations.reject', $donation) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject donation request?')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            @elseif($donation->status === 'approved')
                                <span class="badge bg-success">Processed</span>
                            @else
                                <span class="badge bg-secondary">No Action</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top bg-light">
        {{ $donations->links() }}
        </div>
    </div>
</div>
@else
<div class="text-center py-5">
    <i class="bi bi-droplet display-1 text-muted mb-4"></i>
    <h5 class="text-muted mb-2">No donations found</h5>
    <p class="text-muted mb-4">Try a different filter or wait for new requests.</p>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
        <i class="bi bi-house me-2"></i>Dashboard
    </a>
</div>
@endif
@endsection


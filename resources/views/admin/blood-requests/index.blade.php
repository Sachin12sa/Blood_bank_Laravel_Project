@extends('layouts.app')
@section('title', 'Blood Requests')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'blood-units'])
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Blood Requests</li>
            </ol>
        </nav>
        <h4>Blood Requests</h4>
        <p>Approve, dispatch, or reject hospital blood requests.</p>
    </div>

    {{-- Filters --}}
    <div class="card border-0 mb-3">
        <div class="card-body py-2 px-3">
            <form class="d-flex gap-2 align-items-center flex-wrap" method="GET">
                <select name="status" class="form-select form-select-sm" style="max-width:140px">
                    <option value="">All Status</option>
                    @foreach(['pending','approved','dispatched','rejected'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <select name="urgency" class="form-select form-select-sm" style="max-width:140px">
                    <option value="">Urgency</option>
                    <option value="normal" {{ request('urgency') === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="critical" {{ request('urgency') === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-danger">Filter</button>
                <a href="{{ route('admin.blood-requests.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </form>
        </div>
    </div>

    <div class="card border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr><th>ID</th><th>Hospital</th><th>Items</th><th>Urgency</th><th>Status</th><th>Date</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $r)
                        <tr>
                            <td>#{{ $r->id }}</td>
                            <td style="font-weight:500">{{ $r->hospital->hospital_name ?? '—' }}</td>
                            <td>
                                @foreach($r->requestItems as $item)
                                    <span class="badge-blood me-1" style="font-size:.6rem">{{ $item->blood_group }}</span><small>×{{ $item->units_requested }}</small>
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
                                    $sc = ['pending'=>['#fffbeb','#b45309'],'approved'=>['#eff6ff','#3b82f6'],'dispatched'=>['#f0fdf4','#22c55e'],'rejected'=>['#fef2f2','#e02020']];
                                    $c = $sc[$r->status] ?? ['#f1f3f7','#3a3f52'];
                                @endphp
                                <span class="badge rounded-pill" style="background:{{ $c[0] }};color:{{ $c[1] }};font-size:.72rem">{{ ucfirst($r->status) }}</span>
                            </td>
                            <td>{{ $r->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.blood-requests.show', $r) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:6px"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $requests->withQueryString()->links() }}
@endsection

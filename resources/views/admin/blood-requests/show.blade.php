@extends('layouts.app')
@section('title', 'Request #' . $bloodRequest->id)

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'blood-requests'])
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.blood-requests.index') }}">Blood Requests</a></li>
                <li class="breadcrumb-item active">Request #{{ $bloodRequest->id }}</li>
            </ol>
        </nav>
        <h4>Request #{{ $bloodRequest->id }}</h4>
    </div>

    {{-- Info Row --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Hospital</div>
                <div style="font-weight:700;font-size:1.1rem">{{ $bloodRequest->hospital->hospital_name ?? '—' }}</div>
                <small class="text-muted">{{ $bloodRequest->hospital->user->email ?? '' }}</small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card text-center">
                @php
                    $sc = ['pending'=>['#fffbeb','#b45309'],'approved'=>['#eff6ff','#3b82f6'],'dispatched'=>['#f0fdf4','#22c55e'],'rejected'=>['#fef2f2','#e02020']];
                    $c = $sc[$bloodRequest->status] ?? ['#f1f3f7','#3a3f52'];
                @endphp
                <span class="badge rounded-pill" style="background:{{ $c[0] }};color:{{ $c[1] }};font-size:.9rem;padding:6px 18px">{{ ucfirst($bloodRequest->status) }}</span>
                <div class="stat-label mt-2">Status</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card text-center">
                <div style="font-size:1.1rem;font-weight:600;color:{{ $bloodRequest->urgency === 'critical' ? '#e02020' : '#22c55e' }}">
                    {{ $bloodRequest->urgency === 'critical' ? '🔴 Critical' : 'Normal' }}
                </div>
                <div class="stat-label mt-2">Urgency</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card text-center">
                <div style="font-weight:600">{{ $bloodRequest->created_at->format('M d, Y') }}</div>
                <div class="stat-label">Submitted</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div style="font-weight:600">{{ $bloodRequest->dispatched_at?->format('M d, Y H:i') ?? '—' }}</div>
                <div class="stat-label">Dispatched At</div>
            </div>
        </div>
    </div>

    @if($bloodRequest->notes)
        <div class="alert alert-info mb-4">
            <i class="bi bi-chat-left-text me-2"></i> <strong>Notes:</strong> {{ $bloodRequest->notes }}
        </div>
    @endif

    {{-- Items --}}
    <div class="card border-0 mb-4">
        <div class="card-body p-0">
            <div class="px-4 py-3 border-bottom"><h6 class="mb-0 fw-bold">Requested Items & Stock</h6></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Blood Group</th><th>Requested</th><th>In Stock</th><th>Fulfilled</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($bloodRequest->requestItems as $item)
                        @php $stock = $stockInfo[$item->blood_group] ?? 0; @endphp
                        <tr>
                            <td><span class="badge-blood">{{ $item->blood_group }}</span></td>
                            <td>{{ $item->units_requested }} units</td>
                            <td>
                                <span style="font-weight:600;color:{{ $stock >= $item->units_requested ? '#22c55e' : '#e02020' }}">{{ $stock }} units</span>
                                @if($stock < $item->units_requested)
                                    <small class="text-danger d-block" style="font-size:.7rem">Insufficient!</small>
                                @endif
                            </td>
                            <td>{{ $item->units_fulfilled }} / {{ $item->units_requested }}</td>
                            <td>
                                @php $pct = $item->units_requested > 0 ? round($item->units_fulfilled / $item->units_requested * 100) : 0; @endphp
                                <div class="progress" style="height:6px;border-radius:10px;background:var(--gray-200);width:100px">
                                    <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $pct >= 100 ? '#22c55e' : '#3b82f6' }};border-radius:10px"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex gap-2 flex-wrap">
        @if($bloodRequest->status === 'pending')
            <form action="{{ route('admin.blood-requests.approve', $bloodRequest) }}" method="POST">
                @csrf @method('PATCH')
                <button class="btn" style="background:#22c55e;color:#fff;border-radius:8px">
                    <i class="bi bi-check-lg me-1"></i> Approve
                </button>
            </form>
            <form action="{{ route('admin.blood-requests.reject', $bloodRequest) }}" method="POST" onsubmit="return confirm('Reject this request?')">
                @csrf @method('PATCH')
                <button class="btn btn-outline-danger" style="border-radius:8px">
                    <i class="bi bi-x-lg me-1"></i> Reject
                </button>
            </form>
        @endif

        @if($bloodRequest->status === 'approved')
            <form action="{{ route('admin.blood-requests.dispatch', $bloodRequest) }}" method="POST" onsubmit="return confirm('Dispatch this request? Available blood units will be deducted from inventory.')">
                @csrf @method('PATCH')
                <button class="btn" style="background:var(--red);color:#fff;border-radius:8px">
                    <i class="bi bi-truck me-1"></i> Dispatch
                </button>
            </form>
            <form action="{{ route('admin.blood-requests.reject', $bloodRequest) }}" method="POST" onsubmit="return confirm('Reject this request?')">
                @csrf @method('PATCH')
                <button class="btn btn-outline-danger" style="border-radius:8px">
                    <i class="bi bi-x-lg me-1"></i> Reject
                </button>
            </form>
        @endif

        <a href="{{ route('admin.blood-requests.index') }}" class="btn btn-outline-secondary" style="border-radius:8px">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

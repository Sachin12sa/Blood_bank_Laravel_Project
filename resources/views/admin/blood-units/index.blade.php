@extends('layouts.app')
@section('title', 'Blood Units')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'blood-units'])
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Blood Units</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4>Blood Units</h4>
                <p>Manage all blood units in the system.</p>
            </div>
            <a href="{{ route('admin.blood-units.create') }}" class="btn btn-sm" style="background:var(--red);color:#fff;border-radius:8px">
                <i class="bi bi-plus-lg me-1"></i> Add Unit
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 mb-3">
        <div class="card-body py-2 px-3">
            <form class="d-flex gap-2 align-items-center flex-wrap" method="GET">
                <select name="blood_group" class="form-select form-select-sm" style="max-width:130px">
                    <option value="">All Groups</option>
                    @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select form-select-sm" style="max-width:130px">
                    <option value="">All Status</option>
                    @foreach(['available','reserved','used','expired'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-outline-danger">Filter</button>
                <a href="{{ route('admin.blood-units.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </form>
        </div>
    </div>

    {{-- Units Table --}}
    <div class="card border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Blood Group</th>
                            <th>Donor</th>
                            <th>Collected</th>
                            <th>Expires</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units as $unit)
                            <tr>
                                <td>#{{ $unit->id }}</td>
                                <td><span class="badge-blood">{{ $unit->blood_group }}</span></td>
                                <td>{{ $unit->donor?->user?->name ?? 'N/A' }}</td>
                                <td>{{ $unit->collected_at->format('M d, Y') }}</td>
                                <td>
                                    {{ $unit->expires_at->format('M d, Y') }}
                                    @if($unit->expires_at->isPast())
                                        <span class="badge rounded-pill" style="background:#fef2f2;color:#e02020;font-size:.65rem">Expired</span>
                                    @elseif($unit->expires_at->diffInDays(now()) <= 3)
                                        <span class="badge rounded-pill" style="background:#fffbeb;color:#b45309;font-size:.65rem">Expiring Soon</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $colors = ['available' => ['#f0fdf4','#22c55e'], 'reserved' => ['#fffbeb','#b45309'], 'used' => ['#eff6ff','#3b82f6'], 'expired' => ['#fef2f2','#e02020']];
                                        $c = $colors[$unit->status] ?? ['#f1f3f7','#3a3f52'];
                                    @endphp
                                    <span class="badge rounded-pill" style="background:{{ $c[0] }};color:{{ $c[1] }};font-size:.72rem">{{ ucfirst($unit->status) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.blood-units.edit', $unit) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:6px"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.blood-units.destroy', $unit) }}" method="POST" onsubmit="return confirm('Delete this unit?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" style="border-radius:6px"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No blood units found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ $units->withQueryString()->links() }}
@endsection

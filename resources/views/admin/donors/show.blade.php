@extends('layouts.app')
@section('title', 'Donor Details')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'donors'])
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.donors.index') }}">Donors</a></li>
                <li class="breadcrumb-item active">{{ $donor->user->name }}</li>
            </ol>
        </nav>
        <h4>{{ $donor->user->name }}</h4>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div style="font-size:3rem;font-weight:900;color:var(--red)">{{ $donor->blood_group }}</div>
                <div class="stat-label">Blood Group</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="bi bi-droplet-fill"></i></div>
                <div class="stat-value">{{ $donor->donations->count() }}</div>
                <div class="stat-label">Total Donations</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-calendar-check"></i></div>
                <div class="stat-value">{{ $donor->last_donated_at?->format('M d') ?? '—' }}</div>
                <div class="stat-label">Last Donation</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-shield-check"></i></div>
                <div class="stat-value" style="font-size:1.2rem">
                    @if($donor->is_eligible && !$donor->isInCooldown()) Eligible
                    @elseif($donor->isInCooldown()) Cooldown
                    @else Not Eligible @endif
                </div>
                <div class="stat-label">Status</div>
            </div>
        </div>
    </div>

    <div class="card border-0 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3">Personal Information</h6>
            <div class="row g-2" style="font-size:.875rem">
                <div class="col-md-6"><strong>Email:</strong> {{ $donor->user->email }}</div>
                <div class="col-md-6"><strong>Phone:</strong> {{ $donor->phone ?? '—' }}</div>
                <div class="col-md-6"><strong>DOB:</strong> {{ $donor->date_of_birth?->format('M d, Y') ?? '—' }}</div>
                <div class="col-md-6"><strong>Address:</strong> {{ $donor->address ?? '—' }}</div>
            </div>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body p-0">
            <div class="px-4 py-3 border-bottom"><h6 class="mb-0 fw-bold">Donation History</h6></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Date</th><th>Unit Status</th><th>Expiry</th></tr></thead>
                    <tbody>
                        @forelse($donor->donations as $d)
                        <tr>
                            <td>{{ $d->donated_at->format('M d, Y') }}</td>
                            <td>{{ ucfirst($d->bloodUnit?->status ?? '—') }}</td>
                            <td>{{ $d->bloodUnit?->expires_at?->format('M d, Y') ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-3 text-muted">No donations.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')
@section('title', 'Donation History')

@section('sidebar')
    <span class="sidebar-label">My Account</span>
    <a href="{{ route('donor.dashboard') }}" class="sidebar-link">
        <i class="bi bi-house-fill"></i> Dashboard
    </a>
    <a href="{{ route('donor.profile') }}" class="sidebar-link">
        <i class="bi bi-person-circle"></i> My Profile
    </a>
    <div class="sidebar-divider"></div>
    <span class="sidebar-label">Donations</span>
    <a href="{{ route('donor.history') }}" class="sidebar-link active">
        <i class="bi bi-clock-history"></i> Donation History
    </a>
    <a href="{{ route('donor.certificates') }}" class="sidebar-link">
        <i class="bi bi-award-fill"></i> My Certificates
    </a>
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('donor.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Donation History</li>
            </ol>
        </nav>
        <h4>Donation History</h4>
        <p>A complete record of all your blood donations.</p>
    </div>

    <div class="card border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Blood Group</th>
                            <th>Unit Status</th>
                            <th>Expiry Date</th>
                            <th>Certificate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $i => $d)
                            <tr>
                                <td>{{ $donations->firstItem() + $i }}</td>
                                <td style="font-weight:500">{{ $d->donated_at->format('M d, Y') }}</td>
                                <td><span class="badge-blood">{{ $d->bloodUnit->blood_group ?? $donor->blood_group }}</span></td>
                                <td>
                                    @if($d->bloodUnit)
                                        @php $s = $d->bloodUnit->status; @endphp
                                        <span class="badge rounded-pill" style="
                                            background:{{ $s === 'available' ? '#f0fdf4' : ($s === 'used' ? '#eff6ff' : '#fef2f2') }};
                                            color:{{ $s === 'available' ? '#22c55e' : ($s === 'used' ? '#3b82f6' : '#e02020') }};
                                            font-size:.72rem">
                                            {{ ucfirst($s) }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $d->bloodUnit?->expires_at?->format('M d, Y') ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('donor.certificate.download', $d) }}" class="btn btn-sm btn-outline-danger rounded-pill">
                                        <i class="bi bi-download me-1"></i> PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-droplet fs-4 d-block mb-2"></i>
                                    No donations recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ $donations->links() }}
@endsection

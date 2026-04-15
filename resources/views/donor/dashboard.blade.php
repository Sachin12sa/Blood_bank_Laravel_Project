@extends('layouts.app')
@section('title', 'Donor Dashboard')

@section('sidebar')
    <span class="sidebar-label">My Account</span>
    <a href="{{ route('donor.dashboard') }}" class="sidebar-link active">
        <i class="bi bi-house-fill"></i> Dashboard
    </a>
    <a href="{{ route('donor.profile') }}" class="sidebar-link">
        <i class="bi bi-person-circle"></i> My Profile
    </a>

    <div class="sidebar-divider"></div>
    <span class="sidebar-label">Donations</span>
    <a href="{{ route('donor.history') }}" class="sidebar-link">
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
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
        <h4>Welcome back, {{ Auth::user()->name }} 👋</h4>
        <p>Thank you for being a life-saver. Here's your donation overview.</p>
    </div>

    @if ($donor)
        {{-- Status Banner --}}
        @if ($donor->isInCooldown())
@php
$daysLeft = (int) ceil((now()->addDays(56)->diffInDays($donor->last_donated_at, false)));
            @endphp
            <div class="alert alert-warning d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-hourglass-split fs-5"></i>
                <div>
                    <strong>Cooldown Period Active</strong> — You can donate again in
                    <strong>{{ $daysLeft }} days</strong>.
                    Last donation: {{ $donor->last_donated_at->format('M d, Y') }}.
                </div>
            </div>
        @else
            <div class="alert alert-success d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>
                    <strong>You're eligible to donate!</strong>
                    Record your donation below.
                </div>
                <form action="{{ route('donor.donate') }}" method="POST" class="ms-auto">
                    @csrf
                    <button type="submit" class="btn btn-sm"
                        style="background:var(--red);color:#fff;border-radius:8px;white-space:nowrap">
                        <i class="bi bi-droplet-fill me-1"></i> Donate Now
                    </button>
                </form>
            </div>
        @endif

        {{-- Donor Info Card --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div style="font-size:3.5rem;font-weight:900;color:var(--red);letter-spacing:-2px">
                        {{ $donor->blood_group }}
                    </div>
                    <div class="stat-label">Your Blood Group</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="bi bi-droplet-fill"></i></div>
                    <div class="stat-value">{{ $donor->donations()->count() }}</div>
                    <div class="stat-label">Total Donations</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="bi bi-calendar-check"></i></div>
                    <div class="stat-value">
                        {{ $donor->last_donated_at ? $donor->last_donated_at->format('M d, Y') : '—' }}
                    </div>
                    <div class="stat-label">Last Donation</div>
                </div>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="row g-3">
            <div class="col-md-6">
                <div class="stat-card">
                    <h6 style="font-weight:700;margin-bottom:12px"><i class="bi bi-clock-history me-2"></i>Recent Donations</h6>
                    {{-- $recentDonations passed from controller --}}
                    @if($recentDonations->isEmpty())
                        <p class="text-muted" style="font-size:.875rem">No donations yet. Make your first donation today!</p>
                    @else
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Date</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                @foreach($recentDonations as $d)
                                <tr>
                                    <td>{{ $d->donated_at->format('M d, Y') }}</td>
                                    <td>
                                        @php
                                            $status = $d->status ?? 'pending';
                                            $colors = [
                                                'pending' => ['bg:#fef3c7', 'color:#92400e'],
                                                'approved' => ['bg:#dbeafe', 'color:#1e40af'],
                                                'donated' => ['bg:#f0fdf4', 'color:#15803d'],
                                                'rejected' => ['bg:#fef2f2', 'color:#dc2626'],
                                            ];
                                            $color = $colors[$status] ?? $colors['pending'];
                                        @endphp
                                        <span class="badge rounded-pill" style="background:{{ $color[0] }};color:{{ $color[1] }};font-size:.72rem">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($d->status === 'donated')
                                            <a href="{{ route('donor.certificate.download', $d) }}" class="text-decoration-none" style="font-size:.8rem;color:var(--red)"><i class="bi bi-download"></i> PDF</a>
                                        @else
                                            <span class="text-muted" style="font-size:.8rem">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card">
                    <h6 style="font-weight:700;margin-bottom:12px"><i class="bi bi-info-circle me-2"></i>Your Information</h6>
                    <table class="table table-sm mb-0">
                        <tr><td style="color:var(--gray-500);width:120px">Name</td><td style="font-weight:500">{{ Auth::user()->name }}</td></tr>
                        <tr><td style="color:var(--gray-500)">Email</td><td style="font-weight:500">{{ Auth::user()->email }}</td></tr>
                        <tr><td style="color:var(--gray-500)">Phone</td><td style="font-weight:500">{{ $donor->phone ?? '—' }}</td></tr>
                        <tr><td style="color:var(--gray-500)">Address</td><td style="font-weight:500">{{ $donor->address ?? '—' }}</td></tr>
                        <tr><td style="color:var(--gray-500)">DOB</td><td style="font-weight:500">{{ $donor->date_of_birth ? $donor->date_of_birth->format('M d, Y') : '—' }}</td></tr>
                    </table>
                    <a href="{{ route('donor.profile') }}" class="btn btn-sm btn-outline-danger rounded-pill mt-2">
                        <i class="bi bi-pencil me-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Profile not found.</strong> Please contact the administrator.
        </div>
    @endif
@endsection

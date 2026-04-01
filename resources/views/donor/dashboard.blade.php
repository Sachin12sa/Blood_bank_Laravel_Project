@extends('layouts.app')
@section('title', 'Donor Dashboard')

@section('sidebar')
    <span class="sidebar-label">My Account</span>
    <a href="{{ route('donor.dashboard') }}" class="sidebar-link active">
        <i class="bi bi-house-fill"></i> Dashboard
    </a>
    <a href="#" class="sidebar-link">
        <i class="bi bi-droplet-fill"></i> Donate Blood
    </a>
    <a href="#" class="sidebar-link">
        <i class="bi bi-clock-history"></i> Donation History
    </a>
    <a href="#" class="sidebar-link">
        <i class="bi bi-award-fill"></i> My Certificates
    </a>
    <div class="sidebar-divider"></div>
    <span class="sidebar-label">Account</span>
    <a href="#" class="sidebar-link">
        <i class="bi bi-person-circle"></i> My Profile
    </a>
@endsection

@section('content')

    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
        <h4>Welcome back, 👋</h4>
        <p>Thank you for being a life-saver. Here's your donation overview.</p>
    </div>

    @if ($donor)
        {{-- Status Banner --}}
        @if ($donor->isInCooldown())
            @php
                $daysLeft = 56 - $donor->last_donated_at->diffInDays(now());
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
                    Visit a blood bank center or schedule a donation.
                </div>
                <a href="#" class="btn btn-sm ms-auto"
                    style="background:var(--red);color:#fff;border-radius:8px;white-space:nowrap">
                    Schedule Now
                </a>
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
                        {{ $donor->last_donated_at ? $donor->last_donated_at->format('M d') : '—' }}
                    </div>
                    <div class="stat-label">Last Donation</div>
                </div>
            </div>
        </div>

    @endif
@endsection

@extends('layouts.app')
@section('title', 'My Certificates')

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
    <a href="{{ route('donor.history') }}" class="sidebar-link">
        <i class="bi bi-clock-history"></i> Donation History
    </a>
    <a href="{{ route('donor.certificates') }}" class="sidebar-link active">
        <i class="bi bi-award-fill"></i> My Certificates
    </a>
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('donor.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">My Certificates</li>
            </ol>
        </nav>
        <h4>Donation Certificates</h4>
        <p>Download PDF certificates for each of your donations.</p>
    </div>

    <div class="row g-3">
        @forelse($donations as $d)
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div style="font-size:2.5rem;color:var(--red);margin-bottom:8px">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <h6 style="font-weight:700">Donation #{{ $d->id }}</h6>
                    <p style="font-size:.85rem;color:var(--gray-500);margin-bottom:12px">
                        {{ $d->donated_at->format('M d, Y') }}
                    </p>
                    <a href="{{ route('donor.certificate.download', $d) }}" class="btn btn-sm" style="background:var(--red);color:#fff;border-radius:8px">
                        <i class="bi bi-download me-1"></i> Download PDF
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="bi bi-award fs-1 d-block mb-3"></i>
                        <h6>No certificates yet</h6>
                        <p style="font-size:.875rem">Make a donation to receive your first certificate.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@extends('layouts.app')
@section('title', 'My Profile')

@section('sidebar')
    <span class="sidebar-label">My Account</span>
    <a href="{{ route('donor.dashboard') }}" class="sidebar-link">
        <i class="bi bi-house-fill"></i> Dashboard
    </a>
    <a href="{{ route('donor.profile') }}" class="sidebar-link active">
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
                <li class="breadcrumb-item"><a href="{{ route('donor.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">My Profile</li>
            </ol>
        </nav>
        <h4>My Profile</h4>
        <p>Update your personal and donation information.</p>
    </div>

    <div class="card border-0 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('donor.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                        <small class="text-muted">Email cannot be changed.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Blood Group</label>
                        <select name="blood_group" class="form-select" required>
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $donor->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                        @error('blood_group') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $donor->phone) }}" placeholder="+977-9800000000">
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $donor->date_of_birth?->format('Y-m-d')) }}">
                        @error('date_of_birth') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $donor->address) }}" placeholder="Your city or area">
                        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn" style="background:var(--red);color:#fff;border-radius:8px">
                        <i class="bi bi-check-lg me-1"></i> Save Changes
                    </button>
                    <a href="{{ route('donor.dashboard') }}" class="btn btn-outline-secondary" style="border-radius:8px">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Eligibility Info --}}
    <div class="card border-0">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-shield-check me-2"></i>Eligibility Status</h6>
            <div class="d-flex gap-4 flex-wrap">
                <div>
                    <span class="text-muted" style="font-size:.8rem">Status</span><br>
                    @if($donor->is_eligible && !$donor->isInCooldown())
                        <span class="badge rounded-pill" style="background:#f0fdf4;color:#22c55e">Eligible</span>
                    @elseif($donor->isInCooldown())
                        <span class="badge rounded-pill" style="background:#fffbeb;color:#b45309">Cooldown</span>
                    @else
                        <span class="badge rounded-pill" style="background:#fef2f2;color:#e02020">Not Eligible</span>
                    @endif
                </div>
                <div>
                    <span class="text-muted" style="font-size:.8rem">Total Donations</span><br>
                    <strong>{{ $donor->donations()->count() }}</strong>
                </div>
                <div>
                    <span class="text-muted" style="font-size:.8rem">Last Donation</span><br>
                    <strong>{{ $donor->last_donated_at ? $donor->last_donated_at->format('M d, Y') : 'Never' }}</strong>
                </div>
                @if($donor->isInCooldown())
                <div>
                    <span class="text-muted" style="font-size:.8rem">Next Eligible</span><br>
                    <strong>{{ $donor->last_donated_at->addDays(56)->format('M d, Y') }}</strong>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Donation Requests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
        <i class="bi bi-droplet-half text-danger me-2"></i>
        Pending Donation Requests ({{ $pendingDonations->total() }})
    </h4>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
    </a>
</div>

@if ($pendingDonations->count())
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
                    @foreach($pendingDonations as $donation)
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
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-clock me-1"></i>Pending
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <form action="{{ route('admin.donations.approve', $donation) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this donation?')">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.donations.reject', $donation) }}" method="POST" class="d-inline ms-1">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Reject this donation request?')">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top bg-light">
            {{ $pendingDonations->links() }}
        </div>
    </div>
</div>
@else
<div class="text-center py-5">
    <i class="bi bi-droplet display-1 text-muted mb-4"></i>
    <h5 class="text-muted mb-2">No pending donation requests</h5>
    <p class="text-muted mb-4">Donors will see their requests here after submitting.</p>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
        <i class="bi bi-house me-2"></i>Dashboard
    </a>
</div>
@endif
@endsection


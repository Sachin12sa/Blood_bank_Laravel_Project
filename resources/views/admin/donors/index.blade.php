@extends('layouts.app')
@section('title', 'Manage Donors')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'donors'])
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Donors</li>
            </ol>
        </nav>
        <h4>Manage Donors</h4>
        <p>View all registered donors.</p>
    </div>

    <div class="card border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr><th>Name</th><th>Blood Group</th><th>Phone</th><th>Donations</th><th>Status</th><th>Last Donation</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($donors as $d)
                        <tr>
                            <td>
                                <div style="font-weight:600">{{ $d->user->name ?? '—' }}</div>
                                <small class="text-muted">{{ $d->user->email ?? '' }}</small>
                            </td>
                            <td><span class="badge-blood">{{ $d->blood_group }}</span></td>
                            <td>{{ $d->phone ?? '—' }}</td>
                            <td>{{ $d->donations->count() }}</td>
                            <td>
                                @if($d->is_eligible && !$d->isInCooldown())
                                    <span class="badge rounded-pill" style="background:#f0fdf4;color:#22c55e;font-size:.72rem">Eligible</span>
                                @elseif($d->isInCooldown())
                                    <span class="badge rounded-pill" style="background:#fffbeb;color:#b45309;font-size:.72rem">Cooldown</span>
                                @else
                                    <span class="badge rounded-pill" style="background:#fef2f2;color:#e02020;font-size:.72rem">Not Eligible</span>
                                @endif
                            </td>
                            <td>{{ $d->last_donated_at?->format('M d, Y') ?? 'Never' }}</td>
                            <td>
                                <a href="{{ route('admin.donors.show', $d) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:6px"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No donors registered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $donors->links() }}
@endsection

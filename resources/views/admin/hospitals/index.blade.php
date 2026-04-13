@extends('layouts.app')
@section('title', 'Manage Hospitals')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'hospitals'])
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Hospitals</li>
            </ol>
        </nav>
        <h4>Manage Hospitals</h4>
        <p>Approve or reject hospital registrations.</p>
    </div>

    <div class="card border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr><th>Hospital</th><th>License</th><th>Contact</th><th>Phone</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($hospitals as $h)
                        <tr>
                            <td style="font-weight:600">{{ $h->hospital_name }}</td>
                            <td><code style="font-size:.8rem">{{ $h->license_number }}</code></td>
                            <td>{{ $h->user->name ?? '—' }}</td>
                            <td>{{ $h->phone ?? '—' }}</td>
                            <td>
                                @php
                                    $sc = ['pending'=>['#fffbeb','#b45309'],'approved'=>['#f0fdf4','#22c55e'],'rejected'=>['#fef2f2','#e02020']];
                                    $c = $sc[$h->status] ?? ['#f1f3f7','#3a3f52'];
                                @endphp
                                <span class="badge rounded-pill" style="background:{{ $c[0] }};color:{{ $c[1] }};font-size:.72rem">{{ ucfirst($h->status) }}</span>
                            </td>
                            <td>{{ $h->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($h->status !== 'approved')
                                        <form action="{{ route('admin.hospitals.approve', $h) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-success" style="border-radius:6px" title="Approve"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                    @endif
                                    @if($h->status !== 'rejected')
                                        <form action="{{ route('admin.hospitals.reject', $h) }}" method="POST" onsubmit="return confirm('Reject this hospital?')">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-danger" style="border-radius:6px" title="Reject"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No hospitals registered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $hospitals->links() }}
@endsection

@extends('layouts.app')
@section('title', 'Campaigns Management')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'campaigns'])
@endsection

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Campaigns</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h4>Blood Donation Campaigns</h4>
            <p class="text-muted mb-0">Manage upcoming and past blood donation events.</p>
        </div>
        <div>
            <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Create Campaign
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($campaigns->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($campaigns as $campaign)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $campaign->title }}</div>
                                </td>
                                <td>
                                    <span class="text-muted"><i class="bi bi-calendar"></i> {{ $campaign->date->format('M d, Y') }}</span>
                                </td>
                                <td><small>{{ $campaign->address }}</small></td>
                                <td>
                                    @if($campaign->status === 'upcoming')
                                        <span class="badge bg-primary rounded-pill">Upcoming</span>
                                    @elseif($campaign->status === 'active')
                                        <span class="badge bg-success rounded-pill">Active</span>
                                    @elseif($campaign->status === 'completed')
                                        <span class="badge bg-secondary rounded-pill">Completed</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="btn btn-outline-success border-0 p-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this campaign?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger border-0 p-1" title="Delete">
                                                <i class="bi bi-trash"></i>
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
                {{ $campaigns->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-calendar-event display-1 opacity-25 d-block mb-3"></i>
                <div class="h5 mb-1">No campaigns found</div>
                <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary mt-2">Create your first campaign</a>
            </div>
        @endif
    </div>
</div>
@endsection

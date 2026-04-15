@extends('layouts.app')

@php
    use App\Models\BloodInventory;
@endphp

@section('title', 'Blood Inventory')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'inventories'])
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">
            <i class="bi bi-boxes text-danger me-2"></i>
            Blood Inventory Overview
            <span class="badge bg-primary fs-6 ms-2">{{ $inventory->count() }} Groups</span>
        </h4>
        {{-- <p class="text-muted mb-0">
            Total Units: <strong class="text-danger">{{ number_format($stats['totalUnits']) }}</strong> | 
            Available: <strong class="text-success">{{ number_format($stats['availableUnits']) }}</strong> | 
            Low Stock: <span class="badge bg-warning">{{ $stats['lowStock'] }}</span>
            <span class="badge bg-success ms-2">Live Synced</span>
        </p> --}}
    </div>
    <div>
        {{-- <form action="{{ route('admin.inventories.refresh') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm me-2">
                <i class="bi bi-arrow-clockwise"></i> Sync Inventory
            </button>
        </form> --}}
        {{-- <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Dashboard
        </a> --}}
    </div>
</div>

@if($inventory->count())
<!-- Cards Overview (existing) -->
{{-- <div class="row g-4 mb-5">
    @foreach($inventory as $item)
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
            <div class="card-body d-flex flex-column p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1 fw-bold text-uppercase text-danger fs-4">{{ $item->blood_group }}</h5>
                        <span class="badge bg-light text-dark border fw-semibold px-3 py-2 rounded-pill">
                            {{ number_format($item->total_units) }} Total
                        </span>
                    </div>
                    @if($item->isBelowThreshold())
                        <div class="avatar avatar-sm bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px">
                            <i class="bi bi-exclamation-triangle-fill fs-6"></i>
                        </div>
                    @else
                        <div class="avatar avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px">
                            <i class="bi bi-check-circle-fill fs-6"></i>
                        </div>
                    @endif
                </div>
                
                <div class="flex-grow-1 mb-4">
                    <div class="progress" style="height: 12px;" title="{{ $item->available_units }}/{{ $item->total_units }}">
                        <div class="progress-bar @if($item->isBelowThreshold()) bg-danger @elseif($item->available_units < $item->total_units * 0.3) bg-warning @else bg-success @endif" 
                             role="progressbar" 
                             style="width: {{ ($item->available_units / $item->total_units * 100) }}%"
                             aria-valuenow="{{ $item->available_units }}" 
                             aria-valuemin="0" 
                             aria-valuemax="{{ $item->total_units }}"></div>
                    </div>
                    <div class="mt-2 d-flex justify-content-between">
                        <span class="text-muted small">Available:</span>
                        <strong class="text-lg text-success">{{ number_format($item->available_units) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Threshold:</span>
                        <span class="badge bg-secondary">{{ $item->threshold }}</span>
                    </div>
                </div>
                
                <div class="mt-auto">
                    <small class="text-muted">
                        Last Updated: {{ $item->updated_at->diffForHumans() }}
                    </small>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div> --}}

<!-- NEW: Detailed Stock Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bi bi-table me-2"></i>Detailed Available Stock
        </h6>
        <div class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width: 250px;">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="stockSearch" placeholder="Search blood groups...">
            </div>
            {{-- <button class="btn btn-outline-light btn-sm" id="exportCsv">
                <i class="bi bi-download"></i> CSV
            </button> --}}
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="stockTable">
                <thead class="table-dark sticky-top">
                    <tr>
                        <th>Blood Group</th>
                        <th>Total Units</th>
                        <th>Available (Stock)</th>
                        <th>Threshold</th>
                        <th>% Available</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventory as $item)
                    <tr class="@if($item->isBelowThreshold()) table-danger @elseif($item->available_units == 0) table-warning @endif" 
                        data-group="{{ strtolower($item->blood_group) }}">
                        <td>
                            <strong class="text-uppercase">{{ $item->blood_group }}</strong>
                        </td>
                        <td><span class="badge bg-secondary">{{ number_format($item->total_units) }}</span></td>
                        <td>
                            <strong class="text-success fs-6">{{ number_format($item->available_units) }}</strong>
                            @if($item->available_units == 0)
                            <span class="badge bg-danger ms-1">OUT</span>
                            @endif
                        </td>
                        <td>{{ $item->threshold }}</td>
                        <td>
                            <div class="progress" style="height:8px;">
                                <div class="progress-bar @if($item->isBelowThreshold()) bg-danger @elseif($item->available_units < $item->total_units * 0.5) bg-warning @else bg-success @endif" 
                                     style="width: {{ number_format(($item->available_units / max($item->total_units, 1) * 100), 1) }}%"></div>
                            </div>
                            <small class="text-muted">{{ number_format(($item->available_units / max($item->total_units, 1) * 100), 1) }}%</small>
                        </td>
                        <td>
                            @if($item->isBelowThreshold())
                                <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Low Stock</span>
                            @elseif($item->available_units == 0)
                                <span class="badge bg-warning text-dark"><i class="bi bi-x-circle"></i> Out of Stock</span>
                            @else
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Good</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $item->updated_at->format('M d, Y') }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Summary Cards (existing) -->
<div class="row mt-5">
    <!-- ... existing summary and quick actions ... keep as is -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Stock Status Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Healthy Stock</span>
                    <span class="text-success fw-bold">{{ BloodInventory::whereColumn('available_units', '>=', 'threshold')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Low Stock Alert</span>
                    <span class="text-warning fw-bold">{{ $stats['lowStock'] }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Critical (0 units)</span>
                    <span class="text-danger fw-bold">{{ BloodInventory::where('available_units', 0)->count() }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.blood-units.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-plus-circle me-2"></i>Add Blood Units
                </a>
                <a href="{{ route('admin.blood-requests.index') }}" class="btn btn-outline-warning w-100 mb-2">
                    <i class="bi bi-clipboard-pulse me-2"></i>Pending Requests
                </a>
                <a href="{{ route('admin.donors.index') }}" class="btn btn-outline-success w-100">
                    <i class="bi bi-people me-2"></i>View Donors
                </a>
            </div>
        </div>
    </div>
</div>

@else
<div class="text-center py-8">
    <i class="bi bi-box-seam display-3 text-muted mb-4"></i>
    <h5 class="text-muted mb-3">No Inventory Data</h5>
    <p class="text-muted mb-4 lead">Blood inventory records not found. Add blood units to populate this page.</p>
    <a href="{{ route('admin.blood-units.index') }}" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle me-2"></i>Start Adding Units
    </a>
</div>
@endif

<!-- JS for Search & Export -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('stockSearch');
    const tableRows = document.querySelectorAll('#stockTable tbody tr');
    const exportBtn = document.getElementById('exportCsv');

    // Live search
    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        tableRows.forEach(row => {
            const group = row.getAttribute('data-group') || '';
            row.style.display = group.includes(term) ? '' : 'none';
        });
    });

    // CSV Export
    exportBtn.addEventListener('click', function() {
        let csv = 'Blood Group,Total Units,Available,Threshold,%,Status\\n';
        tableRows.forEach(row => {
            if (row.style.display !== 'none') {
                const cells = row.querySelectorAll('td');
                const rowData = Array.from(cells).map(cell => {
                    let text = cell.textContent.trim();
                    // Clean badges etc.
                    text = text.replace(/\\n/g, ' ').replace(/[<>]/g, '');
                    return '"' + text.replace(/"/g, '""') + '"';
                }).join(',');
                csv += rowData + '\\n';
            }
        });
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'blood-inventory-' + new Date().toISOString().slice(0,10) + '.csv';
        a.click();
        window.URL.revokeObjectURL(url);
    });
});
</script>

@endsection


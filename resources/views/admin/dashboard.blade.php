@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'dashboard'])
@endsection

@section('content')
    {{-- Page Header --}}
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
        <h4>Admin Dashboard</h4>
        <p>Real-time overview of the blood bank operations.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <div class="stat-icon red"><i class="bi bi-people-fill"></i></div>
                <div class="stat-value">{{ $stats['totalDonors'] }}</div>
                <div class="stat-label">Donors</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-hospital-fill"></i></div>
                <div class="stat-value">{{ $stats['totalHospitals'] }}</div>
                <div class="stat-label">Hospitals</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-value">{{ $stats['pendingRequests'] }}</div>
                <div class="stat-label">Requests</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-droplet-fill"></i></div>
                <div class="stat-value">{{ $stats['availableUnits'] }}</div>
                <div class="stat-label">Units</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="bi bi-exclamation-octagon"></i></div>
                <div class="stat-value">{{ $stats['expiringUnits'] }}</div>
                <div class="stat-label">Expiring</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <div class="stat-icon red"><i class="bi bi-building-add"></i></div>
                <div class="stat-value">{{ $stats['pendingHospitals'] }}</div>
                <div class="stat-label">New Reg.</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- Inventory Bar Chart --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0">Blood Inventory (Available Units)</h6>
                        <a href="{{ route('admin.blood-units.index') }}" class="btn btn-sm btn-outline-danger rounded-pill">Manage Inventory</a>
                    </div>
                    <div style="height: 300px;">
                        <canvas id="inventoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Low Stock Alerts --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Critical Stock Alerts</h6>
                    <div class="list-group list-group-flush">
                        @forelse($inventory->filter->isBelowThreshold() as $lowItem)
                            <div class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge-blood">{{ $lowItem->blood_group }}</span>
                                    <div>
                                        <div style="font-weight:600;font-size:.85rem">Stock Critically Low</div>
                                        <div style="font-size:.75rem;color:var(--gray-500)">{{ $lowItem->available_units }} units left / Threshold {{ $lowItem->threshold }}</div>
                                    </div>
                                </div>
                                <span class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i></span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-2"></i>
                                <span style="font-size:.85rem">All stock levels are healthy.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- Request Trends --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Monthly Blood Requests ({{ date('Y') }})</h6>
                    <div style="height: 250px;">
                        <canvas id="trendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Activity Feed --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Recent Activity</h6>
                        <nav>
                            <div class="nav nav-tabs border-0" id="activityTab" role="tablist">
                                <button class="nav-link active py-1 px-3 me-2 border-0 rounded-pill" style="font-size:.75rem" data-bs-toggle="tab" data-bs-target="#tab-donations">Donations</button>
                                <button class="nav-link py-1 px-3 border-0 rounded-pill" style="font-size:.75rem" data-bs-toggle="tab" data-bs-target="#tab-requests">Requests</button>
                            </div>
                        </nav>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-donations">
                            @forelse($recentDonations as $d)
                                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom last-border-0">
                                    <div class="nav-avatar donor" style="width:36px;height:36px;font-size:.8rem">{{ strtoupper(substr($d->donor->user->name, 0, 2)) }}</div>
                                    <div class="flex-grow-1">
                                        <div style="font-weight:600;font-size:.85rem">{{ $d->donor->user->name }} donated blood</div>
                                        <div style="font-size:.7rem;color:var(--gray-500)">{{ $d->donated_at->diffForHumans() }}</div>
                                    </div>
                                    <span class="badge-blood" style="font-size:.6rem">{{ $d->donor->blood_group }}</span>
                                </div>
                            @empty
                                <p class="text-center py-4 text-muted" style="font-size:.85rem">No recent donations.</p>
                            @endforelse
                        </div>
                        <div class="tab-pane fade" id="tab-requests">
                            @forelse($recentRequests as $r)
                                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom last-border-0">
                                    <div class="nav-avatar hospital" style="width:36px;height:36px;font-size:.8rem"><i class="bi bi-hospital"></i></div>
                                    <div class="flex-grow-1">
                                        <div style="font-weight:600;font-size:.85rem">{{ $r->hospital->hospital_name }} requested blood</div>
                                        <div style="font-size:.7rem;color:var(--gray-500)">{{ $r->created_at->diffForHumans() }} · {{ ucfirst($r->status) }}</div>
                                    </div>
                                    <div class="text-end">
                                        @if($r->urgency === 'critical')
                                            <span class="badge rounded-pill" style="background:#fef2f2;color:#e02020;font-size:.6rem">Critical</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-center py-4 text-muted" style="font-size:.85rem">No recent requests.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inventory Chart
    const invCtx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(invCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Stock Units',
                data: {!! json_encode($chartData['data']) !!},
                backgroundColor: 'rgba(192, 21, 42, 0.8)',
                borderColor: '#C0152A',
                borderWidth: 0,
                borderRadius: 8,
                barThickness: 35,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { display: true, drawBorder: false, color: '#f0f0f0' }, ticks: { font: { size: 11 } } },
                x: { grid: { display: false }, ticks: { font: { size: 12, weight: 'bold' } } }
            }
        }
    });

    // Trends Chart
    const trendCtx = document.getElementById('trendsChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trendData['labels']) !!},
            datasets: [{
                label: 'Requests',
                data: {!! json_encode($trendData['data']) !!},
                borderColor: '#C0152A',
                backgroundColor: 'rgba(192, 21, 42, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#C0152A',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f0f0' }, ticks: { stepSize: 1, font: { size: 11 } } },
                x: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });
});
</script>
<style>
.nav-tabs .nav-link { color: var(--gray-500); transition: all 0.2s; }
.nav-tabs .nav-link.active { background: #fef2f2 !important; color: var(--red) !important; font-weight: 600; }
.last-border-0:last-child { border-bottom: 0 !important; margin-bottom: 0 !important; padding-bottom: 0 !important; }
</style>
@endpush

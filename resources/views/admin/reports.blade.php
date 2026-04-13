@extends('layouts.app')
@section('title', 'System Reports')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'reports'])
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Reports</li>
            </ol>
        </nav>
        <h4>System Reports & Analytics</h4>
        <p>Comprehensive overview of system performance and stock history.</p>
    </div>

    <div class="row g-4 mb-4">
        {{-- Blood Request Summary --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <h6 class="fw-bold mb-4 text-start">Blood Request Summary</h6>
                    <div style="height: 200px; display: flex; align-items: center; justify-content: center;">
                        <canvas id="requestPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Urgency Distribution --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Request Urgency Distribution</h6>
                    <div class="mt-4">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1" style="font-size:.85rem">
                                <span>Normal Requests</span>
                                <span class="fw-bold">{{ $urgencyStats['normal'] }} ({{ $requestSummary['total'] > 0 ? round($urgencyStats['normal']/$requestSummary['total']*100) : 0 }}%)</span>
                            </div>
                            <div class="progress" style="height:8px; border-radius:10px; background:#f0f0f0">
                                <div class="progress-bar bg-info" style="width: {{ $requestSummary['total'] > 0 ? ($urgencyStats['normal']/$requestSummary['total']*100) : 0 }}%; border-radius:10px"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1" style="font-size:.85rem">
                                <span>Critical Requests</span>
                                <span class="fw-bold text-danger">{{ $urgencyStats['critical'] }} ({{ $requestSummary['total'] > 0 ? round($urgencyStats['critical']/$requestSummary['total']*100) : 0 }}%)</span>
                            </div>
                            <div class="progress" style="height:8px; border-radius:10px; background:#f0f0f0">
                                <div class="progress-bar bg-danger" style="width: {{ $requestSummary['total'] > 0 ? ($urgencyStats['critical']/$requestSummary['total']*100) : 0 }}%; border-radius:10px"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-light rounded-3 mt-4" style="font-size:.8rem">
                        <i class="bi bi-info-circle me-1"></i> Critical requests require immediate attention and FIFO dispatch of oldest units.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stock Level Details Table --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="p-4 border-bottom"><h6 class="fw-bold mb-0">Complete Inventory Details</h6></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">Group</th>
                            <th>Available Units</th>
                            <th>In-Use/Reserved</th>
                            <th>Threshold</th>
                            <th>Status</th>
                            <th>Health</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupStats as $s)
                        <tr>
                            <td class="px-4"><span class="badge-blood">{{ $s->blood_group }}</span></td>
                            <td class="fw-bold">{{ $s->available_units }} units</td>
                            <td>{{ $s->total_units - $s->available_units }} units</td>
                            <td class="text-muted">{{ $s->threshold }} units</td>
                            <td>
                                @if($s->isBelowThreshold())
                                    <span class="badge rounded-pill bg-danger-subtle text-danger px-3">Critical</span>
                                @else
                                    <span class="badge rounded-pill bg-success-subtle text-success px-3">Sufficient</span>
                                @endif
                            </td>
                            <td style="width: 200px">
                                @php $h = min(100, round($s->available_units / max(1, $s->threshold) * 50)); @endphp
                                <div class="progress" style="height:5px; border-radius:10px">
                                    <div class="progress-bar {{ $s->isBelowThreshold() ? 'bg-danger' : 'bg-success' }}" style="width: {{ $h }}%; border-radius:10px"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Donation Trends --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-4">Yearly Donation Activity</h6>
            <div style="height: 300px">
                <canvas id="donationTrendChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Request Pie Chart
    new Chart(document.getElementById('requestPieChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Approved', 'Dispatched', 'Rejected'],
            datasets: [{
                data: [{{ $requestSummary['pending'] }}, {{ $requestSummary['approved'] }}, {{ $requestSummary['dispatched'] }}, {{ $requestSummary['rejected'] }}],
                backgroundColor: ['#f59e0b', '#3b82f6', '#22c55e', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { size: 11 } } }
            },
            cutout: '70%'
        }
    });

    // Donation Trend Chart
    new Chart(document.getElementById('donationTrendChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyDonations->pluck('month')) !!},
            datasets: [{
                label: 'Donations',
                data: {!! json_encode($monthlyDonations->pluck('count')) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f0f0' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush

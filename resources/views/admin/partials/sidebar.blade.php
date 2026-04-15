{{-- Admin Sidebar Partial --}}
<span class="sidebar-label">Main</span>
<a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ ($active ?? '') === 'dashboard' ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<span class="sidebar-label">Management</span>
<a href="{{ route('admin.hospitals.index') }}" class="sidebar-link {{ ($active ?? '') === 'hospitals' ? 'active' : '' }}">
    <i class="bi bi-hospital"></i> Hospitals
    @php $pendingH = \App\Models\Hospital::where('status','pending')->count(); @endphp
    @if($pendingH > 0)
        <span class="s-badge warn">{{ $pendingH }}</span>
    @endif
</a>
{{-- <a href="{{ route('admin.blood-units.index') }}" class="sidebar-link {{ ($active ?? '') === 'blood-units' ? 'active' : '' }}">
    <i class="bi bi-droplet-half"></i> Blood Units
</a> --}}
<a href="{{ route('admin.inventories.index') }}" class="sidebar-link {{ ($active ?? '') === 'inventories' ? 'active' : '' }}">
    <i class="bi bi-boxes"></i> Blood Inventory
    @php $lowStock = \App\Models\BloodInventory::where('available_units', '<', DB::raw('threshold'))->count(); @endphp
    @if($lowStock > 0)
        <span class="s-badge warn">{{ $lowStock }}</span>
    @endif
</a>
<a href="{{ route('admin.blood-requests.index') }}" class="sidebar-link {{ ($active ?? '') === 'blood-units' ? 'active' : '' }}">
    <i class="bi bi-clipboard-pulse"></i> Blood Requests
    @php $pendingR = \App\Models\BloodRequest::where('status','pending')->count(); @endphp
    @if($pendingR > 0)
        <span class="s-badge">{{ $pendingR }}</span>
    @endif
</a>
<a href="{{ route('admin.donations.index') }}" class="sidebar-link {{ ($active ?? '') === 'donations' ? 'active' : '' }}">
    <i class="bi bi-droplet-fill"></i> Donation Requests
    @php $pendingD = \App\Models\Donation::pending()->count(); @endphp
    @if($pendingD > 0)
        <span class="s-badge warn">{{ $pendingD }}</span>
    @endif
</a>
<a href="{{ route('admin.donors.index') }}" class="sidebar-link {{ ($active ?? '') === 'donors' ? 'active' : '' }}">
    <i class="bi bi-people"></i> Donors
</a>
{{-- <a href="{{ route('admin.campaigns.index') }}" class="sidebar-link {{ ($active ?? '') === 'campaigns' ? 'active' : '' }}">
    <i class="bi bi-calendar-event"></i> Campaigns
</a> --}}


<a href="{{ route('roles.index') }}" class="sidebar-link {{ ($active ?? '') === 'roles' ? 'active' : '' }}">
    <i class="bi bi-shield-lock"></i> Roles & Permissions
</a>
<a href="{{ route('admin.users.index') }}" class="sidebar-link {{ ($active ?? '') === 'users' ? 'active' : '' }}">
    <i class="bi bi-person-lines-fill"></i> Users
</a>

<div class="sidebar-divider"></div>

<span class="sidebar-label">Reports</span>
<a href="{{ route('admin.reports') }}" class="sidebar-link {{ ($active ?? '') === 'reports' ? 'active' : '' }}">
    <i class="bi bi-bar-chart-line"></i> Reports
</a>


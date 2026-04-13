@extends('layouts.app')
@section('title', 'New Blood Request')

@section('sidebar')
    <span class="sidebar-label">My Hospital</span>
    <a href="{{ route('hospital.dashboard') }}" class="sidebar-link">
        <i class="bi bi-house-fill"></i> Dashboard
    </a>
    <a href="{{ route('hospital.profile') }}" class="sidebar-link">
        <i class="bi bi-building"></i> Hospital Profile
    </a>
    <div class="sidebar-divider"></div>
    <span class="sidebar-label">Blood Requests</span>
    <a href="{{ route('hospital.requests.create') }}" class="sidebar-link active">
        <i class="bi bi-plus-circle-fill"></i> New Blood Request
    </a>
    <a href="{{ route('hospital.requests.index') }}" class="sidebar-link">
        <i class="bi bi-clipboard-pulse"></i> My Requests
    </a>
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('hospital.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hospital.requests.index') }}">My Requests</a></li>
                <li class="breadcrumb-item active">New Request</li>
            </ol>
        </nav>
        <h4>Submit Blood Request</h4>
        <p>Specify the blood groups and quantities needed.</p>
    </div>

    <div class="card border-0">
        <div class="card-body p-4">
            <form action="{{ route('hospital.requests.store') }}" method="POST" id="requestForm">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Urgency Level</label>
                        <select name="urgency" class="form-select" required>
                            <option value="normal" {{ old('urgency') === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="critical" {{ old('urgency') === 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Notes (optional)</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="Any additional information...">
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Blood Items</h6>

                <div id="itemsContainer">
                    <div class="item-row row g-2 mb-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label">Blood Group</label>
                            <select name="items[0][blood_group]" class="form-select" required>
                                <option value="">Select</option>
                                @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                    <option value="{{ $bg }}">{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Units Required</label>
                            <input type="number" name="items[0][units]" class="form-control" min="1" max="100" value="1" required>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-sm btn-outline-danger w-100" style="border-radius:6px" onclick="removeItem(this)" disabled>
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-sm btn-outline-secondary mb-4" style="border-radius:6px" onclick="addItem()">
                    <i class="bi bi-plus-lg me-1"></i> Add Another Group
                </button>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn" style="background:var(--red);color:#fff;border-radius:8px">
                        <i class="bi bi-send me-1"></i> Submit Request
                    </button>
                    <a href="{{ route('hospital.requests.index') }}" class="btn btn-outline-secondary" style="border-radius:8px">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
let itemIndex = 1;
function addItem() {
    const container = document.getElementById('itemsContainer');
    const groups = ['A+','A-','B+','B-','O+','O-','AB+','AB-'];
    let options = '<option value="">Select</option>';
    groups.forEach(g => options += `<option value="${g}">${g}</option>`);

    container.insertAdjacentHTML('beforeend', `
        <div class="item-row row g-2 mb-2 align-items-end">
            <div class="col-md-5">
                <select name="items[${itemIndex}][blood_group]" class="form-select" required>
                    ${options}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="items[${itemIndex}][units]" class="form-control" min="1" max="100" value="1" required>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-sm btn-outline-danger w-100" style="border-radius:6px" onclick="removeItem(this)">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </div>
        </div>
    `);
    itemIndex++;
    updateRemoveButtons();
}

function removeItem(btn) {
    btn.closest('.item-row').remove();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.item-row');
    rows.forEach((r, i) => {
        const btn = r.querySelector('button');
        btn.disabled = rows.length <= 1;
    });
}
</script>
@endpush

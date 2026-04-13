@extends('layouts.app')
@section('title', 'Edit Blood Unit')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'blood-units'])
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.blood-units.index') }}">Blood Units</a></li>
                <li class="breadcrumb-item active">Edit #{{ $blood_unit->id }}</li>
            </ol>
        </nav>
        <h4>Edit Blood Unit #{{ $blood_unit->id }}</h4>
    </div>

    <div class="card border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.blood-units.update', $blood_unit) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Blood Group</label>
                        <select name="blood_group" class="form-select" required>
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $blood_unit->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                        @error('blood_group') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Donor</label>
                        <select name="donor_id" class="form-select">
                            <option value="">No donor</option>
                            @foreach($donors as $d)
                                <option value="{{ $d->id }}" {{ old('donor_id', $blood_unit->donor_id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->user->name }} ({{ $d->blood_group }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Collection Date</label>
                        <input type="date" name="collected_at" class="form-control" value="{{ old('collected_at', $blood_unit->collected_at->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach(['available','reserved','used','expired'] as $s)
                                <option value="{{ $s }}" {{ old('status', $blood_unit->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3 p-3 rounded" style="background:var(--gray-100);font-size:.85rem">
                    <i class="bi bi-info-circle me-1"></i>
                    Expiry date will be auto-recalculated: <strong>collection date + 42 days</strong>.
                    Current expiry: <strong>{{ $blood_unit->expires_at->format('M d, Y') }}</strong>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn" style="background:var(--red);color:#fff;border-radius:8px">
                        <i class="bi bi-check-lg me-1"></i> Update Unit
                    </button>
                    <a href="{{ route('admin.blood-units.index') }}" class="btn btn-outline-secondary" style="border-radius:8px">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

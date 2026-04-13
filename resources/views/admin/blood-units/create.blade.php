@extends('layouts.app')
@section('title', 'Add Blood Unit')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'blood-units'])
@endsection

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.blood-units.index') }}">Blood Units</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </nav>
        <h4>Add Blood Unit</h4>
        <p>Record a new blood unit. Expiry date is auto-calculated (42 days from collection).</p>
    </div>

    <div class="card border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.blood-units.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Blood Group</label>
                        <select name="blood_group" class="form-select" required>
                            <option value="">Select</option>
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                        @error('blood_group') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Donor (optional)</label>
                        <select name="donor_id" class="form-select">
                            <option value="">No donor linked</option>
                            @foreach($donors as $d)
                                <option value="{{ $d->id }}" {{ old('donor_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->user->name }} ({{ $d->blood_group }})
                                </option>
                            @endforeach
                        </select>
                        @error('donor_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Collection Date</label>
                        <input type="date" name="collected_at" class="form-control" value="{{ old('collected_at', date('Y-m-d')) }}" required>
                        @error('collected_at') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn" style="background:var(--red);color:#fff;border-radius:8px">
                        <i class="bi bi-plus-lg me-1"></i> Add Unit
                    </button>
                    <a href="{{ route('admin.blood-units.index') }}" class="btn btn-outline-secondary" style="border-radius:8px">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

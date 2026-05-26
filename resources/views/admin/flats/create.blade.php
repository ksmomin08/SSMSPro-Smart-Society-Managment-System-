@extends('layouts.admin')
@section('title', 'Add Flat — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-home-alt"></i> Add New Flat</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('flats.index') }}">Flats</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('flats.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to List
    </a>
</div>

<div class="row animate-fadeInUp">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="card-title"><i class="bx bx-plus-circle me-2 text-primary"></i>Flat Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('flats.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="building_id">Select Building / Wing <span class="text-danger">*</span></label>
                            <select name="building_id" id="building_id" class="form-select @error('building_id') is-invalid @enderror" required>
                                <option value="">Select Building...</option>
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                        {{ $building->building_name }} ({{ $building->building_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('building_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="flate_number">Flat Number <span class="text-danger">*</span></label>
                            <input type="text" name="flate_number" id="flate_number" class="form-control @error('flate_number') is-invalid @enderror" placeholder="e.g. A-101" value="{{ old('flate_number') }}" required>
                            @error('flate_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="floor">Floor <span class="text-danger">*</span></label>
                            <input type="number" name="floor" id="floor" class="form-control @error('floor') is-invalid @enderror" placeholder="e.g. 1" value="{{ old('floor') }}" required>
                            @error('floor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="vacant" {{ old('status') == 'vacant' ? 'selected' : '' }}>Vacant</option>
                                <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                <option value="self-occupied" {{ old('status') == 'self-occupied' ? 'selected' : '' }}>Self-Occupied</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-semibold text-muted mb-3"><i class="bx bx-user me-1"></i> Owner Details (Optional)</h6>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="owner_name">Owner Name</label>
                            <input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="Owner Name" value="{{ old('owner_name') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="owner_phone">Owner Phone</label>
                            <input type="text" name="owner_phone" id="owner_phone" class="form-control" placeholder="+91 XXXXXXXXXX" value="{{ old('owner_phone') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="owner_email">Owner Email</label>
                            <input type="email" name="owner_email" id="owner_email" class="form-control" placeholder="owner@email.com" value="{{ old('owner_email') }}">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('flats.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Save Flat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
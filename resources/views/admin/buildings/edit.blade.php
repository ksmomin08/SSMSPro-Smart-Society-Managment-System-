@extends('layouts.admin')
@section('title', 'Edit Building — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-building"></i> Edit Building</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('buildings.index') }}">Buildings</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('buildings.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to List
    </a>
</div>

<div class="row animate-fadeInUp">
    <div class="col-lg-8 col-md-10">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="card-title"><i class="bx bx-edit me-2 text-primary"></i>Update Building Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('buildings.update', $building->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="building_name" class="form-label">Building / Wing Name <span class="text-danger">*</span></label>
                            <input type="text" id="building_name" name="building_name" class="form-control @error('building_name') is-invalid @enderror" value="{{ old('building_name', $building->building_name) }}" required>
                            @error('building_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="building_code" class="form-label">Building Code <span class="text-danger">*</span></label>
                            <input type="text" id="building_code" name="building_code" class="form-control @error('building_code') is-invalid @enderror" value="{{ old('building_code', $building->building_code) }}" required>
                            @error('building_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <a href="{{ route('buildings.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update Building</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
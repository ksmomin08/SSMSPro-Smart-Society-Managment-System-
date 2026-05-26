@extends('layouts.admin')
@section('title', 'Add Building — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-building"></i> Add New Building</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('buildings.index') }}">Buildings</a></li>
                <li class="breadcrumb-item active">Add New</li>
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
                <h5 class="card-title"><i class="bx bx-plus-circle me-2 text-primary"></i>Building Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('buildings.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="building_name" class="form-label">Building / Wing Name <span class="text-danger">*</span></label>
                            <input type="text" id="building_name" name="building_name" class="form-control @error('building_name') is-invalid @enderror" value="{{ old('building_name') }}" placeholder="e.g. Wing A, Tower 1" required>
                            @error('building_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="building_code" class="form-label">Building Code <span class="text-danger">*</span></label>
                            <input type="text" id="building_code" name="building_code" class="form-control @error('building_code') is-invalid @enderror" value="{{ old('building_code') }}" placeholder="e.g. A, T1, WING-B" required>
                            @error('building_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Short unique code for identification.</div>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <a href="{{ route('buildings.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Save Building</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
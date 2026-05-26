@extends('layouts.admin')
@section('title', 'Add Complaint — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-message-square-error"></i> Add Complaint</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('complaints.index') }}">Complaints</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('complaints.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to List
    </a>
</div>

<div class="row animate-fadeInUp">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="card-title"><i class="bx bx-plus-circle me-2 text-primary"></i>Complaint Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('complaints.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="resident_id" class="form-label">Resident <span class="text-danger">*</span></label>
                            <select name="resident_id" id="resident_id" class="form-select @error('resident_id') is-invalid @enderror" required>
                                <option value="">Select Resident...</option>
                                @foreach($residents ?? [] as $resident)
                                    <option value="{{ $resident->id }}" {{ old('resident_id') == $resident->id ? 'selected' : '' }}>{{ $resident->name }}</option>
                                @endforeach
                            </select>
                            @error('resident_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="title" class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Brief description of the issue" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Detailed description of the complaint..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-end mt-4">
                        <a href="{{ route('complaints.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Submit Complaint</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

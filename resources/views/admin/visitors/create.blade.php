@extends('layouts.admin')
@section('title', 'Add Visitor — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-user-voice"></i> Add Visitor</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('visitors.index') }}">Visitors</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('visitors.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to List
    </a>
</div>

<div class="row animate-fadeInUp">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="card-title"><i class="bx bx-plus-circle me-2 text-primary"></i>Visitor Entry</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('visitors.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="visitor_name" class="form-label">Visitor Name <span class="text-danger">*</span></label>
                            <input type="text" id="visitor_name" name="visitor_name" class="form-control @error('visitor_name') is-invalid @enderror" value="{{ old('visitor_name') }}" placeholder="Full name" required>
                            @error('visitor_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" id="mobile" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" placeholder="+91 XXXXXXXXXX" required>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="resident_id" class="form-label">Visiting Resident <span class="text-danger">*</span></label>
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
                            <label for="purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                            <select id="purpose" name="purpose" class="form-select" required>
                                <option value="Guest">Guest / Friends</option>
                                <option value="Delivery">Delivery / Courier</option>
                                <option value="Maintenance">Maintenance Worker</option>
                                <option value="Service">Home Helper / Maid</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="visit_date" class="form-label">Visit Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" id="visit_date" name="visit_date" class="form-control" value="{{ old('visit_date') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="vehicle_number" class="form-label">Vehicle Number</label>
                            <input type="text" id="vehicle_number" name="vehicle_number" class="form-control" value="{{ old('vehicle_number') }}" placeholder="e.g. MH-12-AB-5678">
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <a href="{{ route('visitors.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Log Visitor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
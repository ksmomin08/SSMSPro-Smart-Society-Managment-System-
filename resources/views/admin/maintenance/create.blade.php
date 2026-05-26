@extends('layouts.admin')
@section('title', 'Add Maintenance Bill — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-rupee"></i> Add Maintenance Bill</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('maintenances.index') }}">Maintenance</a></li>
                <li class="breadcrumb-item active">Add Bill</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('maintenances.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to List
    </a>
</div>

<div class="row animate-fadeInUp">
    <div class="col-lg-8 col-md-10">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="card-title"><i class="bx bx-plus-circle me-2 text-primary"></i>Bill Particulars</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('maintenances.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label" for="resident_id">Select Resident <span class="text-danger">*</span></label>
                        <select name="resident_id" id="resident_id" class="form-select @error('resident_id') is-invalid @enderror" required>
                            <option value="">Choose resident...</option>
                            @foreach($residents as $resident)
                                <option value="{{ $resident->id }}" {{ old('resident_id') == $resident->id ? 'selected' : '' }}>
                                    {{ $resident->name }} @if($resident->flat) ({{ $resident->flat->building->building_name ?? '' }} - {{ $resident->flat->flate_number }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('resident_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="month">Billing Month / Year <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                <input type="text" name="month" id="month" class="form-control @error('month') is-invalid @enderror" placeholder="e.g. October 2026" value="{{ old('month') }}" required>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="amount">Bill Amount (INR) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-rupee"></i></span>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="e.g. 2500" value="{{ old('amount') }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="due_date">Due Date <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-calendar-event"></i></span>
                            <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('maintenances.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Save Bill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
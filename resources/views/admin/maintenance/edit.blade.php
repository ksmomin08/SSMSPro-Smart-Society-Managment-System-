@extends('layouts.admin')
@section('title', 'Edit Maintenance Record — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-rupee"></i> Edit Maintenance Bill</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('maintenances.index') }}">Maintenance</a></li>
                <li class="breadcrumb-item active">Edit Bill</li>
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
                <h5 class="card-title"><i class="bx bx-edit-alt me-2 text-primary"></i>Modify Bill Particulars</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('maintenances.update', $maintenance->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Resident Account</label>
                        <input type="text" class="form-control" value="{{ $maintenance->resident->name ?? 'N/A' }}" disabled>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="month">Billing Month / Year <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                <input type="text" name="month" id="month" class="form-control @error('month') is-invalid @enderror" placeholder="e.g. October 2026" value="{{ old('month', $maintenance->month) }}" required>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="amount">Bill Amount (INR) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-rupee"></i></span>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="e.g. 2500" value="{{ old('amount', $maintenance->amount) }}" required>
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
                            <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $maintenance->due_date) }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('maintenances.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update Bill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
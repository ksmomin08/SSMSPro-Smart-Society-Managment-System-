@extends('layouts.admin')
@section('title', 'Edit Resident — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-group"></i> Edit Resident</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('residents.index') }}">Residents</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to List
    </a>
</div>

<div class="row animate-fadeInUp">
    <div class="col-lg-8 col-md-10">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="card-title"><i class="bx bx-edit-alt me-2 text-primary"></i>Modify Resident Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('residents.update', $resident->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label" for="flate_id">Assign Flat / Unit <span class="text-danger">*</span></label>
                        <select name="flate_id" id="flate_id" class="form-select @error('flate_id') is-invalid @enderror" required>
                            <option value="">Select Flat...</option>
                            @foreach($flats as $flat)
                                <option value="{{ $flat->id }}" {{ old('flate_id', $resident->flate_id) == $flat->id ? 'selected' : '' }}>
                                    {{ $flat->building->building_name ?? '' }} - {{ $flat->flate_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('flate_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="name">Resident Name <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-user"></i></span>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. John Doe" value="{{ old('name', $resident->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="email">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="john.doe@example.com" value="{{ old('email', $resident->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Phone / Mobile Number <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="e.g. 9876543210" value="{{ old('phone', $resident->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="family_members">Family Members Count</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-group"></i></span>
                            <input type="number" name="family_members" id="family_members" class="form-control @error('family_members') is-invalid @enderror" placeholder="e.g. 4" value="{{ old('family_members', $resident->family_members) }}">
                            @error('family_members')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="image">Profile Photograph</label>
                        @if($resident->image)
                            <div class="mb-3 d-flex align-items-center gap-3 p-2 border rounded" style="max-width: 320px; background: #fafafa;">
                                <img src="{{ asset('storage/residents/' . $resident->image) }}" alt="Current Photograph" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <span class="d-block font-size-xs text-muted">Current Photograph</span>
                                    <span class="d-block text-truncate fw-semibold" style="max-width: 200px;">{{ $resident->image }}</span>
                                </div>
                            </div>
                        @endif
                        <input class="form-control @error('image') is-invalid @enderror" type="file" name="image" id="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update Resident</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
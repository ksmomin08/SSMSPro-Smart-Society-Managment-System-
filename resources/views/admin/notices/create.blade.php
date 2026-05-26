@extends('layouts.admin')
@section('title', 'Post Notice — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-bell"></i> Post New Notice</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('notices.index') }}">Notices</a></li>
                <li class="breadcrumb-item active">Post New</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('notices.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to List
    </a>
</div>

<div class="row animate-fadeInUp">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="card-title"><i class="bx bx-plus-circle me-2 text-primary"></i>Notice Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('notices.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Notice Title <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. Water supply maintenance" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-select" required>
                                <option value="Announcement" {{ old('category') == 'Announcement' ? 'selected' : '' }}>Announcement</option>
                                <option value="Emergency" {{ old('category') == 'Emergency' ? 'selected' : '' }}>Emergency</option>
                                <option value="Event" {{ old('category') == 'Event' ? 'selected' : '' }}>Event</option>
                                <option value="Maintenance" {{ old('category') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="General" {{ old('category') == 'General' ? 'selected' : '' }}>General</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="notice_date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" id="notice_date" name="notice_date" class="form-control" value="{{ old('notice_date', date('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Notice Content <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Write the full notice content here..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-end mt-4">
                        <a href="{{ route('notices.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-send me-1"></i> Publish Notice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

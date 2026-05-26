@extends('layouts.admin')
@section('title', 'Register Security Guard — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-shield-quarter"></i> Register Security Guard</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.guards') }}">Guards</a></li>
                <li class="breadcrumb-item active">Register</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.guards') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to List
    </a>
</div>

<div class="row animate-fadeInUp">
    <div class="col-lg-8 col-md-10">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="card-title"><i class="bx bx-plus-circle me-2 text-primary"></i>Guard Security Credentials</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.guards.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="name">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-user"></i></span>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Officer Ram Singh" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="email">Duty Email Address <span class="text-danger">*</span> <small class="text-muted">(Login Username)</small></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="guard@society.com" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Emergency Telephone / Mobile <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="e.g. +91 98765 43210" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="password">Access Password <span class="text-danger">*</span> <small class="text-muted">(Min 6 characters)</small></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter secure login password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.guards') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Register Guard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

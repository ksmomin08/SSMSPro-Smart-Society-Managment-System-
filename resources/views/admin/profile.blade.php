@extends('layouts.admin')
@section('title', 'My Profile — Smart Society')

@section('content')
<div class="page-header">
    <div>
        <h4 class="page-title"><i class="bx bx-user"></i> My Profile</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row animate-fadeInUp">
    <!-- Profile Card -->
    <div class="col-lg-4 col-md-5 mb-4">
        <div class="card">
            <div class="card-body text-center pt-4">
                <div class="mb-3">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=696cff&color=fff&size=120' }}" 
                         alt="Avatar" class="rounded-circle" width="100" height="100" style="object-fit:cover;border:3px solid #696cff;">
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <span class="badge bg-label-primary rounded-pill px-3 py-1 mb-3">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                
                <div class="info-list mt-3 text-start">
                    <div class="info-item">
                        <span class="info-label"><i class="bx bx-envelope me-1"></i> Email</span>
                        <span class="info-value">{{ $user->email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bx bx-phone me-1"></i> Phone</span>
                        <span class="info-value">{{ $user->phone ?? 'Not set' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bx bx-calendar me-1"></i> Joined</span>
                        <span class="info-value">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($user->society)
                    <div class="info-item">
                        <span class="info-label"><i class="bx bx-building me-1"></i> Society</span>
                        <span class="info-value">{{ $user->society->name ?? 'N/A' }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Forms -->
    <div class="col-lg-8 col-md-7">
        <!-- Update Profile -->
        <div class="card form-card mb-4">
            <div class="card-header">
                <h5 class="card-title"><i class="bx bx-edit me-2 text-primary"></i>Update Profile</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+91 XXXXXXXXXX">
                        </div>
                        <div class="col-md-6">
                            <label for="avatar" class="form-label">Profile Photo</label>
                            <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
                            <div class="form-text">Max 2MB. JPG, JPEG, PNG only.</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card form-card">
            <div class="card-header">
                <h5 class="card-title"><i class="bx bx-lock-alt me-2 text-warning"></i>Change Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.change-password') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                            @error('current_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" id="new_password" name="new_password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="new_password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-warning"><i class="bx bx-lock me-1"></i> Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

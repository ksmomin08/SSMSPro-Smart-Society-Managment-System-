@extends('layouts.admin')

@section('header_title', 'Register New Society')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <form action="{{ route('super-admin.societies.store') }}" method="POST" class="glass-card">
            @csrf

            <div class="border-bottom border-secondary border-opacity-25 pb-3 mb-4">
                <h5 class="mb-1 font-weight-bold">🏢 Housing Society Parameters</h5>
                <p class="text-muted mb-0 font-size-sm">Provide corporate name, tenant code, and contact information for the society.</p>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">SOCIETY NAME *</label>
                    <input type="text" name="name" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" placeholder="e.g. Emerald Heights Co-operative" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">TENANT CODE * (UNIQUE ID)</label>
                    <input type="text" name="code" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" placeholder="e.g. EMHL" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">SOCIETY EMAIL ADDRESS</label>
                    <input type="email" name="email" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" placeholder="contact@emeraldheights.com">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">SOCIETY TELEPHONE</label>
                    <input type="text" name="phone" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" placeholder="+1 (555) 019-2834">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">PHYSICAL ADDRESS</label>
                <textarea name="address" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" rows="3" placeholder="102 Luxury Green Blvd, Sector 45..."></textarea>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">SUBSCRIPTION PLAN *</label>
                    <select name="subscription_plan" class="form-select bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" required>
                        <option value="Basic">Basic Plan</option>
                        <option value="Premium">Premium Plan</option>
                        <option value="Elite" selected>Elite SaaS Plan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">LICENSE EXPIRATION DATE *</label>
                    <input type="date" name="expires_at" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" value="{{ date('Y-m-d', strtotime('+1 year')) }}" required>
                </div>
            </div>

            <div class="border-bottom border-secondary border-opacity-25 pb-3 mb-4 pt-3">
                <h5 class="mb-1 font-weight-bold">👤 Primary Society Admin Account</h5>
                <p class="text-muted mb-0 font-size-sm">Provision the main credentials for the society administrator who will configure building blocks.</p>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">ADMINISTRATOR NAME *</label>
                    <input type="text" name="admin_name" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" placeholder="e.g. Stephen Strange" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">ADMIN EMAIL ADDRESS *</label>
                    <input type="email" name="admin_email" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" placeholder="admin@emeraldheights.com" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">ADMIN ACCESS PASSWORD *</label>
                    <input type="password" name="admin_password" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" placeholder="Minimum 6 characters" required>
                </div>
            </div>

            <div class="mt-4 pt-3 d-flex justify-content-end gap-3">
                <a href="{{ route('super-admin.societies') }}" class="btn btn-glass">
                    ❌ Cancel
                </a>
                <button type="submit" class="btn btn-accent px-4">
                    ✨ Create Tenant & Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

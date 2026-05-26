@extends('layouts.admin')

@section('header_title', 'Modify Society')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <form action="{{ route('super-admin.societies.update', $society->id) }}" method="POST" class="glass-card">
            @csrf
            @method('PUT')

            <div class="border-bottom border-secondary border-opacity-25 pb-3 mb-4">
                <h5 class="mb-1 font-weight-bold">🏢 Housing Society Parameters</h5>
                <p class="text-muted mb-0 font-size-sm">Modify tenant details and configure operational bounds.</p>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">SOCIETY NAME *</label>
                    <input type="text" name="name" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" value="{{ $society->name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">TENANT CODE * (UNIQUE ID)</label>
                    <input type="text" name="code" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" value="{{ $society->code }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">SOCIETY EMAIL ADDRESS</label>
                    <input type="email" name="email" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" value="{{ $society->email }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">SOCIETY TELEPHONE</label>
                    <input type="text" name="phone" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" value="{{ $society->phone }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">PHYSICAL ADDRESS</label>
                <textarea name="address" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" rows="3">{{ $society->address }}</textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">SUBSCRIPTION PLAN *</label>
                    <select name="subscription_plan" class="form-select bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" required>
                        <option value="Basic" {{ $society->subscription_plan === 'Basic' ? 'selected' : '' }}>Basic Plan</option>
                        <option value="Premium" {{ $society->subscription_plan === 'Premium' ? 'selected' : '' }}>Premium Plan</option>
                        <option value="Elite" {{ $society->subscription_plan === 'Elite' ? 'selected' : '' }}>Elite SaaS Plan</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">OPERATIONAL STATUS *</label>
                    <select name="status" class="form-select bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" required>
                        <option value="active" {{ $society->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $society->status === 'inactive' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">LICENSE EXPIRATION DATE *</label>
                    <input type="date" name="expires_at" class="form-control bg-dark border-secondary border-opacity-50 text-white p-3 rounded-3" value="{{ date('Y-m-d', strtotime($society->expires_at)) }}" required>
                </div>
            </div>

            <div class="mt-4 pt-3 d-flex justify-content-end gap-3">
                <a href="{{ route('super-admin.societies') }}" class="btn btn-glass">
                    ❌ Cancel
                </a>
                <button type="submit" class="btn btn-accent px-4">
                    ✨ Save Parameters
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('title', 'Security Guards — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-shield-quarter"></i> Security Guards</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Guards</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.guards.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Register Guard
    </a>
</div>

<div class="card table-card animate-fadeInUp">
    <div class="card-header">
        <h5 class="card-title mb-0">Active Security Personnel</h5>
        <div class="table-search-bar">
            <!-- Simple placeholder form if they want searching, but we'll align with generic layout -->
            <form method="GET" action="{{ route('admin.guards') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search guards...">
                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bx bx-search"></i></button>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Officer Profile</th>
                    <th>Duty Email</th>
                    <th>Emergency Mobile</th>
                    <th>Shift Status</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guards as $guard)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-initial-text me-2" style="background:rgba(105,108,255,0.12);color:#696cff;width:38px;height:38px;font-size:0.8rem;">
                                {{ strtoupper(substr($guard->name, 0, 2)) }}
                            </div>
                            <div>
                                <strong class="d-block">{{ $guard->name }}</strong>
                                <small class="text-muted">Staff ID: #{{ $guard->id }}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="text-muted">{{ $guard->email }}</span></td>
                    <td><span class="text-muted"><i class="bx bx-phone me-1 font-size-sm"></i>{{ $guard->phone }}</span></td>
                    <td>
                        @if($guard->status === 'active')
                            <span class="badge badge-status badge-active"><i class="bx bx-check-shield me-1"></i>On Duty</span>
                        @else
                            <span class="badge badge-status badge-inactive"><i class="bx bx-lock-open me-1"></i>Off Duty</span>
                        @endif
                    </td>
                    <td><small class="text-muted">{{ $guard->created_at->format('M d, Y') }}</small></td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('admin.guards.edit', $guard->id) }}" class="btn btn-icon-edit" title="Edit Profile">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <form action="{{ route('admin.guards.destroy', $guard->id) }}" method="POST" class="delete-form" id="delete-guard-{{ $guard->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-icon-delete" title="Revoke access" onclick="confirmDelete('delete-guard-{{ $guard->id }}')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bx bx-shield-quarter"></i>
                            <h6>No Security Guards Registered Yet</h6>
                            <p>Provision accounts for gate guards to enable log ins at the Guard gate console.</p>
                            <a href="{{ route('admin.guards.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-plus me-1"></i> Register First Guard</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($guards->hasPages())
    <div class="card-footer d-flex justify-content-end py-3">
        {{ $guards->links() }}
    </div>
    @endif
</div>
@endsection


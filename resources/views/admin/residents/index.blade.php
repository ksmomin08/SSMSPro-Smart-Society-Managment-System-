@extends('layouts.admin')
@section('title', 'Residents — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-group"></i> Residents</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Residents</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('residents.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Add Resident
    </a>
</div>

<div class="card table-card animate-fadeInUp">
    <div class="card-header">
        <h5 class="card-title mb-0">All Residents</h5>
        <div class="table-search-bar">
            <form method="GET" action="{{ route('residents.index') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Search by name, email, phone...">
                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bx bx-search"></i></button>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Resident</th>
                    <th>Flat / Unit</th>
                    <th>Contact Information</th>
                    <th>Family Members</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($residents as $resident)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($resident->image)
                                <img src="{{ asset('storage/residents/' . $resident->image) }}" alt="Avatar" class="rounded-circle me-2" style="width: 38px; height: 38px; object-fit: cover;">
                            @else
                                <div class="avatar-initial-text me-2" style="background:rgba(105,108,255,0.12);color:#696cff;width:38px;height:38px;">
                                    {{ strtoupper(substr($resident->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <strong class="d-block">{{ $resident->name }}</strong>
                                <small class="text-muted">ID: #{{ $resident->id }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($resident->flat)
                            <span class="badge bg-label-info">
                                {{ $resident->flat->building->building_name ?? '' }} - {{ $resident->flat->flate_number }}
                            </span>
                        @else
                            <span class="badge bg-label-secondary">Not Assigned</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-column font-size-sm">
                            <span><i class="bx bx-envelope text-muted me-1"></i>{{ $resident->email }}</span>
                            <span><i class="bx bx-phone text-muted me-1"></i>{{ $resident->phone }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-status badge-info-custom">
                            <i class="bx bx-group me-1"></i>{{ $resident->family_members }} Members
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('residents.edit', $resident->id) }}" class="btn btn-icon-edit" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <form action="{{ route('residents.destroy', $resident->id) }}" method="POST" class="delete-form" id="delete-resident-{{ $resident->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-icon-delete" title="Delete" onclick="confirmDelete('delete-resident-{{ $resident->id }}')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bx bx-group"></i>
                            <h6>No Residents Found</h6>
                            <p>Get started by registering residents and assigning them to flats.</p>
                            <a href="{{ route('residents.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-plus me-1"></i> Add First Resident</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($residents->hasPages())
    <div class="card-footer d-flex justify-content-end py-3">
        {{ $residents->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection


@extends('layouts.admin')
@section('title', 'Flats — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-home-alt"></i> Flats / Units</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Flats</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('flats.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Add Flat
    </a>
</div>

<div class="card table-card animate-fadeInUp">
    <div class="card-header">
        <h5 class="card-title mb-0">All Flats</h5>
        <div class="table-search-bar">
            <form method="GET" action="{{ route('flats.index') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Search by flat number...">
                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bx bx-search"></i></button>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Flat Number</th>
                    <th>Floor</th>
                    <th>Building / Wing</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($flats as $index => $flat)
                <tr>
                    <td>{{ $flats->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-initial-text me-2" style="background:rgba(3,195,236,0.12);color:#03c3ec;">
                                <i class="bx bx-home-alt" style="font-size:1rem;"></i>
                            </div>
                            <strong>{{ $flat->flat_number ?? $flat->flate_number }}</strong>
                        </div>
                    </td>
                    <td><span class="badge bg-label-info">Floor {{ $flat->floor }}</span></td>
                    <td>
                        <span class="badge bg-label-primary">{{ $flat->building->building_name ?? 'N/A' }}</span>
                    </td>
                    <td>
                        @if($flat->resident)
                            <span class="badge badge-status badge-active"><i class="bx bx-check-circle me-1"></i>Occupied</span>
                        @else
                            <span class="badge badge-status badge-pending"><i class="bx bx-time me-1"></i>Vacant</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('flats.edit', $flat->id) }}" class="btn btn-icon-edit" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <form action="{{ route('flats.destroy', $flat->id) }}" method="POST" class="delete-form" id="delete-flat-{{ $flat->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-icon-delete" title="Delete" onclick="confirmDelete('delete-flat-{{ $flat->id }}')">
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
                            <i class="bx bx-home-alt"></i>
                            <h6>No Flats Added Yet</h6>
                            <p>Add flats to your buildings to start managing residents.</p>
                            <a href="{{ route('flats.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-plus me-1"></i> Add First Flat</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($flats->hasPages())
    <div class="card-footer d-flex justify-content-end py-3">
        {{ $flats->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
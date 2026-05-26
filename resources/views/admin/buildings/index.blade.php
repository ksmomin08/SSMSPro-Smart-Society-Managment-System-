@extends('layouts.admin')
@section('title', 'Buildings — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-building"></i> Buildings / Wings</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Buildings</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('buildings.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Add Building
    </a>
</div>

<div class="card table-card animate-fadeInUp">
    <div class="card-header">
        <h5 class="card-title mb-0">All Buildings</h5>
        <div class="table-search-bar">
            <form method="GET" action="{{ route('buildings.index') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Search by name or code...">
                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bx bx-search"></i></button>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Building Name</th>
                    <th>Building Code</th>
                    <th>Total Flats</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($buildings as $index => $building)
                <tr>
                    <td><span class="fw-semibold">{{ $buildings->firstItem() + $index }}</span></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-initial-text me-2" style="background:rgba(105,108,255,0.12);color:#696cff;width:36px;height:36px;font-size:0.8rem;">
                                {{ strtoupper(substr($building->building_name, 0, 2)) }}
                            </div>
                            <strong>{{ $building->building_name }}</strong>
                        </div>
                    </td>
                    <td><span class="badge bg-label-primary">{{ $building->building_code }}</span></td>
                    <td>{{ $building->flats_count ?? $building->flats->count() ?? 0 }}</td>
                    <td><small class="text-muted">{{ $building->created_at ? $building->created_at->format('M d, Y') : '-' }}</small></td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('buildings.edit', $building->id) }}" class="btn btn-icon-edit" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <form action="{{ route('buildings.destroy', $building->id) }}" method="POST" class="delete-form" id="delete-building-{{ $building->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-icon-delete" title="Delete" onclick="confirmDelete('delete-building-{{ $building->id }}')">
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
                            <i class="bx bx-building"></i>
                            <h6>No Buildings Added Yet</h6>
                            <p>Start by adding your society's buildings or wings.</p>
                            <a href="{{ route('buildings.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-plus me-1"></i> Add First Building</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($buildings->hasPages())
    <div class="card-footer d-flex justify-content-end py-3">
        {{ $buildings->links() }}
    </div>
    @endif
</div>
@endsection
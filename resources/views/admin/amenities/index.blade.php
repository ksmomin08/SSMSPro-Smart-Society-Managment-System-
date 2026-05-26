@extends('layouts.admin')
@section('title', 'Society Amenities — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-spa"></i> Society Amenities</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Amenities</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.amenities.bookings') }}" class="btn btn-outline-primary">
            <i class="bx bx-calendar me-1"></i> Booking Requests
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAmenityModal">
            <i class="bx bx-plus me-1"></i> Add Amenity
        </button>
    </div>
</div>

<div class="row animate-fadeInUp">
    @forelse($amenities as $amenity)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-initial-text me-2" style="background:rgba(105,108,255,0.12);color:#696cff;width:40px;height:40px;font-size:1.1rem;border-radius:0.5rem;">
                                    <i class="bx bx-spa"></i>
                                </div>
                                <h5 class="fw-semibold text-dark mb-0">{{ $amenity->name }}</h5>
                            </div>
                            @if($amenity->status === 'Active')
                                <span class="badge badge-status badge-active">Active</span>
                            @else
                                <span class="badge badge-status badge-inactive">{{ $amenity->status }}</span>
                            @endif
                        </div>
                        <p class="text-muted font-size-sm mb-4" style="min-height: 48px;">
                            {{ Str::limit($amenity->description, 120, '...') }}
                        </p>
                        
                        <div class="d-flex align-items-center gap-3 text-muted border-top border-light pt-3 mb-3 font-size-sm">
                            <div><i class="bx bx-group me-1"></i>Max: <strong class="text-dark">{{ $amenity->capacity ?? 'Unlimited' }}</strong></div>
                            <div>•</div>
                            <div><i class="bx bx-calendar-event me-1"></i>Bookings: <strong class="text-dark">{{ $amenity->bookings_count ?? $amenity->bookings->count() ?? 0 }}</strong></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-auto">
                        <button type="button" class="btn btn-sm btn-outline-primary flex-grow-1" onclick="openEditModal({{ $amenity->id }}, '{{ addslashes($amenity->name) }}', '{{ addslashes($amenity->description) }}', {{ $amenity->capacity ?? 'null' }}, '{{ $amenity->status }}')">
                            <i class="bx bx-cog me-1"></i> Modify
                        </button>
                        <form action="{{ route('admin.amenities.destroy', $amenity->id) }}" method="POST" class="flex-grow-1" id="delete-amenity-{{ $amenity->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="confirmDelete('delete-amenity-{{ $amenity->id }}')">
                                <i class="bx bx-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 py-5">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="bx bx-spa"></i>
                        <h6>No Amenities Added Yet</h6>
                        <p>Configure society common facilities like clubhouse, swimming pool, sports court, etc.</p>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createAmenityModal">
                            <i class="bx bx-plus me-1"></i> Add First Facility
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Create Amenity Modal -->
<div class="modal fade" id="createAmenityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.amenities.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-semibold text-dark"><i class="bx bx-plus-circle text-primary me-2"></i>Add New Facility</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark" for="facility_name">Facility Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="facility_name" class="form-control" placeholder="e.g. Swimming Pool, Community Hall" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark" for="facility_desc">Guidelines & Description</label>
                    <textarea name="description" id="facility_desc" class="form-control" rows="3" placeholder="Specify booking hours, strict rules, dress code, etc."></textarea>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label fw-medium text-dark" for="facility_cap">Max Capacity <small class="text-muted">(Optional)</small></label>
                        <input type="number" name="capacity" id="facility_cap" min="1" class="form-control" placeholder="e.g. 30">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-medium text-dark" for="facility_status">Operational Status <span class="text-danger">*</span></label>
                        <select name="status" id="facility_status" class="form-select" required>
                            <option value="Active">Active</option>
                            <option value="Maintenance">Under Maintenance</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Save Facility</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Amenity Modal -->
<div class="modal fade" id="editAmenityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editAmenityForm" action="" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-semibold text-dark"><i class="bx bx-cog text-primary me-2"></i>Modify Facility Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark" for="edit_name">Facility Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark" for="edit_description">Guidelines & Description</label>
                    <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label fw-medium text-dark" for="edit_capacity">Max Capacity <small class="text-muted">(Optional)</small></label>
                        <input type="number" name="capacity" id="edit_capacity" min="1" class="form-control">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-medium text-dark" for="edit_status">Operational Status <span class="text-danger">*</span></label>
                        <select name="status" id="edit_status" class="form-select" required>
                            <option value="Active">Active</option>
                            <option value="Maintenance">Under Maintenance</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update Facility</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, name, desc, capacity, status) {
        document.getElementById('editAmenityForm').action = `/admin/amenities/${id}/update`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = desc;
        document.getElementById('edit_capacity').value = capacity !== null ? capacity : '';
        document.getElementById('edit_status').value = status;
        
        const editModal = new bootstrap.Modal(document.getElementById('editAmenityModal'));
        editModal.show();
    }
</script>
@endsection

@extends('layouts.admin')
@section('title', 'Parking Administration — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-car"></i> Parking Administration</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Parking</li>
            </ol>
        </nav>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSlotModal">
        <i class="bx bx-plus me-1"></i> Create Parking Slot
    </button>
</div>

<div class="row animate-fadeInUp">
    <!-- Left Column: Slot Grid Map -->
    <div class="col-xl-8 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="bx bx-map-alt me-1 text-primary"></i> Interactive Slot Map</h5>
                <span class="badge bg-label-info font-size-xs">Total: {{ $slots->count() }} Slots</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    @forelse($slots as $slot)
                        <div class="col-md-4 col-sm-6">
                            <div class="card border h-100 shadow-none hover-shadow transition-all" style="border-color: {{ $slot->status === 'Allocated' ? 'rgba(255, 62, 29, 0.15)' : 'rgba(113, 221, 55, 0.15)' }} !important; background: {{ $slot->status === 'Allocated' ? 'rgba(255, 62, 29, 0.02)' : 'rgba(113, 221, 55, 0.02)' }};">
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted font-size-xs fw-semibold">
                                                {!! $slot->vehicle_type === '4-wheeler' ? '<i class="bx bx-car me-1"></i>4-Wheel' : '<i class="bx bx-cycling me-1"></i>2-Wheel' !!}
                                            </span>
                                            @if($slot->status === 'Allocated')
                                                <span class="badge badge-status badge-inactive">Allocated</span>
                                            @else
                                                <span class="badge badge-status badge-active">Available</span>
                                            @endif
                                        </div>
                                        <h4 class="fw-bold text-dark my-2 text-center">{{ $slot->slot_number }}</h4>
                                        
                                        @if($slot->status === 'Allocated' && $slot->flat)
                                            <div class="mt-2 p-2 rounded text-center font-size-xs text-muted" style="background: rgba(0,0,0,0.02);">
                                                <span class="d-block text-dark fw-semibold">Flat: {{ $slot->flat->flate_number }}</span>
                                                <span class="d-block text-truncate">Owner: {{ $slot->flat->owner_name ?? 'N/A' }}</span>
                                            </div>
                                        @else
                                            <div class="mt-2 text-success font-size-xs text-center fw-semibold py-2">
                                                <i class="bx bx-check-circle me-1"></i>Available for Allocation
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-3 pt-2 border-top border-light">
                                        @if($slot->status === 'Allocated')
                                            <form action="{{ route('admin.parking.release', $slot->id) }}" method="POST" class="delete-form" id="release-slot-{{ $slot->id }}">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-outline-danger w-100 py-1" onclick="confirmRelease('release-slot-{{ $slot->id }}')">
                                                    <i class="bx bx-lock-open-alt me-1"></i> Release Slot
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-primary w-100 py-1" onclick="openAllocateModal({{ $slot->id }}, '{{ addslashes($slot->slot_number) }}')">
                                                <i class="bx bx-key me-1"></i> Allocate Slot
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="bx bx-car"></i>
                                <h6>No Parking Slots Registered</h6>
                                <p>Generate parking spaces to allocate slots to society residents.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Parking Allocation Requests -->
    <div class="col-xl-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bx bx-receipt me-1 text-primary"></i> Pending Requests</h5>
            </div>
            <div class="card-body p-4" style="max-height: 520px; overflow-y: auto;">
                <div class="d-flex flex-column gap-3">
                    @forelse($requests as $req)
                        <div class="card border shadow-none bg-light p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-semibold text-dark mb-0">
                                    Flat {{ $req->resident && $req->resident->flat ? $req->resident->flat->flate_number : 'N/A' }}
                                </h6>
                                <span class="badge bg-label-info text-capitalize">{{ $req->vehicle_type }}</span>
                            </div>
                            <p class="text-muted font-size-xs mb-2">
                                Resident: <strong class="text-dark">{{ $req->resident ? $req->resident->name : 'N/A' }}</strong><br>
                                Vehicle: <strong class="text-dark">{{ $req->vehicle_name }}</strong> ({{ $req->vehicle_number }})
                            </p>
                            
                            <div class="d-flex gap-2 mt-2 pt-2 border-top border-light">
                                <form action="{{ route('admin.parking.approve-req', $req->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success w-100 py-1">
                                        <i class="bx bx-check"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.parking.reject-req', $req->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-1">
                                        <i class="bx bx-x"></i> Decline
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state py-5">
                            <i class="bx bx-list-check"></i>
                            <h6>No Requests</h6>
                            <p class="font-size-xs mb-0">Pending resident requests will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Slot Modal -->
<div class="modal fade" id="createSlotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.parking.store-slot') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-semibold text-dark"><i class="bx bx-plus-circle text-primary me-2"></i>Create Parking Slot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark" for="slot_number">Slot Number / Label <span class="text-danger">*</span></label>
                    <input type="text" name="slot_number" id="slot_number" class="form-control" placeholder="e.g. P-105 (Basement)" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark" for="vehicle_type">Vehicle Compatibility <span class="text-danger">*</span></label>
                    <select name="vehicle_type" id="vehicle_type" class="form-select" required>
                        <option value="4-wheeler">4-wheeler (Car/SUV)</option>
                        <option value="2-wheeler">2-wheeler (Bike/Scooter)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Save Parking Slot</button>
            </div>
        </form>
    </div>
</div>

<!-- Allocate Slot Modal -->
<div class="modal fade" id="allocateSlotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="allocateSlotForm" action="" method="POST" class="modal-content">
            @csrf
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-semibold text-dark"><i class="bx bx-key text-primary me-2"></i>Allocate Slot <span id="allocating_slot_label" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark" for="flate_id">Select Allocated Flat <span class="text-danger">*</span></label>
                    <select name="flate_id" id="flate_id" class="form-select" required>
                        <option value="" disabled selected>Select a Flat...</option>
                        @foreach($flats as $flat)
                            <option value="{{ $flat->id }}">
                                {{ $flat->flate_number }} (Owner: {{ $flat->owner_name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-check me-1"></i> Confirm Allocation</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAllocateModal(id, slotName) {
        document.getElementById('allocateSlotForm').action = `/admin/parking/allocate/${id}`;
        document.getElementById('allocating_slot_label').innerText = slotName;
        
        const allocModal = new bootstrap.Modal(document.getElementById('allocateSlotModal'));
        allocModal.show();
    }

    function confirmRelease(formId) {
        Swal.fire({
            title: 'Release Slot Assignment?',
            text: "This slot will become available for general allocation.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff3e1d',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Yes, release it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endsection


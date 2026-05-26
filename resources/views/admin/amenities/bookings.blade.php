@extends('layouts.admin')
@section('title', 'Facility Bookings — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-calendar"></i> Facility Booking Ledger</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.amenities') }}">Amenities</a></li>
                <li class="breadcrumb-item active">Bookings</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.amenities') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to Facilities
    </a>
</div>

<div class="card table-card animate-fadeInUp">
    <div class="card-header">
        <h5 class="card-title mb-0">Booking Requests</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Resident</th>
                    <th>Requested Facility</th>
                    <th>Booking Date</th>
                    <th>Requested Slot</th>
                    <th>Decision Status</th>
                    <th>Action Controls</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $book)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-initial-text me-2" style="background:rgba(105,108,255,0.12);color:#696cff;width:34px;height:34px;font-size:0.75rem;">
                                {{ strtoupper(substr($book->resident->name ?? 'R', 0, 2)) }}
                            </div>
                            <div>
                                <strong class="d-block">{{ $book->resident ? $book->resident->name : 'Unknown Resident' }}</strong>
                                <small class="text-muted"><i class="bx bx-home-alt me-1 font-size-xs"></i>Flat: {{ $book->resident && $book->resident->flat ? $book->resident->flat->flate_number : 'N/A' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-label-primary"><i class="bx bx-spa me-1"></i>{{ $book->amenity ? $book->amenity->name : 'Deleted Facility' }}</span>
                    </td>
                    <td>
                        <small class="text-muted font-weight-semibold"><i class="bx bx-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($book->booking_date)->format('M d, Y') }}</small>
                    </td>
                    <td>
                        <strong class="text-dark font-size-sm"><i class="bx bx-time-five me-1 text-muted"></i>{{ \Carbon\Carbon::parse($book->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($book->end_time)->format('h:i A') }}</strong>
                    </td>
                    <td>
                        @if($book->status === 'Approved')
                            <span class="badge badge-status badge-active"><i class="bx bx-check-circle me-1"></i>Approved</span>
                        @elseif($book->status === 'Pending')
                            <span class="badge badge-status badge-pending"><i class="bx bx-time me-1"></i>Pending</span>
                        @else
                            <span class="badge badge-status badge-inactive"><i class="bx bx-x-circle me-1"></i>Declined</span>
                        @endif
                    </td>
                    <td>
                        @if($book->status === 'Pending')
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.amenities.approve', $book->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bx bx-check me-1"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.amenities.reject', $book->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bx bx-x me-1"></i> Decline
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-muted font-size-xs fw-semibold"><i class="bx bx-lock-alt me-1"></i>Processed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bx bx-calendar"></i>
                            <h6>No Booking Requests Found</h6>
                            <p>When residents book clubhouse or pool slots, they will appear here for validation.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div class="card-footer d-flex justify-content-end py-3">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection


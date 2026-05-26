@extends('layouts.admin')

@section('styles')
<style>
    .glowing-modal-btn {
        animation: pulse-glow 2s infinite;
    }
    @keyframes pulse-glow {
        0% { box-shadow: 0 0 0 0 rgba(40, 199, 111, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(40, 199, 111, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 199, 111, 0); }
    }
    .virtual-pass-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .virtual-pass-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 150px;
        height: 150px;
        background: rgba(105, 108, 255, 0.15);
        border-radius: 50%;
        filter: blur(40px);
    }
    .qr-container {
        background: #fff;
        padding: 10px;
        border-radius: 12px;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<!-- Header Banner Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white overflow-hidden" style="position: relative;">
            <!-- Ambient glow behind banner -->
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.15); border-radius: 50%; filter: blur(50px);"></div>
            
            <div class="d-flex align-items-end row">
                <div class="col-sm-8">
                    <div class="card-body">
                        <h4 class="card-title text-white mb-2">Welcome Home, {{ $resident->name }}! 🏡</h4>
                        <p class="mb-4 text-white-50">
                            Flat <span class="badge bg-white text-primary fw-bold px-2.5 py-1.5">{{ $resident->flats->building->building_name ?? 'Wing' }} - {{ $resident->flats->flate_number ?? 'N/A' }}</span> &nbsp;|&nbsp;
                            Society Code: <span class="fw-semibold text-white">{{ $resident->society->code ?? 'N/A' }}</span>
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-sm btn-white text-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#preApproveModal">
                                <i class="bx bx-user-plus me-1"></i> Pre-approve Visitor
                            </button>
                            <button class="btn btn-sm btn-outline-white text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#raiseComplaintModal">
                                <i class="bx bx-message-square-error me-1"></i> Raise Ticket
                            </button>
                            <button class="btn btn-sm btn-outline-white text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#bookAmenityModal">
                                <i class="bx bx-calendar me-1"></i> Book Facility
                            </button>
                            <button class="btn btn-sm btn-outline-white text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#requestParkingModal">
                                <i class="bx bx-car me-1"></i> Request Parking
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-center text-sm-left d-none d-sm-block">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('admin_assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="Dashboard Banner">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Card Row -->
<div class="row mb-4">
    <!-- Stat 1: Total Dues -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <span class="avatar-initial rounded bg-label-danger p-2"><i class="bx bx-rupee fs-4"></i></span>
                    <span class="badge bg-label-danger rounded-pill">Outstanding</span>
                </div>
                <span class="fw-semibold d-block mb-1 text-muted">Maintenance Dues</span>
                @php
                    $pendingDues = $maintenances->where('payment_status', 'unpaid')->sum('amount');
                @endphp
                <h3 class="card-title mb-1">₹{{ number_format($pendingDues, 2) }}</h3>
                <small class="text-muted">For unpaid invoices</small>
            </div>
        </div>
    </div>

    <!-- Stat 2: Active Visitors -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <span class="avatar-initial rounded bg-label-primary p-2"><i class="bx bx-qr-scan fs-4"></i></span>
                    <span class="badge bg-label-primary rounded-pill">Approved Passes</span>
                </div>
                <span class="fw-semibold d-block mb-1 text-muted">Active Gate Passes</span>
                <h3 class="card-title mb-1">{{ $visitors->where('status', 'Approved')->count() }}</h3>
                <small class="text-muted">Total pre-approved</small>
            </div>
        </div>
    </div>

    <!-- Stat 3: Open Support Tickets -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <span class="avatar-initial rounded bg-label-warning p-2"><i class="bx bx-message-alt-error fs-4"></i></span>
                    <span class="badge bg-label-warning rounded-pill">In Progress</span>
                </div>
                <span class="fw-semibold d-block mb-1 text-muted">Active Tickets</span>
                <h3 class="card-title mb-1">{{ $complaints->where('status', 'pending')->count() }}</h3>
                <small class="text-muted">Pending admin resolution</small>
            </div>
        </div>
    </div>

    <!-- Stat 4: Facility Bookings -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <span class="avatar-initial rounded bg-label-success p-2"><i class="bx bx-calendar-star fs-4"></i></span>
                    <span class="badge bg-label-success rounded-pill">Scheduled</span>
                </div>
                <span class="fw-semibold d-block mb-1 text-muted">Amenity Bookings</span>
                <h3 class="card-title mb-1">{{ $bookings->count() }}</h3>
                <small class="text-muted">Bookings raised</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Core Workflows -->
    <div class="col-lg-8 col-md-12">
        <!-- Section 1: Notice Board -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0"><i class="bx bx-notification me-2 text-primary"></i>📢 Community Notice Board</h5>
            </div>
            <div class="card-body">
                @if($notices->isEmpty())
                    <div class="text-center py-3 text-muted">
                        <i class="bx bx-info-circle fs-3 mb-2"></i>
                        <p class="mb-0">No announcements from management recently.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($notices as $notice)
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex w-100 justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 fw-bold">{{ $notice->title }}</h6>
                                    <span class="badge bg-label-{{ $notice->category == 'Emergency' ? 'danger' : ($notice->category == 'Announcement' ? 'primary' : 'warning') }} fs-tiny">
                                        {{ $notice->category }}
                                    </span>
                                </div>
                                <p class="text-muted mb-2 fs-7">{{ $notice->description }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><i class="bx bx-time-five me-1"></i>Posted {{ \Carbon\Carbon::parse($notice->notice_date)->format('M d, Y') }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 2: Maintenance Bills -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0"><i class="bx bx-wallet me-2 text-success"></i>💰 Maintenance & Utility Bills</h5>
            </div>
            <div class="card-body">
                @if($maintenances->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-smile fs-1 text-success mb-2"></i>
                        <h6 class="fw-bold">No Maintenance Dues!</h6>
                        <p class="mb-0">Your account is currently clear of all outstanding charges.</p>
                    </div>
                @else
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Amount</th>
                                    <th>Late Fee</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach($maintenances as $bill)
                                    <tr>
                                        <td><strong>{{ $bill->month }}</strong></td>
                                        <td>₹{{ number_format($bill->amount, 2) }}</td>
                                        <td>
                                            @if($bill->late_fee > 0)
                                                <span class="text-danger">+ ₹{{ number_format($bill->late_fee, 2) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($bill->due_date)->format('M d, Y') }}</td>
                                        <td>
                                            @if($bill->payment_status === 'paid')
                                                <span class="badge bg-label-success">Paid</span>
                                            @else
                                                <span class="badge bg-label-danger">Unpaid</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($bill->payment_status === 'paid')
                                                @php
                                                    $payRecord = \App\Models\Payment::where('maintenance_id', $bill->id)->first();
                                                @endphp
                                                @if($payRecord)
                                                    <a href="{{ route('resident.maintenance.receipt', $payRecord->id) }}" class="btn btn-xs btn-outline-success">
                                                        <i class="bx bx-download me-1"></i> Receipt
                                                    </a>
                                                @else
                                                    <span class="text-muted fs-7">Offline Logged</span>
                                                @endif
                                            @else
                                                <button class="btn btn-xs btn-primary glowing-modal-btn" onclick="triggerMockPayment({{ $bill->id }}, {{ $bill->amount + $bill->late_fee }}, '{{ $bill->month }}')">
                                                    <i class="bx bx-credit-card me-1"></i> Pay Now
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 3: Support Tickets -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0"><i class="bx bx-support me-2 text-warning"></i>🛠️ Support Tickets & Complaints</h5>
                <button class="btn btn-xs btn-outline-primary" data-bs-toggle="modal" data-bs-target="#raiseComplaintModal">Raise New Ticket</button>
            </div>
            <div class="card-body">
                @if($complaints->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-check-shield fs-1 text-primary mb-2"></i>
                        <p class="mb-0">No complaints registered. Your flat has zero open issues!</p>
                    </div>
                @else
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover" id="complaintsTable">
                            <thead>
                                <tr>
                                    <th>Issue</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Admin Reply</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complaints as $ticket)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">{{ $ticket->title }}</span>
                                                <small class="text-muted fs-7 text-wrap">{{ $ticket->description }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $ticket->category }}</td>
                                        <td>
                                            <span class="badge bg-label-{{ $ticket->priority == 'High' || $ticket->priority == 'Critical' ? 'danger' : ($ticket->priority == 'Medium' ? 'warning' : 'info') }}">
                                                {{ $ticket->priority }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $ticket->status == 'resolved' ? 'success' : 'warning' }}">
                                                {{ ucfirst($ticket->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($ticket->admin_reply)
                                                <span class="text-success fs-7 d-block text-wrap" style="max-width: 200px;"><i class="bx bx-check-double"></i> {{ $ticket->admin_reply }}</span>
                                            @else
                                                <span class="text-muted fs-7"><i class="bx bx-time"></i> Awaiting review</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Secondary Widgets & Live Actions -->
    <div class="col-lg-4 col-md-12">
        <!-- Live Security Gate HUD (Wow factor) -->
        <div class="card mb-4 border border-success border-opacity-25" style="box-shadow: 0 4px 15px rgba(40,199,111,0.1);">
            <div class="card-body text-center py-4">
                <div class="spinner-grow text-success mb-2" role="status" style="width: 15px; height: 15px;"></div>
                <h6 class="fw-bold text-success mb-1">Live Security Gate Feed</h6>
                <p class="text-muted fs-7 mb-3">Monitoring visitor requests at the main entrance gate in real-time.</p>
                <div class="p-3 bg-light rounded text-start fs-7 text-muted border border-dashed">
                    <div class="mb-1"><i class="bx bx-shield-check text-success me-1"></i> Status: <strong>Secure & Active</strong></div>
                    <div><i class="bx bx-wifi text-success me-1"></i> Latency: <strong>Active Channel</strong></div>
                </div>
            </div>
        </div>

        <!-- Section 4: Pre-approved Gate Passes -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0"><i class="bx bx-qr-scan text-primary me-2"></i>🎟️ Active Gate Passes</h5>
            </div>
            <div class="card-body">
                @php
                    $approvedVisitors = $visitors->where('status', 'Approved');
                @endphp
                @if($approvedVisitors->isEmpty())
                    <div class="text-center py-3 text-muted">
                        <p class="fs-7 mb-2">No active pre-approved visitor passes.</p>
                        <button class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#preApproveModal">Generate Pass</button>
                    </div>
                @else
                    <div class="d-flex flex-column gap-3" id="activePassesContainer">
                        @foreach($approvedVisitors as $vis)
                            <div class="border rounded p-3 bg-light position-relative">
                                <h6 class="fw-bold mb-1 text-primary">{{ $vis->visitor_name }}</h6>
                                <div class="fs-7 text-muted mb-1">Code: <strong class="text-dark">{{ $vis->visitor_code }}</strong></div>
                                <div class="fs-7 text-muted mb-1">Purpose: {{ $vis->purpose }}</div>
                                <div class="fs-7 text-muted">Expected: {{ \Carbon\Carbon::parse($vis->visit_date)->format('M d, g:i A') }}</div>
                                <div class="mt-2 d-flex gap-2">
                                    <button class="btn btn-xs btn-outline-primary" onclick="copyPassCode('{{ $vis->visitor_code }}')"><i class="bx bx-copy me-1"></i>Copy</button>
                                    <a href="https://api.whatsapp.com/send?text={{ urlencode('Here is your SmartGate Entry Pass. Code: ' . $vis->visitor_code) }}" target="_blank" class="btn btn-xs btn-outline-success"><i class="bx bxl-whatsapp me-1"></i>Share</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 5: Amenity Bookings -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0"><i class="bx bx-calendar-check text-info me-2"></i>🎪 Facility Bookings</h5>
            </div>
            <div class="card-body">
                @if($bookings->isEmpty())
                    <div class="text-center py-3 text-muted">
                        <p class="fs-7 mb-2">No facility bookings scheduled.</p>
                        <button class="btn btn-xs btn-outline-info" data-bs-toggle="modal" data-bs-target="#bookAmenityModal">Book Amenity</button>
                    </div>
                @else
                    <div class="table-responsive text-nowrap">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Facility</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $book)
                                    <tr>
                                        <td><strong>{{ $book->amenity->name ?? 'Facility' }}</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($book->booking_date)->format('M d') }}</td>
                                        <td>
                                            <span class="badge bg-label-{{ $book->status == 'Approved' ? 'success' : ($book->status == 'Pending' ? 'warning' : 'danger') }} fs-tiny p-1">
                                                {{ $book->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 6: Parking Slots -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0"><i class="bx bx-car text-warning me-2"></i>🚗 Parking Allocation</h5>
            </div>
            <div class="card-body">
                @if($allocatedSlots->isEmpty())
                    <div class="text-center py-3 text-muted">
                        <p class="fs-7 mb-2">No active parking slot allocated.</p>
                        <button class="btn btn-xs btn-outline-warning" data-bs-toggle="modal" data-bs-target="#requestParkingModal">Request Slot</button>
                    </div>
                @else
                    @foreach($allocatedSlots as $slot)
                        <div class="border rounded p-3 bg-light d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $slot->slot_number }}</h6>
                                <small class="text-muted">Type: {{ ucfirst($slot->vehicle_type) }}</small>
                            </div>
                            <span class="badge bg-label-success rounded-pill px-2.5 py-1.5">Allocated</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODALS INJECTION ==================== -->

<!-- Modal 1: Pre-approve Visitor -->
<div class="modal fade" id="preApproveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-shield-plus me-1"></i> Pre-approve Visitor Entry Pass</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="preApproveForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="visitor_name" class="form-label">Visitor Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="visitor_name" name="visitor_name" class="form-control" placeholder="e.g. Robert Downey" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="mobile" class="form-label">Visitor Contact Number <span class="text-danger">*</span></label>
                            <input type="text" id="mobile" name="mobile" class="form-control" placeholder="e.g. +91 9876543210" required>
                        </div>
                        <div class="col">
                            <label for="vehicle_number" class="form-label">Vehicle Number</label>
                            <input type="text" id="vehicle_number" name="vehicle_number" class="form-control" placeholder="e.g. MH-12-AB-5678">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                            <select id="purpose" name="purpose" class="form-select" required>
                                <option value="Guest">Guest / Friends</option>
                                <option value="Delivery">Delivery / Courier</option>
                                <option value="Maintenance">Maintenance Worker</option>
                                <option value="Service">Home Helper / Maid</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="visit_date" class="form-label">Expected Visit Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" id="visit_date" name="visit_date" class="form-control" required>
                        </div>
                    </div>

                    <!-- Virtual pass placeholder to show on successful generation -->
                    <div id="virtualPassWrapper" class="mt-4" style="display: none;">
                        <hr>
                        <div class="virtual-pass-card mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-uppercase fw-bold text-primary mb-0" style="letter-spacing: 1px;">Smart Society Pass</h6>
                                    <small class="text-white-50">Verified Resident Guest</small>
                                </div>
                                <span class="badge bg-success rounded-pill">Active</span>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h5 class="fw-bold mb-1 text-white" id="passVisitorName">Robert Downey</h5>
                                    <div class="fs-7 text-white-50 mb-2">Gate Code: <strong class="text-white fs-6" id="passVisitorCode">PASS-123456</strong></div>
                                    <div class="fs-7 text-white-50 mb-1">Expected: <span id="passVisitDate">May 21, 2026</span></div>
                                    <div class="fs-7 text-white-50">Authorized: <span class="text-white">Flat {{ $resident->flats->flate_number ?? 'Home' }}</span></div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="qr-container">
                                        <!-- Mock QR code svg -->
                                        <svg width="60" height="60" viewBox="0 0 29 29" style="background:#fff">
                                            <path d="M0 0h9v9H0zm1 1h7v7H1zm11 0h5v2h-2v2h-3v1h1v1h-1v2h2v-1h2v-2h1V1h-4zm3 1h2v2h-2zm-3 5h2v1h-2zm-12 5h9v9H0zm1 1h7v7H1zm11 1h2v1h-1v2h-1zm3 0h1v1h-1zm1 2h1v1h-1zm-3 2h2v1h-2zm3 0h3v3h-3zm1 1h1v1h-1zm-13-10h5v5H2zm1 1h3v3H3zm17-6h9v9h-9zm1 1h7v7h-7zm2 2h3v3h-3zm-11 12h2v2h-2zm2 2h3v2h-2v1h-3v-1h2zm4-2h1v2h-1zm2 1h1v1h-1zm-3 2h2v2h-2zm4 0h1v1h-1zm-1 1h1v1h-1zm-2 1h2v1h-2z" fill="#000"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 text-center border-top border-secondary pt-3 d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-light text-white" id="copyPassBtn"><i class="bx bx-copy me-1"></i>Copy Pass Link</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="preApproveFooter">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="preApproveSubmitBtn">Generate Gate Pass</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Raise Support Ticket -->
<div class="modal fade" id="raiseComplaintModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-message-square-error me-1"></i> Raise Support Ticket / Complaint</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="raiseComplaintForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="complaintTitle" class="form-label">Subject / Issue Title <span class="text-danger">*</span></label>
                        <input type="text" id="complaintTitle" name="title" class="form-control" placeholder="e.g. Water leak in guest toilet" required>
                        <small class="text-muted">Tip: Our AI model will automatically categorize and prioritize your ticket based on your description.</small>
                    </div>
                    <div class="mb-3">
                        <label for="complaintDesc" class="form-label">Detailed Description <span class="text-danger">*</span></label>
                        <textarea id="complaintDesc" name="description" class="form-control" rows="4" placeholder="Detail the issue completely (e.g., location, severity, when it started) so our operators can dispatch the correct specialist immediately." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Photo / Attachment (Optional)</label>
                        <input type="file" id="attachment" name="attachment" class="form-control" accept="image/*">
                    </div>

                    <!-- AI Suggested classification feedback container -->
                    <div id="aiClassificationWrapper" class="alert alert-success p-3" style="display: none; border-left: 5px solid #28c76f;">
                        <h6 class="fw-bold mb-1 text-success"><i class="bx bx-chip me-1"></i> Society AI Helper Active</h6>
                        <p class="fs-7 mb-2">Your ticket was submitted and categorized in real-time:</p>
                        <div class="d-flex gap-2">
                            <span>Category: <strong id="aiCategoryBadge" class="badge bg-primary">Plumbing</strong></span>
                            <span>Priority: <strong id="aiPriorityBadge" class="badge bg-danger">High</strong></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="complaintFooter">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="complaintSubmitBtn">File Support Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 3: Book Amenity -->
<div class="modal fade" id="bookAmenityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-calendar-star me-1"></i> Book Society Facility</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bookAmenityForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amenity_id" class="form-label">Select Facility <span class="text-danger">*</span></label>
                        <select id="amenity_id" name="amenity_id" class="form-select" required>
                            @forelse($amenities as $amenity)
                                <option value="{{ $amenity->id }}">{{ $amenity->name }} (Cap: {{ $amenity->capacity }} persons)</option>
                            @empty
                                <option disabled>No facilities available right now.</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="booking_date" class="form-label">Booking Date <span class="text-danger">*</span></label>
                        <input type="date" id="booking_date" name="booking_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" id="start_time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" id="end_time" name="end_time" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="bookAmenitySubmitBtn">Submit Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 4: Request Parking Slot -->
<div class="modal fade" id="requestParkingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-car me-1"></i> Request Parking Slot Allocation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="requestParkingForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="vehicle_name" class="form-label">Vehicle Make & Model <span class="text-danger">*</span></label>
                        <input type="text" id="vehicle_name" name="vehicle_name" class="form-control" placeholder="e.g. Toyota Fortuner, Honda Activa" required>
                    </div>
                    <div class="mb-3">
                        <label for="vehicle_number_req" class="form-label">Vehicle Registration Number <span class="text-danger">*</span></label>
                        <input type="text" id="vehicle_number_req" name="vehicle_number" class="form-control" placeholder="e.g. MH-12-AB-9876" required>
                    </div>
                    <div class="mb-3">
                        <label for="vehicle_type_req" class="form-label">Vehicle Classification <span class="text-danger">*</span></label>
                        <select id="vehicle_type_req" name="vehicle_type" class="form-select" required>
                            <option value="4-wheeler">4-Wheeler (Car/SUV)</option>
                            <option value="2-wheeler">2-Wheeler (Motorcycle/Scooter)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="requestParkingSubmitBtn">Submit Slot Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Live Gate Approval Notification Modal (Pops up in real-time) -->
<div class="modal fade" id="gateNotificationModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border border-danger border-3" style="box-shadow: 0 10px 40px rgba(255, 62, 29, 0.3);">
            <div class="modal-body text-center p-5">
                <div class="avatar avatar-xl bg-label-danger mx-auto mb-4 p-3 rounded-circle border-danger" style="animation: pulse-glow 1.5s infinite;">
                    <i class="bx bx-shield-quarter fs-1"></i>
                </div>
                <h4 class="fw-bold mb-2">Gate Entrance Notification!</h4>
                <p class="text-muted mb-4 fs-6">A visitor has requested entry to your flat at the security cabin.</p>
                
                <div class="bg-light rounded p-4 text-start mb-4 border">
                    <h5 class="fw-bold mb-3 text-center text-primary" id="gateVisitorName">John Walker</h5>
                    <div class="row g-2 fs-6">
                        <div class="col-6 text-muted">Purpose:</div>
                        <div class="col-6 fw-semibold" id="gateVisitorPurpose">Delivery (Amazon)</div>
                        <div class="col-6 text-muted">Vehicle No:</div>
                        <div class="col-6 fw-semibold" id="gateVisitorVehicle">MH-12-PQ-9999</div>
                        <div class="col-6 text-muted">Arrival Time:</div>
                        <div class="col-6 fw-semibold" id="gateVisitorTime">Just Now</div>
                    </div>
                </div>
                
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-danger px-4 py-2 fw-semibold fs-6" id="btnRejectVisitor"><i class="bx bx-x-circle me-1"></i> Deny Access</button>
                    <button type="button" class="btn btn-success px-4 py-2 fw-semibold fs-6 glowing-modal-btn" id="btnApproveVisitor"><i class="bx bx-check-circle me-1"></i> Approve Access</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Global function to copy text
    function copyPassCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            alert('Gate pass code copied to clipboard: ' + code);
        });
    }

    // Trigger simulated invoice billing
    function triggerMockPayment(billId, totalAmount, monthName) {
        if(confirm(`Proceed to trigger simulated payment desk for invoice month: ${monthName} (Amount: ₹${totalAmount.toFixed(2)})?`)) {
            // Mock transaction token
            const mockTxn = 'pay_SSM_' + Math.floor(Math.random() * 1000000000);
            
            $.post("{{ url('/resident/maintenance/pay') }}/" + billId, {
                _token: "{{ csrf_token() }}",
                payment_method: "Razorpay (Sandbox)",
                transaction_id: mockTxn
            }, function(res) {
                if(res.status) {
                    alert('Transaction Completed Successfully!\nReceipt ID Generated: ' + res.payment.receipt_number);
                    location.reload();
                } else {
                    alert('Error: ' + res.message);
                }
            }).fail(function() {
                alert('Connection to transaction sandbox failed. Please retry.');
            });
        }
    }

    $(document).ready(function() {
        // ==================== AJAX SUBMISSIONS FOR MODALS ====================

        // 1. Pre-approve visitor submission
        $('#preApproveForm').on('submit', function(e) {
            e.preventDefault();
            $('#preApproveSubmitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...');

            $.post("{{ route('resident.visitor.pre-approve') }}", $(this).serialize(), function(res) {
                if(res.status) {
                    // Populate virtual pass
                    $('#passVisitorName').text($('#visitor_name').val());
                    $('#passVisitorCode').text(res.visitor_code);
                    $('#passVisitDate').text(new Date($('#visit_date').val()).toLocaleString());
                    
                    // Setup copy pass link
                    $('#copyPassBtn').attr('onclick', "copyPassCode('" + res.visitor_code + "')");
                    
                    // Display pass
                    $('#virtualPassWrapper').slideDown();
                    $('#preApproveSubmitBtn').text('Pass Generated!').addClass('btn-success');
                    
                    // Reload dashboard items after short delay
                    setTimeout(function() {
                        alert('Pass code generated: ' + res.visitor_code);
                    }, 500);
                } else {
                    alert('Failed to generate pass: ' + res.message);
                    $('#preApproveSubmitBtn').prop('disabled', false).text('Generate Gate Pass');
                }
            }).fail(function() {
                alert('Connection to Gate Pass servers failed. Please retry.');
                $('#preApproveSubmitBtn').prop('disabled', false).text('Generate Gate Pass');
            });
        });

        // Reset pre-approve modal on close
        $('#preApproveModal').on('hidden.bs.modal', function () {
            $('#preApproveForm')[0].reset();
            $('#virtualPassWrapper').hide();
            $('#preApproveSubmitBtn').prop('disabled', false).text('Generate Gate Pass').removeClass('btn-success');
            location.reload(); // Refresh to update list
        });

        // 2. Raise Complaint Ticket submission
        $('#raiseComplaintForm').on('submit', function(e) {
            e.preventDefault();
            $('#complaintSubmitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Dispatching Ticket...');

            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('resident.complaint.raise') }}",
                type: 'POST',
                data: formData,
                success: function (res) {
                    if(res.status) {
                        $('#aiCategoryBadge').text(res.suggested_category);
                        $('#aiPriorityBadge').text(res.suggested_priority);
                        
                        // Style badges based on AI response
                        if(res.suggested_priority === 'High' || res.suggested_priority === 'Critical') {
                            $('#aiPriorityBadge').removeClass().addClass('badge bg-danger');
                        } else {
                            $('#aiPriorityBadge').removeClass().addClass('badge bg-warning');
                        }

                        // Display wrapper
                        $('#aiClassificationWrapper').slideDown();
                        $('#complaintSubmitBtn').text('Ticket Filed!').addClass('btn-success');
                        
                        setTimeout(function() {
                            alert('Ticket filed successfully!\nAI Categorization: ' + res.suggested_category + '\nAI Priority: ' + res.suggested_priority);
                        }, 500);
                    } else {
                        alert('Error filing ticket: ' + res.message);
                        $('#complaintSubmitBtn').prop('disabled', false).text('File Support Ticket');
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            }).fail(function() {
                alert('Dispatches failed. Please try again.');
                $('#complaintSubmitBtn').prop('disabled', false).text('File Support Ticket');
            });
        });

        $('#raiseComplaintModal').on('hidden.bs.modal', function () {
            $('#raiseComplaintForm')[0].reset();
            $('#aiClassificationWrapper').hide();
            $('#complaintSubmitBtn').prop('disabled', false).text('File Support Ticket').removeClass('btn-success');
            location.reload();
        });

        // 3. Book Amenity submission
        $('#bookAmenityForm').on('submit', function(e) {
            e.preventDefault();
            $('#bookAmenitySubmitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Querying Timeslot...');

            $.post("{{ route('resident.amenity.book') }}", $(this).serialize(), function(res) {
                if(res.status) {
                    alert('Booking Scheduled Successfully! Sent to admin for clearance.');
                    location.reload();
                } else {
                    alert('Slot Overlap Error: ' + res.message);
                    $('#bookAmenitySubmitBtn').prop('disabled', false).text('Submit Booking');
                }
            }).fail(function() {
                alert('Connection to Facility schedules failed.');
                $('#bookAmenitySubmitBtn').prop('disabled', false).text('Submit Booking');
            });
        });

        // 4. Request Parking Slot submission
        $('#requestParkingForm').on('submit', function(e) {
            e.preventDefault();
            $('#requestParkingSubmitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Locating Slots...');

            $.post("{{ route('resident.parking.request') }}", $(this).serialize(), function(res) {
                if(res.status) {
                    alert('Parking Request Registered! Admin will allocate slot soon.');
                    location.reload();
                } else {
                    alert('Error: ' + res.message);
                    $('#requestParkingSubmitBtn').prop('disabled', false).text('Submit Slot Request');
                }
            }).fail(function() {
                alert('Connection to Parking scheduler failed.');
                $('#requestParkingSubmitBtn').prop('disabled', false).text('Submit Slot Request');
            });
        });

        // ==================== LIVE GATE APPROVAL POLLING ====================
        let pollingActive = true;
        let activeGateVisitorId = null;

        function checkGateActivity() {
            if(!pollingActive) return;

            $.get("{{ route('resident.visitor.pending-poll') }}", function(res) {
                if(res.status && res.has_visitor) {
                    // We have an active visitor at the gate requesting entry!
                    activeGateVisitorId = res.visitor.id;
                    $('#gateVisitorName').text(res.visitor.visitor_name);
                    $('#gateVisitorPurpose').text(res.visitor.purpose);
                    $('#gateVisitorVehicle').text(res.visitor.vehicle_number ? res.visitor.vehicle_number : 'None');
                    
                    // Pause polling and trigger show modal
                    pollingActive = false;
                    $('#gateNotificationModal').modal('show');
                }
            });
        }

        // Action on visitor buttons
        $('#btnApproveVisitor').on('click', function() {
            if(!activeGateVisitorId) return;
            $(this).prop('disabled', true).text('Processing...');
            
            $.post("{{ url('/resident/visitor-approvals') }}/" + activeGateVisitorId + "/action", {
                _token: "{{ csrf_token() }}",
                action: "Approved"
            }, function(res) {
                if(res.status) {
                    $('#gateNotificationModal').modal('hide');
                    alert('Visitor Access GRANTED. Security notified.');
                }
                // Resume polling
                pollingActive = true;
                activeGateVisitorId = null;
                $('#btnApproveVisitor').prop('disabled', false).text('Approve Access');
                location.reload();
            });
        });

        $('#btnRejectVisitor').on('click', function() {
            if(!activeGateVisitorId) return;
            $(this).prop('disabled', true).text('Processing...');
            
            $.post("{{ url('/resident/visitor-approvals') }}/" + activeGateVisitorId + "/action", {
                _token: "{{ csrf_token() }}",
                action: "Rejected"
            }, function(res) {
                if(res.status) {
                    $('#gateNotificationModal').modal('hide');
                    alert('Visitor Access DENIED. Security notified.');
                }
                // Resume polling
                pollingActive = true;
                activeGateVisitorId = null;
                $('#btnRejectVisitor').prop('disabled', false).text('Deny Access');
                location.reload();
            });
        });

        // Check gate activity every 7 seconds
        setInterval(checkGateActivity, 7000);
        checkGateActivity(); // Initial check on load
    });
</script>
@endsection

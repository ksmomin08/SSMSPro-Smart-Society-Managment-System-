@extends('layouts.admin')

@section('header_title')
    🛡️ Security Gate Console
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Quick Statistics Strip -->
    <div class="row mb-4">
        <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
            <div class="glass-card p-3 border-0 bg-gradient text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div>
                    <h6 class="text-white-50 uppercase font-size-xs mb-1" style="font-size:0.75rem; letter-spacing:0.5px;">ACTIVE CHECKED IN</h6>
                    <h3 class="font-weight-bold mb-0 text-white" id="statCheckedIn">{{ $activeCheckedIn }}</h3>
                </div>
                <span style="font-size: 2.2rem;">🚪</span>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
            <div class="glass-card p-3 border-0 bg-gradient text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div>
                    <h6 class="text-white-50 uppercase font-size-xs mb-1" style="font-size:0.75rem; letter-spacing:0.5px;">PENDING RESIDENT APPROVALS</h6>
                    <h3 class="font-weight-bold mb-0 text-white" id="statPending">{{ $pendingApprovals }}</h3>
                </div>
                <span style="font-size: 2.2rem;">📡</span>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="glass-card p-3 border-0 bg-gradient text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <div>
                    <h6 class="text-white-50 uppercase font-size-xs mb-1" style="font-size:0.75rem; letter-spacing:0.5px;">EMERGENCY ALARM MATRIX</h6>
                    <h5 class="font-weight-bold mb-0 text-white">SYSTEM SECURE</h5>
                </div>
                <span style="font-size: 2.2rem;">🚨</span>
            </div>
        </div>
    </div>

    <!-- MAIN GATE INTERACTIVE GRID -->
    <div class="row">
        <!-- Panel 1: Live Visitor Registration Form with Camera Simulator -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="glass-card p-4 h-100 position-relative" id="visitorRegBox">
                <!-- Resident Live Broadcast Modal Overlay (Wait Indicator) -->
                <div id="approvalBroadcaster" class="d-none position-absolute top-0 start-0 w-100 h-100 rounded-4 d-flex flex-column justify-content-center align-items-center p-4 text-center" style="background: rgba(15, 23, 42, 0.95); z-index: 100;">
                    <div class="radar-pulse mb-4">
                        <span class="pulse-wave wave-1"></span>
                        <span class="pulse-wave wave-2"></span>
                        <span class="radar-icon">📡</span>
                    </div>
                    <h4 class="font-weight-bold text-white mb-2" id="broadcastingTitle">BROADCASTING GATEPASS APPROVAL</h4>
                    <p class="text-muted mb-4" style="max-width:380px; font-size:0.9rem;">Sent request to resident at <strong class="text-white" id="broadcastingResident">Flat Name</strong>. Waiting for their live confirmation in real-time...</p>
                    
                    <div class="p-3 rounded-4 border border-secondary border-opacity-25 w-100 mb-4" style="background: rgba(255,255,255,0.02); max-width:380px;">
                        <h6 class="text-warning mb-1 font-weight-bold" id="broadcastingVisitorName">Guest Name</h6>
                        <span class="text-muted font-size-xs" id="broadcastingPurpose">Purpose</span>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-danger btn-sm px-4 rounded-3 text-white border-danger" onclick="cancelBroadcast()">
                            ❌ Cancel Log
                        </button>
                    </div>
                </div>

                <!-- Access Success Overlay -->
                <div id="accessGrantedOverlay" class="d-none position-absolute top-0 start-0 w-100 h-100 rounded-4 d-flex flex-column justify-content-center align-items-center p-4 text-center" style="background: rgba(6, 78, 59, 0.98); z-index: 101;">
                    <div class="d-flex justify-content-center align-items-center rounded-circle bg-success text-white mb-3" style="width: 80px; height: 80px; font-size: 3rem;">
                        ✓
                    </div>
                    <h2 class="font-weight-bold text-white mb-1" style="font-family:'Outfit';">ACCESS GRANTED!</h2>
                    <h5 class="text-success-light mb-4" style="color: #a7f3d0;">Gate opened. Guest checked in successfully.</h5>
                    <button class="btn btn-light text-success font-weight-bold rounded-pill px-4 py-2" onclick="resetGateRegForm()">
                        ✨ Log Next Visitor
                    </button>
                </div>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <span style="font-size: 1.5rem;">🚪</span>
                    <h5 class="mb-0 font-weight-bold">Live Visitor Entry Log</h5>
                </div>
                
                <form id="storeVisitorForm">
                    <!-- Futuristic Webcam simulator box -->
                    <div class="mb-3 position-relative rounded-4 overflow-hidden border border-secondary border-opacity-25 bg-black" style="height: 200px;">
                        <!-- Static scanner overlay lines -->
                        <div class="camera-scan-line"></div>
                        <div class="camera-crosshairs"></div>
                        
                        <!-- Simulated Camera Feed Canvas / Video representation -->
                        <div id="cameraFeed" class="w-100 h-100 d-flex flex-column justify-content-center align-items-center text-center">
                            <span class="text-white-50" style="font-size: 2.2rem;" id="cameraStatusIcon">📷</span>
                            <h6 class="text-white-50 font-weight-bold mb-1" id="cameraStatusText">CAMERA FEED READY</h6>
                            <span class="text-muted font-size-xs" style="font-size:0.75rem;">Simulating high-definition visitor face capture</span>
                        </div>
                        
                        <!-- Real snapshot image display -->
                        <img id="cameraSnapshot" class="d-none w-100 h-100" style="object-fit: cover;">

                        <!-- Target coordinates display -->
                        <span class="position-absolute bottom-2 left-2 text-success font-size-xs opacity-50" style="font-size:0.7rem; bottom:10px; left:15px; font-family:monospace;">
                            FPS: 30.0 | ISO: 400 | LOCK: SECURE
                        </span>
                        
                        <!-- Face Scanning HUD Tag -->
                        <span class="badge bg-danger text-white position-absolute top-2 right-2 px-2 py-1 font-size-xs" id="scanningHud" style="font-size:0.65rem; top:12px; right:15px; display:none; animation: blink HUD 1.5s infinite;">
                            🔴 RECORDING
                        </span>
                    </div>
                    
                    <div class="d-flex gap-2.5 mb-4">
                        <button type="button" class="btn btn-glass btn-sm border-secondary text-main flex-grow-1" id="captureBtn" onclick="captureSnapshot()">
                            📸 Capture Guest Image
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm border-danger text-white flex-grow-1 bg-danger bg-opacity-10 d-none" id="recaptureBtn" onclick="recaptureSnapshot()">
                            🔄 Reset Camera
                        </button>
                    </div>

                    <!-- Hidden image input -->
                    <input type="hidden" name="photo" id="visitor_photo_base64">

                    <!-- Form Inputs -->
                    <div class="mb-3">
                        <label class="form-label text-muted font-weight-bold font-size-sm">DESTINATION FLAT & RESIDENT</label>
                        <select name="resident_id" id="visitor_resident_id" class="form-select" style="background: rgba(255,255,255,0.03); border-color: var(--border-color); color: var(--text-main); border-radius:12px; padding:10px 14px;" required>
                            <option value="">-- Choose Flat Destination --</option>
                            @foreach($residents as $res)
                                <option value="{{ $res->id }}" data-flat-name="{{ $res->flats->building->building_name ?? 'Block' }} - {{ $res->flats->flate_number }}">
                                    {{ $res->flats->building->building_name ?? 'Block' }} - {{ $res->flats->flate_number }} (Resident: {{ $res->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted font-weight-bold font-size-sm">VISITOR FULL NAME</label>
                            <input type="text" name="visitor_name" id="visitor_name" class="form-control" style="background: rgba(255,255,255,0.03); border-color: var(--border-color); color: var(--text-main); border-radius:12px; padding:10px 14px;" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted font-weight-bold font-size-sm">MOBILE NUMBER</label>
                            <input type="tel" name="mobile" id="visitor_mobile" class="form-control" style="background: rgba(255,255,255,0.03); border-color: var(--border-color); color: var(--text-main); border-radius:12px; padding:10px 14px;" placeholder="9876543210" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted font-weight-bold font-size-sm">PURPOSE OF VISIT</label>
                            <select name="purpose" id="visitor_purpose" class="form-select" style="background: rgba(255,255,255,0.03); border-color: var(--border-color); color: var(--text-main); border-radius:12px; padding:10px 14px;" required onchange="toggleGuardDeliveryField()">
                                <option value="Guest">Guest / Personal</option>
                                <option value="Delivery">Delivery / Courier</option>
                                <option value="Cab">Cab Entry</option>
                                <option value="Maintenance">Maintenance / Service</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted font-weight-bold font-size-sm">VEHICLE PLATE (OPTIONAL)</label>
                            <input type="text" name="vehicle_number" id="visitor_vehicle" class="form-control" style="background: rgba(255,255,255,0.03); border-color: var(--border-color); color: var(--text-main); border-radius:12px; padding:10px 14px;" placeholder="MH-12-XX-1234">
                        </div>
                    </div>

                    <div class="mb-4" id="guard_delivery_wrapper" style="display: none;">
                        <label class="form-label text-muted font-weight-bold font-size-sm">DELIVERY SERVICE COMPANY</label>
                        <input type="text" name="delivery_company" id="visitor_delivery_company" class="form-control" style="background: rgba(255,255,255,0.03); border-color: var(--border-color); color: var(--text-main); border-radius:12px; padding:10px 14px;" placeholder="e.g. Swiggy, Zomato, DHL">
                    </div>

                    <button type="submit" class="btn btn-accent w-100 py-3 text-white rounded-3">
                        📡 Broadcast Approval To Resident
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <!-- Panel 2: Pre-approved Pass Code Validator -->
            <div class="glass-card p-4 mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span style="font-size: 1.5rem;">🎟️</span>
                    <h5 class="mb-0 font-weight-bold">Pre-Approved Pass Validator</h5>
                </div>
                <p class="text-muted mb-3 font-size-sm" style="font-size: 0.85rem;">Input visitor gatepass codes (e.g., PASS-XXXXXX) shared by residents for instant clearance verification.</p>

                <form id="verifyPassForm">
                    <div class="d-flex gap-2.5">
                        <input type="text" name="pass_code" id="pass_code" class="form-control py-2.5" style="background: rgba(255,255,255,0.03); border-color: var(--border-color); color: var(--text-main); border-radius:12px; font-weight: 700; text-align: center; letter-spacing: 1px;" placeholder="e.g. PASS-832104" required>
                        <button type="submit" class="btn btn-accent px-4 py-2.5 text-white rounded-3">
                            Verify & Open Gate
                        </button>
                    </div>
                </form>
            </div>

            <!-- Panel 3: Panic Emergency Center -->
            <div class="glass-card p-4 mb-4 border-danger border-opacity-25" style="background: linear-gradient(135deg, rgba(239,68,68,0.02) 0%, rgba(15,23,42,0.85) 100%);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span style="font-size: 1.5rem;">🚨</span>
                    <h5 class="mb-0 font-weight-bold text-danger">Gate Emergency Broadcast Center</h5>
                </div>
                <p class="text-muted mb-4 font-size-sm" style="font-size: 0.85rem;">Broadcast immediate push warnings to all resident dashboards and society administration panels.</p>

                <div class="row g-3">
                    <div class="col-4">
                        <button class="btn btn-outline-danger w-100 py-3 rounded-4 d-flex flex-column align-items-center gap-2" style="background: rgba(239,68,68,0.06);" onclick="triggerPanic('🔥 FIRE HAZARD', 'Immediate fire warning triggered at society main gates!')">
                            <span style="font-size: 1.8rem;">🔥</span>
                            <span class="font-weight-bold" style="font-size:0.75rem;">FIRE WARNING</span>
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-outline-danger w-100 py-3 rounded-4 d-flex flex-column align-items-center gap-2" style="background: rgba(239,68,68,0.06);" onclick="triggerPanic('🩺 MEDICAL DISASTER', 'Emergency ambulance clearance required at main security bay!')">
                            <span style="font-size: 1.8rem;">🩺</span>
                            <span class="font-weight-bold" style="font-size:0.75rem;">AMBULANCE</span>
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-outline-danger w-100 py-3 rounded-4 d-flex flex-column align-items-center gap-2" style="background: rgba(239,68,68,0.06);" onclick="triggerPanic('🛡️ SECURITY BREACH', 'Unauthorized intrusion alert logged! Lockdown gates.')">
                            <span style="font-size: 1.8rem;">🛡️</span>
                            <span class="font-weight-bold" style="font-size:0.75rem;">BREACH ALERT</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Panel 4: Active Gate Checkins Ledger -->
            <div class="glass-card p-4" style="max-height: 250px; overflow-y:auto;">
                <h6 class="font-weight-bold mb-3">🚪 Current Active Guests Inside</h6>
                
                @php
                    $activeGuests = \App\Models\Visitor::where('society_id', auth()->user()->society_id)->where('status', 'Checked In')->latest()->get();
                @endphp

                @if($activeGuests->isEmpty())
                    <div class="text-center py-4 text-muted" style="font-size: 0.85rem;">
                        No checked in guests currently inside.
                    </div>
                @else
                    <div class="active-guests-ledger">
                        @foreach($activeGuests as $guest)
                            <div class="d-flex justify-content-between align-items-center p-2.5 rounded-3 border border-secondary border-opacity-10 mb-2 font-size-sm" style="background:rgba(255,255,255,0.01); font-size: 0.85rem;">
                                <div>
                                    <strong class="text-main">{{ $guest->visitor_name }}</strong> 
                                    <span class="text-muted" style="font-size:0.75rem;">(Flat: {{ $guest->resident->flats->flate_number ?? 'Block' }} | In: {{ $guest->entry_time }})</span>
                                </div>
                                <form action="{{ route('guard.visitor.checkout', $guest->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-glass btn-sm border-danger text-danger py-0.5 px-3 rounded-pill" style="font-size:0.75rem;">
                                        🚪 Exit Gate
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- PANEL 5: DYNAMIC SYSTEM OPERATIONS REGISTRY -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
                    <h5 class="mb-0 font-weight-bold">📜 Gate Operations Activity Ledger</h5>
                    
                    <form action="" method="GET" class="m-0 d-flex gap-2">
                        <input type="text" name="search" class="form-control form-control-sm rounded-pill" style="background: rgba(255,255,255,0.03); border-color: var(--border-color); color: var(--text-main); font-size:0.85rem;" placeholder="Search logs..." value="{{ $search }}">
                        <button type="submit" class="btn btn-accent btn-sm rounded-pill px-3">🔍 Search</button>
                    </form>
                </div>

                @if($visitors->isEmpty())
                    <div class="text-center py-5 text-muted">
                        No guest operations logs logged inside society database.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border-0">
                            <thead>
                                <tr class="text-muted" style="font-size: 0.85rem; border-bottom: 2px solid var(--border-color);">
                                    <th>GUEST DETAILS</th>
                                    <th>DESTINATION FLAT</th>
                                    <th>ACCESS REASON</th>
                                    <th>PLATE NO.</th>
                                    <th>DATE</th>
                                    <th>CHECK TIMES</th>
                                    <th>STATUS</th>
                                    <th class="text-end">OPERATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($visitors as $log)
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <td>
                                            <div class="d-flex align-items-center gap-2.5">
                                                @if($log->photo)
                                                    <img src="{{ $log->photo }}" class="rounded-circle border border-primary border-opacity-25" style="width:36px; height:36px; object-fit:cover;">
                                                @else
                                                    <div class="bg-secondary bg-opacity-25 rounded-circle d-flex justify-content-center align-items-center font-weight-bold" style="width:36px; height:36px; font-size:0.8rem; color: var(--text-muted);">
                                                        {{ strtoupper(substr($log->visitor_name, 0, 2)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <span class="font-weight-bold text-main block">{{ $log->visitor_name }}</span>
                                                    <span class="text-muted font-size-xs block" style="font-size: 0.75rem; display:block;">{{ $log->mobile }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-main">{{ $log->resident->flats->building->building_name ?? 'Block' }} - {{ $log->resident->flats->flate_number ?? 'N/A' }}</span>
                                            <span class="text-muted font-size-xs block" style="font-size: 0.75rem; display:block;">Res: {{ $log->resident->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary font-size-xs">🏷️ {{ $log->purpose }}</span>
                                            @if($log->delivery_company)
                                                <span class="badge bg-secondary bg-opacity-10 text-main font-size-xs">{{ $log->delivery_company }}</span>
                                            @endif
                                        </td>
                                        <td class="text-muted" style="font-size:0.85rem;">{{ $log->vehicle_number ?? '-' }}</td>
                                        <td>📅 {{ \Carbon\Carbon::parse($log->visit_date)->format('M d, Y') }}</td>
                                        <td class="text-muted" style="font-size: 0.85rem;">
                                            ⏰ In: {{ $log->entry_time }} | Out: {{ $log->exit_time ?? 'Inside' }}
                                        </td>
                                        <td>
                                            <span class="status-pill {{ $log->status === 'Checked In' ? 'success' : ($log->status === 'Pending Approval' ? 'warning' : ($log->status === 'Rejected' ? 'danger' : 'info')) }}">
                                                {{ $log->status }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if($log->status === 'Checked In')
                                                <form action="{{ route('guard.visitor.checkout', $log->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <button type="submit" class="btn btn-glass btn-sm border-danger text-danger py-0.5 px-3 rounded-pill" style="font-size:0.75rem;">
                                                        🚪 Out
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted font-size-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $visitors->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Radar pulsing effects for live broadcasting */
    .radar-pulse {
        position: relative;
        width: 100px;
        height: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .radar-icon {
        font-size: 3rem;
        z-index: 10;
        animation: radarPing 2.5s infinite ease-in-out;
    }

    @keyframes radarPing {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .pulse-wave {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(59, 130, 246, 0.4);
        opacity: 0;
        z-index: 1;
    }
    .wave-1 {
        animation: wavePulse 3s infinite linear;
    }
    .wave-2 {
        animation: wavePulse 3s infinite linear;
        animation-delay: 1.5s;
    }

    @keyframes wavePulse {
        0% { transform: scale(0.6); opacity: 0.8; }
        100% { transform: scale(2.4); opacity: 0; }
    }

    /* Camera HUD simulator custom styles */
    .camera-scan-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(180deg, transparent, var(--neon-green), transparent);
        opacity: 0.75;
        z-index: 5;
        animation: cameraScan 3s infinite linear;
    }

    @keyframes cameraScan {
        0% { top: 0; }
        100% { top: 100%; }
    }

    .camera-crosshairs {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 80px;
        height: 80px;
        border: 2px dashed rgba(16, 185, 129, 0.35);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        z-index: 4;
    }
    
    .camera-crosshairs::before, .camera-crosshairs::after {
        content: '';
        position: absolute;
        background: rgba(16, 185, 129, 0.5);
    }
    .camera-crosshairs::before {
        top: 50%;
        left: -15px;
        width: 110px;
        height: 2px;
        transform: translateY(-50%);
    }
    .camera-crosshairs::after {
        left: 50%;
        top: -15px;
        height: 110px;
        width: 2px;
        transform: translateX(-50%);
    }
</style>
@endsection

@section('scripts')
<script>
    // Delivery fields toggle
    function toggleGuardDeliveryField() {
        const purpose = document.getElementById('visitor_purpose').value;
        const wrapper = document.getElementById('guard_delivery_wrapper');
        if (purpose === 'Delivery') {
            wrapper.style.display = 'block';
            document.getElementById('visitor_delivery_company').setAttribute('required', 'required');
        } else {
            wrapper.style.display = 'none';
            document.getElementById('visitor_delivery_company').removeAttribute('required');
        }
    }

    // =========================================================================
    // WEBCAM CAMERA SNAPSHOT GENERATOR MOCK
    // =========================================================================
    function captureSnapshot() {
        // Start scanner blink HUD
        document.getElementById('scanningHud').style.display = 'inline-block';
        document.getElementById('scanningHud').innerText = '🔴 RECORDING';

        // Play mock shutters sound
        try {
            const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2042/2042-600.wav');
            audio.play();
        } catch(e) {}

        // Mock base64 picture generation (using stunning curated Unsplash face avatar)
        // Draw a random high-fidelity face avatar into canvas base64
        const avatarIds = [
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80'
        ];
        const randomAvatarUrl = avatarIds[Math.floor(Math.random() * avatarIds.length)];

        // Generate simulated base64 by drawing image to canvas
        const img = new Image();
        img.crossOrigin = "Anonymous";
        img.src = randomAvatarUrl;
        img.onload = function() {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);
            
            // Get base64
            const dataUrl = canvas.toDataURL('image/jpeg');
            
            // Set base64 to input
            document.getElementById('visitor_photo_base64').value = dataUrl;
            
            // Hide camera feed message, show snapshot
            document.getElementById('cameraFeed').classList.add('d-none');
            const snap = document.getElementById('cameraSnapshot');
            snap.src = dataUrl;
            snap.classList.remove('d-none');

            // Toggle buttons
            document.getElementById('captureBtn').classList.add('d-none');
            document.getElementById('recaptureBtn').classList.remove('d-none');

            document.getElementById('scanningHud').innerText = '🟢 FACE CAPTURED';
        };
    }

    function recaptureSnapshot() {
        document.getElementById('visitor_photo_base64').value = '';
        document.getElementById('cameraSnapshot').classList.add('d-none');
        document.getElementById('cameraFeed').classList.remove('d-none');
        document.getElementById('captureBtn').classList.remove('d-none');
        document.getElementById('recaptureBtn').classList.add('d-none');
        document.getElementById('scanningHud').style.display = 'none';
    }

    // =========================================================================
    // VISITOR GATE REGISTRATION & LIVE RESIDENT POLLING ENGINE
    // =========================================================================
    let pollInterval = null;
    let registeredVisitorId = null;

    document.getElementById('storeVisitorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const resSelect = document.getElementById('visitor_resident_id');
        const flatName = resSelect.options[resSelect.selectedIndex].getAttribute('data-flat-name');
        
        const data = {
            resident_id: document.getElementById('visitor_resident_id').value,
            visitor_name: document.getElementById('visitor_name').value,
            mobile: document.getElementById('visitor_mobile').value,
            purpose: document.getElementById('visitor_purpose').value,
            vehicle_number: document.getElementById('visitor_vehicle').value,
            delivery_company: document.getElementById('visitor_delivery_company').value,
            photo: document.getElementById('visitor_photo_base64').value
        };

        // If no photo was captured, inject a mock base64 signature
        if(!data.photo) {
            // Draw a basic blank mock photo so DB fields sync cleanly
            data.photo = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==";
        }

        // Show Broadcast Radar pulse modal overlay
        document.getElementById('broadcastingVisitorName').innerText = data.visitor_name;
        document.getElementById('broadcastingResident').innerText = flatName;
        document.getElementById('broadcastingPurpose').innerText = `Destination Dues Purpose: ${data.purpose}`;
        document.getElementById('approvalBroadcaster').classList.remove('d-none');

        fetch("{{ route('guard.visitor.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(res => {
            if(res.status) {
                registeredVisitorId = res.visitor.id;
                
                // Start polling status
                pollInterval = setInterval(() => {
                    checkVisitorApprovalState(registeredVisitorId);
                }, 2000); // Check every 2 seconds
            } else {
                cancelBroadcast();
                alert("❌ Request rejected: " + res.message);
            }
        })
        .catch(err => {
            console.error(err);
            cancelBroadcast();
            alert("Error broadcasting gatepass approval.");
        });
    });

    function checkVisitorApprovalState(visitorId) {
        if(!visitorId) return;

        fetch(`/guard/visitor-status/${visitorId}`)
        .then(response => response.json())
        .then(res => {
            if(res.status) {
                if(res.visitor_status === 'Approved') {
                    // Stop polling
                    clearInterval(pollInterval);
                    pollInterval = null;
                    
                    // Call checkin API automatically to check them in
                    fetch(`/guard/visitor/checkin/${visitorId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(checkinRes => {
                        // Play access granted sound
                        try {
                            const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2019/2019-600.wav');
                            audio.play();
                        } catch(e) {}

                        // Hide radar broadcaster, show Access Granted slide
                        document.getElementById('approvalBroadcaster').classList.add('d-none');
                        document.getElementById('accessGrantedOverlay').classList.remove('d-none');
                        
                        // Increment checked in stat counter
                        const count = document.getElementById('statCheckedIn');
                        count.innerText = parseInt(count.innerText) + 1;
                    });

                } else if(res.visitor_status === 'Rejected') {
                    // Stop polling
                    clearInterval(pollInterval);
                    pollInterval = null;
                    
                    document.getElementById('approvalBroadcaster').classList.add('d-none');
                    alert("⚠️ Access Denied! The resident has REJECTED this visitor entry.");
                    resetGateRegForm();
                }
            }
        });
    }

    function cancelBroadcast() {
        if(pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
        registeredVisitorId = null;
        document.getElementById('approvalBroadcaster').classList.add('d-none');
    }

    function resetGateRegForm() {
        cancelBroadcast();
        document.getElementById('storeVisitorForm').reset();
        recaptureSnapshot();
        toggleGuardDeliveryField();
        
        // Hide overlay
        document.getElementById('accessGrantedOverlay').classList.add('d-none');
    }

    // =========================================================================
    // PRE-APPROVED PASS VALIDATION
    // =========================================================================
    document.getElementById('verifyPassForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const code = document.getElementById('pass_code').value;

        fetch("{{ route('guard.pass.verify') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ pass_code: code })
        })
        .then(response => response.json())
        .then(res => {
            if(res.status) {
                // Play access granted sound
                try {
                    const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2019/2019-600.wav');
                    audio.play();
                } catch(e) {}

                alert("✨ Pass Verified successfully! Visitor Checked In.");
                document.getElementById('pass_code').value = '';
                
                // Reload page to refresh all active ledger tables
                setTimeout(() => location.reload(), 1500);
            } else {
                alert("❌ Verification Failed: " + res.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error verifying gate passcode.");
        });
    });

    // =========================================================================
    // EMERGENCY ALARM MATRIX PANIC TRIGGERS
    // =========================================================================
    function triggerPanic(type, desc) {
        if(confirm(`⚠️ CAUTION: Are you sure you want to trigger a public ${type} emergency broadcast?`)) {
            fetch("{{ route('guard.panic-alert') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ type: type, description: desc })
            })
            .then(response => response.json())
            .then(res => {
                if(res.status) {
                    alert("🚨 EMERGENCY ALARM BROADCAST ACTIVE! Authorities and residents alerted immediately.");
                }
            });
        }
    }
</script>
@endsection

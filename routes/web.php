<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    SuperAdminController,
    AdminController,
    BuildingController,
    FlateController,
    ResidentController,
    ComplaintController,
    MaintenanceController,
    NoticeController,
    VisitorController,
    GuardController,
    ResidentPanelController,
    AmenityController,
    ParkingController,
    ProfileController,
    SettingController,
};

// -------------------------------- Profile (All authenticated users)
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function() {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::post('/update', [ProfileController::class, 'update'])->name('update');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
});

// -------------------------------- Home & Redirects
Route::get('/', function () {
    if (auth()->check()) {
        switch (auth()->user()->role) {
            case 'super_admin': return redirect('/super-admin/dashboard');
            case 'admin': return redirect('/admin/dashboard');
            case 'resident': return redirect('/resident/dashboard');
            case 'guard': return redirect('/guard/dashboard');
        }
    }
    return redirect('/login');
});

// -------------------------------- Authentication
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login-check', [AuthController::class, 'loginCheck'])->name('login.check');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot-password');


// -------------------------------- Super Admin Panel (SaaS tenant master)
Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function() {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/societies', [SuperAdminController::class, 'societies'])->name('societies');
    Route::get('/societies/create', [SuperAdminController::class, 'createSociety'])->name('societies.create');
    Route::post('/societies/store', [SuperAdminController::class, 'storeSociety'])->name('societies.store');
    Route::get('/societies/{id}/edit', [SuperAdminController::class, 'editSociety'])->name('societies.edit');
    Route::put('/societies/{id}/update', [SuperAdminController::class, 'updateSociety'])->name('societies.update');
    Route::get('/logs', [SuperAdminController::class, 'activityLogs'])->name('logs');
});

// -------------------------------- Society Admin Panel
Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Guard Staff CRUD
    Route::get('/admin/guards', [AdminController::class, 'guards'])->name('admin.guards');
    Route::get('/admin/guards/create', [AdminController::class, 'createGuard'])->name('admin.guards.create');
    Route::post('/admin/guards/store', [AdminController::class, 'storeGuard'])->name('admin.guards.store');
    Route::get('/admin/guards/{id}/edit', [AdminController::class, 'editGuard'])->name('admin.guards.edit');
    Route::put('/admin/guards/{id}/update', [AdminController::class, 'updateGuard'])->name('admin.guards.update');
    Route::delete('/admin/guards/{id}/delete', [AdminController::class, 'destroyGuard'])->name('admin.guards.destroy');

    // standard resources (isolated via middleware/controller scopes)
    Route::resource('buildings', BuildingController::class);
    Route::resource('flats', FlateController::class);
    Route::resource('residents', ResidentController::class);
    Route::resource('complaints', ComplaintController::class);
    Route::resource('maintenances', MaintenanceController::class);
    Route::resource('notices', NoticeController::class);
    Route::resource('visitors', VisitorController::class);

    // Actions
    Route::get('maintenance-status/{id}', [MaintenanceController::class, 'changeStatus'])->name('maintenance.status');
    Route::get('maintenance-pdf', [MaintenanceController::class, 'downloadPdf'])->name('maintenance.pdf');
    Route::get('visitor-qr/{id}', [VisitorController::class, 'qrCode'])->name('visitor.qr');
    Route::post('visitor-blacklist/{id}', [VisitorController::class, 'toggleBlacklist'])->name('visitor.blacklist');

    // Amenities Bookings Admin Panel
    Route::get('/admin/amenities', [AmenityController::class, 'index'])->name('admin.amenities');
    Route::get('/admin/amenities/create', [AmenityController::class, 'create'])->name('admin.amenities.create');
    Route::post('/admin/amenities/store', [AmenityController::class, 'store'])->name('admin.amenities.store');
    Route::get('/admin/amenities/{id}/edit', [AmenityController::class, 'edit'])->name('admin.amenities.edit');
    Route::put('/admin/amenities/{id}/update', [AmenityController::class, 'update'])->name('admin.amenities.update');
    Route::delete('/admin/amenities/{id}/delete', [AmenityController::class, 'destroy'])->name('admin.amenities.destroy');
    Route::get('/admin/amenities-bookings', [AmenityController::class, 'bookings'])->name('admin.amenities.bookings');
    Route::post('/admin/amenities-bookings/{id}/approve', [AmenityController::class, 'approveBooking'])->name('admin.amenities.approve');
    Route::post('/admin/amenities-bookings/{id}/reject', [AmenityController::class, 'rejectBooking'])->name('admin.amenities.reject');

    // Parking slot structures Admin Panel
    Route::get('/admin/parking', [ParkingController::class, 'index'])->name('admin.parking');
    Route::post('/admin/parking/store-slot', [ParkingController::class, 'storeSlot'])->name('admin.parking.store-slot');
    Route::post('/admin/parking/allocate/{id}', [ParkingController::class, 'allocateSlot'])->name('admin.parking.allocate');
    Route::post('/admin/parking/release/{id}', [ParkingController::class, 'releaseSlot'])->name('admin.parking.release');
    Route::post('/admin/parking/requests/{id}/approve', [ParkingController::class, 'approveRequest'])->name('admin.parking.approve-req');
    Route::post('/admin/parking/requests/{id}/reject', [ParkingController::class, 'rejectRequest'])->name('admin.parking.reject-req');

    // System Settings & dynamic color theme engine
    Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('/admin/settings/update', [SettingController::class, 'update'])->name('admin.settings.update');
    Route::post('/admin/settings/test-email', [SettingController::class, 'sendTestEmail'])->name('admin.settings.test-email');
});

// -------------------------------- Resident Panel
Route::middleware(['auth', 'resident'])->prefix('resident')->name('resident.')->group(function() {
    Route::get('/dashboard', [ResidentPanelController::class, 'dashboard'])->name('dashboard');
    Route::post('/complaint/raise', [ResidentPanelController::class, 'raiseComplaint'])->name('complaint.raise');
    Route::post('/visitor/pre-approve', [ResidentPanelController::class, 'preApproveVisitor'])->name('visitor.pre-approve');
    
    // Live Gate Approval Polling & Action
    Route::get('/visitor-approvals/pending', [ResidentPanelController::class, 'checkPendingVisitor'])->name('visitor.pending-poll');
    Route::post('/visitor-approvals/{id}/action', [ResidentPanelController::class, 'actionVisitor'])->name('visitor.action');
    
    // Payments
    Route::post('/maintenance/pay/{id}', [ResidentPanelController::class, 'payBill'])->name('maintenance.pay');
    Route::get('/maintenance/receipt/{id}', [ResidentPanelController::class, 'downloadReceipt'])->name('maintenance.receipt');
    
    // Parking request
    Route::post('/parking/request', [ResidentPanelController::class, 'requestParking'])->name('parking.request');
    
    // Facility booking
    Route::post('/amenity/book', [ResidentPanelController::class, 'bookAmenity'])->name('amenity.book');
});

// -------------------------------- Security Guard Panel
Route::middleware(['auth', 'guard'])->prefix('guard')->name('guard.')->group(function() {
    Route::get('/dashboard', [GuardController::class, 'dashboard'])->name('dashboard');
    Route::post('/visitor/store', [GuardController::class, 'storeVisitor'])->name('visitor.store');
    Route::get('/visitor-status/{id}', [GuardController::class, 'checkVisitorStatus'])->name('visitor.status');
    Route::post('/visitor/checkin/{id}', [GuardController::class, 'checkInVisitor'])->name('visitor.checkin');
    Route::post('/visitor/checkout/{id}', [GuardController::class, 'checkoutVisitor'])->name('visitor.checkout');
    Route::post('/pass/verify', [GuardController::class, 'verifyPass'])->name('pass.verify');
    Route::post('/panic-alert', [GuardController::class, 'triggerAlert'])->name('panic-alert');
});

// -------------------------------- Public / Shared AJAX notifications
Route::get('/notification-count', function(){
    $societyId = auth()->user()->society_id;
    $count = 0;
    if (auth()->check()) {
        if (auth()->user()->isSocietyAdmin()) {
            $count = \App\Models\Complaint::where('society_id', $societyId)->where('status', 'pending')->count();
        } else if (auth()->user()->isResident()) {
            $resident = auth()->user()->resident;
            $count = \App\Models\Visitor::where('resident_id', $resident->id)->where('status', 'Pending Approval')->count();
        }
    }
    return response()->json(['count' => $count]);
})->middleware('auth');
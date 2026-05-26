@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card animate-fadeInUp" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%); border: 1px solid rgba(99, 102, 241, 0.1);">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title mb-2" style="font-size: 1.5rem; font-weight: 800; color: var(--ss-dark);">Welcome back, {{ auth()->user()->name }}! 🎉</h5>
                        <p class="mb-4" style="font-size: 1rem; color: var(--ss-secondary);">
                            You have <span class="fw-bold" style="color: var(--ss-primary);">{{ $pendingComplaints }}</span> pending complaints to resolve today. Check your new notifications in your profile.
                        </p>
                        <a href="{{ route('complaints.index') }}" class="btn btn-primary">
                            <i class="bx bx-message-square-error me-2"></i>View Complaints
                        </a>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('admin_assets/img/illustrations/man-with-laptop-light.png') }}" height="160" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" style="filter: drop-shadow(0 10px 20px rgba(99, 102, 241, 0.2));">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Stat Card 1 -->
    <div class="col-lg-3 col-md-12 col-6 mb-4">
        <div class="card stat-card h-100 animate-fadeInUp">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon">
                        <i class="bx bx-buildings"></i>
                    </div>
                </div>
                <span class="stat-label d-block mb-2">Flats / Buildings</span>
                <h3 class="stat-value mb-2">{{ $totalFlats }} <span class="fs-6 text-muted fw-normal">/ {{ $totalBuildings }} Wing</span></h3>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-lg-3 col-md-12 col-6 mb-4">
        <div class="card stat-card h-100 animate-fadeInUp" style="animation-delay: 0.1s;">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon" style="background: var(--ss-gradient-success);">
                        <i class="bx bx-rupee"></i>
                    </div>
                </div>
                <span class="stat-label d-block mb-2">Total Revenue</span>
                <h3 class="stat-value mb-2">₹{{ number_format($totalRevenue, 2) }}</h3>
                <small class="stat-change text-warning">Pending: ₹{{ number_format($totalPendingBills, 2) }}</small>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-lg-3 col-md-12 col-6 mb-4">
        <div class="card stat-card h-100 animate-fadeInUp" style="animation-delay: 0.2s;">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon" style="background: var(--ss-gradient-danger);">
                        <i class="bx bx-error-circle"></i>
                    </div>
                </div>
                <span class="stat-label d-block mb-2">Pending Complaints</span>
                <h3 class="stat-value mb-2">{{ $pendingComplaints }}</h3>
                <small class="stat-change text-muted">Total {{ $totalComplaints }}</small>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="col-lg-3 col-md-12 col-6 mb-4">
        <div class="card stat-card h-100 animate-fadeInUp" style="animation-delay: 0.3s;">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon" style="background: var(--ss-gradient-warning);">
                        <i class="bx bx-car"></i>
                    </div>
                </div>
                <span class="stat-label d-block mb-2">Parking Slots</span>
                <h3 class="stat-value mb-2">{{ $allocatedParking }}</h3>
                <small class="stat-change text-muted">Total {{ $totalParking }} slots</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Active Visitors -->
    <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
        <div class="card h-100 animate-fadeInUp" style="animation-delay: 0.4s;">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2" style="font-weight: 700; color: var(--ss-dark);">Gate Activity</h5>
                </div>
                <div class="stat-icon" style="width: 40px; height: 40px; font-size: 1.25rem; background: var(--ss-gradient-info);">
                    <i class="bx bx-door-open"></i>
                </div>
            </div>
            <div class="card-body mt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex flex-column align-items-center gap-1">
                        <h2 class="mb-2 stat-value">{{ $activeVisitors }}</h2>
                        <span class="stat-label">Active Visitors</span>
                    </div>
                    <div id="gateActivityChart"></div>
                </div>
                <ul class="p-0 m-0 mt-4">
                    <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial-text" style="background: var(--ss-gradient-primary); color: #fff; width: 40px; height: 40px; font-size: 0.875rem;"><i class="bx bx-user"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0 fw-semibold" style="color: var(--ss-dark);">Total Residents</h6>
                            </div>
                            <div class="user-progress">
                                <small class="fw-bold" style="color: var(--ss-primary);">{{ $totalResidents }}</small>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial-text" style="background: var(--ss-gradient-success); color: #fff; width: 40px; height: 40px; font-size: 0.875rem;"><i class="bx bx-shield-quarter"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0 fw-semibold" style="color: var(--ss-dark);">Security Guards</h6>
                            </div>
                            <div class="user-progress">
                                <small class="fw-bold" style="color: var(--ss-success);">Active</small>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Recent Complaints -->
    <div class="col-md-6 col-lg-8 col-xl-8 order-1 mb-4">
        <div class="card h-100 animate-fadeInUp" style="animation-delay: 0.5s;">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2" style="font-weight: 700; color: var(--ss-dark);">Recent Complaints</h5>
                <div class="dropdown">
                    <a href="{{ route('complaints.index') }}" class="btn btn-outline-primary">
                        <i class="bx bx-right-arrow-alt me-1"></i> View All
                    </a>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($recentComplaints ?? [] as $complaint)
                            <tr>
                                <td><strong style="color: var(--ss-dark);">{{ $complaint->title }}</strong></td>
                                <td>
                                    @if($complaint->status == 'open')
                                        <span class="badge badge-status badge-inactive"><i class="bx bx-x-circle me-1"></i>Open</span>
                                    @elseif($complaint->status == 'in_progress')
                                        <span class="badge badge-status badge-pending"><i class="bx bx-loader-alt me-1"></i>In Progress</span>
                                    @else
                                        <span class="badge badge-status badge-active"><i class="bx bx-check-circle me-1"></i>Resolved</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $complaint->created_at->diffForHumans() }}</small></td>
                                <td>
                                    <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-icon-view" title="View Details">
                                        <i class="bx bx-show"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="bx bx-message-square-error"></i>
                                        <h6>No Recent Complaints</h6>
                                        <p>All complaints have been resolved.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

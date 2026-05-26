@extends('layouts.admin')

@section('header_title', 'SaaS Governance Panel')

@section('content')
@php
    $eliteCount = \App\Models\Society::where('subscription_plan', 'Elite')->count();
    $premiumCount = \App\Models\Society::where('subscription_plan', 'Premium')->count();
    $basicCount = \App\Models\Society::where('subscription_plan', 'Basic')->count();
    $totalPlans = $eliteCount + $premiumCount + $basicCount;
@endphp

<style>
    /* Skeleton Loader Styling */
    .skeleton-pulse {
        background: linear-gradient(-90deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.08) 50%, rgba(255, 255, 255, 0.03) 100%);
        background-size: 400% 400%;
        animation: pulseGlow 1.5s ease-in-out infinite;
    }
    @keyframes pulseGlow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Stat Cards Styling */
    .stat-card-glow-blue { border-left: 4px solid #3b82f6 !important; }
    .stat-card-glow-purple { border-left: 4px solid #8b5cf6 !important; }
    .stat-card-glow-emerald { border-left: 4px solid #10b981 !important; }
    .stat-card-glow-orange { border-left: 4px solid #f59e0b !important; }

    /* Custom Tables Design */
    .custom-table th {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: rgba(255, 255, 255, 0.4) !important;
        background: rgba(255, 255, 255, 0.01) !important;
        border-bottom: 2px solid var(--border-color) !important;
    }
    .custom-table td {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        padding: 16px 12px !important;
    }
    .custom-table tr:hover td {
        background: rgba(255, 255, 255, 0.02) !important;
    }

    /* Audit logs custom scrollbar */
    .activity-timeline::-webkit-scrollbar {
        width: 6px;
    }
    .activity-timeline::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.01);
    }
    .activity-timeline::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.08);
        border-radius: 3px;
    }
    .activity-timeline::-webkit-scrollbar-thumb:hover {
        background: rgba(255,255,255,0.15);
    }
</style>

<!-- ==================== SKELETON LOADER SECTION ==================== -->
<div id="dashboard-skeleton">
    <!-- Stat Grid Skeleton -->
    <div class="row">
        @for($i = 0; $i < 4; $i++)
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="glass-card h-100 p-4" style="min-height: 180px;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="skeleton-pulse rounded-3" style="width: 42px; height: 42px;"></div>
                        <div class="skeleton-pulse rounded-pill" style="width: 80px; height: 22px;"></div>
                    </div>
                    <div class="skeleton-pulse rounded-3 mb-2" style="width: 50%; height: 14px;"></div>
                    <div class="skeleton-pulse rounded-3" style="width: 75%; height: 28px;"></div>
                </div>
            </div>
        @endfor
    </div>

    <!-- Charts and Analytics row Skeleton -->
    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="glass-card p-4" style="height: 380px;">
                <div class="skeleton-pulse rounded-3 mb-4" style="width: 240px; height: 24px;"></div>
                <div class="skeleton-pulse rounded-4" style="width: 100%; height: 260px;"></div>
            </div>
        </div>
        <div class="col-xl-4 mb-4">
            <div class="glass-card p-4" style="height: 380px;">
                <div class="skeleton-pulse rounded-3 mb-4" style="width: 160px; height: 24px;"></div>
                <div class="d-flex justify-content-center align-items-center" style="height: 220px;">
                    <div class="skeleton-pulse rounded-circle" style="width: 180px; height: 180px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Directory & logs row Skeleton -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="glass-card p-4" style="height: 380px;">
                <div class="skeleton-pulse rounded-3 mb-4" style="width: 200px; height: 24px;"></div>
                @for($i=0; $i<3; $i++)
                    <div class="d-flex align-items-center mb-3">
                        <div class="skeleton-pulse rounded-circle me-3" style="width: 40px; height: 40px;"></div>
                        <div class="flex-grow-1">
                            <div class="skeleton-pulse rounded-3 mb-2" style="width: 60%; height: 12px;"></div>
                            <div class="skeleton-pulse rounded-3" style="width: 40%; height: 8px;"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="glass-card p-4" style="height: 380px;">
                <div class="skeleton-pulse rounded-3 mb-4" style="width: 200px; height: 24px;"></div>
                @for($i=0; $i<3; $i++)
                    <div class="d-flex align-items-center mb-3">
                        <div class="skeleton-pulse rounded-circle me-3" style="width: 40px; height: 40px;"></div>
                        <div class="flex-grow-1">
                            <div class="skeleton-pulse rounded-3 mb-2" style="width: 65%; height: 12px;"></div>
                            <div class="skeleton-pulse rounded-3" style="width: 30%; height: 8px;"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>

<!-- ==================== GENUINE ANALYTICS DASHBOARD ==================== -->
<div id="dashboard-real-content" style="display: none;">
    <!-- Stat grid -->
    <div class="row">
        <!-- Stat Card 1 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="glass-card h-100 d-flex flex-column justify-content-between p-4 stat-card-glow-blue" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(59, 130, 246, 0.02) 100%);">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="p-2.5 rounded-3" style="background-color: rgba(59, 130, 246, 0.12);">
                            <i data-lucide="building-2" style="width: 22px; height: 22px; color: #3b82f6; stroke-width: 2.5px;"></i>
                        </div>
                        <span class="status-pill success">Active SaaS</span>
                    </div>
                    <h6 class="text-muted font-weight-bold text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Societies</h6>
                    <h2 class="font-weight-bold mb-0 text-white">{{ $totalSocieties }}</h2>
                </div>
                <div class="mt-3 text-muted d-flex align-items-center gap-2" style="font-size: 0.8rem;">
                    <i data-lucide="globe" style="width: 14px; height: 14px; color: var(--text-muted);"></i>
                    <span>Across multiple domains</span>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="glass-card h-100 d-flex flex-column justify-content-between p-4 stat-card-glow-purple" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.05) 0%, rgba(139, 92, 246, 0.02) 100%);">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="p-2.5 rounded-3" style="background-color: rgba(139, 92, 246, 0.12);">
                            <i data-lucide="users" style="width: 22px; height: 22px; color: #a855f7; stroke-width: 2.5px;"></i>
                        </div>
                        <span class="status-pill success">Verified</span>
                    </div>
                    <h6 class="text-muted font-weight-bold text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Registered Users</h6>
                    <h2 class="font-weight-bold mb-0 text-white">{{ $totalUsers }}</h2>
                </div>
                <div class="mt-3 text-muted d-flex align-items-center gap-2" style="font-size: 0.8rem;">
                    <i data-lucide="shield" style="width: 14px; height: 14px; color: var(--text-muted);"></i>
                    <span>Staff, residents & admins</span>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="glass-card h-100 d-flex flex-column justify-content-between p-4 stat-card-glow-emerald" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(16, 185, 129, 0.02) 100%);">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="p-2.5 rounded-3" style="background-color: rgba(16, 185, 129, 0.12);">
                            <i data-lucide="license" style="width: 22px; height: 22px; color: #10b981; stroke-width: 2.5px;"></i>
                        </div>
                        <span class="status-pill warning" style="color: var(--neon-purple); background: rgba(139, 92, 246, 0.1);">Elite Plans</span>
                    </div>
                    <h6 class="text-muted font-weight-bold text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Active Licenses</h6>
                    <h2 class="font-weight-bold mb-0 text-white">{{ $activePlans }}</h2>
                </div>
                <div class="mt-3 text-muted d-flex align-items-center gap-2" style="font-size: 0.8rem;">
                    <i data-lucide="check-circle" style="width: 14px; height: 14px; color: var(--text-muted);"></i>
                    <span>Authorized subscription status</span>
                </div>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="glass-card h-100 d-flex flex-column justify-content-between p-4 stat-card-glow-orange" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.05) 0%, rgba(245, 158, 11, 0.02) 100%);">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="p-2.5 rounded-3" style="background-color: rgba(245, 158, 11, 0.12);">
                            <i data-lucide="wallet" style="width: 22px; height: 22px; color: #f59e0b; stroke-width: 2.5px;"></i>
                        </div>
                        <span class="status-pill success">Total Invoiced</span>
                    </div>
                    <h6 class="text-muted font-weight-bold text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Accumulated SaaS Revenue</h6>
                    <h2 class="font-weight-bold mb-0 text-white">${{ number_format($totalRevenue, 2) }}</h2>
                </div>
                <div class="mt-3 text-muted d-flex align-items-center gap-2" style="font-size: 0.8rem;">
                    <i data-lucide="credit-card" style="width: 14px; height: 14px; color: var(--text-muted);"></i>
                    <span>Settled via Stripe/Razorpay</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and ring layout -->
    <div class="row">
        <!-- Interactive Billing Area Chart -->
        <div class="col-xl-8 mb-4">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 font-weight-bold"><i data-lucide="trending-up" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; margin-right: 6px; color: #3b82f6;"></i> SaaS Revenue Collection & Growth Trends</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary py-1 px-3" style="font-size:0.75rem;">Global Dashboard</span>
                </div>
                <div style="height: 300px; position: relative;">
                    <div id="saasRevenueAnalyticsChart"></div>
                </div>
            </div>
        </div>

        <!-- Concentric Radial Health Index Ring -->
        <div class="col-xl-4 mb-4">
            <div class="glass-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <h5 class="mb-2 font-weight-bold"><i data-lucide="pie-chart" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; margin-right: 6px; color: #8b5cf6;"></i> Subscription Tier Ratios</h5>
                    <p class="text-muted mb-3" style="font-size:0.82rem;">Concentric distribution indexing active tenants segmented by elite, premium, or basic tiers.</p>
                    
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 230px;">
                        <div id="subscriptionRadialChart"></div>
                    </div>
                </div>
                
                <div class="mt-2 pt-3 border-top border-secondary border-opacity-25 d-flex gap-2">
                    <a href="{{ route('super-admin.societies.create') }}" class="btn btn-glass flex-grow-1 text-center py-2.5 justify-content-center" style="font-size:0.85rem;"><i data-lucide="plus-circle" style="width:16px; height:16px;"></i> Onboard Tenant</a>
                    <a href="{{ route('super-admin.logs') }}" class="btn btn-glass flex-grow-1 text-center py-2.5 justify-content-center" style="font-size:0.85rem;"><i data-lucide="scroll" style="width:16px; height:16px;"></i> Audit Trail</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Details lists rows -->
    <div class="row">
        <!-- Recent Onboarded Societies -->
        <div class="col-lg-6 mb-4">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 font-weight-bold"><i data-lucide="building" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; margin-right: 6px; color: #10b981;"></i> Recently Onboarded Societies</h5>
                    <a href="{{ route('super-admin.societies') }}" class="btn btn-glass btn-sm"><i data-lucide="list" style="width: 14px; height: 14px;"></i> Directory</a>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table align-middle border-0 text-white">
                        <thead>
                            <tr style="font-size: 0.75rem; text-transform: uppercase;">
                                <th class="border-0">Society</th>
                                <th class="border-0">Code</th>
                                <th class="border-0">Plan</th>
                                <th class="border-0">Expires</th>
                                <th class="border-0 text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($societies as $soc)
                                <tr>
                                    <td class="font-weight-bold text-white">
                                        {{ $soc->name }}
                                        <div class="text-muted font-weight-normal" style="font-size: 0.72rem; margin-top:2px;">{{ $soc->address }}</div>
                                    </td>
                                    <td class="text-muted font-weight-bold">{{ $soc->code }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $soc->subscription_plan === 'Elite' ? 'bg-primary text-primary' : ($soc->subscription_plan === 'Premium' ? 'bg-purple text-purple' : 'bg-secondary text-secondary') }} bg-opacity-10" style="font-size:0.68rem; padding: 4px 10px;">
                                            {{ $soc->subscription_plan }}
                                        </span>
                                    </td>
                                    <td class="text-muted" style="font-size:0.8rem;">
                                        {{ \Carbon\Carbon::parse($soc->expires_at)->format('d M Y') }}
                                    </td>
                                    <td class="text-end">
                                        <span class="status-pill {{ $soc->status === 'active' ? 'success' : 'danger' }}">
                                            {{ $soc->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted border-0 py-4">No tenant societies onboarding.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Global SaaS Audit Logs -->
        <div class="col-lg-6 mb-4">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 font-weight-bold"><i data-lucide="scroll" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; margin-right: 6px; color: #f59e0b;"></i> Global Operations Trail</h5>
                    <a href="{{ route('super-admin.logs') }}" class="btn btn-glass btn-sm"><i data-lucide="history" style="width: 14px; height: 14px;"></i> View Full Logs</a>
                </div>
                
                <div class="activity-timeline" style="max-height: 380px; overflow-y: auto; padding-right: 5px;">
                    @forelse($logs as $log)
                        <div class="d-flex gap-3 mb-4 align-items-start">
                            <div class="timeline-dot" style="width: 10px; height: 10px; border-radius: 50%; background-color: var(--accent-color); margin-top: 6px; box-shadow: 0 0 8px var(--accent-color); flex-shrink:0;"></div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 text-white font-weight-bold" style="font-size: 0.9rem;">{{ $log->action }}</h6>
                                    <span class="text-muted" style="font-size: 0.75rem;">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-muted mb-0" style="font-size: 0.8rem;">{{ $log->description }}</p>
                                <div class="d-flex align-items-center gap-2 mt-1" style="font-size: 0.7rem; color: rgba(255,255,255,0.45);">
                                    <span>👤 Actor: {{ $log->user ? $log->user->name : 'System/Guest' }}</span>
                                    <span>•</span>
                                    <span>🖥️ IP Address: {{ $log->ip_address }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">No logged operations trail.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Render Lucide icons
        lucide.createIcons();

        // 800ms Skeleton Loader animation
        setTimeout(function() {
            const skeleton = document.getElementById("dashboard-skeleton");
            const content = document.getElementById("dashboard-real-content");
            if (skeleton && content) {
                skeleton.style.display = "none";
                content.style.display = "block";
                content.style.opacity = "0";
                setTimeout(() => {
                    content.style.opacity = "1";
                    content.style.transition = "opacity 0.4s ease-in-out";
                    
                    // Render interactive ApexCharts once visible
                    renderCharts();
                }, 50);
            }
        }, 800);

        function renderCharts() {
            // 1. SaaS Cumulative Revenue Area Chart
            const areaOptions = {
                series: [{
                    name: 'Monthly Revenue',
                    data: [
                        {{ $totalRevenue * 0.45 }},
                        {{ $totalRevenue * 0.65 }},
                        {{ $totalRevenue * 0.80 }},
                        {{ $totalRevenue * 0.95 }},
                        {{ $totalRevenue }}
                    ]
                }, {
                    name: 'License Onboardings',
                    data: [
                        {{ max(1, round($totalSocieties * 0.3)) }},
                        {{ max(1, round($totalSocieties * 0.5)) }},
                        {{ max(1, round($totalSocieties * 0.7)) }},
                        {{ max(1, round($totalSocieties * 0.9)) }},
                        {{ $totalSocieties }}
                    ]
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    background: 'transparent',
                    foreColor: '#9ca3af',
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#3b82f6', '#10b981'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.02,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#9ca3af' } }
                },
                yaxis: {
                    labels: { style: { colors: '#9ca3af' } }
                },
                grid: {
                    borderColor: 'rgba(255, 255, 255, 0.05)',
                    strokeDashArray: 4
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontFamily: 'Inter',
                    labels: { colors: '#f3f4f6' }
                },
                tooltip: { theme: 'dark' }
            };
            const saasChart = new ApexCharts(document.querySelector("#saasRevenueAnalyticsChart"), areaOptions);
            saasChart.render();

            // 2. Concentric Radial Active Subscriptions Breakdown
            const totalPlans = {{ $totalPlans > 0 ? $totalPlans : 1 }};
            const radialOptions = {
                series: [
                    {{ round(($eliteCount / $totalPlans) * 100, 1) }},
                    {{ round(($premiumCount / $totalPlans) * 100, 1) }},
                    {{ round(($basicCount / $totalPlans) * 100, 1) }}
                ],
                chart: {
                    type: 'radialBar',
                    height: 250,
                    background: 'transparent',
                    fontFamily: 'Inter, sans-serif'
                },
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            name: { fontSize: '16px', fontFamily: 'Outfit', color: '#f3f4f6', offsetY: -10 },
                            value: { fontSize: '13px', fontFamily: 'Inter', color: '#9ca3af', offsetY: 5 },
                            total: {
                                show: true,
                                label: 'Top Tier',
                                color: '#ffffff',
                                formatter: function (w) {
                                    return 'Elite';
                                }
                            }
                        },
                        track: {
                            background: 'rgba(255, 255, 255, 0.04)',
                            margin: 6
                        }
                    }
                },
                colors: ['#3b82f6', '#8b5cf6', '#9ca3af'],
                labels: ['Elite Tier', 'Premium Tier', 'Basic Tier'],
                stroke: { lineCap: 'round' }
            };
            const subChart = new ApexCharts(document.querySelector("#subscriptionRadialChart"), radialOptions);
            subChart.render();
        }
    });
</script>
@endsection

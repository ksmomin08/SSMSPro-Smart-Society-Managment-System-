@extends('layouts.admin')
@section('title', 'Maintenance Bills — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-rupee"></i> Maintenance Bills</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Maintenance</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('maintenance.pdf') }}" class="btn btn-outline-danger">
            <i class="bx bxs-file-pdf me-1"></i> Download PDF
        </a>
        <a href="{{ route('maintenances.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Add Bill
        </a>
    </div>
</div>

<div class="card table-card animate-fadeInUp">
    <div class="card-header">
        <h5 class="card-title mb-0">Maintenance Ledger</h5>
        <div class="table-search-bar">
            <form method="GET" action="{{ route('maintenances.index') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Search by resident name...">
                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bx bx-search"></i></button>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>Resident</th>
                    <th>Billing Month</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Payment Status</th>
                    <th>Online Payment</th>
                    <th>Manual Control</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($maintenances as $maintenance)
                <tr>
                    <td><span class="fw-semibold" style="color: var(--ss-primary);">#{{ $maintenance->id }}</span></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-initial-text me-2" style="background: var(--ss-gradient-primary); color: #fff; width: 38px; height: 38px; font-size: 0.8125rem;">
                                {{ strtoupper(substr($maintenance->resident->name ?? 'R', 0, 2)) }}
                            </div>
                            <strong style="color: var(--ss-dark);">{{ $maintenance->resident->name ?? 'N/A' }}</strong>
                        </div>
                    </td>
                    <td><span class="badge badge-info-custom">{{ $maintenance->month }}</span></td>
                    <td><strong class="text-dark" style="font-size: 1rem;">₹ {{ number_format($maintenance->amount, 2) }}</strong></td>
                    <td><small class="text-muted">{{ $maintenance->due_date ? \Carbon\Carbon::parse($maintenance->due_date)->format('M d, Y') : '-' }}</small></td>
                    <td>
                        @if(strtolower($maintenance->payment_status) == 'paid')
                            <span class="badge badge-status badge-active"><i class="bx bx-check-circle me-1"></i>Paid</span>
                        @else
                            <span class="badge badge-status badge-pending"><i class="bx bx-time-five me-1"></i>Pending</span>
                        @endif
                    </td>
                    <td>
                        @if(strtolower($maintenance->payment_status) != 'paid')
                            <button class="btn btn-primary pay-btn" data-amount="{{ $maintenance->amount }}" data-id="{{ $maintenance->id }}" style="font-size: 0.8125rem; padding: 0.375rem 0.75rem;">
                                <i class="bx bx-credit-card me-1"></i> Pay Now
                            </button>
                        @else
                            <span class="text-success fs-xs fw-semibold"><i class="bx bx-shield-quarter me-1"></i>Settled</span>
                        @endif
                    </td>
                    <td>
                        @if(strtolower($maintenance->payment_status) != 'paid')
                            <a href="{{ route('maintenance.status', $maintenance->id) }}" class="btn btn-outline-success" style="font-size: 0.8125rem; padding: 0.375rem 0.75rem;">
                                <i class="bx bx-check me-1"></i> Mark Paid
                            </a>
                        @else
                            <a href="{{ route('maintenance.status', $maintenance->id) }}" class="btn btn-outline-warning" style="font-size: 0.8125rem; padding: 0.375rem 0.75rem;">
                                <i class="bx bx-undo me-1"></i> Mark Pending
                            </a>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('maintenances.edit', $maintenance->id) }}" class="btn btn-icon-edit" title="Edit Record">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <form action="{{ route('maintenances.destroy', $maintenance->id) }}" method="POST" class="delete-form" id="delete-maintenance-{{ $maintenance->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-icon-delete" title="Delete Record" onclick="confirmDelete('delete-maintenance-{{ $maintenance->id }}')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="bx bx-rupee"></i>
                            <h6>No Maintenance Bills Registered</h6>
                            <p>Generate maintenance bills for society residents to track collections.</p>
                            <a href="{{ route('maintenances.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Generate First Bill</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($maintenances->hasPages())
    <div class="card-footer d-flex justify-content-end py-3">
        {{ $maintenances->links() }}
    </div>
    @endif
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.querySelectorAll('.pay-btn').forEach(button => {
    button.addEventListener('click', function() {
        let amount = this.dataset.amount * 100;
        let maintenanceId = this.dataset.id;
        let options = {
            "key": "{{ env('RAZORPAY_KEY') }}",
            "amount": amount,
            "currency": "INR",
            "name": "Smart Society Management",
            "description": "Maintenance Payment for Bill #" + maintenanceId,
            "handler": function (response) {
                window.location.href = "/payment-success/" + maintenanceId;
            },
            "theme": {
                "color": "#6366f1"
            }
        };
        let rzp = new Razorpay(options);
        rzp.open();
    });
});
</script>
@endsection

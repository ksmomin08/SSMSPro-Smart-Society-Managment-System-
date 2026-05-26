@extends('layouts.admin')
@section('title', 'Visitor Log — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-user-voice"></i> Visitor Log</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Visitors</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('visitors.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Add Visitor
    </a>
</div>

<div class="card table-card animate-fadeInUp">
    <div class="card-header">
        <h5 class="card-title mb-0">All Visitors</h5>
        <div class="table-search-bar">
            <form method="GET" action="{{ route('visitors.index') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Search visitor name or code...">
                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bx bx-search"></i></button>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Visitor</th>
                    <th>Visiting Resident</th>
                    <th>Purpose</th>
                    <th>Visit Date</th>
                    <th>Pass Code</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visitors as $index => $visitor)
                <tr>
                    <td>{{ $visitors->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-initial-text me-2" style="background:rgba(113,221,55,0.12);color:#71dd37;">
                                {{ strtoupper(substr($visitor->visitor_name, 0, 2)) }}
                            </div>
                            <div>
                                <strong>{{ $visitor->visitor_name }}</strong>
                                <br><small class="text-muted">{{ $visitor->mobile }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $visitor->resident->name ?? 'N/A' }}</td>
                    <td><span class="badge bg-label-info">{{ $visitor->purpose }}</span></td>
                    <td><small>{{ \Carbon\Carbon::parse($visitor->visit_date)->format('M d, Y g:i A') }}</small></td>
                    <td>
                        @if($visitor->visitor_code)
                            <code class="fw-bold text-primary">{{ $visitor->visitor_code }}</code>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($visitor->status == 'Approved')
                            <span class="badge badge-status badge-active"><i class="bx bx-check me-1"></i>Approved</span>
                        @elseif($visitor->status == 'Checked In')
                            <span class="badge badge-status badge-info-custom"><i class="bx bx-log-in me-1"></i>Checked In</span>
                        @elseif($visitor->status == 'Checked Out')
                            <span class="badge bg-label-secondary">Checked Out</span>
                        @elseif($visitor->status == 'Denied')
                            <span class="badge badge-status badge-inactive"><i class="bx bx-x me-1"></i>Denied</span>
                        @else
                            <span class="badge badge-status badge-pending"><i class="bx bx-time me-1"></i>{{ $visitor->status }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('visitor.qr', $visitor->id) }}" class="btn btn-icon-view" title="Generate QR">
                                <i class="bx bx-qr-scan"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="bx bx-user-voice"></i>
                            <h6>No Visitor Records</h6>
                            <p>Visitor entries will appear here when they are logged.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($visitors->hasPages())
    <div class="card-footer d-flex justify-content-end py-3">
        {{ $visitors->links() }}
    </div>
    @endif
</div>
@endsection
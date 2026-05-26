@extends('layouts.admin')
@section('title', 'Complaints — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-message-square-error"></i> Complaints</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Complaints</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card table-card animate-fadeInUp">
    <div class="card-header">
        <h5 class="card-title mb-0">All Complaints</h5>
        <div class="table-search-bar">
            <form method="GET" action="{{ route('complaints.index') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Search complaints...">
                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bx bx-search"></i></button>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Resident</th>
                    <th>Subject</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $index => $complaint)
                <tr>
                    <td>{{ $complaints->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-initial-text me-2" style="background:rgba(255,171,0,0.12);color:#ffab00;font-size:0.7rem;">
                                {{ strtoupper(substr($complaint->resident->name ?? 'N', 0, 2)) }}
                            </div>
                            <div>
                                <strong class="d-block">{{ $complaint->resident->name ?? 'N/A' }}</strong>
                                <small class="text-muted">{{ $complaint->resident->flats->flate_number ?? '' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <strong>{{ $complaint->title }}</strong>
                        <br><small class="text-muted" style="max-width:200px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $complaint->description }}</small>
                    </td>
                    <td><span class="badge bg-label-info">{{ $complaint->category ?? 'General' }}</span></td>
                    <td>
                        @php
                            $prColor = match($complaint->priority ?? 'Low') {
                                'Critical', 'High' => 'danger',
                                'Medium' => 'warning',
                                default => 'info'
                            };
                        @endphp
                        <span class="badge bg-label-{{ $prColor }}">{{ $complaint->priority ?? 'Low' }}</span>
                    </td>
                    <td>
                        @if($complaint->status == 'resolved')
                            <span class="badge badge-status badge-active"><i class="bx bx-check-circle me-1"></i>Resolved</span>
                        @elseif($complaint->status == 'in_progress')
                            <span class="badge badge-status badge-pending"><i class="bx bx-loader-alt me-1"></i>In Progress</span>
                        @else
                            <span class="badge badge-status badge-inactive"><i class="bx bx-error me-1"></i>Open</span>
                        @endif
                    </td>
                    <td><small class="text-muted">{{ $complaint->created_at->format('M d, Y') }}</small></td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('complaints.edit', $complaint->id) }}" class="btn btn-icon-edit" title="View & Reply">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <form action="{{ route('complaints.destroy', $complaint->id) }}" method="POST" class="delete-form" id="delete-complaint-{{ $complaint->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-icon-delete" title="Delete" onclick="confirmDelete('delete-complaint-{{ $complaint->id }}')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="bx bx-happy-beaming"></i>
                            <h6>No Complaints!</h6>
                            <p>All quiet on the society front. No complaints to show.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($complaints->hasPages())
    <div class="card-footer d-flex justify-content-end py-3">
        {{ $complaints->links() }}
    </div>
    @endif
</div>
@endsection
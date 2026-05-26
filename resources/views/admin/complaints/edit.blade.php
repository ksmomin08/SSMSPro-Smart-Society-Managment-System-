@extends('layouts.admin')
@section('title', 'Edit Complaint — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-message-square-error"></i> Complaint #{{ $complaint->id }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('complaints.index') }}">Complaints</a></li>
                <li class="breadcrumb-item active">View & Reply</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('complaints.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to List
    </a>
</div>

<div class="row animate-fadeInUp">
    <div class="col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="bx bx-info-circle me-2 text-info"></i>Complaint Details</h5>
            </div>
            <div class="card-body">
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Resident</span>
                        <span class="info-value fw-bold">{{ $complaint->resident->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Subject</span>
                        <span class="info-value">{{ $complaint->title }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Category</span>
                        <span class="info-value"><span class="badge bg-label-info">{{ $complaint->category ?? 'General' }}</span></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Priority</span>
                        <span class="info-value">
                            @php
                                $prColor = match($complaint->priority ?? 'Low') {
                                    'Critical', 'High' => 'danger',
                                    'Medium' => 'warning',
                                    default => 'info'
                                };
                            @endphp
                            <span class="badge bg-label-{{ $prColor }}">{{ $complaint->priority ?? 'Low' }}</span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            @if($complaint->status == 'resolved')
                                <span class="badge bg-success">Resolved</span>
                            @elseif($complaint->status == 'in_progress')
                                <span class="badge bg-warning">In Progress</span>
                            @else
                                <span class="badge bg-danger">Open</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Filed On</span>
                        <span class="info-value">{{ $complaint->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted d-block mb-1">Description:</small>
                    <p class="mb-0">{{ $complaint->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="card-title"><i class="bx bx-reply me-2 text-primary"></i>Admin Action</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('complaints.update', $complaint->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Update Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Open / Pending</option>
                                <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Priority</label>
                            <select name="priority" id="priority" class="form-select">
                                <option value="Low" {{ ($complaint->priority ?? 'Low') == 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ ($complaint->priority ?? '') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="High" {{ ($complaint->priority ?? '') == 'High' ? 'selected' : '' }}>High</option>
                                <option value="Critical" {{ ($complaint->priority ?? '') == 'Critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="admin_reply" class="form-label">Admin Reply / Resolution Note</label>
                        <textarea id="admin_reply" name="admin_reply" class="form-control" rows="4" placeholder="Add your reply or resolution details here...">{{ old('admin_reply', $complaint->admin_reply) }}</textarea>
                    </div>
                    <div class="text-end mt-4">
                        <a href="{{ route('complaints.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-check me-1"></i> Update Complaint</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
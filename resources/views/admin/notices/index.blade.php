@extends('layouts.admin')
@section('title', 'Notices — Smart Society')

@section('content')
<div class="page-header animate-fadeInUp">
    <div>
        <h4 class="page-title"><i class="bx bx-bell"></i> Notices</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Notices</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('notices.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Post Notice
    </a>
</div>

<div class="card table-card animate-fadeInUp">
    <div class="card-header">
        <h5 class="card-title mb-0">All Notices</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notices as $index => $notice)
                <tr>
                    <td>{{ $notices->firstItem() + $index }}</td>
                    <td><strong>{{ $notice->title }}</strong></td>
                    <td>
                        @php
                            $catColor = match($notice->category ?? 'General') {
                                'Emergency' => 'danger',
                                'Announcement' => 'primary',
                                'Event' => 'success',
                                'Maintenance' => 'warning',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-label-{{ $catColor }}">{{ $notice->category ?? 'General' }}</span>
                    </td>
                    <td><small class="text-muted">{{ \Carbon\Carbon::parse($notice->notice_date)->format('M d, Y') }}</small></td>
                    <td><small class="text-muted" style="max-width:250px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $notice->description }}</small></td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('notices.edit', $notice->id) }}" class="btn btn-icon-edit" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <form action="{{ route('notices.destroy', $notice->id) }}" method="POST" class="delete-form" id="delete-notice-{{ $notice->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-icon-delete" title="Delete" onclick="confirmDelete('delete-notice-{{ $notice->id }}')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bx bx-bell"></i>
                            <h6>No Notices Posted</h6>
                            <p>Create a notice to inform residents about important updates.</p>
                            <a href="{{ route('notices.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-plus me-1"></i> Post First Notice</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($notices->hasPages())
    <div class="card-footer d-flex justify-content-end py-3">
        {{ $notices->links() }}
    </div>
    @endif
</div>
@endsection
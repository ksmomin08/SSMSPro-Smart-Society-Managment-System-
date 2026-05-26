@extends('layouts.admin')

@section('header_title', 'SaaS Activity Trail')

@section('content')
<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1 font-weight-bold">📜 Global Operations Audit Log</h5>
            <p class="text-muted mb-0 font-size-sm">Immutable trail tracking master administrators, gate logs, and billing edits.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border-0 text-white">
            <thead>
                <tr class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">
                    <th class="border-0" style="width: 15%;">Timestamp</th>
                    <th class="border-0" style="width: 20%;">Action Class</th>
                    <th class="border-0" style="width: 40%;">Description</th>
                    <th class="border-0" style="width: 15%;">Operator User</th>
                    <th class="border-0 text-end" style="width: 10%;">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="border-0 text-muted" style="font-size: 0.85rem;">
                            {{ $log->created_at->format('d M Y') }}
                            <div style="font-size: 0.75rem;">{{ $log->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="border-0">
                            <span class="badge bg-secondary rounded-pill" style="font-size: 0.75rem; padding: 4px 10px; background-color: rgba(96,165,250,0.15) !important; color: #60a5fa !important; border: 1px solid rgba(96,165,250,0.2);">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="border-0 text-white" style="font-size: 0.85rem;">{{ $log->description }}</td>
                        <td class="border-0 text-white" style="font-size: 0.85rem;">
                            {{ $log->user ? $log->user->name : 'System/Guest' }}
                            @if($log->user && $log->user->role)
                                <div class="text-muted" style="font-size: 0.7rem; text-transform: uppercase;">{{ $log->user->role }}</div>
                            @endif
                        </td>
                        <td class="border-0 text-muted text-end" style="font-size: 0.85rem;">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted border-0 py-5">
                            <span class="fs-1 mb-2">📜</span>
                            <h6>No logs captured yet.</h6>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 border-top border-secondary border-opacity-25 pt-3 d-flex justify-content-center">
        {{ $logs->links() }}
    </div>
</div>
@endsection

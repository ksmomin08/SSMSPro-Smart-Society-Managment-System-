@extends('layouts.admin')

@section('header_title', 'Manage Tenants')

@section('content')
<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1 font-weight-bold">🏢 Housing Societies Directory</h5>
            <p class="text-muted mb-0 font-size-sm">Add, audit, and configure digital parameters for each tenant society.</p>
        </div>
        <a href="{{ route('super-admin.societies.create') }}" class="btn btn-accent">
            ➕ Register New Society
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border-0 text-white">
            <thead>
                <tr class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">
                    <th class="border-0">Society</th>
                    <th class="border-0">Tenant Code</th>
                    <th class="border-0">Support Contact</th>
                    <th class="border-0">Subscription Plan</th>
                    <th class="border-0">Expires On</th>
                    <th class="border-0">Buildings/Users</th>
                    <th class="border-0">Status</th>
                    <th class="border-0 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($societies as $soc)
                    <tr>
                        <td class="border-0 font-weight-bold text-white">
                            {{ $soc->name }}
                            <div class="text-muted font-weight-normal" style="font-size: 0.75rem;">{{ $soc->address }}</div>
                        </td>
                        <td class="border-0 text-muted font-weight-bold">{{ $soc->code }}</td>
                        <td class="border-0" style="font-size: 0.85rem;">
                            <div class="text-white">{{ $soc->email ?? 'N/A' }}</div>
                            <div class="text-muted">{{ $soc->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="border-0">
                            <span class="badge {{ $soc->subscription_plan === 'Elite' ? 'bg-primary' : ($soc->subscription_plan === 'Premium' ? 'bg-purple' : 'bg-secondary') }} rounded-pill" style="font-size: 0.75rem; padding: 4px 10px;">
                                {{ $soc->subscription_plan }}
                            </span>
                        </td>
                        <td class="border-0 text-muted" style="font-size: 0.85rem;">{{ \Carbon\Carbon::parse($soc->expires_at)->format('d M Y') }}</td>
                        <td class="border-0" style="font-size: 0.85rem;">
                            <div>🏢 {{ $soc->buildings_count }} Buildings</div>
                            <div class="text-muted">👥 {{ $soc->users_count }} Staff & Residents</div>
                        </td>
                        <td class="border-0">
                            <span class="status-pill {{ $soc->status === 'active' ? 'success' : 'danger' }}">
                                {{ $soc->status }}
                            </span>
                        </td>
                        <td class="border-0 text-end">
                            <a href="{{ route('super-admin.societies.edit', $soc->id) }}" class="btn btn-glass btn-sm">
                                🔧 Modify
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted border-0 py-5">
                            <div class="fs-2 mb-2">🏢</div>
                            <h6>No housing societies found.</h6>
                            <p class="text-muted mb-0" style="font-size:0.85rem;">Start by registering your first housing society tenant.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 border-top border-secondary border-opacity-25 pt-3 d-flex justify-content-center">
        {{ $societies->links() }}
    </div>
</div>
@endsection

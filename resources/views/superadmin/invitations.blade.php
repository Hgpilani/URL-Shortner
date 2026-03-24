@extends('layouts.app')

@section('header')
    <h2 class="h4 mb-0">Invitations</h2>
@endsection

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('superadmin.invitations.create') }}" class="btn btn-primary">Invite Admin To New Company</a>
        </div>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Role</th>
                            <th>Invited By</th>
                            <th>Status</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invitations as $invitation)
                            <tr>
                                <td>{{ $invitation->email }}</td>
                                <td>{{ $invitation->company_name }}</td>
                                <td>{{ $invitation->role_name }}</td>
                                <td>{{ $invitation->inviter_name ?? '-' }}</td>
                                <td>
                                    @if ($invitation->accepted_at)
                                        <span class="badge text-bg-success">Accepted</span>
                                    @elseif (now()->greaterThan($invitation->expires_at))
                                        <span class="badge text-bg-secondary">Expired</span>
                                    @else
                                        <span class="badge text-bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if (! $invitation->accepted_at && now()->lessThan($invitation->expires_at))
                                        <a href="{{ route('invitation.accept', $invitation->token) }}" class="btn btn-sm btn-outline-primary">Open</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No invitations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>

                <div class="mt-3">
                    {{ $invitations->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

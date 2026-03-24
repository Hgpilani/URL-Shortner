@extends('layouts.app')

@section('header')
    <h2 class="h4 mb-0">Admin Invitations</h2>
@endsection

@section('content')
    <div class="container py-4">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">Invite Admin or Member (Own Company)</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.invitations.store') }}" class="row g-3">
                    @csrf

                    <div class="col-md-4">
                        <label for="invited_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="invited_name" name="invited_name" value="{{ old('invited_name') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="role_id" class="form-label">Role</label>
                        <select id="role_id" name="role_id" class="form-select" required>
                            <option value="">Choose...</option>
                            @foreach ($allowedRoles as $role)
                                <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Invite</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Invitation History</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Expires</th>
                                <th>Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invitations as $invitation)
                                <tr>
                                    <td>{{ $invitation->invited_name ?? '-' }}</td>
                                    <td>{{ $invitation->email }}</td>
                                    <td>{{ $invitation->company_name }}</td>
                                    <td>{{ $invitation->role_name }}</td>
                                    <td>
                                        @if ($invitation->accepted_at)
                                            <span class="badge text-bg-success">Accepted</span>
                                        @elseif (now()->greaterThan($invitation->expires_at))
                                            <span class="badge text-bg-secondary">Expired</span>
                                        @else
                                            <span class="badge text-bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($invitation->expires_at)->format('Y-m-d H:i') }}</td>
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
                                    <td colspan="7">No invitations yet.</td>
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

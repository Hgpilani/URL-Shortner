@extends('layouts.app')

@section('header')
    <h2 class="h4 mb-0">Team Members</h2>
@endsection

@section('content')
    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>User Email</th>
                                <th>Role</th>
                                <th>Total URLs</th>
                                <th>Total Hits</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teamMembers as $row)
                                <tr>
                                    <td>{{ $row->user_name }}</td>
                                    <td>{{ $row->user_email }}</td>
                                    <td>{{ $row->role_name }}</td>
                                    <td>{{ $row->total_urls }}</td>
                                    <td>{{ $row->total_hits }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No team members yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $teamMembers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


@extends('layouts.app')

@section('header')
    <h2 class="h4 mb-0">Stats</h2>
@endsection

@section('content')
    <div class="container py-4">
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                <h3 class="h5 mb-3">URLs Per Company</h3>
                <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Total URLs</th>
                            <th>Total Hits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($urlsPerCompany as $row)
                            <tr>
                                <td>{{ $row->company_name }}</td>
                                <td>{{ $row->total_urls }}</td>
                                <td>{{ $row->total_hits }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No data yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                <h3 class="h5 mb-3">URLs Per Member/Admin</h3>
                <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>User Email</th>
                            <th>Company</th>
                            <th>Role</th>
                            <th>Total URLs</th>
                            <th>Total Hits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($urlsPerMemberOrAdmin as $row)
                            <tr>
                                <td>{{ $row->user_name }}</td>
                                <td>{{ $row->user_email }}</td>
                                <td>{{ $row->company_name }}</td>
                                <td>{{ $row->role_name }}</td>
                                <td>{{ $row->total_urls }}</td>
                                <td>{{ $row->total_hits }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No data yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                <h3 class="h5 mb-3">Users Per Role</h3>
                <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Total Users</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usersPerRole as $row)
                            <tr>
                                <td>{{ $row->role_name }}</td>
                                <td>{{ $row->total_users }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No data yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

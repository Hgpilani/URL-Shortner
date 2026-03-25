@extends('layouts.app')

@section('header')
    <h2 class="h4 mb-0">SuperAdmin Dashboard</h2>
@endsection

@section('content')
    <div class="container py-4">
        <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-3">
                <a href="{{ route('superadmin.companies') }}" class="card text-decoration-none text-reset h-100">
                    <div class="card-body">
                        <div class="text-muted small">Companies</div>
                        <div class="fs-4 fw-semibold">{{ $companiesCount }}</div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="{{ route('superadmin.clients') }}" class="card text-decoration-none text-reset h-100">
                    <div class="card-body">
                        <div class="text-muted small">Users</div>
                        <div class="fs-4 fw-semibold">{{ $usersCount }}</div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="{{ route('urls.index') }}" class="card text-decoration-none text-reset h-100">
                    <div class="card-body">
                        <div class="text-muted small">Short URLs</div>
                        <div class="fs-4 fw-semibold">{{ $urlsCount }}</div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="{{ route('superadmin.invitations') }}" class="card text-decoration-none text-reset h-100">
                    <div class="card-body">
                        <div class="text-muted small">Pending Invitations</div>
                        <div class="fs-4 fw-semibold">{{ $pendingInvitationsCount }}</div>
                    </div>
                </a>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                            <h3 class="h5 mb-0">Generated Short URLs</h3>
                            <a href="{{ route('urls.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Short URL</th>
                                        <th>Original URL</th>
                                        <th>Company</th>
                                        <th>Created By</th>
                                        <th>Hits</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (($recentUrls ?? []) as $url)
                                        @php
                                            $shortUrl = route('urls.resolve', ['code' => $url->short_code]);
                                        @endphp
                                        <tr>
                                            <td class="text-break">
                                                <a href="{{ $shortUrl }}" target="_blank" rel="noopener">{{ $shortUrl }}</a>
                                            </td>
                                            <td class="text-break">
                                                <a href="{{ $url->original_url }}" target="_blank" rel="noopener">{{ Str::limit($url->original_url, 40, '...') }}</a>
                                            </td>
                                            <td>{{ $url->company?->name ?? '-' }}</td>
                                            <td>{{ $url->creator?->name ?? 'System' }}</td>
                                            <td>{{ $url->hits ?? 0 }}</td>
                                            <td>{{ optional($url->created_at)->format('Y-m-d') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">No URLs yet.</td>
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
                        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                            <h3 class="h5 mb-0">Clients</h3>
                            <a href="{{ route('superadmin.clients') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
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

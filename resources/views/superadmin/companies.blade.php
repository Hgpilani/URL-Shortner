@extends('layouts.app')

@section('header')
    <h2 class="h4 mb-0">Companies</h2>
@endsection

@section('content')
    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Users</th>
                            <th>URLs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companies as $company)
                            <tr>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->users_count }}</td>
                                <td>{{ $company->urls_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No companies found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>

                <div class="mt-3">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

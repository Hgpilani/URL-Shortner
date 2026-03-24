@extends('layouts.app')

@section('header')
    <h2 class="h4 mb-0">Invite Admin To New Company</h2>
@endsection

@section('content')
    <div class="container py-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('superadmin.invitations.store') }}" class="row g-3">
                    @csrf

                    <div class="col-md-4">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="invited_name" class="form-label">Admin Name</label>
                        <input type="text" class="form-control" id="invited_name" name="invited_name" value="{{ old('invited_name') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="email" class="form-label">Admin Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Create Invitation</button>
                        <a href="{{ route('superadmin.invitations') }}" class="btn btn-outline-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

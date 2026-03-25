@extends('layouts.guest')

@section('content')
    <h1 class="h4 mb-3">Forgot Password</h1>

    <p class="text-muted mb-3">
        Forgot your password? Enter your email and we’ll send you a reset link.
    </p>

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

    <form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">
        @csrf

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Email Password Reset Link
        </button>

        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">Back to login</a>
    </form>
@endsection

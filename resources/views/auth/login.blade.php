@extends('layouts.guest')

@section('content')
    <h1 class="h4 mb-3">Login</h1>

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

    <form method="POST" action="{{ route('login') }}" class="vstack gap-3">
        @csrf

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
        </div>

        <div class="form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember" value="1" @checked(old('remember'))>
            <label class="form-check-label" for="remember_me">Remember me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Log in</button>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('password.request') }}" class="link-secondary text-decoration-none">Forgot password?</a>
        </div>
    </form>
@endsection

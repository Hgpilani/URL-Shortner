<section>
    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h3 class="h5 mb-1">Profile Information</h3>
            <div class="text-muted small">Update your name and email address.</div>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success">Profile updated.</div>
    @endif

    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success">A new verification link has been sent to your email address.</div>
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

    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" class="vstack gap-3">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="form-label">Name</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        </div>

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="form-text mt-2">
                    Your email address is unverified.
                    <button form="send-verification" class="btn btn-link p-0 align-baseline">Re-send verification email</button>
                </div>
            @endif
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</section>

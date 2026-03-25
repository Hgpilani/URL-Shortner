<section>
    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h3 class="h5 mb-1">Update Password</h3>
            <div class="text-muted small">Use a long, random password to stay secure.</div>
        </div>
    </div>

    @if (session('status') === 'password-updated')
        <div class="alert alert-success">Password updated.</div>
    @endif

    @if ($errors->updatePassword->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->updatePassword->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="vstack gap-3">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="form-label">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" required>
        </div>

        <div>
            <label for="update_password_password" class="form-label">New Password</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" required>
        </div>

        <div>
            <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</section>

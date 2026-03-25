<section>
    <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
        <div>
            <h3 class="h5 mb-1 text-danger">Delete Account</h3>
            <div class="text-muted small">
                This action is permanent. Enter your password to confirm deletion.
            </div>
        </div>
    </div>

    @if ($errors->userDeletion->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->userDeletion->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.destroy') }}" class="vstack gap-3"
          onsubmit="return confirm('Are you sure? This will permanently delete your account.');">
        @csrf
        @method('delete')

        <div>
            <label for="delete_password" class="form-label">Password</label>
            <input id="delete_password" name="password" type="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-danger">
            Delete Account
        </button>
    </form>
</section>

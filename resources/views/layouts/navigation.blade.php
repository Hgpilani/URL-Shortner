<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    @php
        // keeping this simple for assignment scope
        $roleName = \Illuminate\Support\Facades\DB::table('roles')->where('id', auth()->user()->role_id)->value('name');
        $isSuperAdmin = $roleName === 'SuperAdmin';
        $isAdmin = $roleName === 'Admin';
    @endphp

    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">URL Shortener</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('urls.*') ? 'active' : '' }}" href="{{ route('urls.index') }}">URLs</a>
                </li>
                @if ($isAdmin)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.invitations*') ? 'active' : '' }}" href="{{ route('admin.invitations') }}">Invitations</a>
                    </li>
                @endif
                @if ($isSuperAdmin)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.companies') ? 'active' : '' }}" href="{{ route('superadmin.companies') }}">Companies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.invitations') ? 'active' : '' }}" href="{{ route('superadmin.invitations') }}">Invitations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.stats') ? 'active' : '' }}" href="{{ route('superadmin.stats') }}">Stats</a>
                    </li>
                @endif
            </ul>

            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">{{ Auth::user()->name }}</span>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

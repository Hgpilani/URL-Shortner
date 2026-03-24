@extends('layouts.app')

@section('header')
    <h2 class="h4 mb-0">URLs ({{ $roleName }})</h2>
@endsection

@section('content')
    <div class="container py-4">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if (session('short_url'))
            <div class="alert alert-info">
                Short URL:
                <a href="{{ session('short_url') }}" target="_blank" rel="noopener">{{ session('short_url') }}</a>
            </div>
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

        @if (in_array($roleName, ['Admin', 'Member'], true))
            <div class="card mb-3">
                <div class="card-header">Generate Short URL (System Action)</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('urls.generate-request') }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label for="shared_original_url" class="form-label">Long URL</label>
                            <input id="shared_original_url" name="original_url" type="url" class="form-control" placeholder="e.g. https://example.com/very/long/path" value="{{ old('original_url') }}" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Generate</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <form method="GET" action="{{ route('urls.index') }}" class="row g-2 align-items-end mb-3">
            <div class="col-md-4">
                <label for="interval" class="form-label mb-1">View and download by date interval</label>
                <select id="interval" name="interval" class="form-select">
                    <option value="all" @selected(($interval ?? 'all') === 'all')>All</option>
                    <option value="today" @selected(($interval ?? 'all') === 'today')>Today</option>
                    <option value="last_week" @selected(($interval ?? 'all') === 'last_week')>Last Week</option>
                    <option value="last_month" @selected(($interval ?? 'all') === 'last_month')>Last Month</option>
                    <option value="this_month" @selected(($interval ?? 'all') === 'this_month')>This Month</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-outline-primary">Apply</button>
            </div>
            <div class="col-md-auto">
                <a href="{{ route('urls.download', ['interval' => $interval ?? 'all']) }}" class="btn btn-primary">Download (CSV)</a>
            </div>
        </form>

        <div class="card">
            <div class="card-body">
                @if($urls->isEmpty())
                    <p class="mb-0">No URLs available with your visibility rules.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Original URL</th>
                                    <th>Created By</th>
                                    <th>Hits</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($urls as $url)
                                    <tr>
                                        <td>
                                            @php
                                                $shortUrl = route('urls.resolve', ['code' => $url->short_code]);
                                            @endphp
                                            <a href="{{ $shortUrl }}" target="_blank" rel="noopener">{{ $shortUrl }}</a>
                                        </td>
                                        <td class="text-break"><a href="{{ $url->original_url }}" target="_blank" rel="noopener">{{ Str::limit($url->original_url, 30, '...') }}</a>
                                        
                                        
                                        </td>
                                        <td>{{ $url->creator?->name ?? 'System' }}</td>
                                        <td>{{ $url->hits ?? 0 }}</td>
                                        <td>{{ optional($url->created_at)->format('Y-m-d') }}</td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary copy-short-url-btn"
                                                data-short-url="{{ $shortUrl }}"
                                            >
                                                Copy URL
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $urls->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.copy-short-url-btn').forEach(function (button) {
            button.addEventListener('click', async function () {
                const shortUrl = button.getAttribute('data-short-url');

                try {
                    await navigator.clipboard.writeText(shortUrl);
                    const oldText = button.textContent;
                    button.textContent = 'Copied';
                    setTimeout(function () {
                        button.textContent = oldText;
                    }, 1200);
                } catch (error) {
                    window.prompt('Copy this URL:', shortUrl);
                }
            });
        });
    </script>
@endsection

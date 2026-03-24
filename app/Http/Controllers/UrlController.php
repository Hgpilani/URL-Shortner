<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UrlController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Url::class);

        $user = $request->user();
        $roleName = DB::table('roles')->where('id', $user->role_id)->value('name');

        $query = Url::query()->with('creator');

        // keeping this simple for assignment scope
        if ($roleName === 'SuperAdmin') {
        } elseif ($roleName === 'Admin') {
            $query->where('company_id', '!=', $user->company_id);
        } elseif ($roleName === 'Member') {
            $query->where('created_by', '!=', $user->id);
        } else {
            $query->whereRaw('1 = 0');
        }

        $urls = $query->latest()->get();

        return view('urls.index', [
            'urls' => $urls,
            'roleName' => $roleName,
        ]);
    }

    public function resolve(Request $request, string $code): RedirectResponse
    {
        $url = Url::query()->where('short_code', $code)->firstOrFail();

        try {
            $this->authorize('view', $url);
        } catch (AuthorizationException) {
            // strict mode: do not reveal if code exists
            abort(404);
        }

        return redirect()->away($url->original_url);
    }
}

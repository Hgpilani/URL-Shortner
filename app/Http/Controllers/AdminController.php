<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    private function ensureAdmin(Request $request): int
    {
        abort_unless(DB::table('roles')->where('id', $request->user()->role_id)->value('name') === 'Admin', 403);

        return (int) $request->user()->company_id;
    }

    public function dashboard(Request $request): View
    {
        $companyId = $this->ensureAdmin($request);
        $companyName = (string) (DB::table('companies')->where('id', $companyId)->value('name') ?? 'Company');

        $usersCount = DB::table('users')->where('company_id', $companyId)->count();
        $urlsCount = DB::table('urls')->where('company_id', $companyId)->count();
        $totalHits = (int) DB::table('urls')->where('company_id', $companyId)->sum('hits');
        $pendingInvitationsCount = DB::table('invitations')
            ->where('company_id', $companyId)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->count();

        $urlsPerCompany = DB::table('urls')
            ->join('companies', 'companies.id', '=', 'urls.company_id')
            ->where('urls.company_id', $companyId)
            ->select(
                'companies.name as company_name',
                DB::raw('COUNT(urls.id) as total_urls'),
                DB::raw('COALESCE(SUM(urls.hits), 0) as total_hits')
            )
            ->groupBy('companies.name')
            ->get();

        $urlsPerMemberOrAdmin = DB::table('urls')
            ->join('users', 'users.id', '=', 'urls.created_by')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('users.company_id', $companyId)
            ->whereIn('roles.name', ['Admin', 'Member'])
            ->select(
                'users.name as user_name',
                'users.email as user_email',
                'roles.name as role_name',
                DB::raw('COUNT(urls.id) as total_urls'),
                DB::raw('COALESCE(SUM(urls.hits), 0) as total_hits')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'roles.name')
            ->orderByDesc('total_urls')
            ->limit(5)
            ->get();

        $usersPerRole = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('users.company_id', $companyId)
            ->select('roles.name as role_name', DB::raw('COUNT(users.id) as total_users'))
            ->groupBy('roles.name')
            ->orderBy('roles.name')
            ->get();

        $recentUrls = Url::query()
            ->with('creator')
            ->where('company_id', $companyId)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboards.admin', compact(
            'companyName',
            'usersCount',
            'urlsCount',
            'totalHits',
            'pendingInvitationsCount',
            'urlsPerCompany',
            'urlsPerMemberOrAdmin',
            'usersPerRole',
            'recentUrls'
        ));
    }

    public function teamMembers(Request $request): View
    {
        $companyId = $this->ensureAdmin($request);

        $teamMembers = DB::table('urls')
            ->join('users', 'users.id', '=', 'urls.created_by')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('users.company_id', $companyId)
            ->whereIn('roles.name', ['Admin', 'Member'])
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'roles.name as role_name',
                DB::raw('COUNT(urls.id) as total_urls'),
                DB::raw('COALESCE(SUM(urls.hits), 0) as total_hits')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'roles.name')
            ->orderByDesc('total_urls')
            ->paginate(10)
            ->withQueryString();

        return view('admin.team-members', compact('teamMembers'));
    }
}

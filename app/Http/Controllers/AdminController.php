<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        abort_unless(DB::table('roles')->where('id', $request->user()->role_id)->value('name') === 'Admin', 403);

        $companyId = (int) $request->user()->company_id;
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
            ->get();

        $usersPerRole = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('users.company_id', $companyId)
            ->select('roles.name as role_name', DB::raw('COUNT(users.id) as total_users'))
            ->groupBy('roles.name')
            ->orderBy('roles.name')
            ->get();

        return view('dashboards.admin', compact(
            'companyName',
            'usersCount',
            'urlsCount',
            'totalHits',
            'pendingInvitationsCount',
            'urlsPerCompany',
            'urlsPerMemberOrAdmin',
            'usersPerRole'
        ));
    }
}

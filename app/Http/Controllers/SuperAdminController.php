<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SuperAdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        $this->ensureSuperAdmin($request);
        $statsData = $this->buildStatsData();
        $recentUrls = Url::query()
            ->with(['creator', 'company'])
            ->latest()
            ->limit(5)
            ->get();
        $clientsPreview = $this->buildClientsQuery()
            ->limit(5)
            ->get();

        return view('superadmin.dashboard', [
            'companiesCount' => Company::query()->count(),
            'usersCount' => DB::table('users')->count(),
            'urlsCount' => Url::query()->count(),
            'pendingInvitationsCount' => DB::table('invitations')
                ->whereNull('accepted_at')
                ->where('expires_at', '>', now())
                ->count(),
            'urlsPerCompany' => $statsData['urlsPerCompany'],
            'urlsPerMemberOrAdmin' => $clientsPreview,
            'usersPerRole' => $statsData['usersPerRole'],
            'recentUrls' => $recentUrls,
        ]);
    }

    public function clients(Request $request): View
    {
        $this->ensureSuperAdmin($request);

        $clients = $this->buildClientsQuery()
            ->paginate(10)
            ->withQueryString();

        return view('superadmin.clients', compact('clients'));
    }

    public function companies(Request $request): View
    {
        $this->ensureSuperAdmin($request);

        $companies = Company::query()
            ->withCount(['users', 'urls'])
            ->orderBy('name')
            ->paginate(10);

        return view('superadmin.companies', compact('companies'));
    }

    public function invitations(Request $request): View
    {
        $this->ensureSuperAdmin($request);

        $invitations = DB::table('invitations')
            ->join('companies', 'companies.id', '=', 'invitations.company_id')
            ->join('roles', 'roles.id', '=', 'invitations.role_id')
            ->leftJoin('users as inviters', 'inviters.id', '=', 'invitations.invited_by')
            ->select([
                'invitations.email',
                'invitations.token',
                'invitations.expires_at',
                'invitations.accepted_at',
                'companies.name as company_name',
                'roles.name as role_name',
                'inviters.name as inviter_name',
            ])
            ->orderByDesc('invitations.id')
            ->paginate(10);

        return view('superadmin.invitations', compact('invitations'));
    }

    public function stats(Request $request): View
    {
        $this->ensureSuperAdmin($request);
        $statsData = $this->buildStatsData();

        return view('superadmin.stats', $statsData);
    }

    private function ensureSuperAdmin(Request $request): void
    {
        // keeping this simple for assignment scope
        $roleName = DB::table('roles')->where('id', $request->user()->role_id)->value('name');
        abort_unless($roleName === 'SuperAdmin', 403);
    }

    private function applyDateIntervalScope($query, string $interval): void
    {
        $now = Carbon::now();

        if ($interval === 'today') {
            $query->whereBetween('created_at', [$now->copy()->startOfDay(), $now]);

            return;
        }

        if ($interval === 'last_week') {
            $query->whereBetween('created_at', [$now->copy()->subDays(7), $now]);

            return;
        }

        if ($interval === 'last_month') {
            $query->whereBetween('created_at', [
                $now->copy()->subMonthNoOverflow()->startOfMonth(),
                $now->copy()->subMonthNoOverflow()->endOfMonth(),
            ]);

            return;
        }

        if ($interval === 'this_month') {
            $query->whereBetween('created_at', [$now->copy()->startOfMonth(), $now]);
        }
    }

    private function csvCell(string $value): string
    {
        $escaped = str_replace('"', '""', $value);

        return '"'.$escaped.'"';
    }

    private function buildStatsData(): array
    {
        $urlsPerCompany = DB::table('urls')
            ->join('companies', 'companies.id', '=', 'urls.company_id')
            ->select(
                'companies.name as company_name',
                DB::raw('COUNT(urls.id) as total_urls'),
                DB::raw('COALESCE(SUM(urls.hits), 0) as total_hits')
            )
            ->groupBy('companies.name')
            ->orderByDesc('total_urls')
            ->get();

        $urlsPerMemberOrAdmin = $this->buildClientsQuery()->get();

        $usersPerRole = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->select('roles.name as role_name', DB::raw('COUNT(users.id) as total_users'))
            ->groupBy('roles.name')
            ->orderBy('roles.name')
            ->get();

        return [
            'urlsPerCompany' => $urlsPerCompany,
            'urlsPerMemberOrAdmin' => $urlsPerMemberOrAdmin,
            'usersPerRole' => $usersPerRole,
        ];
    }

    private function buildClientsQuery()
    {
        return DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('companies', 'companies.id', '=', 'users.company_id')
            ->leftJoin('urls', 'urls.created_by', '=', 'users.id')
            ->whereIn('roles.name', ['SuperAdmin', 'Admin', 'Member'])
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'companies.name as company_name',
                'roles.name as role_name',
                DB::raw('COUNT(urls.id) as total_urls'),
                DB::raw('COALESCE(SUM(urls.hits), 0) as total_hits')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'companies.name', 'roles.name')
            ->orderByDesc('total_urls')
            ->orderBy('users.name');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SuperAdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        $this->ensureSuperAdmin($request);
        $statsData = $this->buildStatsData();

        return view('superadmin.dashboard', [
            'companiesCount' => Company::query()->count(),
            'usersCount' => DB::table('users')->count(),
            'urlsCount' => Url::query()->count(),
            'pendingInvitationsCount' => DB::table('invitations')
                ->whereNull('accepted_at')
                ->where('expires_at', '>', now())
                ->count(),
            'urlsPerCompany' => $statsData['urlsPerCompany'],
            'urlsPerMemberOrAdmin' => $statsData['urlsPerMemberOrAdmin'],
            'usersPerRole' => $statsData['usersPerRole'],
        ]);
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

    public function urls(Request $request): View
    {
        $this->ensureSuperAdmin($request);
        $interval = (string) $request->query('interval', 'all');

        $query = Url::query()
            ->with(['creator', 'company'])
            ->latest();

        $this->applyDateIntervalScope($query, $interval);

        $urls = $query->paginate(10)->withQueryString();

        return view('superadmin.urls', compact('urls', 'interval'));
    }

    public function downloadUrlsCsv(Request $request): Response
    {
        $this->ensureSuperAdmin($request);
        $interval = (string) $request->query('interval', 'all');

        $query = Url::query()
            ->with(['creator', 'company'])
            ->latest();

        $this->applyDateIntervalScope($query, $interval);

        $rows = $query->get();
        $csv = [];
        $csv[] = implode(',', ['Short URL', 'Long URL', 'Company', 'Created By', 'Hits', 'Created At']);

        foreach ($rows as $url) {
            $csv[] = implode(',', [
                $this->csvCell(route('urls.resolve', ['code' => $url->short_code])),
                $this->csvCell($url->original_url),
                $this->csvCell($url->company?->name ?? '-'),
                $this->csvCell($url->creator?->name ?? 'System'),
                (string) ($url->hits ?? 0),
                $this->csvCell((string) $url->created_at),
            ]);
        }

        $filename = 'superadmin_urls_'.$interval.'_'.now()->format('Ymd_His').'.csv';

        return response(implode("\n", $csv), 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);

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

        $urlsPerMemberOrAdmin = DB::table('urls')
            ->join('users', 'users.id', '=', 'urls.created_by')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->join('companies', 'companies.id', '=', 'users.company_id')
            ->whereIn('roles.name', ['Admin', 'Member'])
            ->select(
                'users.name as user_name',
                'users.email as user_email',
                'companies.name as company_name',
                'roles.name as role_name',
                DB::raw('COUNT(urls.id) as total_urls'),
                DB::raw('COALESCE(SUM(urls.hits), 0) as total_hits')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'companies.name', 'roles.name')
            ->orderByDesc('total_urls')
            ->get();

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
}

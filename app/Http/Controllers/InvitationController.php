<?php

namespace App\Http\Controllers;

use App\Mail\InvitationMail;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function acceptForm(string $token): View
    {
        $invitation = $this->getActiveInvitationOrFail($token);
        $roleName = DB::table('roles')->where('id', $invitation->role_id)->value('name');

        return view('auth.accept-invitation', [
            'invitation' => $invitation,
            'token' => $token,
            'roleName' => $roleName,
        ]);
    }

    public function acceptStore(Request $request, string $token): RedirectResponse
    {
        $invitation = $this->getActiveInvitationOrFail($token);

        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (DB::table('users')->where('email', $invitation->email)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'This invitation email is already registered.',
            ]);
        }

        DB::transaction(function () use ($invitation, $data): void {
            User::query()->create([
                'company_id' => $invitation->company_id,
                'role_id' => $invitation->role_id,
                'name' => trim($data['name']),
                'email' => $invitation->email,
                'password' => Hash::make($data['password']),
            ]);

            Invitation::query()
                ->where('id', $invitation->id)
                ->update([
                    'accepted_at' => now(),
                    // keeping this simple for assignment scope
                    'token' => Str::random(64),
                ]);
        });

        return redirect()->route('login')->with('status', 'Invitation accepted. You can now log in.');
    }

    public function superAdminCreate(Request $request): View
    {
        $this->ensureRole($request, 'SuperAdmin');

        return view('superadmin.invitations_create');
    }

    public function superAdminStore(Request $request): RedirectResponse
    {
        $this->ensureRole($request, 'SuperAdmin');

        $data = $request->validate([
            'company_name' => ['required', 'string', 'min:2', 'max:120', 'unique:companies,name'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $adminRoleId = $this->getRoleIdOrFail('Admin');
        $email = strtolower(trim($data['email']));

        $this->assertEmailNotRegistered($email);

        $company = Company::query()->create([
            'name' => trim($data['company_name']),
        ]);

        $this->assertNoActiveInvitation($company->id, $email);

        DB::transaction(function () use ($company, $request, $email, $adminRoleId): void {
            $invitation = Invitation::query()->create([
                'company_id' => $company->id,
                'invited_by' => $request->user()->id,
                'email' => $email,
                'role_id' => $adminRoleId,
                'token' => Str::random(64),
                'expires_at' => now()->addDays(7),
            ]);

            // strict mode: if mail fails, invitation creation is rolled back
            Mail::to($email)->send(new InvitationMail(
                $invitation,
                'Admin',
                $company->name,
                (string) $request->user()->name,
                (string) $request->user()->email
            ));
        });

        return redirect()->route('superadmin.invitations')->with('status', 'Admin invitation created and email sent.');
    }

    public function adminIndex(Request $request): View
    {
        $this->ensureRole($request, 'Admin');

        $allowedRoles = DB::table('roles')
            ->whereIn('name', ['Admin', 'Member'])
            ->orderBy('name')
            ->get(['id', 'name']);

        $invitations = DB::table('invitations')
            ->join('companies', 'companies.id', '=', 'invitations.company_id')
            ->join('roles', 'roles.id', '=', 'invitations.role_id')
            ->select([
                'invitations.invited_name',
                'invitations.email',
                'invitations.token',
                'invitations.expires_at',
                'invitations.accepted_at',
                'roles.name as role_name',
                'companies.name as company_name',
            ])
            ->where('invitations.company_id', $request->user()->company_id)
            ->orderByDesc('invitations.id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.invitations', compact('allowedRoles', 'invitations'));
    }

    public function adminStore(Request $request): RedirectResponse
    {
        $this->ensureRole($request, 'Admin');

        $allowedRoleIds = DB::table('roles')->whereIn('name', ['Admin', 'Member'])->pluck('id')->all();

        $data = $request->validate([
            'invited_name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'role_id' => ['required', 'integer', Rule::in($allowedRoleIds)],
        ]);

        $email = strtolower(trim($data['email']));
        $this->assertEmailNotRegistered($email);
        $this->assertNoActiveInvitation($request->user()->company_id, $email);

        $roleName = DB::table('roles')->where('id', (int) $data['role_id'])->value('name');
        $companyName = DB::table('companies')->where('id', $request->user()->company_id)->value('name') ?? 'Company';

        DB::transaction(function () use ($request, $email, $data, $roleName, $companyName): void {
            $invitation = Invitation::query()->create([
                'company_id' => $request->user()->company_id,
                'invited_by' => $request->user()->id,
                'invited_name' => trim($data['invited_name']),
                'email' => $email,
                'role_id' => (int) $data['role_id'],
                'token' => Str::random(64),
                'expires_at' => now()->addDays(7),
            ]);

            // strict mode: if mail fails, invitation creation is rolled back
            Mail::to($email)->send(new InvitationMail(
                $invitation,
                (string) $roleName,
                $companyName,
                (string) $request->user()->name,
                (string) $request->user()->email
            ));
        });

        return redirect()->route('admin.invitations')->with('status', 'Invitation created and email sent.');
    }

    private function ensureRole(Request $request, string $roleName): void
    {
        // keeping this simple for assignment scope
        $currentRole = DB::table('roles')->where('id', $request->user()->role_id)->value('name');
        abort_unless($currentRole === $roleName, 403);
    }

    private function getRoleIdOrFail(string $roleName): int
    {
        $roleId = DB::table('roles')->where('name', $roleName)->value('id');
        abort_if(! $roleId, 500, 'Required role missing.');

        return (int) $roleId;
    }

    private function assertEmailNotRegistered(string $email): void
    {
        if (DB::table('users')->where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'This email already belongs to a user.',
            ]);
        }
    }

    private function assertNoActiveInvitation(int $companyId, string $email): void
    {
        $hasActiveInvite = Invitation::query()
            ->where('company_id', $companyId)
            ->where('email', $email)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->exists();

        if ($hasActiveInvite) {
            throw ValidationException::withMessages([
                'email' => 'Active invitation already exists for this email in company.',
            ]);
        }
    }

    private function getActiveInvitationOrFail(string $token): Invitation
    {
        $invitation = Invitation::query()
            ->where('token', $token)
            ->first();

        abort_if(! $invitation, 404);
        abort_if($invitation->accepted_at !== null, 404);
        abort_if(now()->greaterThan($invitation->expires_at), 404);

        return $invitation;
    }
}

<?php

namespace App\Policies;

use App\Models\Url;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UrlPolicy
{
    public function viewAny(User $user): bool
    {
        $roleName = DB::table('roles')->where('id', $user->role_id)->value('name');

        return in_array($roleName, ['SuperAdmin', 'Admin', 'Member'], true);
    }

    public function view(User $user, Url $url): bool
    {
        $roleName = DB::table('roles')->where('id', $user->role_id)->value('name');

        if ($roleName === 'SuperAdmin') {
            return true;
        }

        if ($roleName === 'Admin') {
            // updated rule: can see URLs from own company
            return (int) $url->company_id === (int) $user->company_id;
        }

        if ($roleName === 'Member') {
            // updated rule: can see URLs created by self
            return (int) $url->created_by === (int) $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        // assignment rule: no role can create short URLs
        return false;
    }
}

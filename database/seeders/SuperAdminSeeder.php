<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = DB::table('roles')->where('name', 'SuperAdmin')->first();

        if (! $superAdminRole) {
            return;
        }

        $existingUser = DB::table('users')->where('email', 'superadmin@example.com')->exists();
        if ($existingUser) {
            return;
        }

        $company = DB::table('companies')->where('name', 'Platform')->first();
        $companyId = $company?->id ?? DB::table('companies')->insertGetId([
            'name' => 'Platform',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // raw SQL is required by the assignment for this user creation
        DB::insert(
            'INSERT INTO users (company_id, role_id, name, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                $companyId,
                $superAdminRole->id,
                'Super Admin',
                'superadmin@example.com',
                Hash::make('password'),
                now(),
                now(),
            ]
        );
    }
}

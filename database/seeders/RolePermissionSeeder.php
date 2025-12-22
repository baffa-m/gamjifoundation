<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view hero-slides', 'create hero-slides', 'edit hero-slides', 'delete hero-slides',
            'view awards', 'create awards', 'edit awards', 'delete awards', 'suspend awards',
            'view all applications', 'view own applications', 'create applications', 
            'review applications', 'approve applications', 'reject applications',
            'view sponsors', 'create sponsors', 'edit sponsors', 'approve sponsors', 
            'reject sponsors', 'suspend sponsors',
            'view news', 'create news', 'edit news', 'delete news', 'publish news',
            'view applicants', 'edit applicants', 'delete applicants',
            'view admin-dashboard', 'view applicant-dashboard', 'view sponsor-dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Admin Role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Create Applicant Role
        $applicantRole = Role::create(['name' => 'applicant']);
        $applicantRole->givePermissionTo([
            'view awards', 'view own applications', 'create applications',
            'view news', 'view applicant-dashboard',
        ]);

        // Create Sponsor Role
        $sponsorRole = Role::create(['name' => 'sponsor']);
        $sponsorRole->givePermissionTo([
            'view awards', 'create awards', 'edit awards',
            'view sponsors', 'view news', 'view sponsor-dashboard',
        ]);

        // Create Super Admin
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create Test Applicant
        $applicantUser = User::create([
            'name' => 'Test Applicant',
            'email' => 'applicant@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $applicantUser->assignRole('applicant');

        // Create Test Sponsor
        $sponsorUser = User::create([
            'name' => 'Test Sponsor',
            'email' => 'sponsor@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $sponsorUser->assignRole('sponsor');
    }
}
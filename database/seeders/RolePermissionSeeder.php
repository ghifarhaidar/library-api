<?php

namespace Database\Seeders;

use App\Enums\RoleType;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch roles
        $adminRole = Role::where('name', RoleType::ADMIN->value)->first();
        $librarianRole = Role::where('name', RoleType::LIBRARIAN->value)->first();

        // ADMIN gets all permissions
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id'));

        // LIBRARIAN gets only some permissions
        $librarianPermissions = Permission::whereIn('name', [
            'clients.create',
            'clients.view',
            'clients.update',
            'clients.delete',
            'clients.view_details',
            'authors.view',
            'books.view',
            'borrowings.create',
            'borrowings.view',
            'borrowings.update',
            'borrowings.delete',
            'borrowings.view_details',
        ])->get();

        $librarianRole->permissions()->sync($librarianPermissions->pluck('id'));
    }
}

<?php

namespace Database\Seeders;

use App\Enums\RoleType;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'users.create'],
            ['name' => 'users.view'],
            ['name' => 'users.update'],
            ['name' => 'users.delete'],
            ['name' => 'users.view_details'],

            ['name' => 'clients.create'],
            ['name' => 'clients.view'],
            ['name' => 'clients.update'],
            ['name' => 'clients.delete'],
            ['name' => 'clients.view_details'],

            ['name' => 'authors.create'],
            ['name' => 'authors.view'],
            ['name' => 'authors.update'],
            ['name' => 'authors.delete'],
            ['name' => 'authors.view_details'],

            ['name' => 'books.create'],
            ['name' => 'books.view'],
            ['name' => 'books.update'],
            ['name' => 'books.delete'],
            ['name' => 'books.view_details'],

            ['name' => 'borrowings.create'],
            ['name' => 'borrowings.view'],
            ['name' => 'borrowings.update'],
            ['name' => 'borrowings.delete'],
            ['name' => 'borrowings.view_details'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrInsert(['name' => $perm['name']], $perm);
        }
    }
}

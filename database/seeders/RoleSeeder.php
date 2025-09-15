<?php

namespace Database\Seeders;

use App\Enums\RoleType;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => RoleType::ADMIN->value],
            ['name' => RoleType::LIBRARIAN->value],
        ];

        foreach ($roles as $role) {
            Role::updateOrInsert(['name' => $role['name']], $role);
        }
    }
}

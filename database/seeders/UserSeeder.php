<?php

namespace Database\Seeders;

use App\Enums\RoleType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', RoleType::ADMIN->value)->first();
        $librarianRole = Role::where('name', RoleType::LIBRARIAN->value)->first();

        // Create default admin
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'phone' => '111-111-1111',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,

        ]);

        // Create default librarian
        $librarian = User::factory()->create([
            'name' => 'Default Librarian',
            'phone' => '222-222-2222',
            'password' => Hash::make('password'),
            'role_id' => $librarianRole->id,

        ]);

        // Create 5 more random librarians
        User::factory()->count(5)->create([
            'role_id' => $librarianRole->id,
        ]);
    }
}

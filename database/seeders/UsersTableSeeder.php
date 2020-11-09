<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        \DB::table('role_user')->truncate();

        $adminRole = Role::where('name', 'admin')->first();
        $editorRole = Role::where('name', 'editor')->first();
        $userRole = Role::where('name', 'user')->first();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@am.am',
            'password' => Hash::make('admin')
        ]);

        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@am.am',
            'password' => Hash::make('editor')
        ]);

        $user = User::create([
            'name' => 'User',
            'email' => 'user@am.am',
            'password' => Hash::make('user')
        ]);

        $admin->roles()->attach($adminRole);
        $editor->roles()->attach($editorRole);
        $user->roles()->attach($userRole);
    }
}

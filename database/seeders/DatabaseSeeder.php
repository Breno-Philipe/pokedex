<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Admin',
            'email'=>'admin@email.com',
            'password'=>bcrypt('123456'),
            'role'=>'admin'
        ]);

        User::create([
            'name'=>'Editor',
            'email'=>'editor@email.com',
            'password'=>bcrypt('123456'),
            'role'=>'editor'
        ]);

        User::create([
            'name'=>'Viewer',
            'email'=>'viewer@email.com',
            'password'=>bcrypt('123456'),
            'role'=>'viewer'
        ]);
    }
}

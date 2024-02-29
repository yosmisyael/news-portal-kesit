<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User([
            'username' => 'test',
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'test',
        ]);

        $user->save();

        $user = new User([
            'username' => 'demo',
            'name' => 'demo',
            'email' => 'demo@demo.com',
            'password' => 'demo',
        ]);
        $user->save();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name' => 'ユーザー1', 'email' => 'user1@test.com'],
            ['name' => 'ユーザー2', 'email' => 'user2@test.com'],
            ['name' => 'ユーザー3', 'email' => 'user3@test.com'],
            ['name' => 'ユーザー4', 'email' => 'user4@test.com'],
            ['name' => 'ユーザー5', 'email' => 'user5@test.com'],
        ];

        foreach ($users as $user){
            DB::table('users')->updateOrInsert(
                [
                    'email' => $user['email'],
                ],
                [
                    'role' => 'user',
                    'name' => $user['name'],
                    'password' => Hash::make('user1234'),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

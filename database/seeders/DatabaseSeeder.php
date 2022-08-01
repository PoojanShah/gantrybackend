<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
             'name' => 'admin',
             'email' => 'k.makienko1990@gmail.com',
            'email_verified_at' => '2020-12-01 09:44:26',
            'password' => '$2y$10$aHwispQqRvADyT3D0fs8QORSJFUcmYeNc53WMtc6LPxac2BZWzpTS',
            'remember_token' => 'ltQ7723U1Fx8RSOTuTLkHuonC4ZUbkevnpYKJEVRA8ZpRm7O1T7HQ8UzlB7n',
            'new_email' => '',
            'new_password' => '',
            'token_for_password' => '',
            'superadmin' => '1',
         ]);
    }
}

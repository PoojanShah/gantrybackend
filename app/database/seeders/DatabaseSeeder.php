<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
             'name' => 'admin',
             'email' => 'k.makienko1990@gmail.com',
            'email_verified_at' => '2020-12-01 09:44:26',
            'password' => Hash::make('12345'),
            'remember_token' => 'ltQ7723U1Fx8RSOTuTLkHuonC4ZUbkevnpYKJEVRA8ZpRm7O1T7HQ8UzlB7n',
            'new_email' => '',
            'new_password' => '',
            'token_for_password' => '',
            'superadmin' => '1',
         ]);

        Subscription::factory(3)->create();
        foreach (Subscription::all() as $k  =>  $subscription){
            $customer = new Customer();
            $customer->zoho_customer_id = $subscription->zoho_customer_id;
            $customer->installation_id = uniqid();
            $customer->display_name = 'Customer' . $k;
            $customer->save();

            $subscription->customer_id = $customer->id;
            $subscription->save();
        }
    }
}

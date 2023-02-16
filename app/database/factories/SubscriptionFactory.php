<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'zoho_subscription_id' => rand(1, 999999999999),
            'zoho_product_id' => rand(1, 999999999999),
            'plan_code' => Str::random(10),
            'previous_subscription_status' => 'created',
            'subscription_status' => 'active',
            'modified_at' => $this->faker->date("yyyy-MM-dd'T'HH:mm:ssZ"),
            'zoho_customer_id' => rand(1, 999999999999),
            'customer_id' => rand(1, 999999999999),
        ];
    }


}

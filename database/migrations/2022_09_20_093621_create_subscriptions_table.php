<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zoho_subscription_id')->unique();
            $table->unsignedBigInteger('zoho_product_id');
            $table->string('plan_code');
            $table->unsignedBigInteger('zoho_plan_id');
            $table->string('previous_subscription_status');
            $table->string('subscription_status');
            $table->string('modified_at');
            $table->unsignedBigInteger('zoho_customer_id')->unique();
            $table->unsignedBigInteger('customer_id')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};

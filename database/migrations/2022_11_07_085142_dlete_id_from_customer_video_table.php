<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customer_video', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }

    public function down()
    {
        Schema::table('customer_video', function (Blueprint $table) {
            $table->id();
        });
    }
};

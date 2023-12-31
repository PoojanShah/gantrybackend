<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('video', function (Blueprint $table) {
            $table->string('zoho_addon_code')->unique()->nullable();
        });
    }

    public function down()
    {
        Schema::table('video', function (Blueprint $table) {
            $table->dropColumn('zoho_addon_code');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('access_keys', function (Blueprint $table) {
            $table->boolean('is_prepaid')->after('is_satellite')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('access_keys', function (Blueprint $table) {
            $table->dropColumn('is_prepaid');
        });
    }
};

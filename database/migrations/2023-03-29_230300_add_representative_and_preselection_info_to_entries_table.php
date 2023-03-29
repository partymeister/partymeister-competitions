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
        Schema::table('entries', function (Blueprint $table) {
            $table->boolean('notify_about_status')->after('discord_name')->default(false);
            $table->string('representative')->after('discord_name');
            $table->integer('identifier')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn('notify_about_status');
            $table->dropColumn('representative');
            $table->dropColumn('identifier');
        });
    }
};

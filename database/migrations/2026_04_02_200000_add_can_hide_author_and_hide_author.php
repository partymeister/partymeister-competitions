<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competition_types', function (Blueprint $table) {
            $table->boolean('can_hide_author')->default(false)->after('is_anonymous');
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->boolean('hide_author')->default(false)->after('is_prepared');
        });
    }

    public function down(): void
    {
        Schema::table('competition_types', function (Blueprint $table) {
            $table->dropColumn('can_hide_author');
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn('hide_author');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('competition_types', function (Blueprint $table) {
            $table->boolean('has_out_of_competition_voting')
                ->after('file_is_optional')
                ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competition_types', function (Blueprint $table) {
            $table->dropColumn('has_out_of_competition_voting');
        });
    }
};

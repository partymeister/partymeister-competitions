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
            $table->boolean('has_ai_options')->after('file_is_optional')->default(false);
            $table->boolean('has_engine_options')->after('file_is_optional')->default(false);
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->string('ai_usage')->after('discord_name')->default(false);
            $table->text('ai_usage_description')->after('discord_name')->default('')->nullable();
            $table->string('engine_option')->after('discord_name')->default('')->nullable();
            $table->text('engine_option_description')->after('discord_name')->default('')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competition_types', function (Blueprint $table) {
            $table->dropColumn('has_ai_options');
            $table->dropColumn('has_engine_options');
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn('ai_usage');
            $table->dropColumn('ai_usage_description');
            $table->dropColumn('engine_option');
            $table->dropColumn('engine_option_description');
        });
    }
};

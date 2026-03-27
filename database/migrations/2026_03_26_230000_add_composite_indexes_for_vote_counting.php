<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->index(['entry_id', 'visitor_id'], 'idx_votes_entry_visitor');
            $table->index(['entry_id', 'special_vote'], 'idx_votes_entry_special');
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->index(['competition_id', 'status'], 'idx_entries_competition_status');
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropIndex('idx_votes_entry_visitor');
            $table->dropIndex('idx_votes_entry_special');
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->dropIndex('idx_entries_competition_status');
        });
    }
};

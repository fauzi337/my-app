<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Clean up orphaned records in activity_t where agenda_id refers to a non-existent agenda
        DB::statement('DELETE FROM activity_t WHERE agenda_id IS NOT NULL AND agenda_id NOT IN (SELECT id FROM agenda_meeting)');

        // 2. Add foreign key to activity_t.agenda_id pointing to agenda_meeting.id with cascade delete
        Schema::table('activity_t', function (Blueprint $table) {
            $table->foreign('agenda_id')->references('id')->on('agenda_meeting')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_t', function (Blueprint $table) {
            $table->dropForeign(['agenda_id']);
        });
    }
};

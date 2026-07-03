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
        Schema::table('project_wbs', function (Blueprint $table) {
            $table->uuid('predecessor_id')->nullable()->after('task_to');
            $table->foreign('predecessor_id')->references('id')->on('project_wbs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_wbs', function (Blueprint $table) {
            $table->dropForeign(['predecessor_id']);
            $table->dropColumn('predecessor_id');
        });
    }
};

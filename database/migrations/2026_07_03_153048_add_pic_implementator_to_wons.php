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
        Schema::table('wons', function (Blueprint $table) {
            $table->unsignedBigInteger('pic_implementator_id')->nullable()->after('pic_koordinator_id');
            $table->foreign('pic_implementator_id')->references('id')->on('pegawai_m')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wons', function (Blueprint $table) {
            $table->dropForeign(['pic_implementator_id']);
            $table->dropColumn('pic_implementator_id');
        });
    }
};

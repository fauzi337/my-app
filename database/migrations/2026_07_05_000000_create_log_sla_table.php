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
        if (!Schema::hasTable('log_sla_t')) {
            Schema::create('log_sla_t', function (Blueprint $table) {
                $table->id();
                $table->integer('jadwal_id');
                $table->string('tipe_aktifitas'); // 'Created', 'Developer Update', 'PIC Request Update', 'Detail Edit'
                $table->string('status_sebelumnya')->nullable();
                $table->string('status_sesudahnya')->nullable();
                $table->text('aktifitas');
                $table->integer('user_id')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_sla_t');
    }
};

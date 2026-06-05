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
        Schema::create('project_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prioritas_id');
            $table->integer('site_id');
            $table->integer('pic_id'); // references pegawai_m.id
            $table->date('tgl_masuk');
            $table->date('tgl_deadline');
            $table->text('task');
            $table->boolean('statusenabled')->default(true);
            $table->timestamps();

            // Foreign keys
            $table->foreign('prioritas_id')->references('id')->on('prioritas_m');
            $table->foreign('site_id')->references('id')->on('site_m');
            $table->foreign('pic_id')->references('id')->on('pegawai_m');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_activity');
    }
};

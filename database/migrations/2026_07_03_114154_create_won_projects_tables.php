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
        Schema::create('wons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lead_id');
            $table->uuid('proposal_id')->nullable();
            $table->string('project_name');
            $table->unsignedBigInteger('site_id');
            $table->bigInteger('nilai_kontrak');
            $table->date('tanggal_kontrak');
            $table->string('file_kontrak');
            $table->unsignedBigInteger('pic_koordinator_id');
            $table->string('pic_request');
            $table->date('target_go_live');
            $table->string('status')->default('Initiated');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('set null');
            $table->foreign('site_id')->references('id')->on('site_m')->onDelete('cascade');
            $table->foreign('pic_koordinator_id')->references('id')->on('pegawai_m')->onDelete('cascade');
        });

        Schema::create('won_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('won_id');
            $table->string('modul_name');
            $table->unsignedBigInteger('pic_developer_id')->nullable();
            $table->integer('progress')->default(0);
            $table->string('status')->default('To Do');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('won_id')->references('id')->on('wons')->onDelete('cascade');
            $table->foreign('pic_developer_id')->references('id')->on('pegawai_m')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('won_details');
        Schema::dropIfExists('wons');
    }
};

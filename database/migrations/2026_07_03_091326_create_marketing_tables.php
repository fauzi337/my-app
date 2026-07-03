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
        // 1. Add role to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('pic_request');
        });

        // Seed roles for existing users
        DB::table('users')->where('email', 'admin@mail.com')->update(['role' => 'admin']);
        DB::table('users')->where('email', 'su@example.com')->update(['role' => 'admin']);
        DB::table('users')->where('email', 'admin@example.com')->update(['role' => 'manager']);
        DB::table('users')->where('email', 'gs@example.com')->update(['role' => 'developer']);

        // 2. Create leads table
        Schema::create('leads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_institusi');
            $table->enum('jenis_institusi', ['RS_Umum', 'RS_Khusus', 'Klinik', 'Puskesmas', 'Lainnya']);
            $table->string('kota');
            $table->string('provinsi');
            $table->enum('ukuran', ['Kecil', 'Menengah', 'Besar']);
            $table->enum('sumber_lead', ['Referral', 'Website', 'Event', 'Cold_Outreach', 'Lainnya']);
            $table->string('pic_klien');
            $table->string('jabatan_pic');
            $table->string('no_hp_pic');
            $table->string('email_pic');
            $table->enum('pipeline_status', ['New', 'Qualified', 'Demo', 'Proposal', 'Negotiation', 'Won', 'Lost', 'Nurture'])->default('New');
            $table->string('alasan_lost')->nullable();
            $table->bigInteger('estimasi_nilai');
            $table->text('modul_diminati'); // Stored as JSON/Serialized array
            $table->unsignedBigInteger('pic_internal');
            $table->date('tanggal_masuk');
            $table->date('target_closing')->nullable();
            $table->date('tanggal_followup_berikutnya')->nullable();
            $table->text('catatan')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('pic_internal')->references('id')->on('users')->onDelete('restrict');
        });

        // 3. Create proposals table
        Schema::create('proposals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lead_id');
            $table->string('nomor_proposal');
            $table->date('tanggal_proposal');
            $table->integer('versi')->default(1);
            $table->text('modul_ditawarkan'); // Stored as JSON/Serialized array
            $table->bigInteger('nilai_penawaran');
            $table->integer('masa_implementasi_bulan');
            $table->text('catatan_scope');
            $table->string('file_proposal');
            $table->enum('status_proposal', ['Draft', 'Terkirim', 'Revisi', 'Disetujui', 'Ditolak'])->default('Draft');
            $table->date('tanggal_kirim')->nullable();
            $table->date('tanggal_respon_klien')->nullable();
            $table->text('catatan_revisi')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
        });

        // 4. Create lead_activities table
        Schema::create('lead_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lead_id');
            $table->enum('tipe_aktivitas', ['Telepon', 'Email', 'Kunjungan', 'Demo', 'Presentasi', 'Follow_Up', 'Kickoff', 'Lainnya']);
            $table->dateTime('tanggal_aktivitas');
            $table->unsignedBigInteger('pic_internal');
            $table->text('deskripsi');
            $table->enum('hasil', ['Positif', 'Netral', 'Negatif']);
            $table->text('tindak_lanjut')->nullable();
            $table->date('tanggal_followup_berikutnya')->nullable();
            $table->unsignedInteger('meeting_id')->nullable(); // references meeting_result(id)
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('pic_internal')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('meeting_id')->references('id')->on('meeting_result')->onDelete('set null');
        });

        // 5. Alter project table
        Schema::table('project', function (Blueprint $table) {
            $table->uuid('lead_id')->nullable();
            $table->uuid('proposal_id')->nullable();
            $table->bigInteger('nilai_kontrak')->nullable();
            $table->date('tanggal_kontrak')->nullable();
            $table->string('file_kontrak')->nullable();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('set null');
            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project', function (Blueprint $table) {
            $table->dropForeign(['lead_id']);
            $table->dropForeign(['proposal_id']);
            $table->dropColumn(['lead_id', 'proposal_id', 'nilai_kontrak', 'tanggal_kontrak', 'file_kontrak']);
        });

        Schema::dropIfExists('lead_activities');
        Schema::dropIfExists('proposals');
        Schema::dropIfExists('leads');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};

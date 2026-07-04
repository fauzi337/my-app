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
        // 1. Add columns to existing table jadwal_t if not exists
        Schema::table('jadwal_t', function (Blueprint $table) {
            if (!Schema::hasColumn('jadwal_t', 'sla_hours')) {
                $table->integer('sla_hours')->nullable()->after('timeline_id');
            }
            if (!Schema::hasColumn('jadwal_t', 'performa_score')) {
                $table->integer('performa_score')->nullable()->after('tgl_selesai');
            }
        });

        // 2. Create master_sla_m table if not exists
        if (!Schema::hasTable('master_sla_m')) {
            Schema::create('master_sla_m', function (Blueprint $table) {
                $table->id();
                $table->integer('prioritas_id')->nullable();
                $table->integer('jenistask_id')->nullable();
                $table->integer('sla_hours');
                $table->timestamps();
            });
        }

        // 3. Create sdm_evaluasi_kinerja_t table if not exists
        if (!Schema::hasTable('sdm_evaluasi_kinerja_t')) {
            Schema::create('sdm_evaluasi_kinerja_t', function (Blueprint $table) {
                $table->id();
                $table->integer('pegawai_id');
                $table->integer('bulan');
                $table->integer('tahun');
                $table->integer('total_task')->default(0);
                $table->integer('task_tepat_waktu')->default(0);
                $table->integer('task_terlambat')->default(0);
                $table->decimal('rata_rata_skor', 5, 2)->default(0.00);
                $table->decimal('persentase_potongan', 5, 2)->default(0.00);
                $table->string('status_evaluasi', 50)->default('Draft');
                $table->timestamps();
            });
        }

        // 4. Create payroll_gaji_t table if not exists
        if (!Schema::hasTable('payroll_gaji_t')) {
            Schema::create('payroll_gaji_t', function (Blueprint $table) {
                $table->id();
                $table->integer('pegawai_id');
                $table->integer('bulan');
                $table->integer('tahun');
                $table->decimal('gaji_pokok', 15, 2)->default(0.00);
                $table->decimal('tunjangan_kinerja', 15, 2)->default(0.00);
                $table->decimal('potongan_performa', 15, 2)->default(0.00);
                $table->decimal('gaji_diterima', 15, 2)->default(0.00);
                $table->string('status_pembayaran', 50)->default('Draft');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_gaji_t');
        Schema::dropIfExists('sdm_evaluasi_kinerja_t');
        Schema::dropIfExists('master_sla_m');

        Schema::table('jadwal_t', function (Blueprint $table) {
            if (Schema::hasColumn('jadwal_t', 'sla_hours')) {
                $table->dropColumn('sla_hours');
            }
            if (Schema::hasColumn('jadwal_t', 'performa_score')) {
                $table->dropColumn('performa_score');
            }
        });
    }
};

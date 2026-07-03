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
        Schema::create('master_moduls', function (Blueprint $table) {
            $table->id();
            $table->string('nama_modul');
            $table->boolean('statusenabled')->default(true);
            $table->timestamps();
        });

        Schema::create('master_modul_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_modul_id');
            $table->string('nama_detail');
            $table->boolean('statusenabled')->default(true);
            $table->timestamps();

            $table->foreign('master_modul_id')->references('id')->on('master_moduls')->onDelete('cascade');
        });

        Schema::create('won_detail_checklists', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('won_detail_id');
            $table->string('nama_detail');
            $table->boolean('is_checked')->default(false);
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();

            $table->foreign('won_detail_id')->references('id')->on('won_details')->onDelete('cascade');
        });

        // Seed some master moduls & details
        $modules = [
            'Pendaftaran & Antrean' => [
                'Setting Mesin Display Antrean',
                'Uji Coba Cetak Karcis Pendaftaran',
                'Integrasi Antrean Online BPJS',
                'Training Operator & Admisi Pasien'
            ],
            'Rawat Jalan' => [
                'Konfigurasi Poli & Jadwal Dokter',
                'Setting Template SOAP Medis Dokter',
                'Uji Coba Resep Elektronik Rawat Jalan',
                'Training Perawat & Dokter Poli'
            ],
            'Rawat Inap' => [
                'Setting Ruangan & Alokasi Bed',
                'Konfigurasi Alur Billing Kamar Inap',
                'Asesmen Awal Keperawatan & CPPT',
                'Training Perawat & Admin Rawat Inap'
            ],
            'Farmasi' => [
                'Setup Master Obat & Stok Awal',
                'Konfigurasi Aturan Pakai & Racikan',
                'Integrasi E-Resep & Dispensing Obat',
                'Training Apoteker & Asisten Apoteker'
            ],
            'Kasir & Billing' => [
                'Setting Metode Pembayaran & EDC',
                'Konfigurasi Tarif Layanan & Paket Tindakan',
                'Uji Coba Cetak Kuitansi Billing Akhir',
                'Training Petugas Kasir'
            ],
            'Laboratorium' => [
                'Setup Jenis Pemeriksaan & Nilai Normal',
                'Konfigurasi Formulir Permintaan Lab',
                'Bridging Alat Laboratorium (LIS)',
                'Training Analis Laboratorium'
            ],
            'Bridging BPJS (VClaim)' => [
                'Setting Parameter BPJS Trust & Secret',
                'Uji Coba Pembuatan SEP Rawat Jalan/Inap',
                'Uji Coba Pembuatan Rujukan Online',
                'Training Petugas Pengendali BPJS'
            ],
            'SatuSehat' => [
                'Setting Client ID & Client Secret SatuSehat',
                'Koneksi API Sandbox Kemkes',
                'Uji Coba Kirim Data Profil Pasien (Kyc)',
                'Uji Coba Kirim Data Kunjungan (Encounter)'
            ]
        ];

        foreach ($modules as $modName => $details) {
            $modId = DB::table('master_moduls')->insertGetId([
                'nama_modul' => $modName,
                'statusenabled' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($details as $detailName) {
                DB::table('master_modul_details')->insert([
                    'master_modul_id' => $modId,
                    'nama_detail' => $detailName,
                    'statusenabled' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('won_detail_checklists');
        Schema::dropIfExists('master_modul_details');
        Schema::dropIfExists('master_moduls');
    }
};

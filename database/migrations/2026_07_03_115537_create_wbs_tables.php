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
        Schema::create('master_wbs', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_struktur'); // Kick-Off Meeting, Master Data, etc.
            $table->string('wbs_code'); // 1, 1.1, 1.2
            $table->string('detail_task');
            $table->string('task_to'); // Vendor, Client, Both
            $table->integer('order_num');
            $table->boolean('statusenabled')->default(true);
            $table->timestamps();
        });

        Schema::create('project_wbs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('won_id');
            $table->string('jenis_struktur');
            $table->string('wbs_code');
            $table->string('detail_task');
            $table->string('task_to');
            $table->unsignedBigInteger('jmt_pic_id')->nullable();
            $table->string('client_pic')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('duration')->nullable();
            $table->string('status')->default('NOT STARTED'); // NOT STARTED, IN PROGRESS, DONE, NEED REVIEW, DELAYED
            $table->date('finish_date')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('link_file')->nullable();
            $table->integer('order_num');
            $table->timestamps();

            $table->foreign('won_id')->references('id')->on('wons')->onDelete('cascade');
            $table->foreign('jmt_pic_id')->references('id')->on('pegawai_m')->onDelete('set null');
        });

        // Seed master_wbs
        $masterWbs = [
            // Kick-Off Meeting
            ['jenis_struktur' => 'Kick-Off Meeting', 'wbs_code' => '1', 'detail_task' => 'Kick-Off Meeting', 'task_to' => 'Both', 'order_num' => 10, 'created_at' => now()],
            ['jenis_struktur' => 'Kick-Off Meeting', 'wbs_code' => '1.1', 'detail_task' => 'Pertemuan Vendor & RS', 'task_to' => 'Both', 'order_num' => 11, 'created_at' => now()],
            ['jenis_struktur' => 'Kick-Off Meeting', 'wbs_code' => '1.2', 'detail_task' => 'Penentuan PIC RS', 'task_to' => 'Both', 'order_num' => 12, 'created_at' => now()],
            ['jenis_struktur' => 'Kick-Off Meeting', 'wbs_code' => '1.3', 'detail_task' => 'Timeline Implementasi', 'task_to' => 'Vendor', 'order_num' => 13, 'created_at' => now()],
            ['jenis_struktur' => 'Kick-Off Meeting', 'wbs_code' => '1.4', 'detail_task' => 'Kesepakatan Implementasi', 'task_to' => 'Both', 'order_num' => 14, 'created_at' => now()],

            // Master Data
            ['jenis_struktur' => 'Master Data', 'wbs_code' => '2', 'detail_task' => 'Master Data', 'task_to' => 'Client', 'order_num' => 20, 'created_at' => now()],
            ['jenis_struktur' => 'Master Data', 'wbs_code' => '2.1', 'detail_task' => 'Collecting Master Data', 'task_to' => 'Client', 'order_num' => 21, 'created_at' => now()],
            ['jenis_struktur' => 'Master Data', 'wbs_code' => '2.1.1', 'detail_task' => 'Master Data Pasien', 'task_to' => 'Client', 'order_num' => 22, 'created_at' => now()],
            ['jenis_struktur' => 'Master Data', 'wbs_code' => '2.1.2', 'detail_task' => 'Master Data Barang Umum', 'task_to' => 'Client', 'order_num' => 23, 'created_at' => now()],
            ['jenis_struktur' => 'Master Data', 'wbs_code' => '2.1.3', 'detail_task' => 'Master Data Obat & Consumables', 'task_to' => 'Client', 'order_num' => 24, 'created_at' => now()],
            ['jenis_struktur' => 'Master Data', 'wbs_code' => '2.1.4', 'detail_task' => 'Master Data Ruangan', 'task_to' => 'Client', 'order_num' => 25, 'created_at' => now()],
            ['jenis_struktur' => 'Master Data', 'wbs_code' => '2.1.5', 'detail_task' => 'Master Data Tempat Tidur', 'task_to' => 'Client', 'order_num' => 26, 'created_at' => now()],
            ['jenis_struktur' => 'Master Data', 'wbs_code' => '2.1.6', 'detail_task' => 'Master Data Pegawai', 'task_to' => 'Client', 'order_num' => 27, 'created_at' => now()],
            ['jenis_struktur' => 'Master Data', 'wbs_code' => '2.1.7', 'detail_task' => 'Master Data Login', 'task_to' => 'Client', 'order_num' => 28, 'created_at' => now()],
            ['jenis_struktur' => 'Master Data', 'wbs_code' => '2.1.8', 'detail_task' => 'Master Data Tarif', 'task_to' => 'Client', 'order_num' => 29, 'created_at' => now()],
            ['jenis_struktur' => 'Master Data', 'wbs_code' => '2.1.9', 'detail_task' => 'Form Rekam Medis', 'task_to' => 'Client', 'order_num' => 30, 'created_at' => now()],
            ['jenis_struktur' => 'Master Data', 'wbs_code' => '2.2', 'detail_task' => 'Konfigurasi Master Data', 'task_to' => 'Vendor', 'order_num' => 31, 'created_at' => now()],

            // Assesment
            ['jenis_struktur' => 'Assesment', 'wbs_code' => '3', 'detail_task' => 'Assesment', 'task_to' => 'Client', 'order_num' => 40, 'created_at' => now()],
            ['jenis_struktur' => 'Assesment', 'wbs_code' => '3.1', 'detail_task' => 'Assessment Alur', 'task_to' => 'Client', 'order_num' => 41, 'created_at' => now()],
            ['jenis_struktur' => 'Assesment', 'wbs_code' => '3.2', 'detail_task' => 'Assessment Jaringan & Hardware', 'task_to' => 'Client', 'order_num' => 42, 'created_at' => now()],
            ['jenis_struktur' => 'Assesment', 'wbs_code' => '3.3', 'detail_task' => 'IP Public, SSL, Domain', 'task_to' => 'Client', 'order_num' => 43, 'created_at' => now()],

            // Instalasi Sistem
            ['jenis_struktur' => 'Instalasi Sistem', 'wbs_code' => '4', 'detail_task' => 'Instalasi Sistem', 'task_to' => 'Vendor', 'order_num' => 50, 'created_at' => now()],
            ['jenis_struktur' => 'Instalasi Sistem', 'wbs_code' => '4.1', 'detail_task' => 'Instalasi Server', 'task_to' => 'Vendor', 'order_num' => 51, 'created_at' => now()],
            ['jenis_struktur' => 'Instalasi Sistem', 'wbs_code' => '4.1.1', 'detail_task' => 'Setting & Konfigurasi', 'task_to' => 'Vendor', 'order_num' => 52, 'created_at' => now()],
            ['jenis_struktur' => 'Instalasi Sistem', 'wbs_code' => '4.2', 'detail_task' => 'Instalasi Sistem', 'task_to' => 'Vendor', 'order_num' => 53, 'created_at' => now()],
            ['jenis_struktur' => 'Instalasi Sistem', 'wbs_code' => '4.2.1', 'detail_task' => 'Input Master Data', 'task_to' => 'Vendor', 'order_num' => 54, 'created_at' => now()],
            ['jenis_struktur' => 'Instalasi Sistem', 'wbs_code' => '4.3', 'detail_task' => 'Laporan Instalasi', 'task_to' => 'Vendor', 'order_num' => 55, 'created_at' => now()],

            // Training
            ['jenis_struktur' => 'Training', 'wbs_code' => '5', 'detail_task' => 'Training', 'task_to' => 'Both', 'order_num' => 60, 'created_at' => now()],
            ['jenis_struktur' => 'Training', 'wbs_code' => '5.1', 'detail_task' => 'Sosialisasi & Kick-off Training', 'task_to' => 'Both', 'order_num' => 61, 'created_at' => now()],
            ['jenis_struktur' => 'Training', 'wbs_code' => '5.2', 'detail_task' => 'Training Modul Utama', 'task_to' => 'Both', 'order_num' => 62, 'created_at' => now()],
            ['jenis_struktur' => 'Training', 'wbs_code' => '5.3', 'detail_task' => 'Training Lanjutan & UAT', 'task_to' => 'Both', 'order_num' => 63, 'created_at' => now()],

            // Bridging
            ['jenis_struktur' => 'Bridging', 'wbs_code' => '6', 'detail_task' => 'Bridging', 'task_to' => 'Both', 'order_num' => 70, 'created_at' => now()],
            ['jenis_struktur' => 'Bridging', 'wbs_code' => '6.1', 'detail_task' => 'Integrasi Bridging BPJS', 'task_to' => 'Both', 'order_num' => 71, 'created_at' => now()],
            ['jenis_struktur' => 'Bridging', 'wbs_code' => '6.2', 'detail_task' => 'Uji Coba Bridging', 'task_to' => 'Both', 'order_num' => 72, 'created_at' => now()],

            // Development & Quick Customize
            ['jenis_struktur' => 'Development & Quick Customize', 'wbs_code' => '7', 'detail_task' => 'Development & Quick Customize', 'task_to' => 'Vendor', 'order_num' => 80, 'created_at' => now()],
            ['jenis_struktur' => 'Development & Quick Customize', 'wbs_code' => '7.1', 'detail_task' => 'Penyesuaian Kebutuhan Alur', 'task_to' => 'Vendor', 'order_num' => 81, 'created_at' => now()],

            // Go - Live
            ['jenis_struktur' => 'Go - Live', 'wbs_code' => '8', 'detail_task' => 'Go - Live', 'task_to' => 'Both', 'order_num' => 90, 'created_at' => now()],
            ['jenis_struktur' => 'Go - Live', 'wbs_code' => '8.1', 'detail_task' => 'Go-Live Pendampingan Hari 1-3', 'task_to' => 'Both', 'order_num' => 91, 'created_at' => now()],
            ['jenis_struktur' => 'Go - Live', 'wbs_code' => '8.2', 'detail_task' => 'Evaluasi Pasca Go-Live', 'task_to' => 'Both', 'order_num' => 92, 'created_at' => now()],
        ];

        DB::table('master_wbs')->insert($masterWbs);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_wbs');
        Schema::dropIfExists('master_wbs');
    }
};

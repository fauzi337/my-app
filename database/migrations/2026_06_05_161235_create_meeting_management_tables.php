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
        // 1. master_action_category
        Schema::create('master_action_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('statusenabled')->default(true);
            $table->timestamps();
        });

        DB::table('master_action_category')->insert([
            ['name' => 'Develop', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Non Develop', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. master_action_status
        Schema::create('master_action_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('statusenabled')->default(true);
            $table->timestamps();
        });

        DB::table('master_action_status')->insert([
            ['name' => 'Open', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'In Progress', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Waiting', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Done', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cancel', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. master_priority
        Schema::create('master_priority', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('statusenabled')->default(true);
            $table->timestamps();
        });

        DB::table('master_priority')->insert([
            ['name' => 'Low', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Medium', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'High', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Critical', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. project
        Schema::create('project', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_code')->unique();
            $table->string('project_name');
            $table->integer('site_id');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('target_date')->nullable();
            $table->string('status')->default('Open');
            $table->double('progress')->default(0);
            $table->boolean('statusenabled')->default(true);
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('site_m');
        });

        // 5. agenda_meeting
        Schema::create('agenda_meeting', function (Blueprint $table) {
            $table->increments('id');
            $table->text('kegiatan');
            $table->date('tgl_jadwal');
            $table->integer('site_id');
            $table->integer('jam_id');
            $table->integer('status_id');
            $table->integer('parties_id');
            $table->integer('unit_id');
            $table->integer('picintern_id');
            $table->integer('picextern_id');
            $table->boolean('statusenabled')->default(true);
            $table->string('kd_list');
            $table->integer('nourut');
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('site_m');
            $table->foreign('jam_id')->references('id')->on('jam_m');
            $table->foreign('status_id')->references('id')->on('status_m');
            $table->foreign('parties_id')->references('id')->on('parties_m');
            $table->foreign('unit_id')->references('id')->on('unit_m');
            $table->foreign('picintern_id')->references('id')->on('pegawai_m');
            $table->foreign('picextern_id')->references('id')->on('pegawai_m');
        });

        // 6. meeting_result
        Schema::create('meeting_result', function (Blueprint $table) {
            $table->increments('id');
            $table->string('meeting_code')->unique();
            $table->integer('agenda_meeting_id');
            $table->integer('project_id')->nullable();
            $table->string('status')->default('Pending');
            $table->text('notes')->nullable();
            $table->date('tgl_realisasi')->nullable();
            $table->boolean('statusenabled')->default(true);
            $table->timestamps();

            $table->foreign('agenda_meeting_id')->references('id')->on('agenda_meeting')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('project')->onDelete('set null');
        });

        // 7. meeting_notes
        Schema::create('meeting_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meeting_result_id');
            $table->text('notulen');
            $table->date('tgl_realisasi');
            $table->boolean('statusenabled')->default(true);
            $table->timestamps();

            $table->foreign('meeting_result_id')->references('id')->on('meeting_result')->onDelete('cascade');
        });

        // 8. action_item
        Schema::create('action_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meeting_result_id');
            $table->integer('project_id')->nullable();
            $table->text('description');
            $table->integer('category_id');
            $table->integer('unit_id');
            $table->integer('pic_person_id'); // referencing users(id)
            $table->integer('priority_id');
            $table->date('target_date');
            $table->integer('status_id');
            $table->boolean('statusenabled')->default(true);
            $table->timestamps();

            $table->foreign('meeting_result_id')->references('id')->on('meeting_result')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('project')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('master_action_category');
            $table->foreign('unit_id')->references('id')->on('unit_m');
            $table->foreign('pic_person_id')->references('id')->on('users');
            $table->foreign('priority_id')->references('id')->on('master_priority');
            $table->foreign('status_id')->references('id')->on('master_action_status');
        });

        // 9. action_item_progress
        Schema::create('action_item_progress', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('action_item_id');
            $table->date('progress_date');
            $table->text('notes');
            $table->string('attachment')->nullable();
            $table->integer('created_by'); // referencing users(id)
            $table->timestamps();

            $table->foreign('action_item_id')->references('id')->on('action_item')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
        });

        // 10. audit_log
        Schema::create('audit_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('activity');
            $table->text('details')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // 11. Migrate existing agenda_t data
        $existing = DB::table('agenda_t')->get();
        foreach ($existing as $item) {
            // Insert into agenda_meeting
            DB::table('agenda_meeting')->insert([
                'id' => $item->id,
                'kegiatan' => $item->kegiatan,
                'tgl_jadwal' => $item->tgl_jadwal,
                'site_id' => $item->site_id,
                'jam_id' => $item->jam_id,
                'status_id' => $item->status_id,
                'parties_id' => $item->parties_id,
                'unit_id' => $item->unit_id,
                'picintern_id' => $item->picintern_id,
                'picextern_id' => $item->picextern_id,
                'statusenabled' => $item->statusenabled,
                'kd_list' => $item->kd_list,
                'nourut' => $item->nourut,
                'created_at' => $item->created_at ?? now(),
                'updated_at' => $item->updated_at ?? now()
            ]);

            // Get site code
            $kdSite = DB::table('site_m')->where('id', $item->site_id)->value('kdsite');
            $meetCode = 'MEET-' . trim($kdSite) . '-' . $item->nourut;

            // Check if meeting result already exists
            $exists = DB::table('meeting_result')->where('meeting_code', $meetCode)->exists();
            if (!$exists) {
                // Determine status mapping
                $statusMap = 'Pending';
                $statusIdTrimmed = intval($item->status_id);
                if ($statusIdTrimmed == 24) {
                    $statusMap = 'Cancel';
                } elseif ($statusIdTrimmed == 25) {
                    $statusMap = 'Done';
                }

                DB::table('meeting_result')->insert([
                    'meeting_code' => $meetCode,
                    'agenda_meeting_id' => $item->id,
                    'project_id' => null,
                    'status' => $statusMap,
                    'notes' => null,
                    'tgl_realisasi' => $item->tgl_realisasi,
                    'statusenabled' => true,
                    'created_at' => $item->created_at ?? now(),
                    'updated_at' => $item->updated_at ?? now()
                ]);
            }
        }

        // Reset sequence in pgsql
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("SELECT setval('agenda_meeting_id_seq', coalesce((SELECT MAX(id) FROM agenda_meeting), 1))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_log');
        Schema::dropIfExists('action_item_progress');
        Schema::dropIfExists('action_item');
        Schema::dropIfExists('meeting_notes');
        Schema::dropIfExists('meeting_result');
        Schema::dropIfExists('agenda_meeting');
        Schema::dropIfExists('project');
        Schema::dropIfExists('master_priority');
        Schema::dropIfExists('master_action_status');
        Schema::dropIfExists('master_action_category');
    }
};

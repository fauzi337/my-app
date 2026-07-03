<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lime-600 dark:text-lime-400 text-xl leading-tight" style="font-size: 32px">
            {{ __('Detail Lead: ') }} {{ $lead->nama_institusi }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6" x-data="{ activeTab: 'activities' }">
        
        <!-- Top Navigation & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('marketing.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 uppercase tracking-widest no-underline transition-colors">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
            
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('marketing.leads.edit', $lead->id) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg shadow-sm transition-colors uppercase tracking-wider no-underline">
                    <i class="bi bi-pencil-square"></i> Edit Lead
                </a>

                @if($lead->pipeline_status !== 'Won' && $lead->pipeline_status !== 'Lost')
                    <a href="{{ route('marketing.leads.handoff', $lead->id) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors uppercase tracking-wider no-underline">
                        <i class="bi bi-award-fill"></i> Tandai WON / Deal
                    </a>

                    <!-- Mark as Lost Button (triggers modal or toggle form) -->
                    <button type="button" onclick="document.getElementById('lost-reason-modal').showModal()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors uppercase tracking-wider">
                        <i class="bi bi-x-circle-fill"></i> Tandai LOST
                    </button>
                @endif

                <form method="POST" action="{{ route('marketing.leads.destroy', $lead->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lead ini (soft delete)?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-slate-200 hover:bg-red-100 hover:text-red-750 text-slate-700 text-xs font-bold rounded-lg shadow-sm transition-colors uppercase tracking-wider">
                        <i class="bi bi-trash"></i> Hapus Lead
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500)" x-show="show"
                class="flex items-center gap-3 bg-emerald-105 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300 px-4 py-3 rounded-xl shadow-sm border border-emerald-300 dark:border-emerald-800 transition-all duration-300">
                <i class="bi bi-check-circle-fill text-emerald-600 text-lg"></i>
                <span class="text-xs font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-55 text-red-800 rounded-xl border border-red-200 text-xs space-y-1">
                <p class="font-bold">Terjadi kesalahan input:</p>
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- LAYOUT GRID: Left (Lead Profile) & Right (Interactions/Proposals) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- LEFT PANEL: Lead Profile Card -->
            <div class="premium-card p-6 space-y-6 lg:col-span-1">
                
                <!-- Institution Header -->
                <div class="border-b pb-4">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 uppercase">
                        {{ str_replace('_', ' ', $lead->jenis_institusi) }}
                    </span>
                    <h3 class="text-lg font-black text-slate-800 mt-2">{{ $lead->nama_institusi }}</h3>
                    <p class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                        <i class="bi bi-geo-alt"></i> {{ $lead->kota }}, {{ $lead->provinsi }}
                    </p>

                    <!-- Pipeline Status Badge -->
                    @php
                        $badges = [
                            'New' => 'bg-slate-100 text-slate-800 border-slate-200',
                            'Qualified' => 'bg-indigo-50 text-indigo-700 border-indigo-250',
                            'Demo' => 'bg-blue-50 text-blue-700 border-blue-250',
                            'Proposal' => 'bg-purple-50 text-purple-700 border-purple-250',
                            'Negotiation' => 'bg-amber-50 text-amber-700 border-amber-250',
                            'Won' => 'bg-emerald-50 text-emerald-700 border-emerald-250',
                            'Lost' => 'bg-red-50 text-red-700 border-red-250',
                            'Nurture' => 'bg-teal-50 text-teal-700 border-teal-250',
                        ];
                    @endphp
                    <div class="mt-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $badges[$lead->pipeline_status] }}">
                            <span class="w-2 h-2 rounded-full bg-current"></span>
                            Pipeline: {{ $lead->pipeline_status }}
                        </span>
                        @if($lead->pipeline_status == 'Lost')
                            <p class="mt-2 text-xs text-red-800 bg-red-50 border border-red-250 rounded-lg p-2.5">
                                <strong>Alasan Lost:</strong><br>{{ $lead->alasan_lost }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Client Contact Information -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Kontak Person Klien</h4>
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 text-xs space-y-2">
                        <div>
                            <span class="text-slate-400 block">Nama PIC</span>
                            <span class="font-bold text-slate-800">{{ $lead->pic_klien }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Jabatan</span>
                            <span class="font-semibold text-slate-700">{{ $lead->jabatan_pic }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Telepon / WhatsApp</span>
                            <span class="font-bold text-slate-800">{{ $lead->no_hp_pic }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Email</span>
                            <span class="font-semibold text-slate-700">{{ $lead->email_pic }}</span>
                        </div>
                    </div>
                </div>

                <!-- Internal metadata -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Estimasi & PIC Internal</h4>
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 text-xs space-y-2">
                        <div>
                            <span class="text-slate-400 block">Estimasi Nilai Kontrak</span>
                            <span class="text-sm font-extrabold text-lime-600">Rp {{ number_format($lead->estimasi_nilai, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">PIC Internal (Sales)</span>
                            <span class="font-semibold text-slate-700">{{ $lead->picInternal->name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Tanggal Masuk</span>
                            <span class="font-medium text-slate-700">{{ $lead->tanggal_masuk ? $lead->tanggal_masuk->format('d M Y') : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Target Closing</span>
                            <span class="font-medium text-slate-700">{{ $lead->target_closing ? $lead->target_closing->format('d M Y') : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Umur Lead</span>
                            <span class="font-semibold text-slate-800">{{ $lead->created_at->diffInDays(now()) }} Hari</span>
                        </div>
                    </div>
                </div>

                <!-- Interested Modules -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Modul yang Diminati</h4>
                    <div class="flex flex-wrap gap-1">
                        @foreach($lead->modul_diminati as $modul)
                            <span class="px-2 py-1 bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-bold rounded">
                                <i class="bi bi-check-lg text-lime-500"></i> {{ $modul }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Notes -->
                @if($lead->catatan)
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Catatan Tambahan</h4>
                        <p class="text-xs text-slate-600 bg-yellow-50/40 p-2.5 rounded-lg border border-yellow-100 whitespace-pre-line">{{ $lead->catatan }}</p>
                    </div>
                @endif

            </div>

            <!-- RIGHT PANEL: Tabs & Forms -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Tab Headers -->
                <div class="flex border-b border-slate-200 bg-white p-1 rounded-xl shadow-sm">
                    <button @click="activeTab = 'activities'" :class="{'bg-lime-600 text-white font-bold': activeTab === 'activities', 'text-slate-500 hover:text-slate-850': activeTab !== 'activities'}" class="flex-1 py-2 text-xs font-bold rounded-lg border-0 transition-all uppercase tracking-wider">
                        <i class="bi bi-clock-history"></i> Aktivitas & Log
                    </button>
                    <button @click="activeTab = 'proposals'" :class="{'bg-lime-600 text-white font-bold': activeTab === 'proposals', 'text-slate-500 hover:text-slate-850': activeTab !== 'proposals'}" class="flex-1 py-2 text-xs font-bold rounded-lg border-0 transition-all uppercase tracking-wider">
                        <i class="bi bi-file-earmark-text"></i> Proposal Penawaran
                    </button>
                    @if($lead->pipeline_status === 'Won')
                        <button @click="activeTab = 'projects'" :class="{'bg-lime-600 text-white font-bold': activeTab === 'projects', 'text-slate-500 hover:text-slate-850': activeTab !== 'projects'}" class="flex-1 py-2 text-xs font-bold rounded-lg border-0 transition-all uppercase tracking-wider">
                            <i class="bi bi-check-circle-fill"></i> Proyek & Tiket
                        </button>
                    @endif
                </div>

                <!-- TAB CONTENT: ACTIVITIES & TIMELINE -->
                <div x-show="activeTab === 'activities'" class="space-y-6" x-transition>
                    
                    <!-- Add Activity Form -->
                    @if($lead->pipeline_status !== 'Won' && $lead->pipeline_status !== 'Lost')
                        <div class="premium-card p-6 bg-slate-50/50">
                            <h4 class="text-xs font-extrabold uppercase tracking-widest text-indigo-700 flex items-center gap-1.5 mb-4">
                                <i class="bi bi-plus-circle"></i> Catat Aktivitas Baru
                            </h4>

                            <form method="POST" action="{{ route('marketing.activities.store', $lead->id) }}" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Tipe Aktivitas</label>
                                        <select name="tipe_aktivitas" class="form-select text-xs mt-1 border-slate-300 rounded-lg w-full" required>
                                            <option value="Telepon">Telepon / Chat</option>
                                            <option value="Email">Email</option>
                                            <option value="Kunjungan">Kunjungan</option>
                                            <option value="Demo">Demo</option>
                                            <option value="Presentasi">Presentasi</option>
                                            <option value="Follow_Up">Follow Up</option>
                                            <option value="Kickoff">Kickoff</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Waktu Realisasi</label>
                                        <input type="datetime-local" name="tanggal_aktivitas" class="form-control text-xs mt-1 border-slate-300 rounded-lg w-full" required value="{{ date('Y-m-d\TH:i') }}">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Hasil Aktivitas</label>
                                        <select name="hasil" class="form-select text-xs mt-1 border-slate-300 rounded-lg w-full" required>
                                            <option value="Positif">Positif</option>
                                            <option value="Netral" selected>Netral</option>
                                            <option value="Negatif">Negatif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Hubungkan dengan Meeting</label>
                                        <select name="meeting_id" class="form-select text-xs mt-1 border-slate-300 rounded-lg w-full">
                                            <option value="">-- Pilih Rapat (Opsional) --</option>
                                            @foreach($meetings as $mtg)
                                                <option value="{{ $mtg->id }}">{{ $mtg->tgl_realisasi }} - {{ $mtg->judul_agenda }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-[10px] text-slate-450 mt-1 block">Atau buat meeting baru di halaman <a href="{{ route('dashboard.agenda') }}" class="text-lime-600 underline font-semibold">Agenda & Rapat</a></span>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Update Pipeline Status</label>
                                        <select name="next_pipeline_status" class="form-select text-sm mt-1 border-slate-300 rounded-lg w-full">
                                            <option value="">-- Tetap Status Saat Ini ({{ $lead->pipeline_status }}) --</option>
                                            @if($lead->pipeline_status === 'New')
                                                <option value="Qualified">Qualified</option>
                                                <option value="Nurture">Nurture</option>
                                                <option value="Lost">Lost (Gagal)</option>
                                            @elseif($lead->pipeline_status === 'Qualified')
                                                <option value="Demo">Demo</option>
                                                <option value="Nurture">Nurture</option>
                                                <option value="Lost">Lost (Gagal)</option>
                                            @elseif($lead->pipeline_status === 'Demo')
                                                <option value="Proposal">Proposal</option>
                                                <option value="Nurture">Nurture</option>
                                                <option value="Lost">Lost (Gagal)</option>
                                            @elseif($lead->pipeline_status === 'Proposal')
                                                <option value="Negotiation">Negotiation</option>
                                                <option value="Nurture">Nurture</option>
                                                <option value="Lost">Lost (Gagal)</option>
                                            @elseif($lead->pipeline_status === 'Negotiation')
                                                <option value="Won">Won (Deal)</option>
                                                <option value="Lost">Lost (Gagal)</option>
                                                <option value="Nurture">Nurture</option>
                                            @elseif($lead->pipeline_status === 'Nurture')
                                                <option value="Qualified">Qualified (Re-Qualify)</option>
                                                <option value="Lost">Lost (Gagal)</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Deskripsi Pembahasan / Notulen</label>
                                    <textarea name="deskripsi" rows="2" class="form-control text-xs mt-1 border-slate-300 rounded-lg w-full" placeholder="Tuliskan intisari pembahasan dan kesepakatan aktivitas ini..." required></textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Tindak Lanjut berikutnya (Next Action)</label>
                                        <input type="text" name="tindak_lanjut" class="form-control text-xs mt-1 border-slate-300 rounded-lg w-full" placeholder="Contoh: Kirim revisi proposal penawaran">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal Follow-up Berikutnya</label>
                                        <input type="date" name="tanggal_followup_berikutnya" class="form-control text-xs mt-1 border-slate-300 rounded-lg w-full">
                                    </div>
                                </div>

                                <button type="submit" class="px-4 py-2 bg-lime-600 hover:bg-lime-700 text-white text-xs font-bold rounded-lg border-0 shadow-sm uppercase tracking-wider transition-colors">
                                    Simpan Aktivitas
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Timeline List -->
                    <div class="premium-card p-6">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest text-indigo-700 mb-6 flex items-center gap-1">
                            <i class="bi bi-clock-history"></i> Histori Aktivitas
                        </h4>

                        <div class="relative border-l border-slate-200 dark:border-slate-800 ml-4 space-y-6">
                            @forelse($lead->activities as $act)
                                <div class="relative pl-6">
                                    <!-- Indicator dot -->
                                    @php
                                        $dotColors = [
                                            'Positif' => 'bg-emerald-500 ring-emerald-100',
                                            'Negatif' => 'bg-red-500 ring-red-100',
                                            'Netral' => 'bg-slate-400 ring-slate-100',
                                        ];
                                    @endphp
                                    <span class="absolute left-0 top-1.5 w-3 h-3 rounded-full {{ $dotColors[$act->hasil] }} ring-4"></span>
                                    
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-2">
                                        <div class="flex items-center justify-between gap-2 text-xs">
                                            <span class="font-bold text-slate-800">
                                                <i class="bi bi-chat-right-text text-slate-450 mr-1"></i> {{ $act->tipe_aktivitas }}
                                            </span>
                                            <span class="text-slate-500 font-semibold">{{ $act->tanggal_aktivitas->format('d M Y, H:i') }}</span>
                                        </div>
                                        <p class="text-xs text-slate-600 whitespace-pre-line m-0">{{ $act->deskripsi }}</p>
                                        
                                        @if($act->tindak_lanjut)
                                            <div class="bg-indigo-50/50 p-2 rounded-lg text-[11px] border border-indigo-100/50 text-indigo-800">
                                                <strong>Next Step:</strong> {{ $act->tindak_lanjut }}
                                                @if($act->tanggal_followup_berikutnya)
                                                    <span class="font-bold text-[10px] block mt-0.5">Jadwal Follow-up: {{ \Carbon\Carbon::parse($act->tanggal_followup_berikutnya)->format('d M Y') }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        @if($act->meeting)
                                            <div class="text-[10px] text-slate-500 flex items-center gap-1 mt-1">
                                                <i class="bi bi-calendar3"></i> Terhubung dengan Rapat: 
                                                <a href="{{ route('meeting.detail', $act->meeting_id) }}" class="text-lime-600 font-bold underline">
                                                    {{ $act->meeting->judul_agenda }}
                                                </a>
                                            </div>
                                        @endif
                                        
                                        <div class="text-[10px] text-slate-450 text-right mt-1">
                                            Dicatat oleh: {{ $act->picInternal->name }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-slate-400">
                                    Belum ada aktivitas yang dicatat.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- TAB CONTENT: PROPOSALS -->
                <div x-show="activeTab === 'proposals'" class="space-y-6" x-transition>
                    
                    <!-- Create Proposal Form -->
                    @if($lead->pipeline_status !== 'Won' && $lead->pipeline_status !== 'Lost')
                        <div class="premium-card p-6 bg-slate-50/50">
                            <h4 class="text-xs font-extrabold uppercase tracking-widest text-indigo-700 flex items-center gap-1.5 mb-4">
                                <i class="bi bi-file-earmark-plus"></i> Unggah Proposal Baru
                            </h4>

                            <form method="POST" action="{{ route('marketing.proposals.store', $lead->id) }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal Proposal</label>
                                        <input type="date" name="tanggal_proposal" class="form-control text-xs mt-1 border-slate-300 rounded-lg w-full" required value="{{ date('Y-m-d') }}">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Nilai Penawaran (Rupiah)</label>
                                        <input type="number" name="nilai_penawaran" class="form-control text-xs mt-1 border-slate-300 rounded-lg w-full" required placeholder="Contoh: 120000000">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Masa Implementasi (Bulan)</label>
                                        <input type="number" name="masa_implementasi_bulan" class="form-control text-xs mt-1 border-slate-300 rounded-lg w-full" required value="3" min="1">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Modul yang Ditawarkan</label>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 bg-white p-3 rounded-lg border border-slate-200 mt-1">
                                        @foreach($lead->modul_diminati as $modul)
                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="modul_ditawarkan[]" value="{{ $modul }}" class="rounded text-lime-600 focus:ring-lime-500 border-slate-300" checked>
                                                <span class="text-xs font-medium text-slate-700">{{ $modul }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Catatan Ruang Lingkup (Scope & Limitasi)</label>
                                    <textarea name="catatan_scope" rows="2" class="form-control text-xs mt-1 border-slate-300 rounded-lg w-full" placeholder="Tuliskan scope pengerjaan utama, syarat pembiayaan, atau batasan implementasi..." required></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Berkas Proposal (PDF, Maks 10MB)</label>
                                    <input type="file" name="file_proposal" class="form-control text-xs mt-1 border-slate-300 rounded-lg w-full" accept=".pdf" required>
                                </div>

                                <button type="submit" class="px-4 py-2 bg-lime-600 hover:bg-lime-700 text-white text-xs font-bold rounded-lg border-0 shadow-sm uppercase tracking-wider transition-colors">
                                    Simpan Proposal
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Proposals History -->
                    <div class="premium-card p-6">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest text-indigo-700 mb-4">
                            <i class="bi bi-file-earmark-text"></i> Histori Proposal & Versi
                        </h4>

                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-100 text-xs">
                                <thead class="bg-slate-50 text-slate-600 uppercase font-bold">
                                    <tr>
                                        <th class="px-4 py-2.5 text-center w-16">Versi</th>
                                        <th class="px-4 py-2.5 text-left">No. Proposal</th>
                                        <th class="px-4 py-2.5 text-left">Tanggal</th>
                                        <th class="px-4 py-2.5 text-right">Nilai Penawaran</th>
                                        <th class="px-4 py-2.5 text-left">Dokumen</th>
                                        <th class="px-4 py-2.5 text-left">Status</th>
                                        <th class="px-4 py-2.5 text-center w-24">Update Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse($lead->proposals as $prop)
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-4 py-3 text-center font-bold text-slate-700">v{{ $prop->versi }}</td>
                                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $prop->nomor_proposal }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">{{ $prop->tanggal_proposal->format('d M Y') }}</td>
                                            <td class="px-4 py-3 text-right font-bold text-slate-800">Rp {{ number_format($prop->nilai_penawaran, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <a href="{{ $prop->file_proposal }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded border border-rose-200 font-bold no-underline transition-colors">
                                                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                                                </a>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $propBadges = [
                                                        'Draft' => 'bg-slate-100 text-slate-800 border-slate-200',
                                                        'Terkirim' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                        'Revisi' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                        'Disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-250',
                                                        'Ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded font-bold border {{ $propBadges[$prop->status_proposal] }}">
                                                    {{ $prop->status_proposal }}
                                                </span>
                                                @if($prop->status_proposal == 'Revisi')
                                                    <p class="text-[10px] text-amber-800 bg-amber-50 rounded p-1.5 mt-1 max-w-[200px] whitespace-pre-line">
                                                        <strong>Revisi Note:</strong> {{ $prop->catatan_revisi }}
                                                    </p>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                @if($lead->pipeline_status !== 'Won' && $lead->pipeline_status !== 'Lost')
                                                    <button type="button" onclick="openStatusProposalModal('{{ $prop->id }}', '{{ $prop->status_proposal }}')" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-semibold transition-colors">
                                                        Update Status
                                                    </button>
                                                @else
                                                    <span class="text-slate-400 text-[10px]">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-6 text-slate-400">
                                                Belum ada proposal yang diunggah.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- TAB CONTENT: PROJECTS & TICKETS (IF WON) -->
                @if($lead->pipeline_status === 'Won')
                    <div x-show="activeTab === 'projects'" class="space-y-6" x-transition>
                        
                        <div class="premium-card p-6 space-y-4">
                            <h4 class="text-xs font-extrabold uppercase tracking-widest text-indigo-700 flex items-center gap-1.5 mb-2">
                                <i class="bi bi-journal-check"></i> Proyek & Kontrak Terkait
                            </h4>

                            @forelse($lead->projects as $proj)
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-3">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div>
                                            <h5 class="text-sm font-extrabold text-slate-800 m-0">{{ $proj->project_name }}</h5>
                                            <span class="text-[10px] text-slate-450 block font-semibold">Kode Project: {{ $proj->project_code }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ route('project.tracker') }}" class="inline-flex items-center gap-1 text-xs font-bold text-lime-600 hover:text-lime-700 no-underline">
                                                Buka Project Tracker <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs pt-2 border-t">
                                        <div>
                                            <span class="text-slate-400 block">Nilai Kontrak</span>
                                            <span class="font-bold text-slate-850">Rp {{ number_format($proj->nilai_kontrak, 0, ',', '.') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 block">Tanggal Kontrak</span>
                                            <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($proj->tanggal_kontrak)->format('d M Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 block">Target Go-Live</span>
                                            <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($proj->target_date)->format('d M Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 block">Status Kontrak</span>
                                            <a href="{{ $proj->file_kontrak }}" target="_blank" class="text-rose-600 font-bold underline">
                                                Lihat Berkas Kontrak
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-slate-400">
                                    Proyek terdaftar tidak ditemukan.
                                </div>
                            @endforelse
                        </div>

                    </div>
                @endif

            </div>

        </div>

    </div>

    <!-- MODAL 1: TANDAI LOST -->
    <dialog id="lost-reason-modal" class="p-0 rounded-2xl border-0 shadow-2xl w-full max-w-md bg-white">
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-1.5 m-0">
                    <i class="bi bi-x-circle-fill text-red-500"></i> Konfirmasi Lead Gagal (LOST)
                </h3>
                <button type="button" onclick="document.getElementById('lost-reason-modal').close()" class="bg-transparent border-0 text-slate-400 hover:text-slate-750 text-xl font-bold p-0 leading-none">&times;</button>
            </div>
            
            <form method="POST" action="{{ route('marketing.leads.update', $lead->id) }}" class="space-y-4">
                @csrf
                <!-- Pass all other fields as hidden to prevent reset -->
                <input type="hidden" name="nama_institusi" value="{{ $lead->nama_institusi }}">
                <input type="hidden" name="jenis_institusi" value="{{ $lead->jenis_institusi }}">
                <input type="hidden" name="kota" value="{{ $lead->kota }}">
                <input type="hidden" name="provinsi" value="{{ $lead->provinsi }}">
                <input type="hidden" name="ukuran" value="{{ $lead->ukuran }}">
                <input type="hidden" name="sumber_lead" value="{{ $lead->sumber_lead }}">
                <input type="hidden" name="pic_klien" value="{{ $lead->pic_klien }}">
                <input type="hidden" name="jabatan_pic" value="{{ $lead->jabatan_pic }}">
                <input type="hidden" name="no_hp_pic" value="{{ $lead->no_hp_pic }}">
                <input type="hidden" name="email_pic" value="{{ $lead->email_pic }}">
                <input type="hidden" name="estimasi_nilai" value="{{ $lead->estimasi_nilai }}">
                <input type="hidden" name="pic_internal" value="{{ $lead->pic_internal }}">
                <input type="hidden" name="tanggal_masuk" value="{{ $lead->tanggal_masuk ? $lead->tanggal_masuk->format('Y-m-d') : '' }}">
                <input type="hidden" name="target_closing" value="{{ $lead->target_closing ? $lead->target_closing->format('Y-m-d') : '' }}">
                @foreach($lead->modul_diminati as $modul)
                    <input type="hidden" name="modul_diminati[]" value="{{ $modul }}">
                @endforeach

                <input type="hidden" name="pipeline_status" value="Lost">

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Alasan Gagal Closing</label>
                    <textarea name="alasan_lost" rows="3" class="form-control text-xs mt-1 border-slate-350 rounded-lg w-full" placeholder="Contoh: Klien menganggap budget terlalu tinggi atau memilih vendor lain..." required></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 border-t pt-3">
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg border-0 shadow-sm uppercase tracking-wider">
                        Simpan & Tandai Lost
                    </button>
                    <button type="button" onclick="document.getElementById('lost-reason-modal').close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg border-0 shadow-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- MODAL 2: UPDATE STATUS PROPOSAL -->
    <dialog id="proposal-status-modal" 
            x-data="{ propStatus: 'Draft', currentStatus: 'Draft' }"
            @open-proposal-modal.window="
                if ($event.detail.proposalId) {
                    currentStatus = $event.detail.currentStatus;
                    // Set default next status based on current
                    if (currentStatus === 'Draft') propStatus = 'Terkirim';
                    else if (currentStatus === 'Terkirim') propStatus = 'Disetujui';
                    else if (currentStatus === 'Revisi') propStatus = 'Terkirim';
                    else propStatus = currentStatus;
                    
                    document.getElementById('proposal-status-form').action = '/marketing/proposals/' + $event.detail.proposalId + '/status';
                    $el.showModal();
                }
            "
            class="p-0 rounded-2xl border-0 shadow-2xl w-full max-w-md bg-white">
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-1.5 m-0">
                    <i class="bi bi-file-earmark-diff text-indigo-500"></i> Update Status Proposal
                </h3>
                <button type="button" onclick="document.getElementById('proposal-status-modal').close()" class="bg-transparent border-0 text-slate-400 hover:text-slate-750 text-xl font-bold p-0 leading-none">&times;</button>
            </div>
            
            <form id="proposal-status-form" method="POST" action="" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Status Proposal</label>
                    <select name="status_proposal" x-model="propStatus" class="form-select text-sm mt-1 border-slate-350 rounded-lg w-full" required>
                        <option value="Draft" x-show="false">Draft</option>
                        <option value="Terkirim" x-show="['Draft', 'Revisi'].includes(currentStatus)">Terkirim ke Klien</option>
                        <option value="Revisi" x-show="['Terkirim'].includes(currentStatus)">Minta Revisi</option>
                        <option value="Disetujui" x-show="['Terkirim'].includes(currentStatus)">Disetujui (Deal)</option>
                        <option value="Ditolak" x-show="['Draft', 'Terkirim', 'Revisi'].includes(currentStatus)">Ditolak oleh Klien</option>
                    </select>
                </div>

                <!-- Revisi Note (shown only when status is Revisi) -->
                <div x-show="propStatus == 'Revisi'" x-transition class="p-3 bg-amber-50 rounded-lg border border-amber-200">
                    <label class="block text-xs font-bold text-amber-800 uppercase tracking-wider">Catatan / Detail Revisi Klien</label>
                    <textarea name="catatan_revisi" rows="2" class="form-control text-sm mt-1 border-amber-300 rounded-lg w-full text-amber-900 bg-white" placeholder="Tuliskan bagian proposal mana yang butuh revisi..." :required="propStatus == 'Revisi'"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 border-t pt-3">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg border-0 shadow-sm uppercase tracking-wider">
                        Update Status
                    </button>
                    <button type="button" onclick="document.getElementById('proposal-status-modal').close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg border-0 shadow-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function openStatusProposalModal(proposalId, currentStatus) {
            window.dispatchEvent(new CustomEvent('open-proposal-modal', {
                detail: { proposalId, currentStatus }
            }));
        }
    </script>

</x-app-layout>
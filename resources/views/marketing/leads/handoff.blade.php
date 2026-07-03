<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lime-600 dark:text-lime-400 text-xl leading-tight" style="font-size: 32px">
            {{ __('Serah Terima Proyek (Handoff Won)') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto space-y-6">
        
        <!-- Back Link -->
        <a href="{{ route('marketing.leads.show', $lead->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 uppercase tracking-widest no-underline transition-colors mb-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Detail Lead
        </a>

        <!-- Form Card -->
        <div class="premium-card p-6">
            <div class="border-b pb-3 mb-6 flex items-center gap-2">
                <i class="bi bi-award-fill text-emerald-500 text-lg"></i>
                <div>
                    <h3 class="text-base font-bold text-slate-800 m-0">Inisialisasi Proyek Baru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Konversi lead faskes menjadi proyek dan tiket implementasi</p>
                </div>
            </div>

            <!-- Error List -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-xs space-y-1">
                    <p class="font-bold">Terjadi kesalahan input:</p>
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('marketing.leads.process_handoff', $lead->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Section 1: Info Kontrak & Project -->
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 space-y-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-widest text-indigo-700">1. Informasi Kontrak & Proyek</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Nama Proyek</label>
                            <input type="text" name="project_name" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('project_name', $lead->nama_institusi) }}" placeholder="Contoh: Implementasi SIMRS {{ $lead->nama_institusi }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Nilai Kontrak Final (Rupiah)</label>
                                <input type="number" name="nilai_kontrak" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('nilai_kontrak', $latestProposal ? $latestProposal->nilai_penawaran : $lead->estimasi_nilai) }}">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal Penandatanganan Kontrak</label>
                                <input type="date" name="tanggal_kontrak" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('tanggal_kontrak', date('Y-m-d')) }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Unggah File Kontrak / PKS (PDF, JPG, PNG, Maks 10MB)</label>
                            <input type="file" name="file_kontrak" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required accept=".pdf,image/*">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Penugasan Tim & Target -->
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 space-y-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-widest text-indigo-700">2. Penugasan & Jadwal Implementasi</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">PIC Koordinator Project</label>
                            <select name="pic_koordinator_id" class="form-select text-sm mt-1 border-slate-350 rounded-lg w-full" required>
                                <option value="" disabled selected>-- Pilih PIC Koordinator --</option>
                                @foreach($picKoordinators as $prog)
                                    <option value="{{ $prog->id }}" {{ old('pic_koordinator_id') == $prog->id ? 'selected' : '' }}>{{ $prog->namapegawai }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">PIC Request (Client Coordinator)</label>
                            <input type="text" name="pic_request" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('pic_request', $lead->pic_klien) }}" placeholder="Contoh: Budi Susanto">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Target Go-Live / Selesai</label>
                            <input type="date" name="target_go_live" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('target_go_live', $lead->target_closing ? $lead->target_closing->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-200 text-xs text-indigo-900 space-y-2">
                    <p class="font-bold flex items-center gap-1"><i class="bi bi-info-circle-fill"></i> APA YANG AKAN TERJADI SELANJUTNYA?</p>
                    <ol class="list-decimal pl-4 space-y-1">
                        <li>Faskes akan didaftarkan otomatis sebagai <strong>Site</strong> baru di sistem jika belum terdaftar.</li>
                        <li>Sebuah <strong>Project</strong> akan dibuat di Project Tracker dengan status <strong>Open</strong>.</li>
                        <li><strong>Timeline Request (Tiket Kerja)</strong> dengan prefix <code class="bg-indigo-100 px-1 py-0.5 rounded font-bold">[IMPL]</code> akan dibuat untuk setiap modul SIMRS yang disetujui dalam proposal, dengan prioritas <strong>High</strong> dan status awal <strong>To Do</strong>.</li>
                        <li>Status Lead ini akan diperbarui menjadi <strong>Won</strong>.</li>
                    </ol>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <button type="submit" class="flex items-center gap-1.5 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg shadow-sm border-0 transition-all">
                        <i class="bi bi-check2-all"></i> Proses Serah Terima (Won)
                    </button>
                    <a href="{{ route('marketing.leads.show', $lead->id) }}" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold rounded-lg no-underline transition-all shadow-sm">
                        Batal
                    </a>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>
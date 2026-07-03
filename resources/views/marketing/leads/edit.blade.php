<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lime-600 dark:text-lime-400 text-xl leading-tight" style="font-size: 32px">
            {{ __('Edit Lead: ') }} {{ $lead->nama_institusi }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto space-y-6" x-data="{ status: '{{ old('pipeline_status', $lead->pipeline_status) }}' }">
        
        <!-- Back Link -->
        <a href="{{ route('marketing.leads.show', $lead->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 uppercase tracking-widest no-underline transition-colors mb-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Detail Lead
        </a>

        <!-- Form Card -->
        <div class="premium-card p-6">
            <div class="border-b pb-3 mb-6 flex items-center gap-2">
                <i class="bi bi-pencil-square text-lime-500 text-lg"></i>
                <h3 class="text-base font-bold text-slate-800 m-0">Edit Informasi Lead</h3>
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

            <form method="POST" action="{{ route('marketing.leads.update', $lead->id) }}" class="space-y-6">
                @csrf

                <!-- Section 1: Institusi -->
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 space-y-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-widest text-indigo-700">1. Informasi Faskes / Institusi</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Nama Institusi / Rumah Sakit / Klinik</label>
                            <input type="text" name="nama_institusi" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('nama_institusi', $lead->nama_institusi) }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Jenis Institusi</label>
                            <select name="jenis_institusi" class="form-select text-sm mt-1 border-slate-350 rounded-lg w-full" required>
                                <option value="RS_Umum" {{ old('jenis_institusi', $lead->jenis_institusi) == 'RS_Umum' ? 'selected' : '' }}>Rumah Sakit Umum</option>
                                <option value="RS_Khusus" {{ old('jenis_institusi', $lead->jenis_institusi) == 'RS_Khusus' ? 'selected' : '' }}>Rumah Sakit Khusus</option>
                                <option value="Klinik" {{ old('jenis_institusi', $lead->jenis_institusi) == 'Klinik' ? 'selected' : '' }}>Klinik Pratama/Utama</option>
                                <option value="Puskesmas" {{ old('jenis_institusi', $lead->jenis_institusi) == 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                                <option value="Lainnya" {{ old('jenis_institusi', $lead->jenis_institusi) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Kota</label>
                            <input type="text" name="kota" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('kota', $lead->kota) }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('provinsi', $lead->provinsi) }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Ukuran Institusi (TT / Bed)</label>
                            <select name="ukuran" class="form-select text-sm mt-1 border-slate-350 rounded-lg w-full" required>
                                <option value="Kecil" {{ old('ukuran', $lead->ukuran) == 'Kecil' ? 'selected' : '' }}>Kecil (< 50 Tempat Tidur)</option>
                                <option value="Menengah" {{ old('ukuran', $lead->ukuran) == 'Menengah' ? 'selected' : '' }}>Menengah (50 - 200 Tempat Tidur)</option>
                                <option value="Besar" {{ old('ukuran', $lead->ukuran) == 'Besar' ? 'selected' : '' }}>Besar (> 200 Tempat Tidur)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Sumber Lead</label>
                            <select name="sumber_lead" class="form-select text-sm mt-1 border-slate-350 rounded-lg w-full" required>
                                <option value="Referral" {{ old('sumber_lead', $lead->sumber_lead) == 'Referral' ? 'selected' : '' }}>Referral</option>
                                <option value="Website" {{ old('sumber_lead', $lead->sumber_lead) == 'Website' ? 'selected' : '' }}>Website / SEO</option>
                                <option value="Event" {{ old('sumber_lead', $lead->sumber_lead) == 'Event' ? 'selected' : '' }}>Event / Seminar</option>
                                <option value="Cold_Outreach" {{ old('sumber_lead', $lead->sumber_lead) == 'Cold_Outreach' ? 'selected' : '' }}>Cold Outreach</option>
                                <option value="Lainnya" {{ old('sumber_lead', $lead->sumber_lead) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: PIC Klien -->
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 space-y-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-widest text-indigo-700">2. Kontak Person Klien</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Nama Lengkap PIC</label>
                            <input type="text" name="pic_klien" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('pic_klien', $lead->pic_klien) }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Jabatan PIC</label>
                            <input type="text" name="jabatan_pic" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('jabatan_pic', $lead->jabatan_pic) }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">No. HP / WhatsApp PIC</label>
                            <input type="text" name="no_hp_pic" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('no_hp_pic', $lead->no_hp_pic) }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Email PIC</label>
                            <input type="email" name="email_pic" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('email_pic', $lead->email_pic) }}">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Status & Teknis -->
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 space-y-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-widest text-indigo-700">3. Nilai, Status & Kebutuhan Teknis</h4>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Estimasi Nilai Kontrak (Rupiah)</label>
                                <input type="number" name="estimasi_nilai" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('estimasi_nilai', $lead->estimasi_nilai) }}">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">PIC Internal (Sales/Marketing)</label>
                                <select name="pic_internal" class="form-select text-sm mt-1 border-slate-350 rounded-lg w-full" required>
                                    @foreach($pics as $p)
                                        <option value="{{ $p->id }}" {{ old('pic_internal', $lead->pic_internal) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Status Pipeline</label>
                                <select name="pipeline_status" x-model="status" class="form-select text-sm mt-1 border-slate-350 rounded-lg w-full" required>
                                    <option value="New" {{ old('pipeline_status', $lead->pipeline_status) == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Qualified" {{ old('pipeline_status', $lead->pipeline_status) == 'Qualified' ? 'selected' : '' }}>Qualified</option>
                                    <option value="Demo" {{ old('pipeline_status', $lead->pipeline_status) == 'Demo' ? 'selected' : '' }}>Demo</option>
                                    <option value="Proposal" {{ old('pipeline_status', $lead->pipeline_status) == 'Proposal' ? 'selected' : '' }}>Proposal</option>
                                    <option value="Negotiation" {{ old('pipeline_status', $lead->pipeline_status) == 'Negotiation' ? 'selected' : '' }}>Negotiation</option>
                                    <option value="Won" {{ old('pipeline_status', $lead->pipeline_status) == 'Won' ? 'selected' : '' }}>Won</option>
                                    <option value="Lost" {{ old('pipeline_status', $lead->pipeline_status) == 'Lost' ? 'selected' : '' }}>Lost</option>
                                    <option value="Nurture" {{ old('pipeline_status', $lead->pipeline_status) == 'Nurture' ? 'selected' : '' }}>Nurture</option>
                                </select>
                            </div>
                        </div>

                        <!-- Lost Reason (conditionally shown via Alpine.js) -->
                        <div x-show="status == 'Lost'" x-transition class="p-3 bg-red-50 rounded-lg border border-red-200">
                            <label class="block text-xs font-bold text-red-800 uppercase tracking-wider">Alasan Lost / Gagal Closing</label>
                            <textarea name="alasan_lost" rows="2" class="form-control text-sm mt-1 border-red-300 rounded-lg w-full text-red-900 bg-white" placeholder="Sebutkan alasan mengapa prospek ini gagal closing..." :required="status == 'Lost'">{{ old('alasan_lost', $lead->alasan_lost) }}</textarea>
                        </div>

                        <!-- Checkboxes Modul -->
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Modul SIMRS yang Diminati</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 bg-white p-3 rounded-lg border border-slate-200">
                                @php
                                    $moduls = ['EMR / Rekam Medis Elektronik', 'Bridging BPJS (VClaim/PCare)', 'Pendaftaran & Antrean', 'Kasir & Keuangan', 'Apotek & Farmasi', 'Laboratorium & Radiologi', 'Logistik & Inventori', 'Kepegawaian / HRD', 'Pelaporan & Eksekutif'];
                                    $selectedModuls = old('modul_diminati', $lead->modul_diminati ?? []);
                                @endphp
                                @foreach($moduls as $modul)
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="modul_diminati[]" value="{{ $modul }}" class="rounded text-lime-600 focus:ring-lime-500 border-slate-300" {{ in_array($modul, $selectedModuls) ? 'checked' : '' }}>
                                        <span class="text-xs font-medium text-slate-700">{{ $modul }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal Masuk</label>
                                <input type="date" name="tanggal_masuk" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('tanggal_masuk', $lead->tanggal_masuk ? $lead->tanggal_masuk->format('Y-m-d') : '') }}">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Target Closing / Go-Live</label>
                                <input type="date" name="target_closing" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" value="{{ old('target_closing', $lead->target_closing ? $lead->target_closing->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Catatan Tambahan</label>
                            <textarea name="catatan" rows="3.5" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" placeholder="Tuliskan info tambahan mengenai lead..." >{{ old('catatan', $lead->catatan) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center gap-3">
                    <button type="submit" class="flex items-center gap-1.5 px-5 py-2.5 bg-lime-600 hover:bg-lime-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all border-0">
                        <i class="bi bi-save2"></i> Update Lead
                    </button>
                    <a href="{{ route('marketing.leads.show', $lead->id) }}" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold rounded-lg no-underline transition-all shadow-sm">
                        Batal
                    </a>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lime-600 dark:text-lime-400 text-xl leading-tight" style="font-size: 32px">
            {{ __('Tambah Lead Baru') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto space-y-6">
        
        <!-- Back Link -->
        <a href="{{ route('marketing.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 uppercase tracking-widest no-underline transition-colors mb-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>

        <!-- Form Card -->
        <div class="premium-card p-6">
            <div class="border-b pb-3 mb-6 flex items-center gap-2">
                <i class="bi bi-person-plus text-lime-500 text-lg"></i>
                <h3 class="text-base font-bold text-slate-800 m-0">Formulir Informasi Calon Klien</h3>
            </div>

            <form method="POST" action="{{ route('marketing.leads.store') }}" class="space-y-6">
                @csrf

                <!-- Section 1: Institusi -->
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 space-y-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-widest text-indigo-700">1. Informasi Faskes / Institusi</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Nama Institusi / Rumah Sakit / Klinik</label>
                            <input type="text" name="nama_institusi" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" placeholder="Contoh: RSUD Harapan Sehat" required value="{{ old('nama_institusi') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Jenis Institusi</label>
                            <select name="jenis_institusi" class="form-select text-sm mt-1 border-slate-350 rounded-lg w-full" required>
                                <option value="" disabled selected>-- Pilih Jenis --</option>
                                <option value="RS_Umum" {{ old('jenis_institusi') == 'RS_Umum' ? 'selected' : '' }}>Rumah Sakit Umum</option>
                                <option value="RS_Khusus" {{ old('jenis_institusi') == 'RS_Khusus' ? 'selected' : '' }}>Rumah Sakit Khusus</option>
                                <option value="Klinik" {{ old('jenis_institusi') == 'Klinik' ? 'selected' : '' }}>Klinik Pratama/Utama</option>
                                <option value="Puskesmas" {{ old('jenis_institusi') == 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                                <option value="Lainnya" {{ old('jenis_institusi') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Kota</label>
                            <input type="text" name="kota" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" placeholder="Contoh: Cirebon" required value="{{ old('kota') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" placeholder="Contoh: Jawa Barat" required value="{{ old('provinsi') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Ukuran Institusi (TT / Bed)</label>
                            <select name="ukuran" class="form-select text-sm mt-1 border-slate-350 rounded-lg w-full" required>
                                <option value="" disabled selected>-- Pilih Ukuran --</option>
                                <option value="Kecil" {{ old('ukuran') == 'Kecil' ? 'selected' : '' }}>Kecil (< 50 Tempat Tidur)</option>
                                <option value="Menengah" {{ old('ukuran') == 'Menengah' ? 'selected' : '' }}>Menengah (50 - 200 Tempat Tidur)</option>
                                <option value="Besar" {{ old('ukuran') == 'Besar' ? 'selected' : '' }}>Besar (> 200 Tempat Tidur)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Sumber Lead</label>
                            <select name="sumber_lead" class="form-select text-sm mt-1 border-slate-350 rounded-lg w-full" required>
                                <option value="" disabled selected>-- Pilih Sumber --</option>
                                <option value="Referral" {{ old('sumber_lead') == 'Referral' ? 'selected' : '' }}>Referral</option>
                                <option value="Website" {{ old('sumber_lead') == 'Website' ? 'selected' : '' }}>Website / SEO</option>
                                <option value="Event" {{ old('sumber_lead') == 'Event' ? 'selected' : '' }}>Event / Seminar</option>
                                <option value="Cold_Outreach" {{ old('sumber_lead') == 'Cold_Outreach' ? 'selected' : '' }}>Cold Outreach</option>
                                <option value="Lainnya" {{ old('sumber_lead') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                            <input type="text" name="pic_klien" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" placeholder="Contoh: dr. Setiawan, Sp.PK" required value="{{ old('pic_klien') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Jabatan PIC</label>
                            <input type="text" name="jabatan_pic" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" placeholder="Contoh: Direktur Utama / Kepala IT" required value="{{ old('jabatan_pic') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">No. HP / WhatsApp PIC</label>
                            <input type="text" name="no_hp_pic" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" placeholder="Contoh: 08123456789" required value="{{ old('no_hp_pic') }}">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Email PIC</label>
                            <input type="email" name="email_pic" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" placeholder="Contoh: pic@institusi.com" required value="{{ old('email_pic') }}">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Estimasi & Teknis -->
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 space-y-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-widest text-indigo-700">3. Nilai & Kebutuhan Teknis</h4>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Estimasi Budget Klien (Rupiah)</label>
                                <input type="number" name="estimasi_nilai" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" placeholder="Contoh: 150000000" required value="{{ old('estimasi_nilai') }}">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">PIC Internal (Sales/Marketing)</label>
                                <select name="pic_internal" class="form-select text-sm mt-1 border-slate-350 rounded-lg w-full" required>
                                    <option value="" disabled selected>-- Pilih PIC Internal --</option>
                                    @foreach($pics as $p)
                                        <option value="{{ $p->id }}" {{ old('pic_internal') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Checkboxes Modul -->
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Modul SIMRS yang Diminati</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 bg-white p-3 rounded-lg border border-slate-200">
                                @php
                                    $moduls = ['EMR / Rekam Medis Elektronik', 'Bridging BPJS (VClaim/PCare)', 'Pendaftaran & Antrean', 'Kasir & Keuangan', 'Apotek & Farmasi', 'Laboratorium & Radiologi', 'Logistik & Inventori', 'Kepegawaian / HRD', 'Pelaporan & Eksekutif'];
                                @endphp
                                @foreach($moduls as $modul)
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="modul_diminati[]" value="{{ $modul }}" class="rounded text-lime-600 focus:ring-lime-500 border-slate-300">
                                        <span class="text-xs font-medium text-slate-700">{{ $modul }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal Masuk</label>
                                <input type="date" name="tanggal_masuk" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" required value="{{ old('tanggal_masuk', date('Y-m-d')) }}">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Target Closing / Go-Live</label>
                                <input type="date" name="target_closing" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" value="{{ old('target_closing') }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Catatan Tambahan</label>
                            <textarea name="catatan" rows="3.5" class="form-control text-sm mt-1 border-slate-350 rounded-lg w-full" placeholder="Tuliskan info tambahan mengenai lead, seperti histori kontak sebelumnya atau kebutuhan khusus..." >{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center gap-3">
                    <button type="submit" class="flex items-center gap-1.5 px-5 py-2.5 bg-lime-600 hover:bg-lime-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all border-0">
                        <i class="bi bi-save2"></i> Simpan Lead
                    </button>
                    <a href="{{ route('marketing.dashboard') }}" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold rounded-lg no-underline transition-all shadow-sm">
                        Batal
                    </a>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>
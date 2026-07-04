<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight uppercase tracking-wider">
                {{ __('Master SLA & Performa') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-200" x-data="{
        editMode: false,
        activeSlaId: null,
        prioritasId: '',
        jenistaskId: '',
        slaHours: '',
        openEditModal(sla) {
            this.editMode = true;
            this.activeSlaId = sla.id;
            this.prioritasId = sla.prioritas_id || '';
            this.jenistaskId = sla.jenistask_id || '';
            this.slaHours = sla.sla_hours;
            document.getElementById('sla-form').action = '/master-sla/' + sla.id;
            document.getElementById('_method').value = 'PUT';
            document.getElementById('form-submit-btn').innerText = 'UPDATE SLA';
            document.getElementById('cancel-edit-btn').classList.remove('hidden');
            document.getElementById('form-title').innerText = 'Edit Aturan SLA';
            window.scrollTo({top: 0, behavior: 'smooth'});
        },
        resetForm() {
            this.editMode = false;
            this.activeSlaId = null;
            this.prioritasId = '';
            this.jenistaskId = '';
            this.slaHours = '';
            document.getElementById('sla-form').action = '{{ route('sla.store') }}';
            document.getElementById('_method').value = 'POST';
            document.getElementById('form-submit-btn').innerText = 'SIMPAN SLA';
            document.getElementById('cancel-edit-btn').classList.add('hidden');
            document.getElementById('form-title').innerText = 'Tambah Aturan SLA Baru';
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-lime-800 rounded-2xl bg-lime-50 border border-lime-200 dark:bg-lime-950 dark:text-lime-200 dark:border-lime-900 shadow-sm" role="alert">
                    <span class="font-bold">Sukses!</span> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-200 dark:bg-red-950 dark:text-red-200 dark:border-red-900 shadow-sm" role="alert">
                    <span class="font-bold">Error!</span> {{ $errors->first() }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form SLA -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl p-6 h-fit transition-all duration-200">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider mb-4 border-b border-slate-100 dark:border-slate-700 pb-3" id="form-title">
                        Tambah Aturan SLA Baru
                    </h3>
                    
                    <form id="sla-form" method="POST" action="{{ route('sla.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="POST">

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Prioritas</label>
                            <select name="prioritas_id" x-model="prioritasId" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all p-2.5">
                                <option value="">-- Semua Prioritas --</option>
                                @foreach($prioritas as $p)
                                    <option value="{{ $p->id }}">{{ $p->namaprioritas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Jenis Task</label>
                            <select name="jenistask_id" x-model="jenistaskId" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all p-2.5">
                                <option value="">-- Semua Jenis Task --</option>
                                @foreach($jenistasks as $jt)
                                    <option value="{{ $jt->id }}">{{ $jt->jenistask }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Durasi SLA (Jam)</label>
                            <input type="number" name="sla_hours" x-model="slaHours" required min="1" placeholder="Contoh: 24" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all p-2.5">
                        </div>

                        <div class="pt-2 flex flex-col gap-2">
                            <button type="submit" id="form-submit-btn" class="w-full py-2.5 bg-lime-600 hover:bg-lime-700 text-white text-xs font-bold rounded-xl shadow-md transition-all uppercase tracking-wider">
                                SIMPAN SLA
                            </button>
                            <button type="button" id="cancel-edit-btn" @click="resetForm()" class="hidden w-full py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-650 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-all uppercase tracking-wider">
                                Batal Edit
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table SLA List -->
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl p-6 transition-all duration-200">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider mb-4 border-b border-slate-100 dark:border-slate-700 pb-3">
                        Daftar Aturan SLA Terdaftar
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                                    <th class="py-3.5 px-4 rounded-l-xl">No</th>
                                    <th class="py-3.5 px-4">Prioritas</th>
                                    <th class="py-3.5 px-4">Jenis Task</th>
                                    <th class="py-3.5 px-4 text-center">Durasi SLA</th>
                                    <th class="py-3.5 px-4 text-center rounded-r-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-200 font-medium">
                                @forelse($slas as $index => $sla)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                                        <td class="py-3.5 px-4">{{ $index + 1 }}</td>
                                        <td class="py-3.5 px-4">
                                            @if($sla->prioritas)
                                                <span class="inline-block px-2.5 py-1 bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 rounded-lg text-[10px] font-bold uppercase tracking-wide">
                                                    {{ $sla->prioritas->namaprioritas }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">Semua Prioritas</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @if($sla->jenistask)
                                                <span class="inline-block px-2.5 py-1 bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 rounded-lg text-[10px] font-bold uppercase tracking-wide">
                                                    {{ $sla->jenistask->jenistask }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">Semua Jenis Task</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-800 dark:text-slate-100">
                                            {{ $sla->sla_hours }} Jam
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex justify-center items-center gap-2">
                                                <button @click="openEditModal({
                                                    id: {{ $sla->id }},
                                                    prioritas_id: '{{ $sla->prioritas_id }}',
                                                    jenistask_id: '{{ $sla->jenistask_id }}',
                                                    sla_hours: {{ $sla->sla_hours }}
                                                })" class="w-7 h-7 flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors shadow-sm" title="Edit SLA">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.83 20.84a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                    </svg>
                                                </button>
                                                <form action="/master-sla/{{ $sla->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aturan SLA ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-7 h-7 flex items-center justify-center bg-rose-600 hover:bg-rose-700 text-white rounded-lg transition-colors shadow-sm" title="Hapus SLA">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-400 font-medium">
                                            Tidak ada aturan SLA terdaftar. Silakan tambahkan baru.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Panduan Pengujian & Rumus SLA (Label Penjelas) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl p-6 transition-all duration-200">
                <h3 class="text-sm font-bold text-slate-850 dark:text-slate-100 uppercase tracking-wider mb-4 pb-3 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
                    <i class="bi bi-info-circle-fill text-lime-600"></i> Panduan Pengujian & Rumus Perhitungan SLA (Real-Time Test Guide)
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-xs text-slate-650 dark:text-slate-350 leading-relaxed font-semibold">
                    <!-- Kolom 1: Perhitungan Deadline & SLA -->
                    <div class="space-y-2 bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <span class="inline-block px-2 py-0.5 bg-lime-100 dark:bg-lime-950 text-lime-800 dark:text-lime-200 text-[10px] font-bold rounded-lg uppercase">1. Auto-Deadline SLA</span>
                        <p class="mt-1.5">
                            Saat membuat <strong>Timeline Request baru</strong>, jika kolom <strong>Task Deadline</strong> dikosongkan, sistem akan otomatis menghitung:
                            <br><span class="text-slate-800 dark:text-white font-bold">Deadline = Task Masuk + Durasi SLA (Jam)</span>
                        </p>
                        <p class="text-[10px] text-slate-400">
                            *SLA jam ditarik otomatis berdasarkan kecocokan prioritas & jenis task pada aturan Master SLA di atas.
                        </p>
                    </div>

                    <!-- Kolom 2: Rumus Keterlambatan Presisi -->
                    <div class="space-y-2 bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <span class="inline-block px-2 py-0.5 bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-200 text-[10px] font-bold rounded-lg uppercase">2. Rumus Denda & Skor</span>
                        <p class="mt-1.5">
                            Keterlambatan dihitung presisi tingkat menit. Pecahan hari keterlambatan (seperti terlambat beberapa jam) dibulatkan ke atas (<code class="text-rose-600">ceil</code>):
                            <br><span class="text-slate-800 dark:text-white font-bold">Hari Telat = Ceil(Selisih Menit / 1440)</span>
                            <br><span class="text-slate-800 dark:text-white font-bold">Skor Performa = 100 - (Hari Telat × 10)</span>
                        </p>
                        <p class="text-[10px] text-slate-400">
                            *Denda minimum keterlambatan harian adalah denda 10 poin (Skor = 90).
                        </p>
                    </div>

                    <!-- Kolom 3: Penanganan Bug & Done-Rev -->
                    <div class="space-y-2 bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <span class="inline-block px-2 py-0.5 bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-200 text-[10px] font-bold rounded-lg uppercase">3. Transisi & Grace Period</span>
                        <p class="mt-1.5">
                            • <strong>Kasus Bugs</strong>: Jika status PIC Request diubah menjadi Bugs, tugas kembali ke <strong>Progress</strong>, tanggal selesai di-reset, dan deadline mendapat <strong>Grace Period +24 Jam</strong>.
                            <br>• <strong>Done & Revisi</strong>: Status developer otomatis berganti menjadi <strong>Done - Rev</strong>.
                        </p>
                        <p class="text-[10px] text-slate-400">
                            *Aturan ini menjaga keadilan performa developer saat perbaikan bug.
                        </p>
                    </div>

                    <!-- Kolom 4: Indikator Evaluasi SDM -->
                    <div class="space-y-2 bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <span class="inline-block px-2 py-0.5 bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-200 text-[10px] font-bold rounded-lg uppercase">4. Indikator Evaluasi SDM</span>
                        <div class="mt-1.5 space-y-1">
                            <div class="flex justify-between items-center bg-white dark:bg-slate-800 px-2 py-1 rounded-lg border border-slate-100 dark:border-slate-700">
                                <span class="text-lime-600 font-bold">● Skor &ge; 80</span>
                                <span class="bg-lime-50 text-lime-700 px-1.5 py-0.5 rounded text-[10px] font-bold">Baik (0%)</span>
                            </div>
                            <div class="flex justify-between items-center bg-white dark:bg-slate-800 px-2 py-1 rounded-lg border border-slate-100 dark:border-slate-700">
                                <span class="text-amber-600 font-bold">● Skor 50-79</span>
                                <span class="bg-amber-50 text-amber-700 px-1.5 py-0.5 rounded text-[10px] font-bold">Kurang (5%)</span>
                            </div>
                            <div class="flex justify-between items-center bg-white dark:bg-slate-800 px-2 py-1 rounded-lg border border-slate-100 dark:border-slate-700">
                                <span class="text-rose-600 font-bold">● Skor &lt; 50</span>
                                <span class="bg-red-50 text-red-700 px-1.5 py-0.5 rounded text-[10px] font-bold">Buruk (15%)</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">
                            *Potongan persen ini diterapkan langsung memotong nominal Tunjangan Kinerja bulanan di slip Gaji.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

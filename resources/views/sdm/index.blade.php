<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight uppercase tracking-wider">
            {{ __('SDM - Evaluasi Kinerja PIC') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-200">
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

            <!-- Filter & Action Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl p-6 transition-all duration-200">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <!-- Form Filter -->
                    <form method="GET" action="{{ route('sdm.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full md:max-w-2xl">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Bulan</label>
                            <select name="bulan" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all p-2.5">
                                @for($m=1; $m<=12; $m++)
                                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Tahun</label>
                            <select name="tahun" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all p-2.5">
                                @for($y=Carbon\Carbon::now()->year - 2; $y<=Carbon\Carbon::now()->year + 1; $y++)
                                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full py-2.5 bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold rounded-xl transition-all uppercase tracking-wider shadow-md">
                                TAMPILKAN
                            </button>
                        </div>
                    </form>

                    <!-- Generate Button Form -->
                    <form method="POST" action="{{ route('sdm.generate') }}" class="w-full md:w-auto">
                        @csrf
                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                        <input type="hidden" name="tahun" value="{{ $tahun }}">
                        <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-lime-600 hover:bg-lime-700 text-white text-xs font-bold rounded-xl shadow-md transition-all uppercase tracking-wider">
                            GENERATE / SYNC EVALUASI
                        </button>
                    </form>
                </div>
            </div>

            <!-- List Evaluasi Kinerja -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl p-6 transition-all duration-200">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">
                        Hasil Evaluasi Kinerja Pegawai (Periode: {{ Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }})
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                                <th class="py-3.5 px-4 rounded-l-xl">Nama Pegawai</th>
                                <th class="py-3.5 px-4 text-center">Total Task Selesai</th>
                                <th class="py-3.5 px-4 text-center">Tepat Waktu</th>
                                <th class="py-3.5 px-4 text-center">Terlambat</th>
                                <th class="py-3.5 px-4 text-center">Rata-rata Skor</th>
                                <th class="py-3.5 px-4 text-center">Persentase Potongan Tukin</th>
                                <th class="py-3.5 px-4 text-center">Status SDM</th>
                                <th class="py-3.5 px-4 text-center rounded-r-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-200 font-medium">
                            @forelse($evaluasis as $eval)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-800 dark:text-slate-100">{{ $eval->pegawai->namapegawai }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold">{{ $eval->pegawai->jenispegawai }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold">{{ $eval->total_task }}</td>
                                    <td class="py-3.5 px-4 text-center text-lime-600 dark:text-lime-400 font-bold">{{ $eval->task_tepat_waktu }}</td>
                                    <td class="py-3.5 px-4 text-center text-rose-600 dark:text-rose-400 font-bold">{{ $eval->task_terlambat }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-block px-2.5 py-1 rounded-lg font-bold {{ $eval->rata_rata_skor >= 80 ? 'bg-lime-50 text-lime-700 dark:bg-lime-950/40 dark:text-lime-300' : ($eval->rata_rata_skor >= 50 ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300' : 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-300') }}">
                                            {{ number_format($eval->rata_rata_skor, 1) }} / 100
                                        </span>
                                        <div class="text-[10px] mt-1 font-bold uppercase tracking-wider {{ $eval->rata_rata_skor >= 80 ? 'text-lime-600 dark:text-lime-400' : ($eval->rata_rata_skor >= 50 ? 'text-amber-600 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400') }}">
                                            {{ $eval->rata_rata_skor >= 80 ? 'Kinerja Baik' : ($eval->rata_rata_skor >= 50 ? 'Kinerja Kurang' : 'Kinerja Buruk') }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold text-rose-600 dark:text-rose-400">
                                        @if($eval->persentase_potongan > 0)
                                            -{{ number_format($eval->persentase_potongan, 0) }}%
                                        @else
                                            <span class="text-lime-600 dark:text-lime-400">0% (Aman)</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg {{ $eval->status_evaluasi === 'Approved' ? 'bg-lime-50 text-lime-700 dark:bg-lime-950/40 dark:text-lime-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">
                                            {{ $eval->status_evaluasi }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if($eval->status_evaluasi === 'Draft')
                                            <form method="POST" action="{{ route('sdm.approve', $eval->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-lime-600 hover:bg-lime-700 text-white rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm transition-colors">
                                                    APPROVE
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-slate-400 font-semibold text-[10px] uppercase">No Action</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-slate-400 font-medium">
                                        Tidak ada data evaluasi kinerja untuk periode ini. Silakan klik tombol "Generate / Sync Evaluasi" di atas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Standar Kinerja Panel -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-lime-500 to-emerald-600 rounded-3xl p-6 text-white shadow-lg">
                    <h4 class="font-bold text-sm uppercase tracking-wider mb-2">Kinerja Prima (Aman)</h4>
                    <p class="text-xs text-lime-100 leading-relaxed">
                        PIC dengan rata-rata skor performa <strong>&ge; 75.0</strong>.
                        Tunjangan kinerja dibayarkan penuh tanpa potongan (Potongan 0%).
                    </p>
                </div>
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl p-6 text-white shadow-lg">
                    <h4 class="font-bold text-sm uppercase tracking-wider mb-2">Kinerja Kurang (Peringatan)</h4>
                    <p class="text-xs text-amber-100 leading-relaxed">
                        PIC dengan rata-rata skor performa antara <strong>60.0 s.d. 74.9</strong>.
                        Tunjangan kinerja bulanan dikenakan potongan sebesar <strong>5%</strong>.
                    </p>
                </div>
                <div class="bg-gradient-to-br from-rose-500 to-red-650 rounded-3xl p-6 text-white shadow-lg">
                    <h4 class="font-bold text-sm uppercase tracking-wider mb-2">Kinerja Buruk (Pinalti)</h4>
                    <p class="text-xs text-rose-100 leading-relaxed">
                        PIC dengan rata-rata skor performa di bawah <strong>60.0</strong>.
                        Tunjangan kinerja bulanan dikenakan potongan sebesar <strong>15%</strong>.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

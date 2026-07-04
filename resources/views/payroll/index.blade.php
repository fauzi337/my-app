<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight uppercase tracking-wider">
            {{ __('Payroll - Penggajian PIC') }}
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
                    <form method="GET" action="{{ route('payroll.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full md:max-w-2xl">
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
                    <form method="POST" action="{{ route('payroll.generate') }}" class="w-full md:w-auto">
                        @csrf
                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                        <input type="hidden" name="tahun" value="{{ $tahun }}">
                        <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-lime-600 hover:bg-lime-700 text-white text-xs font-bold rounded-xl shadow-md transition-all uppercase tracking-wider">
                            GENERATE / SYNC GAJI
                        </button>
                    </form>
                </div>
            </div>

            <!-- List Slip Gaji -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl p-6 transition-all duration-200">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">
                        Slip Gaji Bulanan Pegawai (Periode: {{ Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }})
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                                <th class="py-3.5 px-4 rounded-l-xl">Nama Pegawai</th>
                                <th class="py-3.5 px-4">Gaji Pokok</th>
                                <th class="py-3.5 px-4">Tunjangan Kinerja</th>
                                <th class="py-3.5 px-4">Potongan Kinerja</th>
                                <th class="py-3.5 px-4">Total Gaji Diterima</th>
                                <th class="py-3.5 px-4 text-center">Status Bayar</th>
                                <th class="py-3.5 px-4 text-center rounded-r-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-200 font-medium">
                            @forelse($payrolls as $pay)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-800 dark:text-slate-100">{{ $pay->pegawai->namapegawai }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold">{{ $pay->pegawai->jenispegawai }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold">Rp {{ number_format($pay->gaji_pokok, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-lime-600 dark:text-lime-400">Rp {{ number_format($pay->tunjangan_kinerja, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-rose-600 dark:text-rose-400">
                                        @if($pay->potongan_performa > 0)
                                            -Rp {{ number_format($pay->potongan_performa, 0, ',', '.') }}
                                        @else
                                            Rp 0
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">Rp {{ number_format($pay->gaji_diterima, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg {{ $pay->status_pembayaran === 'Paid' ? 'bg-lime-50 text-lime-700 dark:bg-lime-950/40 dark:text-lime-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">
                                            {{ $pay->status_pembayaran }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if($pay->status_pembayaran === 'Draft')
                                            <form method="POST" action="{{ route('payroll.pay', $pay->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-lime-600 hover:bg-lime-700 text-white rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm transition-colors">
                                                    BAYAR GAJI
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-lime-600 dark:text-lime-400 font-bold text-[10px] uppercase flex items-center justify-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                                Lunas
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400 font-medium">
                                        Tidak ada data slip gaji untuk periode ini. Silakan klik tombol "Generate / Sync Gaji" di atas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes Panel -->
            <div class="bg-slate-150 dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-350">
                <h4 class="font-bold text-xs uppercase text-slate-800 dark:text-slate-100 tracking-wider mb-2">Informasi Penting</h4>
                <ul class="list-disc list-inside text-xs space-y-1.5">
                    <li>Daftar penggajian ditarik berdasarkan data <strong>Evaluasi Kinerja Bulanan SDM</strong> yang telah berstatus <strong>Approved</strong>.</li>
                    <li>Jika ada pegawai yang kinerjanya belum di-approve oleh SDM, slip gajinya tidak akan digenerate untuk menghindari kesalahan perhitungan.</li>
                    <li>Sistem ini menggunakan gaji standar industri: Programmer & Koordinator (Gapok Rp 8 jt, Tukin Rp 4 jt), Analis (Gapok Rp 7 jt, Tukin Rp 3 jt), dan Staf Operator (Gapok Rp 6 jt, Tukin Rp 2 jt).</li>
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>

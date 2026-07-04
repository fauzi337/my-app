<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight uppercase tracking-wider">
            {{ __('SLA & Status Change Log Historis') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-200" x-data="{ activeTab: 'developer' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Tab Buttons -->
            <div class="bg-white dark:bg-slate-800 p-2.5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-lg flex gap-2 w-fit transition-all duration-200">
                <button @click="activeTab = 'developer'" 
                    :class="activeTab === 'developer' ? 'bg-lime-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-900'"
                    class="px-5 py-2 text-xs font-bold rounded-xl uppercase tracking-wider transition-all duration-200 focus:outline-none">
                    TAB 1: Dev Activity Log
                </button>
                <button @click="activeTab = 'pic_request'" 
                    :class="activeTab === 'pic_request' ? 'bg-lime-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-900'"
                    class="px-5 py-2 text-xs font-bold rounded-xl uppercase tracking-wider transition-all duration-200 focus:outline-none">
                    TAB 2: PIC Request Log
                </button>
            </div>

            <!-- Tab 1 Content: Developer & Task Created Activity -->
            <div x-show="activeTab === 'developer'" x-transition:enter="transition ease-out duration-200" class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl p-6 transition-all duration-200">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">
                        Riwayat Aktivitas
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                                <th class="py-3.5 px-4 rounded-l-xl">Waktu Log</th>
                                <th class="py-3.5 px-4">Tipe Log</th>
                                <th class="py-3.5 px-4">Detail Aktifitas</th>
                                <th class="py-3.5 px-4">Transisi Status (Sebelum -> Sesudah)</th>
                                <th class="py-3.5 px-4">Tugas / Site</th>
                                <th class="py-3.5 px-4">PIC Developer</th>
                                <th class="py-3.5 px-4">Operator</th>
                                <th class="py-3.5 px-4 text-center rounded-r-xl">SLA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-200 font-medium">
                            @forelse($devActivities as $act)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                                    <td class="py-3.5 px-4 whitespace-nowrap text-slate-500 dark:text-slate-400">
                                        {{ \Carbon\Carbon::parse($act->created_at)->translatedFormat('d M Y H:i') }}
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="inline-block px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider rounded-lg {{ $act->tipe_aktifitas === 'Created' ? 'bg-lime-50 text-lime-700 dark:bg-lime-950/40 dark:text-lime-300' : ($act->tipe_aktifitas === 'Detail Edit' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400') }}">
                                            {{ $act->tipe_aktifitas }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 max-w-xs break-words text-slate-650 dark:text-slate-350">
                                        {{ $act->aktifitas }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400 font-semibold">
                                        @if($act->status_sesudahnya)
                                            <span class="text-[10px] font-bold text-slate-400 line-through">{{ $act->status_sebelumnya ?? 'None' }}</span>
                                            <span class="mx-1 text-slate-400">&rarr;</span>
                                            <span class="text-[10px] font-bold text-lime-600 dark:text-lime-400">{{ $act->status_sesudahnya }}</span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-800 dark:text-slate-100">{{ $act->task ?? '-' }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold">{{ $act->namasite ?? '-' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 font-bold whitespace-nowrap">
                                        {{ $act->pic_developer ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                        {{ $act->nama_user ?? 'System' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-800 dark:text-slate-100">
                                        @if($act->sla_hours)
                                            {{ $act->sla_hours }} Jam
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-slate-400 font-medium">
                                        Belum ada riwayat log aktivitas developer di tabel `log_sla_t`.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 2 Content: PIC Request Activity -->
            <div x-show="activeTab === 'pic_request'" x-transition:enter="transition ease-out duration-200" class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl p-6 transition-all duration-200">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">
                        Riwayat Aktivitas
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                                <th class="py-3.5 px-4 rounded-l-xl">Waktu Log</th>
                                <th class="py-3.5 px-4">Tipe Log</th>
                                <th class="py-3.5 px-4">Detail Aktifitas</th>
                                <th class="py-3.5 px-4">Transisi Status (Sebelum -> Sesudah)</th>
                                <th class="py-3.5 px-4">Tugas / Site</th>
                                <th class="py-3.5 px-4">PIC Request</th>
                                <th class="py-3.5 px-4 rounded-r-xl">Operator</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-200 font-medium">
                            @forelse($picActivities as $act)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                                    <td class="py-3.5 px-4 whitespace-nowrap text-slate-500 dark:text-slate-400">
                                        {{ \Carbon\Carbon::parse($act->created_at)->translatedFormat('d M Y H:i') }}
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="inline-block px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider rounded-lg bg-lime-50 text-lime-700 dark:bg-lime-950/40 dark:text-lime-300">
                                            {{ $act->tipe_aktifitas }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 max-w-xs break-words text-slate-650 dark:text-slate-350">
                                        {{ $act->aktifitas }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400 font-semibold">
                                        @if($act->status_sesudahnya)
                                            <span class="text-[10px] font-bold text-slate-400 line-through">{{ $act->status_sebelumnya ?? 'None' }}</span>
                                            <span class="mx-1 text-slate-400">&rarr;</span>
                                            <span class="text-[10px] font-bold text-lime-600 dark:text-lime-400">{{ $act->status_sesudahnya }}</span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-800 dark:text-slate-100">{{ $act->task ?? '-' }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold">{{ $act->namasite ?? '-' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 font-bold whitespace-nowrap">
                                        {{ $act->pic_request ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                        {{ $act->nama_user ?? 'System' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400 font-medium">
                                        Belum ada riwayat log aktivitas PIC Request di tabel `log_sla_t`.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

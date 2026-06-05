<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-orange-600/75 text-xl text-gray-800 leading-tight" style="font-size: 50px">
            {{ __('Detail Meeting') }}
        </h2>
    </x-slot>

    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detail Meeting - {{ $meeting->meeting_code }}</title>

        <!-- Tailwind CSS -->
        @vite('resources/css/app.css')

        <!-- Bootstrap 5.3 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #f9fafb;
            }
        </style>
    </head>

    <div class="p-6 max-w-7xl mx-auto space-y-8">
        
        <!-- Back Link & Header -->
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard.agenda') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-orange-500 hover:text-orange-600 no-underline transition-colors">
                <i class="bi bi-arrow-left"></i> Kembali ke Agenda
            </a>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-white text-gray-700 font-medium text-xs rounded-full border border-gray-200 shadow-sm">
                    Status: <span class="font-bold text-orange-600">{{ $meeting->status ?? 'Active' }}</span>
                </span>
                <span class="px-3 py-1 bg-orange-100 text-orange-800 font-mono font-bold text-xs rounded-full border border-orange-200 shadow-sm">
                    {{ $meeting->meeting_code }}
                </span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="border-b pb-3 mb-2 flex items-center gap-2">
                <i class="bi bi-info-circle-fill text-orange-500 text-lg"></i>
                <h3 class="text-base font-bold text-gray-800">Informasi Rapat</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Site</span>
                    <span class="text-gray-800 font-medium">{{ $meeting->agendaMeeting->site->namasite ?? '-' }}</span>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Waktu Jadwal</span>
                    <span class="text-gray-800 font-medium">
                        <i class="bi bi-calendar3 me-1 text-gray-400"></i>
                        {{ $meeting->agendaMeeting->tgl_jadwal }} / Pukul {{ $meeting->agendaMeeting->jam->jam ?? '-' }}
                    </span>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Waktu Realisasi</span>
                    <span class="text-gray-800 font-medium">
                        <i class="bi bi-calendar-check me-1 text-emerald-500"></i>
                        {{ $meeting->tgl_realisasi ?? 'Belum terealisasi' }}
                    </span>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Pihak Rapat</span>
                    <span class="text-gray-800 font-medium">{{ $meeting->agendaMeeting->parties->parties ?? '-' }}</span>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Unit</span>
                    <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 text-gray-700 rounded text-xs font-medium inline-block mt-0.5">
                        {{ $meeting->agendaMeeting->unit->unit ?? '-' }}
                    </span>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">PIC Internal</span>
                    <span class="text-gray-800 font-medium">{{ $meeting->agendaMeeting->picInternal->namapegawai ?? '-' }}</span>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">PIC Eksternal</span>
                    <span class="text-gray-800 font-medium">{{ $meeting->agendaMeeting->picExternal->namapegawai ?? '-' }}</span>
                </div>
            </div>

            <div class="border-t pt-4 space-y-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Agenda Kegiatan</span>
                <p class="text-sm text-gray-800 bg-gray-50 p-3 rounded-lg border border-gray-100 leading-relaxed font-medium">
                    {{ $meeting->agendaMeeting->kegiatan }}
                </p>
            </div>

            <div class="border-t pt-4 space-y-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Notulen Meeting / Hasil Rapat</span>
                <p class="text-sm text-gray-800 bg-orange-50/30 p-3 rounded-lg border border-orange-100/50 leading-relaxed">
                    {{ $meeting->notes ?? 'Belum ada notulen rapat.' }}
                </p>
            </div>
        </div>

        <!-- SECTION C: DAFTAR ACTION ITEM -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-list-task text-orange-500"></i>
                    Action Items dari Meeting
                </h2>
                <p class="text-sm text-gray-500 mt-1">Daftar tindak lanjut tugas yang harus dikerjakan oleh unit dan PIC terkait.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/70 text-gray-600">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Action Item</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-32">Kategori</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-24">Unit</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-40">PIC</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-28">Priority</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-32">Target Date</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-28">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white text-sm">
                        @forelse ($meeting->actionItems as $item)
                            <tr class="align-top hover:bg-gray-50/30 transition-colors">
                                <td class="px-6 py-4 space-y-2">
                                    <div class="font-medium text-gray-900 leading-relaxed">{{ $item->description }}</div>
                                    
                                    <!-- Collapsible Progress Log (Fitur 10/11 support) -->
                                    @if ($item->progressUpdates->count() > 0)
                                        <div class="mt-2 text-xs">
                                            <details class="bg-gray-50 border rounded-lg p-2.5 space-y-2 group">
                                                <summary class="font-bold text-orange-600 hover:text-orange-700 cursor-pointer focus:outline-none flex items-center justify-between">
                                                    <span><i class="bi bi-clock-history me-1"></i> Riwayat Progress Update ({{ $item->progressUpdates->count() }})</span>
                                                    <i class="bi bi-chevron-down group-open:rotate-180 transition-transform"></i>
                                                </summary>
                                                <div class="divide-y divide-gray-200 pt-2 space-y-2 max-h-[200px] overflow-y-auto pr-1">
                                                    @foreach ($item->progressUpdates as $prog)
                                                        <div class="pt-2 text-[11px] space-y-1">
                                                            <div class="flex items-center justify-between text-gray-400 font-semibold">
                                                                <span><i class="bi bi-person me-1"></i> {{ $prog->creator->name ?? 'User' }}</span>
                                                                <span><i class="bi bi-calendar me-1"></i> {{ $prog->progress_date }}</span>
                                                            </div>
                                                            <p class="text-gray-700 m-0 leading-relaxed">{{ $prog->notes }}</p>
                                                            @if($prog->attachment)
                                                                <div class="pt-0.5">
                                                                    <a href="{{ asset($prog->attachment) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] text-blue-500 font-bold hover:underline">
                                                                        <i class="bi bi-paperclip"></i> Lihat Lampiran
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </details>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs text-gray-600 font-semibold bg-gray-100 border px-2 py-0.5 rounded">
                                        {{ $item->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs text-gray-600 font-semibold bg-indigo-50 border border-indigo-150 px-2 py-0.5 rounded text-indigo-700">
                                        {{ $item->unit->unit ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-6 h-6 rounded-full bg-orange-50 border border-orange-100 flex items-center justify-center text-[10px] font-bold text-orange-600">
                                            {{ strtoupper(substr($item->picPerson->name ?? 'US', 0, 2)) }}
                                        </div>
                                        <span class="text-gray-700 font-medium">{{ $item->picPerson->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $priName = trim($item->priority->name ?? '');
                                        $priClass = match($priName) {
                                            'Critical' => 'bg-red-100 text-red-800 border-red-200',
                                            'High' => 'bg-orange-100 text-orange-800 border-orange-200',
                                            'Medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            default => 'bg-green-100 text-green-800 border-green-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $priClass }}">
                                        {{ $priName }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                                    {{ $item->target_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statName = trim($item->live_status ?? 'Open');
                                        $statClass = match($statName) {
                                            'Done', 'Selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'In Progress', 'Progress', 'Development' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Waiting', 'Pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Cancel', 'Revisi' => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-gray-50 text-gray-600 border-gray-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statClass }}">
                                        {{ $statName }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-400">
                                    <i class="bi bi-inbox text-2xl block mb-1"></i>
                                    Rapat ini belum memiliki action items.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>

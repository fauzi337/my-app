<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-orange-600/75 text-xl text-gray-800 leading-tight" style="font-size: 50px">
            {{ __('Agenda & Meeting Management') }}
        </h2>
    </x-slot>

    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aplikasi Agenda, Manajemen Rapat, dan Tracker Kegiatan.">
        <title>Agenda & Meeting Management</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="/favicon.png" />

        <!-- Tailwind CSS -->
        @vite('resources/css/app.css')

        <!-- Bootstrap 5.3 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Alpine.js -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #f9fafb;
            }
        </style>
    </head>

    <div x-data="{ activeTab: 'agenda' }" class="p-6 max-w-7xl mx-auto space-y-6">
        
        <!-- Tab Navigation -->
        <div class="flex border-b border-gray-200 dark:border-gray-700 bg-white rounded-t-xl px-4 pt-2 shadow-sm">
            <button @click="activeTab = 'agenda'" 
                :class="activeTab === 'agenda' ? 'border-orange-500 text-orange-600 font-bold border-b-2' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="py-3 px-6 font-semibold text-sm transition-all focus:outline-none flex items-center gap-2">
                <i class="bi bi-calendar-event-fill"></i>
                AGENDA MEETING
            </button>
            <button @click="activeTab = 'hasil'" 
                :class="activeTab === 'hasil' ? 'border-orange-500 text-orange-600 font-bold border-b-2' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="py-3 px-6 font-semibold text-sm transition-all focus:outline-none flex items-center gap-2">
                <i class="bi bi-journal-check"></i>
                HASIL MEETING
                @if($listMeetingResults->where('status', 'Pending')->count() > 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                        {{ $listMeetingResults->where('status', 'Pending')->count() }}
                    </span>
                @endif
            </button>
        </div>

        <!-- Success Toast -->
        @if (session('success'))
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="fixed top-5 right-5 z-50 flex items-start gap-3 bg-green-100 text-green-800 px-4 py-3 rounded-lg shadow-md border border-green-300"
            >
                <i class="bi bi-check-circle-fill text-green-600 text-xl"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Error Toast -->
        @if ($errors->any())
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 4000)"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="fixed top-5 right-5 z-50 flex items-start gap-3 bg-red-100 text-red-800 px-4 py-3 rounded-lg shadow-md border border-red-300"
            >
                <i class="bi bi-x-circle-fill text-red-600 text-xl"></i>
                <span class="text-sm font-medium">{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- TAB 1: AGENDA MEETING CONTENT -->
        <div x-show="activeTab === 'agenda'" class="space-y-6">
            
            <!-- Agenda Creation Form -->
            <form method="POST" action="{{ route('dashboard.agendas') }}" class="p-6 bg-white shadow-sm rounded-xl border border-gray-100 space-y-4">
                @csrf
                <div class="border-b pb-3 mb-2 flex items-center gap-2">
                    <i class="bi bi-calendar-plus text-orange-500 text-lg"></i>
                    <h3 class="text-base font-bold text-gray-800">Buat Agenda Meeting Baru</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kegiatan</label>
                    <textarea name="kegiatan" class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-orange-500 focus:border-orange-500 mt-1" rows="3" placeholder="Tulis rincian kegiatan agenda..."></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Site</label>
                        <select name="site" class="w-full border-gray-300 rounded-lg p-2 mt-1 focus:ring-orange-500 focus:border-orange-500">
                            @foreach ($site as $itemSite)
                                <option value="{{ $itemSite->id }}">{{ $itemSite->namasite }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Penjadwalan</label>
                        <input type="date" name="tgl_jadwal" class="w-full border-gray-300 rounded-lg p-2 mt-1 focus:ring-orange-500 focus:border-orange-500" value="{{ old('tgl_jadwal', date('Y-m-d')) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jam</label>
                        <select name="jam" class="w-full border-gray-300 rounded-lg p-2 mt-1 focus:ring-orange-500 focus:border-orange-500">
                            @foreach ($jam as $itemjam)
                                <option value="{{ $itemjam->id }}">{{ $itemjam->jam }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pihak</label>
                        <select name="parties" class="w-full border-gray-300 rounded-lg p-2 mt-1 focus:ring-orange-500 focus:border-orange-500">
                            @foreach ($parties as $itemparties)
                                <option value="{{ $itemparties->id }}">{{ $itemparties->parties }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit</label>
                        <select name="unit" class="w-full border-gray-300 rounded-lg p-2 mt-1 focus:ring-orange-500 focus:border-orange-500">
                            @foreach ($unit as $itemunit)
                                <option value="{{ $itemunit->id }}">{{ $itemunit->unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">PIC Internal</label>
                        <select name="pic_internal" class="w-full border-gray-300 rounded-lg p-2 mt-1 focus:ring-orange-500 focus:border-orange-500">
                            @foreach ($picInternal as $itempicInternal)
                                <option value="{{ $itempicInternal->id }}">{{ $itempicInternal->namapegawai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">PIC Eksternal</label>
                        <select name="pic_external" class="w-full border-gray-300 rounded-lg p-2 mt-1 focus:ring-orange-500 focus:border-orange-500">
                            @foreach ($picExternal as $itempicExternal)
                                <option value="{{ $itempicExternal->id }}">{{ $itempicExternal->namapegawai }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="flex items-center gap-1.5 px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-lg shadow-sm transition-all">
                        <i class="bi bi-save"></i>
                        Simpan Agenda
                    </button>
                </div>
            </form>

            <!-- Agenda Listing Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-list-task text-orange-500"></i>
                            Daftar Agenda Meeting
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">Status default agenda baru adalah Scheduled.</p>
                    </div>
                    <div class="flex items-center gap-3 text-sm flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-blue-800 bg-blue-50 border border-blue-200">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            Cancel
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-orange-800 bg-orange-50 border border-orange-200">
                            <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                            Scheduled
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/70 text-gray-600">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider w-16">Aksi</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-24">KD-List</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-28">Site</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-32">Tgl Jadwal</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-20">Jam</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider min-w-[280px]">Kegiatan</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-28">Status</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-28">Pihak</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-24">Unit</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-36">Pic Internal</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-36">Pic Eksternal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($listAgenda as $item)
                                <tr class="transition-colors hover:bg-gray-50/70"
                                    @if($item->stid == 23) style="background-color: rgba(255, 224, 187, 0.25);"
                                    @elseif($item->stid == 24) style="background-color: rgba(187, 218, 255, 0.25);" 
                                    @endif>
                                    <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium w-24 space-x-1">
                                        <button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-200 hover:border-blue-600 transition-all duration-200 update-status-btn @if(trim($item->status) == 'Done' || $item->stid == 25 || trim($item->status) == 'Cancel' || $item->stid == 24) disabled pointer-events-none opacity-40 bg-gray-50 border-gray-200 text-gray-400 @endif shadow-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#statusModal" 
                                            data-id="{{ $item->id }}"
                                            onclick="setId({{ $item->id }})"
                                            title="Edit Status">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-indigo-600 hover:text-white hover:bg-indigo-600 border border-indigo-200 hover:border-indigo-600 transition-all duration-200 shadow-sm"
                                            onclick="showAgendaTimeline('{{ $item->id }}', '{{ addslashes($item->kegiatan) }}')"
                                            title="Lihat Timeline Rapat">
                                            <i class="bi bi-clock-history"></i>
                                        </button>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs font-semibold text-gray-500 font-mono w-24">{{ $item->kd_list }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 w-28">{{ $item->namasite }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 w-32">
                                        <div class="flex items-center gap-1.5">
                                            <i class="bi bi-calendar3 text-gray-400 text-xs"></i>
                                            <span>{{ $item->tgl_jadwal ? \Carbon\Carbon::parse($item->tgl_jadwal)->format('Y-m-d') : '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 w-20">
                                        <div class="flex items-center gap-1.5">
                                            <i class="bi bi-clock text-gray-400 text-xs"></i>
                                            <span>{{ $item->jam }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-800 font-medium min-w-[280px] break-words" title="{{ $item->kegiatan }}">{{ $item->kegiatan }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm w-28">
                                        @if(trim($item->status) == 'Done' || $item->stid == 25)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Done
                                            </span>
                                        @elseif(trim($item->status) == 'Scheduled' || $item->stid == 23)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-250">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Scheduled
                                            </span>
                                        @elseif(trim($item->status) == 'Cancel' || $item->stid == 24)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-250">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                Cancel
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-700 border border-gray-250">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                {{ trim($item->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 w-28">{{ $item->parties }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 w-24">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">{{ $item->unit }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 w-36">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-600 border border-gray-200 flex-shrink-0">
                                                {{ strtoupper(substr($item->picintern, 0, 2)) }}
                                            </div>
                                            <span class="truncate max-w-[120px]" title="{{ $item->picintern }}">{{ $item->picintern }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 w-36">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-6 h-6 rounded-full bg-orange-50 flex items-center justify-center text-[10px] font-bold text-orange-600 border border-orange-100 flex-shrink-0">
                                                {{ strtoupper(substr($item->picextern, 0, 2)) }}
                                            </div>
                                            <span class="truncate max-w-[120px]" title="{{ $item->picextern }}">{{ $item->picextern }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: HASIL MEETING CONTENT -->
        <div x-show="activeTab === 'hasil'" class="space-y-6" style="display: none;">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-journal-text text-orange-500"></i>
                        Manajemen Hasil Meeting
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Agenda yang dibuat otomatis masuk sebagai draft hasil meeting di sini dengan status awal Pending.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/70 text-gray-600">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider w-36">Aksi</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-36">Kode Meeting</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-28">Site</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-32">Tanggal</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider min-w-[250px]">Kegiatan Agenda</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-28">Pihak</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-28">Status</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider w-40">Project Terkait</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider min-w-[200px]">Notulen Rapat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($listMeetingResults as $item)
                                <tr class="transition-colors hover:bg-gray-50/70">
                                    <td class="px-4 py-3 whitespace-nowrap text-center text-xs font-medium space-x-1">
                                        <!-- Notes Button -->
                                        <button type="button" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded shadow-sm transition-all @if($item->status == 'Done' || $item->status == 'On Going') opacity-50 pointer-events-none @endif" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#meetingNotesModal"
                                            onclick="setNotesId('{{ $item->id }}', '{{ $item->meeting_code }}', '{{ addslashes($item->kegiatan) }}', '{{ addslashes($item->namasite) }}', '{{ $item->unit_id }}')">
                                            <i class="bi bi-pencil-square"></i> Notes
                                        </button>
                                        <!-- Detail Link -->
                                        <a href="{{ route('meeting.detail', $item->id) }}" 
                                           class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded shadow-sm transition-all no-underline">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        <!-- Follow-up (+) Button -->
                                        @if($item->show_plus_button)
                                            <a href="{{ route('meeting.followup', $item->id) }}" 
                                               class="inline-flex items-center justify-center w-6 h-6 bg-green-500 hover:bg-green-600 text-white rounded shadow-sm transition-all text-xs font-bold no-underline"
                                               title="Buat Pertemuan Tindak Lanjut"
                                               onclick="return confirm('Apakah Anda ingin membuat pertemuan tindak lanjut dari rapat ini?')">
                                                <i class="bi bi-plus-lg"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-700 font-mono">{{ $item->meeting_code }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $item->namasite }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                        <span>{{ $item->tgl_jadwal ? \Carbon\Carbon::parse($item->tgl_jadwal)->format('Y-m-d') : '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-800 font-medium min-w-[250px] break-words">{{ $item->kegiatan }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $item->parties }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        @if($item->status == 'Done')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                Done
                                            </span>
                                        @elseif($item->status == 'Cancel')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                                Cancel
                                            </span>
                                        @elseif($item->status == 'On Going')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200 animate-pulse">
                                                On Going
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                        @if($item->project_name)
                                            <span class="px-2 py-0.5 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded text-xs font-semibold">
                                                {{ $item->project_name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 font-light text-xs">Belum dikaitkan</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600 max-w-xs truncate" title="{{ $item->notes }}">
                                        {{ $item->notes ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- NOTES MODAL (FITUR 2 & 3) -->
        <div class="modal fade" id="meetingNotesModal" tabindex="-1" aria-labelledby="meetingNotesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form id="meetingNotesForm" method="POST" x-data="{
                    actionItems: [],
                    extractText: '',
                    showExtract: false,
                    meetingCode: '',
                    kegiatan: '',
                    site: '',
                    unitId: '',
                    addActionItem() {
                        this.actionItems.push({
                            description: '',
                            category_id: '',
                            unit_id: this.unitId,
                            pic_person_id: '',
                            priority_id: '',
                            target_date: '{{ date('Y-m-d') }}'
                        });
                    },
                    removeActionItem(index) {
                        this.actionItems.splice(index, 1);
                    },
                    extractActionItems() {
                        if (!this.extractText.trim()) return;
                        const sentences = this.extractText.split(/[\n.]+/).map(s => s.trim()).filter(s => s.length > 3);
                        sentences.forEach(sentence => {
                            this.actionItems.push({
                                description: sentence,
                                category_id: '',
                                unit_id: this.unitId,
                                pic_person_id: '',
                                priority_id: '',
                                target_date: '{{ date('Y-m-d') }}'
                            });
                        });
                        this.extractText = '';
                        this.showExtract = false;
                    },
                    initModal(code, keg, sit, unitId) {
                        this.meetingCode = code;
                        this.kegiatan = keg;
                        this.site = sit;
                        this.unitId = unitId;
                        this.actionItems = [];
                        this.extractText = '';
                        this.showExtract = false;
                    }
                }" @meeting-notes-open.window="initModal($event.detail.code, $event.detail.kegiatan, $event.detail.site, $event.detail.unitId)">
                    @csrf
                    <div class="modal-content rounded-xl overflow-hidden shadow-lg border-0">
                        <div class="modal-header bg-orange-500 text-white py-3.5">
                            <h5 class="modal-title font-bold text-base flex items-center gap-2" id="meetingNotesModalLabel">
                                <i class="bi bi-pencil-square"></i> Update Hasil Meeting & Action Items
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                            
                            <!-- Meeting Info Header -->
                            <div class="p-3 bg-orange-50/50 border border-orange-100 rounded-lg text-xs text-orange-800 space-y-1">
                                <div><strong>Kode Meeting:</strong> <span x-text="meetingCode" class="font-mono bg-orange-100/50 px-1 py-0.5 rounded"></span></div>
                                <div><strong>Site:</strong> <span x-text="site"></span></div>
                                <div><strong>Agenda Kegiatan:</strong> <span x-text="kegiatan"></span></div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-xs font-semibold text-gray-700">Tanggal Realisasi Meeting</label>
                                    <input type="date" name="tgl_realisasi" required class="form-control text-sm mt-1 border-gray-300 rounded-lg" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-span-2 hidden">
                                    <label class="block text-xs font-semibold text-gray-700">Kaitkan ke Project (Optional)</label>
                                    <select name="project_id" class="form-select text-sm mt-1 border-gray-300 rounded-lg">
                                        <option value="">-- Tidak Ada Project (Agenda Umum) --</option>
                                        @foreach ($projects as $proj)
                                            <option value="{{ $proj->id }}">{{ $proj->project_code }} - {{ $proj->project_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700">Notulen Meeting / Hasil Rapat</label>
                                <textarea name="notulen" required class="form-control text-sm mt-1 border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500" rows="3" placeholder="Tulis catatan dan kesimpulan hasil rapat..."></textarea>
                            </div>

                            <!-- Action Items Section -->
                            <div class="border-t pt-4 mt-2">
                                <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                                    <h6 class="font-bold text-sm text-gray-700 flex items-center gap-1.5 m-0">
                                        <i class="bi bi-list-task text-orange-500"></i> Action Items Monitoring
                                    </h6>
                                    <div class="flex gap-2">
                                        <button type="button" @click="showExtract = !showExtract" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold border border-orange-500 text-orange-500 hover:bg-orange-500 hover:text-white rounded transition-colors">
                                            <i class="bi bi-file-earmark-text"></i> Extract Action Items
                                        </button>
                                        <button type="button" @click="addActionItem()" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold bg-orange-500 hover:bg-orange-600 text-white rounded transition-colors shadow-sm">
                                            <i class="bi bi-plus-lg"></i> Tambah Action Item
                                        </button>
                                    </div>
                                </div>

                                <!-- Extract Action Items Textarea -->
                                <div x-show="showExtract" class="p-3 bg-gray-50 border border-gray-200 rounded-lg mb-3 space-y-2">
                                    <label class="block text-xs font-semibold text-gray-600">Tempel Draft Notulen (Tiap baris/kalimat akan di-extract menjadi 1 Action Item terpisah)</label>
                                    <textarea x-model="extractText" class="form-control text-xs" rows="3" placeholder="Contoh:&#10;Konfirmasi ke marketing terkait sosialisasi bridging.&#10;Konfirmasi ke legal terkait perjanjian kerja sama."></textarea>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="showExtract = false" class="btn btn-sm btn-secondary text-xs px-3 py-1">Batal</button>
                                        <button type="button" @click="extractActionItems()" class="btn btn-sm btn-warning text-xs px-3 py-1 text-white">Extract</button>
                                    </div>
                                </div>

                                <!-- Action Item Dynamic Rows List -->
                                <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
                                    <template x-for="(item, index) in actionItems" :key="index">
                                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg relative space-y-2.5 shadow-sm">
                                            
                                            <!-- Remove Row Button -->
                                            <button type="button" @click="removeActionItem(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold border-0 bg-transparent" title="Hapus Baris">
                                                <i class="bi bi-trash-fill text-sm"></i>
                                            </button>
                                            
                                            <!-- Description -->
                                            <div class="w-[90%]">
                                                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500">Deskripsi Action Item</label>
                                                <textarea x-model="item.description" :name="'action_items['+index+'][description]'" required class="form-control text-xs mt-1 border-gray-300 rounded focus:ring-orange-500 focus:border-orange-500" rows="1.5" placeholder="Tugas yang harus diselesaikan..."></textarea>
                                            </div>

                                            <!-- Grid Inputs -->
                                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
                                                <div>
                                                    <label class="block text-[10px] font-semibold text-gray-500">Kategori</label>
                                                    <select x-model="item.category_id" :name="'action_items['+index+'][category_id]'" required class="form-select text-xs mt-1 border-gray-300 rounded py-1">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach ($actionCategories as $cat)
                                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-semibold text-gray-500">Unit PIC</label>
                                                    <select x-model="item.unit_id" :name="'action_items['+index+'][unit_id]'" required class="form-select text-xs mt-1 border-gray-300 rounded py-1">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach ($units as $u)
                                                            <option value="{{ $u->id }}">{{ trim($u->unit) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-semibold text-gray-500">PIC Person</label>
                                                    <select x-model="item.pic_person_id" :name="'action_items['+index+'][pic_person_id]'" required class="form-select text-xs mt-1 border-gray-300 rounded py-1">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach ($users as $usr)
                                                            <option value="{{ $usr->id }}">{{ trim($usr->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-semibold text-gray-500">Priority</label>
                                                    <select x-model="item.priority_id" :name="'action_items['+index+'][priority_id]'" required class="form-select text-xs mt-1 border-gray-300 rounded py-1">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach ($priorities as $pri)
                                                            <option value="{{ $pri->id }}">{{ $pri->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-semibold text-gray-500">Target Date</label>
                                                    <input type="date" x-model="item.target_date" :name="'action_items['+index+'][target_date]'" required class="form-control text-xs mt-1 border-gray-300 rounded py-1">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-semibold text-gray-500">Status</label>
                                                    <input type="text" value="Open" readonly class="form-control text-xs mt-1 bg-gray-150 border-gray-300 rounded py-1 text-gray-500 font-semibold">
                                                </div>
                                            </div>

                                        </div>
                                    </template>
                                    
                                    <div x-show="actionItems.length === 0" class="text-center py-8 text-xs text-gray-400 bg-gray-50/50 rounded-lg border border-dashed">
                                        <i class="bi bi-inbox text-xl block mb-1"></i>
                                        Belum ada action item. Klik "Tambah Action Item" atau gunakan "Extract Action Items" untuk menambah tugas baru.
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer bg-gray-50 py-3.5">
                            <button type="button" class="btn btn-secondary text-sm px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success text-sm px-5 bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm border-0">Simpan Hasil Rapat</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- UPDATE STATUS MODAL (LEGACY FORM SUPPORT) -->
        <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="updateStatusForm" method="POST">
                    @csrf
                    <div class="modal-content rounded-xl overflow-hidden shadow-lg border-0 bg-white dark:bg-gray-800 text-gray-950 dark:text-gray-50">
                        <div class="modal-header bg-gray-800 dark:bg-gray-950 text-white py-3.5">
                            <h5 class="modal-title font-bold text-base" id="statusModalLabel">Update Status Agenda</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-6 space-y-4">
                            <!-- Hidden ID -->
                            <input type="hidden" name="id" id="dataId">

                            <div class="mb-3" id="agenda-status">
                                <label for="status_agenda" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Status Agenda</label>
                                <select name="statusid" class="w-full border-gray-300 dark:border-gray-600 rounded-lg p-2 mt-1 focus:ring-orange-500 focus:border-orange-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    @foreach ($statusAgenda as $itemStatus)
                                        <option value="{{ $itemStatus->id }}">{{ $itemStatus->status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3" id="tgl-selesai-group">
                                <label for="tgl_selesai" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Tgl Realisasi</label>
                                <input type="date" name="tgl_selesai" class="w-full border-gray-300 dark:border-gray-600 rounded-lg p-2 mt-1 focus:ring-orange-500 focus:border-orange-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" value="{{ old('tgl_selesai', date('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="modal-footer bg-gray-50 dark:bg-gray-900/50 py-3.5">
                            <button type="button" class="btn btn-secondary text-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary text-sm bg-orange-500 border-0 hover:bg-orange-600 text-white font-semibold">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- AGENDA TIMELINE MODAL -->
        <div class="modal fade" id="agendaTimelineModal" tabindex="-1" aria-labelledby="agendaTimelineModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content rounded-xl overflow-hidden shadow-lg border-0 bg-white dark:bg-gray-800 transition-colors duration-200">
                    <div class="modal-header bg-gray-800 dark:bg-gray-950 text-white py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-clock-history text-lg text-orange-400"></i>
                            <div>
                                <h5 class="modal-title font-bold text-base text-white" id="agendaTimelineModalLabel">Timeline Pertemuan Rapat</h5>
                                <p class="text-xs text-gray-300 dark:text-gray-400 mt-0.5 font-medium" id="timelineAgendaKegiatan"></p>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-6 max-h-[70vh] overflow-y-auto bg-gray-50 dark:bg-gray-900/40 transition-colors duration-200">
                        <div id="timelineContent" class="relative pl-6 border-l-2 border-gray-200 dark:border-gray-700 ml-4 space-y-6">
                            <!-- Dynamic timeline items go here -->
                        </div>
                        <div id="timelineEmptyState" class="hidden text-center py-12 text-gray-400 dark:text-gray-500">
                            <i class="bi bi-journal-x text-5xl block mb-3 text-gray-300 dark:text-gray-755"></i>
                            <p class="text-sm font-semibold text-gray-650 dark:text-gray-400">Belum ada data tindak lanjut untuk agenda ini.</p>
                            <p class="text-xs mt-1 text-gray-500 dark:text-gray-500">Buat hasil meeting pertama untuk memulai timeline rapat.</p>
                        </div>
                    </div>
                    <div class="modal-footer bg-gray-50 dark:bg-gray-900/60 border-t border-gray-150 dark:border-gray-800 py-3.5">
                        <button type="button" class="btn btn-secondary text-sm px-4 bg-gray-500 hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 text-white font-semibold shadow-sm" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function setId(id) {
            const form = document.getElementById('updateStatusForm');
            const inputId = document.getElementById('dataId');
            inputId.value = id;
            form.action = `/update-statusagenda/${id}`;
        }

        function setNotesId(id, code, kegiatan, site, unitId) {
            const form = document.getElementById('meetingNotesForm');
            form.action = `/save-meeting-notes/${id}`;
            
            // Dispatch event to Alpine in modal
            window.dispatchEvent(new CustomEvent('meeting-notes-open', {
                detail: { code, kegiatan, site, unitId }
            }));
        }

        function showAgendaTimeline(id, kegiatan) {
            document.getElementById('timelineAgendaKegiatan').textContent = kegiatan;
            const timelineContent = document.getElementById('timelineContent');
            const emptyState = document.getElementById('timelineEmptyState');
            
            // Show loading state
            timelineContent.innerHTML = `
                <div class="text-center py-12 text-gray-500 dark:text-gray-400 w-full col-span-full">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-orange-500 border-t-transparent mb-3"></div>
                    <p class="text-sm font-medium">Memuat data timeline rapat...</p>
                </div>
            `;
            timelineContent.className = "w-full"; 
            emptyState.classList.add('hidden');
            
            // Open modal using bootstrap
            const modalEl = document.getElementById('agendaTimelineModal');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(modalEl);
            }
            modalInstance.show();
            
            fetch(`/agenda-timeline/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.timeline && data.timeline.length > 0) {
                        timelineContent.className = "relative pl-6 border-l-2 border-gray-200 dark:border-gray-700 ml-4 space-y-6";
                        timelineContent.innerHTML = '';
                        emptyState.classList.add('hidden');
                        
                        data.timeline.forEach((item, index) => {
                            let indicatorBgClass = 'bg-gray-400 dark:bg-gray-500';
                            let statusTextClass = 'text-gray-800 dark:text-gray-200';
                            let statusBgClass = 'bg-gray-100 dark:bg-gray-700/60 border border-gray-200 dark:border-gray-700';
                            let statusDotClass = 'bg-gray-400 dark:bg-gray-500';
                            
                            const statusLower = item.status.toLowerCase().trim();
                            if (statusLower === 'done' || statusLower === 'selesai') {
                                indicatorBgClass = 'bg-emerald-500';
                                statusTextClass = 'text-emerald-700 dark:text-emerald-300';
                                statusBgClass = 'bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-250 dark:border-emerald-900/30';
                                statusDotClass = 'bg-emerald-500';
                            } else if (statusLower === 'on going' || statusLower === 'ongoing') {
                                indicatorBgClass = 'bg-blue-500';
                                statusTextClass = 'text-blue-700 dark:text-blue-300';
                                statusBgClass = 'bg-blue-50 dark:bg-blue-950/40 border border-blue-250 dark:border-blue-900/30';
                                statusDotClass = 'bg-blue-500';
                            } else if (statusLower === 'pending') {
                                indicatorBgClass = 'bg-amber-500';
                                statusTextClass = 'text-amber-700 dark:text-amber-350';
                                statusBgClass = 'bg-amber-50 dark:bg-amber-950/40 border border-amber-250 dark:border-amber-900/30';
                                statusDotClass = 'bg-amber-500';
                            } else if (statusLower === 'cancel') {
                                indicatorBgClass = 'bg-red-500';
                                statusTextClass = 'text-red-700 dark:text-red-300';
                                statusBgClass = 'bg-red-50 dark:bg-red-950/40 border border-red-250 dark:border-red-900/30';
                                statusDotClass = 'bg-red-500';
                            }
                            
                            const element = document.createElement('div');
                            element.className = 'relative pl-8';
                            element.innerHTML = `
                                <!-- Circle indicator on the timeline border line -->
                                <div class="absolute top-1.5 w-[18px] h-[18px] rounded-full border-2 border-white dark:border-gray-800 shadow-sm flex items-center justify-center ${indicatorBgClass}" style="left: -9px;">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                </div>
                                
                                <!-- Meeting details card -->
                                <div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl transition-all duration-300 hover:shadow-md hover:border-gray-300 dark:hover:border-gray-600">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 pb-2.5 border-b border-gray-150 dark:border-gray-700/60">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-mono text-xs font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700">${item.meeting_code}</span>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold ${statusTextClass} ${statusBgClass}">
                                                <span class="w-1.5 h-1.5 rounded-full ${statusDotClass}"></span>
                                                ${item.status}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 font-medium">
                                            <i class="bi bi-calendar3"></i>
                                            <span>Realisasi: ${item.tgl_realisasi}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="text-sm text-gray-700 dark:text-gray-350 whitespace-pre-wrap leading-relaxed font-medium">
                                        ${item.notes}
                                    </div>
                                </div>
                            `;
                            timelineContent.appendChild(element);
                        });
                    } else {
                        timelineContent.innerHTML = '';
                        timelineContent.classList.add('hidden');
                        emptyState.classList.remove('hidden');
                    }
                })
                .catch(err => {
                    console.error('Error fetching timeline:', err);
                    timelineContent.innerHTML = '';
                    timelineContent.classList.add('hidden');
                    emptyState.classList.remove('hidden');
                    emptyState.querySelector('p').textContent = 'Gagal memuat timeline rapat.';
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const statusModal = document.getElementById('statusModal');
            statusModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                document.getElementById('dataId').value = id;
                const form = document.getElementById('updateStatusForm');
                form.action = `/update-statusagenda/${id}`;
            });
        });
    </script>
</x-app-layout>
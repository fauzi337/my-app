<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-orange-600/75 text-xl text-gray-800 leading-tight" style="font-size: 50px">
            {{ __('Agenda') }}
        </h2>
    </x-slot>

    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aplikasi Agenda dan Manajemen Jadwal.">
        <meta name="author" content="Nama Anda / Tim Developer">
        <title>Agenda | Manajemen Jadwal</title>

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

        <!-- Optional: Custom Font (Google Fonts) -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

        <!-- Optional: Dark mode theme or custom style -->
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #f9fafb;
            }
        </style>
    </head>


    <form method="POST" action="{{ route('dashboard.agendas') }}" class="p-6 bg-white shadow-md rounded-md space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium">Kegiatan</label>
            <textarea name="kegiatan" class="datepicker-input border border-gray-300 rounded-lg p-2 w-full focus:ring-red-500 focus:border-red-500" rows="3" placeholder="Isi Agenda..."></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
            <div>
                <label class="block text-sm font-medium">Site</label>
                <select name="site" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                    @foreach ($site as $itemSite)
                        <option value="{{ $itemSite->id }}">{{ $itemSite->namasite }}</option>
                        {{-- <option data-deadline="{{ \Carbon\Carbon::parse($itemSite->tgl_deadline)->format('Y-m-d') }}" value="{{ $itemSite->id }}">{{ $itemSite->week }}</option> --}}
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Penjadwalan</label>
                <input type="date" name="tgl_jadwal" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500 @error('tgl_jadwal') @enderror" value="{{ old('tgl_jadwal',date('Y-m-d')) }}">
            </div>
            <div>
                <label class="block text-sm font-medium">Jam</label>
                <select name="jam" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                    @foreach ($jam as $itemjam)
                        <option value="{{ $itemjam->id }}">{{ $itemjam->jam }}</option>
                    @endforeach
                </select>
            </div>

            {{-- <div>
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                    @foreach ($status as $itemstatus)
                        <option value="{{ $itemstatus->id }}">{{ $itemstatus->status }}</option>
                    @endforeach
                </select>
            </div> --}}
            <div>
                <label class="block text-sm font-medium">Pihak</label>
                <select name="parties" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                    @foreach ($parties as $itemparties)
                        <option value="{{ $itemparties->id }}">{{ $itemparties->parties }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Unit</label>
                <select name="unit" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                    @foreach ($unit as $itemunit)
                        <option value="{{ $itemunit->id }}">{{ $itemunit->unit }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">PIC Internal</label>
                <select name="pic_internal" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                    @foreach ($picInternal as $itempicInternal)
                        <option value="{{ $itempicInternal->id }}">{{ $itempicInternal->namapegawai }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">PIC Eksternal</label>
                <select name="pic_external" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                    @foreach ($picExternal as $itempicExternal)
                        <option value="{{ $itempicExternal->id }}">{{ $itempicExternal->namapegawai }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
        <!-- Tombol Simpan -->
            <button type="submit" class="flex items-center gap-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 ease-in-out transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan
            </button>
        <!-- Tombol Halaman Utama -->
            {{-- <a href="{{ route('antrian.index') }}" class="flex items-center gap-1 px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-sm no-underline font-semibold rounded-lg shadow-sm transition-all duration-200 ease-in-out transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Halaman Utama
            </a> --}}
        </div>

    </form>

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
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
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
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span class="text-sm font-medium">{{ $errors->first() }}</span>
        </div>
    @endif


    <script src="https://unpkg.com/flowbite@1.6.5/dist/datepicker.js"></script>
    <script>
    document.getElementById('timelineSelect').addEventListener('change', function () { 
        debugger
        const selectedOption = this.options[this.selectedIndex];
        const deadline = selectedOption.getAttribute('data-deadline');

        if (deadline) {
            document.getElementById('taskDeadline').value = deadline;
        }
    });
    </script>

    </html>

    <div class="flex items-center justify-between gap-4 mt-8 mb-4">
    <h2 class="text-xl font-semibold">List  Agenda</h2>
    <div class="flex gap-3 text-sm">

        <span class="px-3 py-1 rounded-full font-semibold text-blue-800 bg-[#bbdaff] shadow-sm">
        Cancel
        </span>
        {{-- <span class="px-3 py-1 rounded-full font-semibold text-red-800 bg-[#f8d7da] shadow-sm">
        High
        </span> --}}
        <span class="px-3 py-1 rounded-full font-semibold text-orange-800 bg-[#ffe0bb] shadow-sm">
        Scheduled
        </span>
    </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">KD-List</th>
                    <th class="px-4 py-2 text-left">Site</th>
                    <th class="px-4 py-2 text-left">Tgl Jadwal</th>
                    <th class="px-4 py-2 text-left">Jam</th>
                    <th class="px-4 py-2 text-left">Kegiatan</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Pihak</th>
                    <th class="px-4 py-2 text-left">Tgl Realisasi</th>
                    <th class="px-4 py-2 text-left">Unit</th>
                    <th class="px-4 py-2 text-left">Pic Internal</th>
                    <th class="px-4 py-2 text-left">Pic Eksternal</th>
                    <th class="px-4 py-2 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($listAgenda as $item)
                    <tr @if($item->stid == 23) style="background-color: #ffe0bb;"
                        @elseif($item->stid == 24) style="background-color: #bbdaff;" 
                        @endif>
                        <td class="px-4 py-2">{{ $item->kd_list }}</td>
                        <td class="px-4 py-2">{{ $item->namasite }}</td>
                        <td class="px-4 py-2">{{ $item->tgl_jadwal ? \Carbon\Carbon::parse($item->tgl_jadwal)->format('Y-m-d') : '-' }}</td>
                        <td class="px-4 py-2">{{ $item->jam }}</td>
                        <td class="px-4 py-2">{{ $item->kegiatan }}</td>
                        <td class="px-4 py-2">{{ $item->status }}</td>
                        <td class="px-4 py-2">{{ $item->parties }}</td>
                        <td class="px-4 py-2">{{ $item->tgl_realisasi ? \Carbon\Carbon::parse($item->tgl_realisasi)->format('Y-m-d') : '-' }}</td>
                        <td class="px-4 py-2">{{ $item->unit }}</td>
                        <td class="px-4 py-2">{{ $item->picintern }}</td>
                        <td class="px-4 py-2">{{ $item->picextern }}</td>
                        <td class="px-4 py-2">
                            <button type="button" 
                            class="btn btn-outline-primary update-status-btn @if($item->status == 'Done') disabled pointer-events-none opacity-50 @endif" 
                            data-bs-toggle="modal" 
                            data-bs-target="#statusModal" 
                            data-id="{{ $item->id }}"
                            data-devstatus="{{ $item->devstid }}"
                            data-servstatus="{{ $item->servstid }}"
                            onclick="setId({{ $item->id }})"
                            title="Edit Status">
                                {{-- Update --}}
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="updateStatusForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                    <!-- Hidden ID -->
                    <input type="hidden" name="id" id="dataId">

                    <!-- Developer Status -->
                        {{-- <div class="mb-3">
                            <label for="developer_status" class="form-label">Developer Status</label>
                            <select id="dev-status" name="devstid" class="w-full border-gray-300 rounded-lg p-2 focus:ring-yellow-500 focus:border-yellow-500">
                                @foreach ($statusDev as $itemStatusDev)
                                    <option value="{{ $itemStatusDev->id }}">
                                        {{ $itemStatusDev->status }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        <!-- Server Status -->
                        <div class="mb-3" id="agenda-status">
                            <label for="status_agenda" class="form-label">Status</label>
                            <select name="statusid" class="w-full border-gray-300 rounded-lg p-2 focus:ring-yellow-500 focus:border-yellow-500">
                                @foreach ($statusAgenda as $itemStatus)
                                    <option value="{{ $itemStatus->id }}">{{ $itemStatus->status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- tgl selesai -->
                        <div class="mb-3" id="tgl-selesai-group">
                            <label for="tgl_selesai" class="form-label">Tgl Realisasi</label>
                            <input type="date"  name="tgl_selesai" class="w-full border-gray-300 rounded-lg p-2 focus:ring-yellow-500 focus:border-yellow-500" 
                            value="{{ old('tgl_selesai',date('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

    <script>
    function setId(id) {
        const form = document.getElementById('updateStatusForm');
        const inputId = document.getElementById('dataId');
        inputId.value = id;

        // Atur action form ke URL update dengan ID
        // form.action = `/update-statusdev/${id}`;
        form.action = `/update-statusagenda/$`+id;
    }
    document.addEventListener('DOMContentLoaded', function () {
            const statusModal = document.getElementById('statusModal');

            statusModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                const id = button.getAttribute('data-id');
                // const devstatus = button.getAttribute('data-devstatus');
                // const servstatus = button.getAttribute('data-servstatus');

                // Set hidden ID
                document.getElementById('dataId').value = id;

                // Set dropdown selected value
                // const devSelect = statusModal.querySelector('select[name="devstid"]');
                // const servSelect = statusModal.querySelector('select[name="servstid"]');

                // if (devSelect) devSelect.value = devstatus;
                // if (servSelect) servSelect.value = servstatus;

                // Update form action jika perlu
                const form = document.getElementById('updateStatusForm');
                form.action = `/update-statusagenda/${id}`;
            });
        });
    </script>
</x-app-layout>
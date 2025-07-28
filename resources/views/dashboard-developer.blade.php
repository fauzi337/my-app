<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-orange-600/75 text-xl text-gray-800 leading-tight" style="font-size: 50px">
            {{ __('Timeline Request') }}
        </h2>
    </x-slot>

    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aplikasi Agenda dan Manajemen Jadwal.">
        <meta name="author" content="Nama Anda / Tim Developer">
        <title>Dashboard Developer</title>

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

    {{-- <form method="POST" action="{{ route('dashboard.jadwalpost') }}" class="p-6 bg-white shadow-md rounded-md space-y-4">
        @csrf

        <h2 class="text-6xl font-semibold text-orange-600/75">Timeline Request</h2>
    

    </form> --}}
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


    <div class="flex items-center gap-4 mt-8 mb-4">
    <a href="{{ route('dashboard.jadwal') }}"
        class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-3 py-1.5 rounded-md no-underline shadow-sm transition duration-200 ease-in-out hover:scale-105">
        <!-- Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Timeline Utama
    </a>
    <a href="{{ route('dashboard.reqserver') }}"
        class="relative inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-3 py-1.5 rounded-md no-underline shadow-sm transition duration-200 ease-in-out hover:scale-105">
        <!-- Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>
        Request Production
    @if($jumlahReqServer > 0)
        <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full px-1.5 py-0.5">
            {{ $jumlahReqServer }}
        </span>
        {{-- -- real time --
        <span id="badgeJumlah" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full px-1.5 py-0.5">
            {{ $jumlahReqServer }}
        </span> --}}

    @endif
    </a>
    </div>


    <div class="flex items-center justify-between gap-4 mt-8 mb-4">
    <h2 class="text-xl font-semibold">List Developer</h2>
    <div class="flex gap-3 text-sm">

        <span class="px-3 py-1 rounded-full font-semibold text-pink-800 bg-[#ffbbda] shadow-sm">
        Very High
        </span>
        <span class="px-3 py-1 rounded-full font-semibold text-red-800 bg-[#f8d7da] shadow-sm">
        High
        </span>
        <span class="px-3 py-1 rounded-full font-semibold text-yellow-800 bg-[#fff3cd] shadow-sm">
        Medium
        </span>
    </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">KD-List</th>
                    <th class="px-4 py-2 text-left">Jenis Task</th>
                    <th class="px-4 py-2 text-left">Site</th>
                    <th class="px-4 py-2 text-left">Task</th>
                    <th class="px-4 py-2 text-left">Timeline</th>
                    <th class="px-4 py-2 text-left">Tanggal Deadline</th>
                    <th class="px-4 py-2 text-left">PIC Request</th>
                    <th class="px-4 py-2 text-left">PIC Developer</th>
                    <th class="px-4 py-2 text-left">Developer Status</th>
                    <th class="px-4 py-2 text-left">Server Status</th>
                    {{-- <th class="px-4 py-2 text-left">prio</th> --}}
                    <th class="px-4 py-2 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($listDev as $item)
                    <tr @if($item->namaprioritas == 'Very High') style="background-color: #ffbbda;" 
                        @elseif($item->namaprioritas == 'High') style="background-color: #f8d7da;"
                        @elseif($item->namaprioritas == 'Medium') style="background-color: #fff3cd;"
                        @endif>
                        <td class="px-4 py-2">{{ $item->kd_list }}</td>
                        <td class="px-4 py-2">{{ $item->jenistask }}</td>
                        <td class="px-4 py-2">{{ $item->namasite }}</td>
                        <td class="px-4 py-2">{{ $item->task }}</td>
                        <td class="px-4 py-2">{{ $item->gabung }}</td>
                        <td class="px-4 py-2">{{ $item->tgl_deadline ? \Carbon\Carbon::parse($item->tgl_deadline)->format('Y-m-d') : '-' }}</td>
                        <td class="px-4 py-2">{{ $item->namapegawai }}</td>
                        <td class="px-4 py-2">{{ $item->dev }}</td>
                        <td class="px-4 py-2">{{ $item->devstatus }}</td>
                        <td class="px-4 py-2">{{ $item->servstatus }}</td>
                        {{-- <td class="px-4 py-2">{{ $item->namaprioritas }}</td> --}}
                        <td>
                            <button type="button" 
                            class="btn btn-outline-primary update-status-btn" 
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
        <!-- Modal -->
        <!-- Tombol Update -->
        {{-- <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#statusModal" onclick="setId({{ $item->id }})">
            Update
        </button> --}}

        <!-- Modal -->
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
                        <div class="mb-3">
                            <label for="developer_status" class="form-label">Developer Status</label>
                            <select id="dev-status" name="devstid" class="w-full border-gray-300 rounded-lg p-2 focus:ring-yellow-500 focus:border-yellow-500">
                                @foreach ($statusDev as $itemStatusDev)
                                    <option value="{{ $itemStatusDev->id }}">
                                        {{ $itemStatusDev->status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Server Status -->
                        <div class="mb-3" id="server-status" style="display: none;">
                            <label for="server_status" class="form-label">Server Status</label>
                            <select name="servstid" class="w-full border-gray-300 rounded-lg p-2 focus:ring-yellow-500 focus:border-yellow-500">
                                @foreach ($statusServer2 as $itemStatusServer)
                                    <option value="{{ $itemStatusServer->id }}">{{ $itemStatusServer->status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- tgl selesai -->
                        <div class="mb-3" id="tgl-selesai-group" style="display: none;">
                            <label for="tgl_selesai" class="form-label">Tgl Selesai</label>
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
    </div>
    <script>
    function setId(id) {
        const form = document.getElementById('updateStatusForm');
        const inputId = document.getElementById('dataId');
        inputId.value = id;

        // Atur action form ke URL update dengan ID
        // form.action = `/update-statusdev/${id}`;
        form.action = `/update-statusdev/$`+id;
    }
    document.addEventListener('DOMContentLoaded', function () {
            const statusModal = document.getElementById('statusModal');

            statusModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                const id = button.getAttribute('data-id');
                const devstatus = button.getAttribute('data-devstatus');
                const servstatus = button.getAttribute('data-servstatus');

                // Set hidden ID
                document.getElementById('dataId').value = id;

                // Set dropdown selected value
                const devSelect = statusModal.querySelector('select[name="devstid"]');
                const servSelect = statusModal.querySelector('select[name="servstid"]');

                if (devSelect) devSelect.value = devstatus;
                if (servSelect) servSelect.value = servstatus;

                // Update form action jika perlu
                const form = document.getElementById('updateStatusForm');
                form.action = `/update-statusdev/${id}`;
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const statusSelect = document.getElementById('dev-status');
            const tglSelesaiGroup = document.getElementById('tgl-selesai-group');
            const serverStatus = document.getElementById('server-status');

            function toggleTanggalSelesai() {
                if (statusSelect.value === '3') { // 6 adalah ID untuk "Selesai"
                    tglSelesaiGroup.style.display = 'block';
                    serverStatus.style.display = 'block';
                } else {
                    tglSelesaiGroup.style.display = 'none';
                    serverStatus.style.display = 'none';
                }
            }

            // Jalankan saat halaman dimuat
            toggleTanggalSelesai();

            // Jalankan saat nilai dropdown berubah
            statusSelect.addEventListener('change', toggleTanggalSelesai);
        });

        // function fetchJumlahReqServer() {
        //     fetch('/jumlah-reqserver')
        //         .then(res => res.json())
        //         .then(data => {
        //             const badge = document.getElementById('badgeJumlah');
        //             if (data.jumlah > 0) {
        //                 badge.innerText = data.jumlah;
        //                 badge.style.display = 'inline';
        //             } else {
        //                 badge.style.display = 'none';
        //             }
        //         });
        // }

        // // Jalankan pertama kali
        // fetchJumlahReqServer();

        // // Perbarui setiap 10 detik
        // setInterval(fetchJumlahReqServer, 10000);
    </script>
</x-app-layout>
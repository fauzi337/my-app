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
        <title>Dashboard PIC Request</title>

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
    <div class="flex items-center gap-4 mt-8 mb-4">
    <a href="{{ route('dashboard.jadwal') }}"
        class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-3 py-1.5 rounded-md no-underline shadow-sm transition duration-200 ease-in-out hover:scale-105">
        <!-- Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Timeline Utama
    </a>
    <a href="{{ route('dashboard.daily') }}"
            class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-3 py-1.5 rounded-md no-underline shadow-sm transition duration-200 ease-in-out hover:scale-105">
        <!-- Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
        </svg>
        Daily Report
    </a>
    <a href="{{ route('dashboard.weekly') }}"
            class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-3 py-1.5 rounded-md no-underline shadow-sm transition duration-200 ease-in-out hover:scale-105">
        <!-- Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
        </svg>
        Weekly Report
    </a>
    </div>
    <div class="flex items-center justify-between gap-4 mt-8 mb-4">
    <h2 class="text-xl font-semibold">List PIC Request</h2>
    <div class="flex gap-3 text-sm">
        <span class="px-3 py-1 rounded-full font-semibold text-purple-800 bg-[#e0bbff] shadow-sm">
        Hold
        </span>
        <span class="px-3 py-1 rounded-full font-semibold text-yellow-800 bg-[#fcffbb] shadow-sm">
        Ready to Test
        </span>
        <span class="px-3 py-1 rounded-full font-semibold text-orange-800 bg-[#ffb59a] shadow-sm">
        Document Required
        </span>
    </div>
    </div>


    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">KD-List</th>
                    <th class="px-4 py-2 text-left">Jenis Task</th>
                    <th class="px-4 py-2 text-left">Task</th>
                    <th class="px-4 py-2 text-left">Timeline</th>
                    <th class="px-4 py-2 text-left">Tanggal Deadline</th>
                    <th class="px-4 py-2 text-left">PIC Request</th>
                    <th class="px-4 py-2 text-left">PIC Developer</th>
                    {{-- <th class="px-4 py-2 text-left">Developer Status</th> --}}
                    <th class="px-4 py-2 text-left">Server Status</th>
                    <th class="px-4 py-2 text-left">PIC Request Status</th>
                    <th class="px-4 py-2 text-left">Final Status</th>
                    <th class="px-4 py-2 text-left"></th>
                    <th class="px-4 py-2 text-left"></th>
                    <th class="px-4 py-2 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($listPicReq as $item)
                    <tr @if($item->devstid == 5) style="background-color: #e0bbff;" 
                        @elseif($item->devstid == 3 && $item->picreqstid == 11) style="background-color: #fcffbb;"
                        @elseif($item->devstid == 3 && $item->finalstid == 22) style="background-color: #ffb59a;"
                        @endif>
                        <td class="px-4 py-2">{{ $item->kd_list }}</td>
                        <td class="px-4 py-2">{{ $item->jenistask }}</td>
                        <td class="px-4 py-2">{{ $item->task }}</td>
                        <td class="px-4 py-2">{{ $item->gabung }}</td>
                        <td class="px-4 py-2">{{ $item->tgl_deadline ? \Carbon\Carbon::parse($item->tgl_deadline)->format('Y-m-d') : '-' }}</td>
                        <td class="px-4 py-2">{{ $item->namapegawai }}</td>
                        <td class="px-4 py-2">{{ $item->dev }}</td>
                        {{-- <td class="px-4 py-2">{{ $item->devstatus }}</td> --}}
                        <td class="px-4 py-2">{{ $item->servstatus }}</td>
                        <td class="px-4 py-2">{{ $item->picreqst }}</td>
                        <td class="px-4 py-2">{{ $item->finalst }}</td>
                        <td>
                            <button type="button" 
                            class="btn btn-outline-primary update-status-btn @if($item->finalstid == 19 || $item->finalstid == 22) disabled pointer-events-none opacity-50 @endif"  
                            data-bs-toggle="modal" 
                            data-bs-target="#statusModal" 
                            data-id="{{ $item->id }}"
                            data-picreqstatus="{{ $item->picreqstid }}"
                            data-finalstatus="{{ $item->finalstid }}"
                            onclick="setId({{ $item->id }})"
                            title="Edit Status">
                                {{-- Update --}}
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                        <td>
                            <button type="button" 
                            class="btn btn-outline-secondary update-status-btn @if($item->finalstid == 19 || $item->finalstid != 22) disabled pointer-events-none opacity-50 @endif" 
                            data-bs-toggle="modal" 
                            data-bs-target="#uploadModal" 
                            data2-id="{{ $item->id }}"
                            {{-- data-picreqstatus="{{ $item->picreqstid }}"
                            data-finalstatus="{{ $item->finalstid }}" --}}
                            onclick="setId({{ $item->id }})"
                            title="Upload File">
                                {{-- Update --}}
                                <i class="bi bi-cloud-upload"></i>
                            </button>
                        </td>
                        <td>
                        <a href="{{ asset($item->path) }}" target="_blank" title="Lihat" class="@if($item->finalstid != 19) disabled pointer-events-none opacity-50 @endif">
                                <i class="bi bi-eye-fill btn btn-outline-info"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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

                        <!-- Pic Req Status -->
                            <div class="mb-3">
                                <label for="pic_request_status" class="form-label">Pic Request Status</label>
                                <select id="pic-request-status" name="picreqstid" class="w-full border-gray-300 rounded-lg p-2 focus:ring-yellow-500 focus:border-yellow-500">
                                    @foreach ($statusPicReq as $itemPicReq)
                                        <option value="{{ $itemPicReq->id }}">
                                            {{ $itemPicReq->status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Final Status -->
                            <div class="mb-3" id="final-status-group" style="display: none;">
                                <label for="final_status" class="form-label">Final Status</label>
                                <select name="finalstid" class="w-full border-gray-300 rounded-lg p-2 focus:ring-yellow-500 focus:border-yellow-500">
                                    @foreach ($statusFinal as $itemStatusFinal)
                                        <option value="{{ $itemStatusFinal->id }}">{{ $itemStatusFinal->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Upload PDF -->
                            {{-- <div class="mb-3">
                                <label for="pdf_file" class="form-label">Upload File PDF</label>
                                <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf" class="form-control">
                            </div> --}}

                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
        <!-- Modal upload-->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                $listPicReq as $item
                    <form action="{{ route('upload.pdf', $item->id) }}" id="uploadFiless" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Upload File</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Hidden ID -->
                            <input type="hidden" name="id" id="dataId">

                            <label for="pdf_file" class="block mb-2">
                            <i class="bi bi-cloud-upload me-2">  Upload File PDF:</i>
                            </label>
                            <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none form-control file-upload-hidden">

                            <button type="submit"
                                class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                Upload
                            </button>
                        </div>
                    </form>

            </div>
        </div>
    </div>
    {{-- <div class="d-flex justify-content-left gap-3 mt-4">
        <a href="{{ route('dashboard.jadwal') }}" class="bg-red-400 text-white px-4 py-2 rounded no-underline hover:bg-gray-700">Timeline Utama</a>
    </div> --}}
    <script>
    function setId(id) {
        const form = document.getElementById('updateStatusForm');
        const form2 = document.getElementById('uploadFiless');
        const inputId = document.getElementById('dataId');
        inputId.value = id;

        // Atur action form ke URL update dengan ID
        // form.action = `/update-statuspicreq/${id}`;
        form.action = `/update-statuspicreq/$`+id;
        form2.action = `/upload-pdf/${id}`;
    }
    document.addEventListener('DOMContentLoaded', function () {
            const statusModal = document.getElementById('statusModal');
            const uploadModal = document.getElementById('uploadModal');

            statusModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                const id = button.getAttribute('data-id');
                const picreqst = button.getAttribute('data-picreqstatus');
                const finalstatus = button.getAttribute('data-finalstatus');

                // Set hidden ID
                document.getElementById('dataId').value = id;

                // Set dropdown selected value
                const picReqSelect = statusModal.querySelector('select[name="picreqstid"]');
                const finalstSelect = statusModal.querySelector('select[name="finalstid"]');

                if (picReqSelect) picReqSelect.value = picreqst;
                if (finalstSelect) finalstSelect.value = finalstatus;

                // Update form action jika perlu
                const form = document.getElementById('updateStatusForm');
                form.action = `/update-statuspicreq/${id}`;
            });
            uploadModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                const id = button.getAttribute('data2-id');

                // Set hidden ID
                document.getElementById('dataId').value = id;

                // Set dropdown selected value
                // const picReqSelect = statusModal.querySelector('select[name="picreqstid"]');
                // const finalstSelect = statusModal.querySelector('select[name="finalstid"]');

                // if (picReqSelect) picReqSelect.value = picreqst;
                // if (finalstSelect) finalstSelect.value = finalstatus;

                // Update form action jika perlu
                const form2 = document.getElementById('uploadFiless');
                form2.action = `/upload-pdf/${id}`;
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const statusSelect = document.getElementById('pic-request-status');
            const tglSelesaiGroup = document.getElementById('final-status-group');

            function toggleTanggalSelesai() {
                if (statusSelect.value === '15') { // 6 adalah ID untuk "Selesai"
                    tglSelesaiGroup.style.display = 'block';
                } else {
                    tglSelesaiGroup.style.display = 'none';
                }
            }

            // Jalankan saat halaman dimuat
            toggleTanggalSelesai();

            // Jalankan saat nilai dropdown berubah
            statusSelect.addEventListener('change', toggleTanggalSelesai);
        });
    </script>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-indigo-600 dark:text-indigo-400 text-xl leading-tight" style="font-size: 32px">
            {{ __('Request Production Management') }}
        </h2>
    </x-slot>

    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aplikasi Agenda dan Manajemen Jadwal.">
        <title>Request Server Status</title>

        <!-- Tailwind CSS -->
        @vite('resources/css/app.css')

        <!-- Bootstrap 5.3 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Google Fonts (Inter) -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #f8fafc;
            }
            .premium-card {
                border-radius: 16px;
                border: 1px solid #e2e8f0;
                background: #ffffff;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            }
            .hover-lift {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .hover-lift:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            }
        </style>
    </head>

    <div class="p-6 max-w-7xl mx-auto space-y-6" x-data="{
        currentPage: 1,
        perPage: 5,
        totalItems: {{ count($listReqServ) }},
        get totalPages() { return Math.ceil(this.totalItems / this.perPage); }
    }">

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
                class="fixed top-5 right-5 z-50 flex items-start gap-3 bg-emerald-100 text-emerald-800 px-4 py-3 rounded-xl shadow-md border border-emerald-300"
            >
                <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
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
                class="fixed top-5 right-5 z-50 flex items-start gap-3 bg-red-100 text-red-800 px-4 py-3 rounded-xl shadow-md border border-red-300"
            >
                <i class="bi bi-x-circle-fill text-red-600 text-xl"></i>
                <span class="text-sm font-medium">{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Header Controls & Legend -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <a href="{{ route('dashboard.dev') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg shadow-sm border-0 no-underline transition-all">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard Dev
            </a>

            <!-- Table Legend -->
            <div class="flex items-center gap-2 text-xs font-semibold">
                <span class="px-2.5 py-1 rounded-full text-pink-850 bg-[#ffbbda] shadow-sm border border-pink-300">
                    Need Merge
                </span>
            </div>
        </div>

        <!-- LIST REQUEST PRODUCTION -->
        <div class="premium-card p-6">
            <div class="border-b pb-3 mb-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 m-0">
                    <i class="bi bi-server text-indigo-500"></i> List Request Production
                </h3>
                <span class="text-xs font-semibold bg-indigo-50 border border-indigo-200 text-indigo-700 px-2.5 py-1 rounded-lg">
                    Total: {{ count($listReqServ) }} Request
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 align-middle">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">KD-List</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Jenis Task</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Site</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider min-w-[350px]">Task</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">PIC Developer</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Server Status</th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($listReqServ as $index => $item)
                            @php
                                $rowStyle = '';
                                $rowTextClass = 'text-slate-600';
                                $kdListTextClass = 'text-slate-800 font-semibold';
                                $taskTextClass = 'text-slate-800 font-semibold';
                                if($item->servstid == 9 || $item->servstid == 7) {
                                    $rowStyle = 'background-color: #ffbbda;';
                                    $rowTextClass = 'text-pink-900';
                                    $kdListTextClass = 'text-pink-950 font-bold';
                                    $taskTextClass = 'text-pink-950 font-semibold';
                                }
                            @endphp
                            <tr x-show="Math.floor({{ $index }} / perPage) + 1 === currentPage" style="{{ $rowStyle }}" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3 w-px whitespace-nowrap {{ $kdListTextClass }}">{{ $item->kd_list }}</td>
                                <td class="px-4 py-3 w-px whitespace-nowrap {{ $rowTextClass }} font-medium">{{ $item->jenistask }}</td>
                                <td class="px-4 py-3 w-px whitespace-nowrap font-bold">
                                    <span class="text-xs px-2 py-0.5 rounded border {{ ($item->servstid == 9 || $item->servstid == 7) ? 'bg-white/20 border-white/40 text-pink-950' : 'bg-indigo-50 border-indigo-200 text-indigo-700' }}">
                                        {{ $item->namasite }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="leading-relaxed min-w-[350px] break-words {{ $taskTextClass }}">{{ $item->task }}</div>
                                </td>
                                <td class="px-4 py-3 w-px whitespace-nowrap {{ $rowTextClass }} font-medium">{{ $item->dev }}</td>
                                <td class="px-4 py-3 w-px whitespace-nowrap font-semibold">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs border {{ ($item->servstid == 9 || $item->servstid == 7) ? 'bg-white/20 border-white/40 text-pink-950' : 'bg-slate-100 border-slate-300 text-slate-800' }}">
                                        {{ $item->servstatus }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 w-px whitespace-nowrap text-center">
                                    <button type="button" 
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg shadow-sm border-0 transition-colors {{ ($item->servstid == 9 || $item->servstid == 7) ? 'bg-white/20 hover:bg-white/30 text-pink-950' : 'bg-indigo-600 hover:bg-indigo-700 text-white' }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#statusModal" 
                                        data-id="{{ $item->id }}"
                                        data-devstatus="{{ $item->devstid }}"
                                        data-servstatus="{{ $item->servstid }}"
                                        onclick="setId({{ $item->id }})"
                                        title="Edit Status">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400">
                                    <i class="bi bi-inbox text-3xl block mb-2 text-slate-300"></i>
                                    Belum ada request server yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div x-show="totalPages > 1" class="px-4 py-3 flex items-center justify-between border-t border-slate-100 bg-slate-50 rounded-b-xl mt-4">
                <div class="text-xs text-slate-500">
                    Menampilkan <span class="font-semibold" x-text="((currentPage - 1) * perPage) + 1"></span> sampai
                    <span class="font-semibold" x-text="Math.min(currentPage * perPage, totalItems)"></span> dari
                    <span class="font-semibold" x-text="totalItems"></span> data
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" 
                            @click="if (currentPage > 1) currentPage--" 
                            :disabled="currentPage === 1" 
                            class="inline-flex items-center justify-center px-2.5 py-1 bg-white hover:bg-slate-50 text-slate-700 disabled:opacity-50 disabled:pointer-events-none text-xs font-semibold rounded-lg shadow-sm border border-slate-200 transition-colors">
                        Sebelumnya
                    </button>
                    <template x-for="p in totalPages" :key="p">
                        <button type="button" 
                                @click="currentPage = p" 
                                :class="currentPage === p ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-700 hover:bg-slate-50 border-slate-200'"
                                class="inline-flex items-center justify-center w-7 h-7 text-xs font-semibold rounded-lg shadow-sm border transition-colors"
                                x-text="p">
                        </button>
                    </template>
                    <button type="button" 
                            @click="if (currentPage < totalPages) currentPage++" 
                            :disabled="currentPage === totalPages" 
                            class="inline-flex items-center justify-center px-2.5 py-1 bg-white hover:bg-slate-50 text-slate-700 disabled:opacity-50 disabled:pointer-events-none text-xs font-semibold rounded-lg shadow-sm border border-slate-200 transition-colors">
                        Selanjutnya
                    </button>
                </div>
            </div>
        </div>

        <!-- UPDATE STATUS MODAL -->
        <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="updateStatusForm" method="POST">
                    @csrf
                    <div class="modal-content rounded-2xl overflow-hidden shadow-lg border-0">
                        <div class="modal-header bg-indigo-600 text-white py-3.5">
                            <h5 class="modal-title font-bold text-base" id="statusModalLabel">
                                <i class="bi bi-pencil-square"></i> Update Status
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-6 space-y-4">
                            <!-- Hidden ID -->
                            <input type="hidden" name="id" id="dataId">

                            <!-- Server Status -->
                            <div>
                                <label for="server_status" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Server Status</label>
                                <select name="servstid" required class="form-select text-sm w-full border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    @foreach ($statusServer as $itemStatusServer)
                                        <option value="{{ $itemStatusServer->id }}">{{ $itemStatusServer->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer bg-slate-50 py-3.5">
                            <button type="button" class="btn btn-secondary text-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary text-sm bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm border-0">Simpan Status</button>
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
        form.action = `/update-statusserver/${id}`;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const statusModal = document.getElementById('statusModal');

        statusModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');
            const devstatus = button.getAttribute('data-devstatus');
            const servstatus = button.getAttribute('data-servstatus');

            document.getElementById('dataId').value = id;

            const devSelect = statusModal.querySelector('select[name="devstid"]');
            const servSelect = statusModal.querySelector('select[name="servstid"]');

            if (devSelect) devSelect.value = devstatus;
            if (servSelect) servSelect.value = servstatus;

            const form = document.getElementById('updateStatusForm');
            form.action = `/update-statusserver/${id}`;
        });
    });
    </script>
    </html>
</x-app-layout>
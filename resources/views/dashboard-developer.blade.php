<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-indigo-600 dark:text-indigo-400 text-xl leading-tight" style="font-size: 32px">
            {{ __('Developer Dashboard') }}
        </h2>
    </x-slot>

    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aplikasi Agenda dan Manajemen Jadwal.">
        <title>Dashboard Developer</title>

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
        selectedSite: '',
        sites: [],
        init() {
            const siteNames = this.items.map(item => item.siteName);
            this.sites = [...new Set(siteNames)].sort();
        },
        get filteredItems() {
            if (!this.selectedSite) return this.items;
            return this.items.filter(item => item.siteName === this.selectedSite);
        },
        get totalItems() {
            return this.filteredItems.length;
        },
        get totalPages() {
            return Math.ceil(this.totalItems / this.perPage);
        },
        isRowVisible(index) {
            const item = this.items[index];
            if (this.selectedSite && item.siteName !== this.selectedSite) {
                return false;
            }
            const filteredIndex = this.filteredItems.findIndex(fi => fi.index === index);
            if (filteredIndex === -1) return false;
            
            const start = (this.currentPage - 1) * this.perPage;
            const end = this.currentPage * this.perPage;
            return filteredIndex >= start && filteredIndex < end;
        },
        showSiteHeader(index, siteName) {
            if (!this.isRowVisible(index)) return false;
            
            const filteredIndex = this.filteredItems.findIndex(fi => fi.index === index);
            const start = (this.currentPage - 1) * this.perPage;
            
            if (filteredIndex === start) return true;
            
            const prevItem = this.filteredItems[filteredIndex - 1];
            return prevItem.siteName !== siteName;
        },
        items: [
            @foreach ($listDev as $index => $item)
                { index: {{ $index }}, siteName: '{{ addslashes($item->namasite) }}' },
            @endforeach
        ]
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

        <!-- Header Controls & Badges -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('dashboard.jadwal') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg shadow-sm border-0 no-underline transition-all">
                    <i class="bi bi-arrow-left"></i> Timeline Utama
                </a>
                <a href="{{ route('dashboard.reqserver') }}"
                    class="relative inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm border-0 no-underline transition-all">
                    <i class="bi bi-server"></i> Request Production
                    @if($jumlahReqServer > 0)
                        <span class="absolute -top-2 -right-2 bg-rose-600 text-white text-xs font-bold rounded-full px-1.5 py-0.5 shadow-md border border-white">
                            {{ $jumlahReqServer }}
                        </span>
                    @endif
                </a>

                <!-- Dropdown Filter Site -->
                <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-lg px-3 py-1.5 shadow-sm">
                    <i class="bi bi-funnel text-indigo-500 text-sm"></i>
                    <select x-model="selectedSite" @change="currentPage = 1" class="text-xs font-semibold text-slate-700 border-0 bg-transparent p-0 pr-6 focus:ring-0 cursor-pointer">
                        <option value="">Semua Site</option>
                        <template x-for="site in sites" :key="site">
                            <option :value="site" x-text="site"></option>
                        </template>
                    </select>
                </div>
            </div>

            <!-- Table Legend -->
            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                <span class="px-2.5 py-1 rounded-full text-white bg-[#ff006b] shadow-sm border border-pink-600">
                    Bug / Re-work
                </span>
                <span class="px-2.5 py-1 rounded-full text-pink-800 bg-[#ffbbda] shadow-sm border border-pink-300">
                    Very High
                </span>
                <span class="px-2.5 py-1 rounded-full text-red-800 bg-[#f8d7da] shadow-sm border border-red-300">
                    High
                </span>
                <span class="px-2.5 py-1 rounded-full text-yellow-800 bg-[#fff3cd] shadow-sm border border-yellow-300">
                    Medium
                </span>
            </div>
        </div>

        <!-- LIST DEVELOPER TASKS -->
        <div class="premium-card p-6">
            <div class="border-b pb-3 mb-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 m-0">
                    <i class="bi bi-person-workspace text-indigo-500"></i> List Developer Tasks
                </h3>
                <span class="text-xs font-semibold bg-indigo-50 border border-indigo-200 text-indigo-700 px-2.5 py-1 rounded-lg">
                    Total: {{ count($listDev) }} Tugas
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 align-middle">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Aksi</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">KD-List</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Jenis Task</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider min-w-[350px]">Task</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Timeline</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Deadline</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">PIC Request</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">PIC Dev</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Dev Status</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Server Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($listDev as $index => $item)
                            <!-- Site Group Header Row -->
                            <tr x-show="showSiteHeader({{ $index }}, '{{ addslashes($item->namasite) }}')" class="bg-indigo-50/30">
                                <td colspan="10" class="px-4 py-2.5 bg-indigo-50/80 border-y border-indigo-100/50 text-indigo-700 font-bold text-xs uppercase tracking-wider">
                                    <div class="flex items-center gap-1.5">
                                        <i class="bi bi-geo-alt-fill text-indigo-500"></i> Site: {{ $item->namasite }}
                                    </div>
                                </td>
                            </tr>

                            @php
                                $rowStyle = '';
                                $rowTextClass = 'text-slate-600';
                                $kdListTextClass = 'text-slate-800 font-semibold';
                                $taskTextClass = 'text-slate-800 font-semibold';
                                if($item->picreqstid == 14) {
                                    $rowStyle = 'background-color: #ff006b; color: white;';
                                    $rowTextClass = 'text-white/90';
                                    $kdListTextClass = 'text-white font-bold';
                                    $taskTextClass = 'text-white font-semibold';
                                } elseif($item->namaprioritas == 'High') {
                                    $rowStyle = 'background-color: #f8d7da;';
                                    $rowTextClass = 'text-red-900';
                                    $kdListTextClass = 'text-red-950 font-bold';
                                    $taskTextClass = 'text-red-950 font-semibold';
                                } elseif($item->namaprioritas == 'Medium') {
                                    $rowStyle = 'background-color: #fff3cd;';
                                    $rowTextClass = 'text-yellow-900';
                                    $kdListTextClass = 'text-yellow-950 font-bold';
                                    $taskTextClass = 'text-yellow-950 font-semibold';
                                } elseif($item->namaprioritas == 'Very High') {
                                    $rowStyle = 'background-color: #ffbbda;';
                                    $rowTextClass = 'text-pink-900';
                                    $kdListTextClass = 'text-pink-950 font-bold';
                                    $taskTextClass = 'text-pink-950 font-semibold';
                                }
                            @endphp
                            <tr x-show="isRowVisible({{ $index }})" style="{{ $rowStyle }}" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3 w-px whitespace-nowrap text-center">
                                    <button type="button" 
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg shadow-sm border-0 transition-colors {{ $item->picreqstid == 14 ? 'bg-white/20 hover:bg-white/30 text-white' : 'bg-indigo-600 hover:bg-indigo-700 text-white' }}"
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
                                <td class="px-4 py-3 w-px whitespace-nowrap {{ $kdListTextClass }}">{{ $item->kd_list }}</td>
                                <td class="px-4 py-3 w-px whitespace-nowrap {{ $rowTextClass }} font-medium">{{ $item->jenistask }}</td>
                                <td class="px-4 py-3">
                                    <div class="leading-relaxed min-w-[350px] break-words {{ $taskTextClass }}">{{ $item->task }}</div>
                                </td>
                                <td class="px-4 py-3 w-px whitespace-nowrap {{ $rowTextClass }} font-medium">{{ $item->gabung }}</td>
                                <td class="px-4 py-3 w-px whitespace-nowrap {{ $rowTextClass }} font-semibold">
                                    {{ $item->tgl_deadline ? \Carbon\Carbon::parse($item->tgl_deadline)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 w-px whitespace-nowrap {{ $rowTextClass }}">{{ $item->namapegawai }}</td>
                                <td class="px-4 py-3 w-px whitespace-nowrap {{ $rowTextClass }} font-medium">{{ $item->dev }}</td>
                                <td class="px-4 py-3 w-px whitespace-nowrap font-semibold">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs border {{ $item->picreqstid == 14 ? 'bg-white/20 border-white/40 text-white' : 'bg-slate-100 border-slate-300 text-slate-800' }}">
                                        {{ $item->devstatus }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 w-px whitespace-nowrap font-semibold">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs border {{ $item->picreqstid == 14 ? 'bg-white/20 border-white/40 text-white' : 'bg-slate-100 border-slate-300 text-slate-800' }}">
                                        {{ $item->servstatus }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-12 text-slate-400">
                                    <i class="bi bi-inbox text-3xl block mb-2 text-slate-300"></i>
                                    Belum ada tugas developer yang terdaftar.
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

                            <!-- Developer Status -->
                            <div>
                                <label for="dev-status" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Developer Status</label>
                                <select id="dev-status" name="devstid" required class="form-select text-sm w-full border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    @foreach ($statusDev as $itemStatusDev)
                                        <option value="{{ $itemStatusDev->id }}">
                                            {{ $itemStatusDev->status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Server Status -->
                            <div id="server-status" style="display: none;">
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Server Status</label>
                                <select name="servstid" class="form-select text-sm w-full border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    @foreach ($statusServer2 as $itemStatusServer)
                                        <option value="{{ $itemStatusServer->id }}">{{ $itemStatusServer->status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- tgl selesai -->
                            <div id="tgl-selesai-group" style="display: none;">
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Tanggal Selesai</label>
                                <input type="datetime-local" name="tgl_selesai" class="form-control text-sm w-full border-slate-300 rounded-lg" value="{{ old('tgl_selesai', date('Y-m-d\TH:i')) }}">
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
        form.action = `/update-statusdev/${id}`;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const statusModal = document.getElementById('statusModal');
        const devSelect = document.getElementById('dev-status');
        const tglSelesaiGroup = document.getElementById('tgl-selesai-group');
        const serverStatus = document.getElementById('server-status');

        // Simpan opsi asli status developer
        let originalOptions = [];
        if (devSelect) {
            originalOptions = Array.from(devSelect.options).map(opt => ({
                value: opt.value,
                text: opt.text.trim()
            }));
        }

        function toggleTanggalSelesai() {
            if (devSelect && devSelect.value === '3') { // ID 3 = Selesai
                tglSelesaiGroup.style.display = 'block';
                serverStatus.style.display = 'block';
            } else {
                tglSelesaiGroup.style.display = 'none';
                serverStatus.style.display = 'none';
            }
        }

        function rebuildDevStatusOptions(currentStatusId) {
            if (!devSelect) return;
            devSelect.innerHTML = '';

            originalOptions.forEach(opt => {
                const val = parseInt(opt.value);
                const curr = parseInt(currentStatusId);

                let show = false;
                if (curr === 1) { // Current: Not Yet
                    // Opsi: Not Yet (1), Progress (2), Hold (5)
                    show = (val === 1 || val === 2 || val === 5);
                } else if (curr === 2) { // Current: Progress
                    // Opsi: Progress (2), Done (3)
                    show = (val === 2 || val === 3);
                } else if (curr === 4) { // Current: Done - Rev
                    // Opsi: Done - Rev (4), Progress (2), Hold (5)
                    show = (val === 4 || val === 2 || val === 5);
                } else {
                    // Status lain (Done/Hold) tampilkan semua opsi
                    show = true;
                }

                if (show) {
                    const newOpt = document.createElement('option');
                    newOpt.value = opt.value;
                    newOpt.text = opt.text;
                    devSelect.appendChild(newOpt);
                }
            });

            devSelect.value = currentStatusId;
            toggleTanggalSelesai();
        }

        statusModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');
            const devstatus = button.getAttribute('data-devstatus');
            const servstatus = button.getAttribute('data-servstatus');

            document.getElementById('dataId').value = id;

            // Rebuild opsi terlebih dahulu berdasarkan status saat ini
            rebuildDevStatusOptions(devstatus);

            const servSelect = statusModal.querySelector('select[name="servstid"]');
            if (servSelect) servSelect.value = servstatus;

            const form = document.getElementById('updateStatusForm');
            form.action = `/update-statusdev/${id}`;
        });

        if (devSelect) {
            devSelect.addEventListener('change', toggleTanggalSelesai);
        }
    });
    </script>
    </html>
</x-app-layout>
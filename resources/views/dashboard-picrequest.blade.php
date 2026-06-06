<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-indigo-600 dark:text-indigo-400 text-xl leading-tight" style="font-size: 32px">
            {{ __('PIC Request Dashboard') }}
        </h2>
    </x-slot>

    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aplikasi Agenda dan Manajemen Jadwal.">
        <title>Dashboard PIC Request</title>

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
        selectedStatus: '',
        get filteredItems() {
            if (!this.selectedStatus) return this.items;
            return this.items.filter(item => {
                if (this.selectedStatus === 'hold') {
                    return item.devstid === 5;
                }
                if (this.selectedStatus === 'ready_to_test') {
                    return item.devstid === 3 && item.picreqstid === 11;
                }
                if (this.selectedStatus === 'document_required') {
                    return item.devstid === 3 && item.finalstid === 22;
                }
                if (this.selectedStatus === 'done') {
                    return item.finalstid === 19;
                }
                return true;
            });
        },
        get totalItems() {
            return this.filteredItems.length;
        },
        get totalPages() {
            return Math.ceil(this.totalItems / this.perPage);
        },
        isRowVisible(index) {
            const item = this.items[index];
            if (this.selectedStatus) {
                if (this.selectedStatus === 'hold' && item.devstid !== 5) return false;
                if (this.selectedStatus === 'ready_to_test' && !(item.devstid === 3 && item.picreqstid === 11)) return false;
                if (this.selectedStatus === 'document_required' && !(item.devstid === 3 && item.finalstid === 22)) return false;
                if (this.selectedStatus === 'done' && item.finalstid !== 19) return false;
            }
            const filteredIndex = this.filteredItems.findIndex(fi => fi.index === index);
            if (filteredIndex === -1) return false;

            const start = (this.currentPage - 1) * this.perPage;
            const end = this.currentPage * this.perPage;
            return filteredIndex >= start && filteredIndex < end;
        },
        items: [
            @foreach ($listPicReq as $index => $item)
                { 
                    index: {{ $index }}, 
                    devstid: {{ $item->devstid ?? 'null' }}, 
                    picreqstid: {{ $item->picreqstid ?? 'null' }}, 
                    finalstid: {{ $item->finalstid ?? 'null' }} 
                },
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

        <!-- Header Controls & Legend -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('dashboard.jadwal') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg shadow-sm border-0 no-underline transition-all">
                    <i class="bi bi-arrow-left"></i> Timeline Utama
                </a>
                <a href="{{ route('dashboard.daily') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-550 border border-slate-250 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-lg shadow-sm no-underline bg-white transition-all">
                    <i class="bi bi-journal-text"></i> Daily Report
                </a>
                <a href="{{ route('dashboard.weekly') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-550 border border-slate-250 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-lg shadow-sm no-underline bg-white transition-all">
                    <i class="bi bi-calendar-range"></i> Weekly Report
                </a>

                <!-- Dropdown Filter Status -->
                <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-lg px-3 py-1.5 shadow-sm">
                    <i class="bi bi-funnel text-indigo-500 text-sm"></i>
                    <select x-model="selectedStatus" @change="currentPage = 1" class="text-xs font-semibold text-slate-700 border-0 bg-transparent p-0 pr-6 focus:ring-0 cursor-pointer">
                        <option value="">Semua Status (All Filter)</option>
                        <option value="ready_to_test">Ready to Test</option>
                        <option value="hold">Hold / Pending</option>
                        <option value="document_required">Document Required</option>
                        <option value="done">Done</option>
                    </select>
                </div>
            </div>

            <!-- Table Legend -->
            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                <span class="px-2.5 py-1 rounded-full text-purple-800 bg-[#e0bbff] shadow-sm border border-purple-300">
                    Hold / Pending
                </span>
                <span class="px-2.5 py-1 rounded-full text-yellow-800 bg-[#fcffbb] shadow-sm border border-yellow-300">
                    Ready to Test
                </span>
                <span class="px-2.5 py-1 rounded-full text-orange-800 bg-[#ffb59a] shadow-sm border border-orange-300">
                    Document Required
                </span>
            </div>
        </div>

        <!-- LIST PIC REQUEST -->
        <div class="premium-card p-6">
            <div class="border-b pb-3 mb-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 m-0">
                    <i class="bi bi-person-lines-fill text-indigo-500"></i> List PIC Request
                </h3>
                <span class="text-xs font-semibold bg-indigo-50 border border-indigo-200 text-indigo-700 px-2.5 py-1 rounded-lg">
                    Total: {{ count($listPicReq) }} Request
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 align-middle">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap" style="min-width: 140px">Aksi</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">KD-List</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Jenis Task</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider min-w-[350px]">Task</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Timeline</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Deadline</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">PIC Request</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">PIC Dev</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Server Status</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">PIC Req Status</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Final Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($listPicReq as $index => $item)
                            @php
                                $rowStyle = '';
                                $rowTextClass = 'text-slate-600';
                                $kdListTextClass = 'text-slate-800 font-semibold';
                                $taskTextClass = 'text-slate-800 font-semibold';
                                if($item->devstid == 5) {
                                    $rowStyle = 'background-color: #e0bbff;';
                                    $rowTextClass = 'text-purple-900';
                                    $kdListTextClass = 'text-purple-950 font-bold';
                                    $taskTextClass = 'text-purple-950 font-semibold';
                                } elseif($item->devstid == 3 && $item->picreqstid == 11) {
                                    $rowStyle = 'background-color: #fcffbb;';
                                    $rowTextClass = 'text-yellow-900';
                                    $kdListTextClass = 'text-yellow-950 font-bold';
                                    $taskTextClass = 'text-yellow-950 font-semibold';
                                } elseif($item->devstid == 3 && $item->finalstid == 22) {
                                    $rowStyle = 'background-color: #ffb59a;';
                                    $rowTextClass = 'text-orange-900';
                                    $kdListTextClass = 'text-orange-950 font-bold';
                                    $taskTextClass = 'text-orange-950 font-semibold';
                                }
                            @endphp
                            <tr x-show="isRowVisible({{ $index }})" style="{{ $rowStyle }}" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3 w-px whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- Update Status Button -->
                                        <button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg shadow-sm border-0 transition-colors {{ ($item->finalstid == 19 || $item->finalstid == 22) ? 'bg-slate-200/50 text-slate-400 cursor-not-allowed pointer-events-none' : (($item->devstid == 5 || ($item->devstid == 3 && $item->picreqstid == 11) || ($item->devstid == 3 && $item->finalstid == 22)) ? 'bg-white/20 hover:bg-white/30 text-slate-850' : 'bg-indigo-600 hover:bg-indigo-700 text-white') }}"  
                                            data-bs-toggle="modal" 
                                            data-bs-target="#statusModal" 
                                            data-id="{{ $item->id }}"
                                            data-picreqstatus="{{ $item->picreqstid }}"
                                            data-finalstatus="{{ $item->finalstid }}"
                                            onclick="setId({{ $item->id }})"
                                            title="Edit Status"
                                            @if($item->finalstid == 19 || $item->finalstid == 22) disabled @endif>
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Upload File Button -->
                                        <button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg shadow-sm border-0 transition-colors {{ ($item->finalstid == 19 || $item->finalstid != 22) ? 'bg-slate-200/50 text-slate-400 cursor-not-allowed pointer-events-none' : (($item->devstid == 5 || ($item->devstid == 3 && $item->picreqstid == 11) || ($item->devstid == 3 && $item->finalstid == 22)) ? 'bg-white/20 hover:bg-white/30 text-slate-850' : 'bg-indigo-600 hover:bg-indigo-700 text-white') }}" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#uploadModal" 
                                            data2-id="{{ $item->id }}"
                                            onclick="setId({{ $item->id }})"
                                            title="Upload File"
                                            @if($item->finalstid == 19 || $item->finalstid != 22) disabled @endif>
                                            <i class="bi bi-cloud-upload"></i>
                                        </button>

                                        <!-- View File Link -->
                                        <a href="{{ asset(str_replace('public/storage/pdf/', 'pdf/', $item->path)) }}" 
                                            target="_blank" 
                                            title="Lihat PDF" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg shadow-sm border-0 transition-colors no-underline {{ ($item->finalstid != 19) ? 'bg-slate-200/50 text-slate-400 cursor-not-allowed pointer-events-none' : (($item->devstid == 5 || ($item->devstid == 3 && $item->picreqstid == 11) || ($item->devstid == 3 && $item->finalstid == 22)) ? 'bg-white/20 hover:bg-white/30 text-slate-850' : 'bg-indigo-600 hover:bg-indigo-700 text-white') }}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </div>
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
                                    <span class="px-2.5 py-0.5 rounded-full text-xs border {{ ($item->devstid == 5 || ($item->devstid == 3 && $item->picreqstid == 11) || ($item->devstid == 3 && $item->finalstid == 22)) ? 'bg-white/20 border-white/40' : 'bg-slate-100 border-slate-300 text-slate-800' }}">
                                        {{ $item->servstatus }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 w-px whitespace-nowrap font-semibold">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs border {{ ($item->devstid == 5 || ($item->devstid == 3 && $item->picreqstid == 11) || ($item->devstid == 3 && $item->finalstid == 22)) ? 'bg-white/20 border-white/40' : 'bg-slate-100 border-slate-300 text-slate-800' }}">
                                        {{ $item->picreqst }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 w-px whitespace-nowrap font-semibold">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs border {{ ($item->devstid == 5 || ($item->devstid == 3 && $item->picreqstid == 11) || ($item->devstid == 3 && $item->finalstid == 22)) ? 'bg-white/20 border-white/40' : 'bg-slate-100 border-slate-300 text-slate-800' }}">
                                        {{ $item->finalst }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-12 text-slate-400">
                                    <i class="bi bi-inbox text-3xl block mb-2 text-slate-300"></i>
                                    Belum ada PIC request yang terdaftar.
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

        <!-- STATUS UPDATE MODAL -->
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
                            <input type="hidden" name="id">

                            <!-- Pic Req Status -->
                            <div>
                                <label for="pic-request-status" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Pic Request Status</label>
                                <select id="pic-request-status" name="picreqstid" required class="form-select text-sm w-full border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    @foreach ($statusPicReq as $itemPicReq)
                                        <option value="{{ $itemPicReq->id }}">
                                            {{ $itemPicReq->status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Final Status -->
                            <div id="final-status-group" style="display: none;">
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Final Status</label>
                                <select name="finalstid" class="form-select text-sm w-full border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    @foreach ($statusFinal as $itemStatusFinal)
                                        <option value="{{ $itemStatusFinal->id }}">{{ $itemStatusFinal->status }}</option>
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

        <!-- UPLOAD FILE MODAL -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="uploadFiless" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content rounded-2xl overflow-hidden shadow-lg border-0">
                        <div class="modal-header bg-indigo-600 text-white py-3.5">
                            <h5 class="modal-title font-bold text-base" id="uploadModalLabel">
                                <i class="bi bi-cloud-upload"></i> Upload File UAT
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-6 space-y-4">
                            <!-- Hidden ID -->
                            <input type="hidden" name="id">

                            <div>
                                <label for="pdf_file" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                                    <i class="bi bi-file-earmark-pdf text-rose-500"></i> Pilih File PDF
                                </label>
                                <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf" required
                                    class="block w-full text-sm text-slate-700 border border-slate-300 rounded-lg cursor-pointer bg-slate-50 focus:outline-none p-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="modal-footer bg-slate-50 py-3.5">
                            <button type="button" class="btn btn-secondary text-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary text-sm bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm border-0">Upload File</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
    function setId(id) {
        const form = document.getElementById('updateStatusForm');
        const form2 = document.getElementById('uploadFiless');
        
        const inputId1 = form.querySelector('input[name="id"]');
        if (inputId1) inputId1.value = id;
        
        const inputId2 = form2.querySelector('input[name="id"]');
        if (inputId2) inputId2.value = id;

        form.action = `/update-statuspicreq/${id}`;
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

            statusModal.querySelector('input[name="id"]').value = id;

            const picReqSelect = statusModal.querySelector('select[name="picreqstid"]');
            const finalstSelect = statusModal.querySelector('select[name="finalstid"]');

            if (picReqSelect) picReqSelect.value = picreqst;
            if (finalstSelect) finalstSelect.value = finalstatus;

            if (picReqSelect) {
                picReqSelect.dispatchEvent(new Event('change'));
            }

            const form = document.getElementById('updateStatusForm');
            form.action = `/update-statuspicreq/${id}`;
        });

        uploadModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data2-id');

            uploadModal.querySelector('input[name="id"]').value = id;

            const form2 = document.getElementById('uploadFiless');
            form2.action = `/upload-pdf/${id}`;
        });

        const statusSelect = document.getElementById('pic-request-status');
        const finalStatusGroup = document.getElementById('final-status-group');

        function toggleFinalStatus() {
            if (statusSelect.value === '15') { // ID 15 = Selesai
                finalStatusGroup.style.display = 'block';
            } else {
                finalStatusGroup.style.display = 'none';
            }
        }

        toggleFinalStatus();
        statusSelect.addEventListener('change', toggleFinalStatus);
    });
    </script>
    </html>
</x-app-layout>
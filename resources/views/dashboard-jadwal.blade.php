<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-indigo-600 dark:text-indigo-400 text-xl leading-tight" style="font-size: 32px">
            {{ __('Timeline Request Management') }}
        </h2>
    </x-slot>

    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aplikasi Agenda dan Manajemen Jadwal.">
        <title>Dashboard Timeline</title>

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
        totalItems: {{ count($daftarJadwal) }},
        get totalPages() { return Math.ceil(this.totalItems / this.perPage); },
        showSiteHeader(index, siteName) {
            const start = (this.currentPage - 1) * this.perPage;
            const end = this.currentPage * this.perPage;
            
            if (index < start || index >= end) return false;
            if (index === start) return true;
            
            return this.items[index - 1].siteName !== siteName;
        },
        items: [
            @foreach ($daftarJadwal as $index => $item)
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LIST TIMELINE REQUESTS -->
            <div class="lg:col-span-2 space-y-6">
                <div class="premium-card p-6">
                    <div class="border-b pb-3 mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 m-0">
                            <i class="bi bi-clock-history text-indigo-500"></i> Daftar Timeline Request
                        </h3>
                        <span class="text-xs font-semibold bg-indigo-50 border border-indigo-200 text-indigo-700 px-2.5 py-1 rounded-lg">
                            Total: {{ count($daftarJadwal) }} Request
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 align-middle">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Aksi</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">KD-List / Prioritas</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Jenis Task / PIC Dev</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider min-w-[350px]">Task</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Timeline</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Tgl Masuk</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-px whitespace-nowrap">Tgl Deadline</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white text-sm">
                                @forelse ($daftarJadwal as $index => $item)
                                    <!-- Site Group Header Row -->
                                    <tr x-show="showSiteHeader({{ $index }}, '{{ addslashes($item->namasite) }}')" class="bg-indigo-50/30">
                                        <td colspan="7" class="px-4 py-2.5 bg-indigo-50/80 border-y border-indigo-100/50 text-indigo-700 font-bold text-xs uppercase tracking-wider">
                                            <div class="flex items-center gap-1.5">
                                                <i class="bi bi-geo-alt-fill text-indigo-500"></i> Site: {{ $item->namasite }}
                                            </div>
                                        </td>
                                    </tr>

                                    <tr x-show="Math.floor({{ $index }} / perPage) + 1 === currentPage" class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-3 w-px whitespace-nowrap">
                                            <div class="flex items-center gap-1.5">
                                                @if(trim(strtolower($item->devstatus ?? '')) === 'not yet')
                                                    <button type="button" 
                                                        class="inline-flex items-center justify-center w-7 h-7 bg-amber-500 hover:bg-amber-600 text-white rounded-lg shadow-sm border-0 transition-colors"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editJadwalModal"
                                                        onclick="setEditJadwalData('{{ $item->id }}', '{{ $item->prioritas_id }}', '{{ $item->jenistask_id }}', '{{ $item->site_id }}', '{{ $item->timeline_id }}', '{{ $item->tgl_masuk }}', '{{ $item->tgl_deadline }}', '{{ addslashes($item->task) }}', '{{ $item->picrequest_id }}', '{{ $item->picdeveloper_id }}')"
                                                        title="Edit Request">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <a href="{{ route('jadwal.delete', $item->id) }}" 
                                                       class="inline-flex items-center justify-center w-7 h-7 bg-rose-500 hover:bg-rose-600 text-white rounded-lg shadow-sm border-0 transition-colors"
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus request ini?')"
                                                       title="Hapus Request">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                @else
                                                    <span class="text-[10px] text-slate-400 font-bold bg-slate-100 border border-slate-200 px-1.5 py-0.5 rounded uppercase tracking-wider">Processed</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 w-px whitespace-nowrap">
                                            <div class="font-semibold text-slate-800">{{ $item->kd_list }}</div>
                                            <div class="mt-1">
                                                @php
                                                    $priName = trim($item->namaprioritas ?? '');
                                                    $priClass = match($priName) {
                                                        'Urgent' => 'bg-rose-100 text-rose-800 border-rose-200',
                                                        'High' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                        'Medium' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                        default => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                    };
                                                @endphp
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $priClass }}">
                                                    {{ $priName }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 w-px whitespace-nowrap">
                                            <div class="text-slate-600 font-medium text-xs">{{ $item->jenistask }}</div>
                                            <div class="text-indigo-600 font-semibold text-xs mt-0.5">
                                                <i class="bi bi-person-gear text-indigo-400"></i> {{ $item->pic_developer ?? 'Belum ada' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-800 leading-relaxed min-w-[350px] break-words">{{ $item->task }}</div>
                                        </td>
                                        <td class="px-4 py-3 w-px whitespace-nowrap text-slate-600 font-medium">{{ $item->gabung }}</td>
                                        <td class="px-4 py-3 w-px whitespace-nowrap text-slate-600 font-medium">
                                            {{ $item->tgl_masuk ? \Carbon\Carbon::parse($item->tgl_masuk)->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 w-px whitespace-nowrap text-slate-600 font-semibold">
                                            {{ $item->tgl_deadline ? \Carbon\Carbon::parse($item->tgl_deadline)->format('d M Y') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-12 text-slate-400">
                                            <i class="bi bi-inbox text-3xl block mb-2 text-slate-300"></i>
                                            Belum ada timeline request yang terdaftar.
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
            </div>

            <!-- CREATE REQUEST FORM -->
            <div class="col-span-1">
                <form method="POST" action="{{ route('dashboard.jadwalpost') }}" class="premium-card p-6 space-y-4 hover-lift">
                    @csrf
                    <div class="border-b pb-3 flex items-center gap-2">
                        <i class="bi bi-file-earmark-plus text-indigo-500 text-xl"></i>
                        <h3 class="text-lg font-bold text-slate-800 m-0 font-sans">Tambah Request Baru</h3>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Prioritas</label>
                        <select name="prioritas" required class="form-select text-sm mt-1 border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @foreach ($prioritas as $itemprioritas)
                                <option value="{{ $itemprioritas->id }}">{{ $itemprioritas->namaprioritas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Jenis Task</label>
                        <select name="jenistask" required class="form-select text-sm mt-1 border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @foreach ($jenisTask as $itemJenisTask)
                                <option value="{{ $itemJenisTask->id }}">{{ $itemJenisTask->jenistask }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Site</label>
                        <select name="site" required class="form-select text-sm mt-1 border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @foreach ($site as $itemSite)
                                <option value="{{ $itemSite->id }}">{{ $itemSite->namasite }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Pengerjaan</label>
                        <select name="timeline" id="timelineSelect" required class="form-select text-sm mt-1 border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @foreach ($timeline as $itemTimeline)
                                <option data-deadline="{{ \Carbon\Carbon::parse($itemTimeline->tgl_deadline)->format('Y-m-d') }}" value="{{ $itemTimeline->id }}">{{ $itemTimeline->week }} {{ $itemTimeline->month }} {{ $itemTimeline->year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Task Masuk</label>
                            <input type="date" name="task_masuk" required class="form-control text-sm mt-1 border-slate-300 rounded-lg" value="{{ old('task_masuk', date('Y-m-d')) }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Task Deadline</label>
                            <input type="date" name="task_deadline" id="taskDeadline" required class="form-control text-sm mt-1 border-slate-300 rounded-lg" 
                                value="{{ old('task_deadline', isset($selectedTimeline) ? \Carbon\Carbon::parse($selectedTimeline->tgl_deadline)->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Task</label>
                        <textarea name="task" required class="form-control text-sm mt-1 border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" rows="3" placeholder="Isi Task..."></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">PIC Request</label>
                        <select name="picrequest" required class="form-select text-sm mt-1 border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @foreach ($picReq as $itemPicReq)
                                <option value="{{ $itemPicReq->id }}">{{ $itemPicReq->namalengkap }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">PIC Developer</label>
                        <select name="pegawai" required class="form-select text-sm mt-1 border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @foreach ($pegawai as $pagawe)
                                <option value="{{ $pagawe->id }}">{{ $pagawe->kdjenispegawai }} - {{ $pagawe->namapegawai }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Hidden fields wrapper -->
                    <div style="display: none">
                        <input type="date" name="tgl_selesai">
                        <select name="devst">
                            @foreach ($statusDev as $itemStatusDev)
                                <option value="{{ $itemStatusDev->id }}">{{ $itemStatusDev->status }}</option>
                            @endforeach
                        </select>
                        <select name="servetst">
                            @foreach ($statusServer as $itemStatusServer)
                                <option value="{{ $itemStatusServer->id }}">{{ $itemStatusServer->status }}</option>
                            @endforeach
                        </select>
                        <select name="picreqst">
                            @foreach ($statusPicReq as $itemStatusPicReq)
                                <option value="{{ $itemStatusPicReq->id }}">{{ $itemStatusPicReq->status }}</option>
                            @endforeach
                        </select>
                        <select name="finalst">
                            @foreach ($statusFinal as $itemStatusFinal)
                                <option value="{{ $itemStatusFinal->id }}">{{ $itemStatusFinal->status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-all border-0">
                        <i class="bi bi-save"></i> Simpan Request
                    </button>

                    <!-- Navigation Links -->
                    <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                        <a href="{{ route('dashboard.dev') }}" class="w-full flex items-center justify-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition-all border-0 no-underline">
                            <i class="bi bi-person-workspace text-slate-500"></i> Dashboard Developer
                        </a>
                        <a href="{{ route('dashboard.picreq') }}" class="w-full flex items-center justify-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition-all border-0 no-underline">
                            <i class="bi bi-person-lines-fill text-slate-500"></i> Dashboard PIC Request
                        </a>
                    </div>
                </form>
            </div>

        </div>

        <!-- EDIT JADWAL MODAL -->
        <div class="modal fade" id="editJadwalModal" tabindex="-1" aria-labelledby="editJadwalModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="editJadwalForm" method="POST">
                    @csrf
                    <div class="modal-content rounded-2xl overflow-hidden shadow-lg border-0">
                        <div class="modal-header bg-indigo-600 text-white py-3.5">
                            <h5 class="modal-title font-bold text-base" id="editJadwalModalLabel">
                                <i class="bi bi-pencil-square"></i> Edit Timeline Request
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-6 space-y-4">
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Prioritas</label>
                                <select name="prioritas_id" id="editPrioritasId" required class="form-select text-sm mt-1 border-slate-300 rounded-lg">
                                    @foreach ($prioritas as $itemprioritas)
                                        <option value="{{ $itemprioritas->id }}">{{ $itemprioritas->namaprioritas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Jenis Task</label>
                                <select name="jenistask_id" id="editJenistaskId" required class="form-select text-sm mt-1 border-slate-300 rounded-lg">
                                    @foreach ($jenisTask as $itemJenisTask)
                                        <option value="{{ $itemJenisTask->id }}">{{ $itemJenisTask->jenistask }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Site</label>
                                <select name="site_id" id="editSiteId" required class="form-select text-sm mt-1 border-slate-300 rounded-lg">
                                    @foreach ($site as $itemSite)
                                        <option value="{{ $itemSite->id }}">{{ $itemSite->namasite }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Pengerjaan</label>
                                <select name="timeline_id" id="editTimelineId" required class="form-select text-sm mt-1 border-slate-300 rounded-lg">
                                    @foreach ($timeline as $itemTimeline)
                                        <option data-deadline="{{ \Carbon\Carbon::parse($itemTimeline->tgl_deadline)->format('Y-m-d') }}" value="{{ $itemTimeline->id }}">{{ $itemTimeline->week }} {{ $itemTimeline->month }} {{ $itemTimeline->year }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Task Masuk</label>
                                    <input type="date" name="tgl_masuk" id="editTglMasuk" required class="form-control text-sm mt-1 border-slate-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Task Deadline</label>
                                    <input type="date" name="tgl_deadline" id="editTglDeadline" required class="form-control text-sm mt-1 border-slate-300 rounded-lg">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Task</label>
                                <textarea name="task" id="editTask" required class="form-control text-sm mt-1 border-slate-300 rounded-lg" rows="3.5" placeholder="Tulis detail request..."></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">PIC Request</label>
                                <select name="picrequest_id" id="editPicRequestId" required class="form-select text-sm mt-1 border-slate-300 rounded-lg">
                                    @foreach ($picReq as $itemPicReq)
                                        <option value="{{ $itemPicReq->id }}">{{ $itemPicReq->namalengkap }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">PIC Developer</label>
                                <select name="picdeveloper_id" id="editPicDeveloperId" required class="form-select text-sm mt-1 border-slate-300 rounded-lg">
                                    <option value="" disabled selected>-- Pilih PIC Developer --</option>
                                    @foreach ($pegawai as $pagawe)
                                        <option value="{{ $pagawe->id }}">{{ $pagawe->namapegawai }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer bg-slate-50 py-3.5">
                            <button type="button" class="btn btn-secondary text-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary text-sm bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm border-0">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
    document.getElementById('timelineSelect').addEventListener('change', function () { 
        const selectedOption = this.options[this.selectedIndex];
        const deadline = selectedOption.getAttribute('data-deadline');

        if (deadline) {
            document.getElementById('taskDeadline').value = deadline;
        }
    });

    document.getElementById('editTimelineId').addEventListener('change', function () { 
        const selectedOption = this.options[this.selectedIndex];
        const deadline = selectedOption.getAttribute('data-deadline');

        if (deadline) {
            document.getElementById('editTglDeadline').value = deadline;
        }
    });

    function setEditJadwalData(id, prioritasId, jenistaskId, siteId, timelineId, tglMasuk, tglDeadline, task, picrequestId, picdeveloperId) {
        const form = document.getElementById('editJadwalForm');
        form.action = `/update-jadwal/${id}`;

        document.getElementById('editPrioritasId').value = prioritasId;
        document.getElementById('editJenistaskId').value = jenistaskId;
        document.getElementById('editSiteId').value = siteId;
        document.getElementById('editTimelineId').value = timelineId;
        
        // Parse date strings to YYYY-MM-DD for input[type="date"]
        document.getElementById('editTglMasuk').value = tglMasuk ? tglMasuk.split(' ')[0] : '';
        document.getElementById('editTglDeadline').value = tglDeadline ? tglDeadline.split(' ')[0] : '';
        
        document.getElementById('editTask').value = task;
        document.getElementById('editPicRequestId').value = picrequestId;
        document.getElementById('editPicDeveloperId').value = picdeveloperId;
    }
    </script>
    </html>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-indigo-600 dark:text-indigo-400 text-xl leading-tight" style="font-size: 32px">
            {{ __('Project Activity Management') }}
        </h2>
    </x-slot>

    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Project Activity</title>

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

    <div class="p-6 max-w-7xl mx-auto space-y-6">

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
            
            <!-- LIST ACTIVITIES -->
            <div class="lg:col-span-2 space-y-6">
                <div class="premium-card p-6">
                    <div class="border-b pb-3 mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 m-0">
                            <i class="bi bi-activity text-indigo-500"></i> Daftar Project Activity
                        </h3>
                        <span class="text-xs font-semibold bg-indigo-50 border border-indigo-200 text-indigo-700 px-2.5 py-1 rounded-lg">
                            Total: {{ $projectActivities->count() }} Kegiatan
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 align-middle">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Aksi</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Task</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Site / PIC</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Tanggal Masuk</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Deadline</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Prioritas</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white text-sm">
                                @forelse ($projectActivities as $pa)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-1.5">
                                                @if(trim($pa->status) !== 'Done')
                                                    <button type="button" 
                                                        class="inline-flex items-center justify-center w-7 h-7 bg-amber-500 hover:bg-amber-600 text-white rounded-lg shadow-sm border-0 transition-colors"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editActivityModal"
                                                        onclick="setEditData('{{ $pa->id }}', '{{ $pa->prioritas_id }}', '{{ $pa->site_id }}', '{{ $pa->pic_id }}', '{{ $pa->tgl_masuk }}', '{{ $pa->tgl_deadline }}', '{{ addslashes($pa->task) }}', '{{ $pa->status }}')"
                                                        title="Edit Activity">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('project.activity.delete', $pa->id) }}" 
                                                   class="inline-flex items-center justify-center w-7 h-7 bg-rose-500 hover:bg-rose-600 text-white rounded-lg shadow-sm border-0 transition-colors"
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus activity ini?')"
                                                   title="Hapus Activity">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-800 leading-relaxed max-w-[280px] break-words">{{ $pa->task }}</div>
                                        </td>
                                        <td class="px-4 py-3 space-y-1">
                                            <div>
                                                <span class="text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded">
                                                    {{ $pa->site->namasite ?? '-' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-1 mt-1">
                                                <i class="bi bi-person text-slate-400"></i>
                                                <span class="text-xs text-slate-600 font-medium">{{ $pa->pic->namapegawai ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-slate-600 font-medium">
                                            {{ \Carbon\Carbon::parse($pa->tgl_masuk)->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap font-semibold">
                                            @php
                                                $isOverdue = (strtotime($pa->tgl_deadline) < time());
                                            @endphp
                                            <div class="flex flex-col gap-0.5">
                                                <span class="{{ $isOverdue ? 'text-rose-600' : 'text-slate-700' }}">
                                                    {{ \Carbon\Carbon::parse($pa->tgl_deadline)->format('d M Y') }}
                                                </span>
                                                @if($isOverdue)
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[9px] font-bold bg-rose-100 text-rose-700 border border-rose-200 self-start">
                                                        OVERDUE
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @php
                                                $priName = trim($pa->prioritas->namaprioritas ?? '');
                                                $priClass = match($priName) {
                                                    'Urgent' => 'bg-rose-100 text-rose-800 border-rose-200',
                                                    'High' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                    'Medium' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                    default => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $priClass }}">
                                                {{ $priName }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @php
                                                $statName = trim($pa->status ?? 'Open');
                                                $statClass = match($statName) {
                                                    'Done' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    default => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statClass }}">
                                                {{ $statName }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-12 text-slate-400">
                                            <i class="bi bi-inbox text-3xl block mb-2 text-slate-300"></i>
                                            Belum ada project activity yang terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CREATE ACTIVITY FORM -->
            <div class="col-span-1">
                <form method="POST" action="{{ route('project.activity.save') }}" class="premium-card p-6 space-y-4 hover-lift">
                    @csrf
                    <div class="border-b pb-3 flex items-center gap-2">
                        <i class="bi bi-file-earmark-plus text-indigo-500 text-xl"></i>
                        <h3 class="text-lg font-bold text-slate-800 m-0 font-sans">Tambah Activity Baru</h3>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Prioritas</label>
                        <select name="prioritas_id" required class="form-select text-sm mt-1 border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih Prioritas --</option>
                            @foreach ($priorities as $p)
                                <option value="{{ $p->id }}">{{ $p->namaprioritas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Site</label>
                        <select name="site_id" required class="form-select text-sm mt-1 border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih Site --</option>
                            @foreach ($sites as $s)
                                <option value="{{ $s->id }}">{{ $s->namasite }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">PIC</label>
                        <select name="pic_id" required class="form-select text-sm mt-1 border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih PIC --</option>
                            @foreach ($pics as $pic)
                                <option value="{{ $pic->id }}">{{ $pic->namapegawai }} ({{ $pic->jenispegawai }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal Masuk</label>
                            <input type="date" name="tgl_masuk" required class="form-control text-sm mt-1 border-slate-300 rounded-lg" value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Deadline</label>
                            <input type="date" name="tgl_deadline" required class="form-control text-sm mt-1 border-slate-300 rounded-lg" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Status</label>
                        <select name="status" required class="form-select text-sm mt-1 border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="Open">Open</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Task</label>
                        <textarea name="task" required class="form-control text-sm mt-1 border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" rows="3.5" placeholder="Tulis detail aktifitas/tugas..."></textarea>
                    </div>

                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-all border-0">
                        <i class="bi bi-save"></i> Simpan Activity
                    </button>
                </form>
            </div>

        </div>

        <!-- EDIT ACTIVITY MODAL -->
        <div class="modal fade" id="editActivityModal" tabindex="-1" aria-labelledby="editActivityModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="editActivityForm" method="POST">
                    @csrf
                    <div class="modal-content rounded-2xl overflow-hidden shadow-lg border-0">
                        <div class="modal-header bg-indigo-600 text-white py-3.5">
                            <h5 class="modal-title font-bold text-base" id="editActivityModalLabel">
                                <i class="bi bi-pencil-square"></i> Edit Project Activity
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-6 space-y-4">
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Prioritas</label>
                                <select name="prioritas_id" id="editPrioritasId" required class="form-select text-sm mt-1 border-slate-300 rounded-lg">
                                    @foreach ($priorities as $p)
                                        <option value="{{ $p->id }}">{{ $p->namaprioritas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Site</label>
                                <select name="site_id" id="editSiteId" required class="form-select text-sm mt-1 border-slate-300 rounded-lg">
                                    @foreach ($sites as $s)
                                        <option value="{{ $s->id }}">{{ $s->namasite }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">PIC</label>
                                <select name="pic_id" id="editPicId" required class="form-select text-sm mt-1 border-slate-300 rounded-lg">
                                    @foreach ($pics as $pic)
                                        <option value="{{ $pic->id }}">{{ $pic->namapegawai }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal Masuk</label>
                                    <input type="date" name="tgl_masuk" id="editTglMasuk" required class="form-control text-sm mt-1 border-slate-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Deadline</label>
                                    <input type="date" name="tgl_deadline" id="editTglDeadline" required class="form-control text-sm mt-1 border-slate-300 rounded-lg">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Status</label>
                                <select name="status" id="editStatus" required class="form-select text-sm mt-1 border-slate-300 rounded-lg">
                                    <option value="Open">Open</option>
                                    <option value="Done">Done</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Task</label>
                                <textarea name="task" id="editTask" required class="form-control text-sm mt-1 border-slate-300 rounded-lg" rows="4" placeholder="Tulis detail aktifitas/tugas..."></textarea>
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
        function setEditData(id, prioritasId, siteId, picId, tglMasuk, tglDeadline, task, status) {
            const form = document.getElementById('editActivityForm');
            form.action = `/update-project-activity/${id}`;

            document.getElementById('editPrioritasId').value = prioritasId;
            document.getElementById('editSiteId').value = siteId;
            document.getElementById('editPicId').value = picId;
            document.getElementById('editTglMasuk').value = tglMasuk;
            document.getElementById('editTglDeadline').value = tglDeadline;
            document.getElementById('editTask').value = task;
            document.getElementById('editStatus').value = status;
        }
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-slate-800 dark:text-slate-200 leading-tight flex items-center gap-2">
            <i class="bi bi-grid-3x3-gap-fill text-indigo-650 dark:text-indigo-400"></i>
            Master WBS Template
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">
        @if (session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-250 dark:border-emerald-850 text-emerald-800 dark:text-emerald-300 rounded-2xl text-sm font-semibold flex items-center gap-2 shadow-xs">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- LEFT PANEL: ADD FORM -->
            <div class="glass-card p-6 h-fit">
                <h3 class="text-base font-extrabold text-slate-850 dark:text-white flex items-center gap-1.5 mb-4 border-b pb-2">
                    <i class="bi bi-plus-circle text-indigo-500"></i> Tambah Master Task
                </h3>
                <form action="{{ route('wbs.master.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Jenis Struktur</label>
                        <select name="jenis_struktur" class="form-select text-sm w-full rounded-xl border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="" disabled selected>-- Pilih Struktur --</option>
                            <option value="Kick-Off Meeting">Kick-Off Meeting</option>
                            <option value="Master Data">Master Data</option>
                            <option value="Assesment">Assesment</option>
                            <option value="Instalasi Sistem">Instalasi Sistem</option>
                            <option value="Training">Training</option>
                            <option value="Bridging">Bridging</option>
                            <option value="Development & Quick Customize">Development & Quick Customize</option>
                            <option value="Go - Live">Go - Live</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">WBS Code (e.g. 1.1, 2.1.1)</label>
                        <input type="text" name="wbs_code" placeholder="Contoh: 1.1" class="form-control text-sm w-full rounded-xl border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-white" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Detail Task</label>
                        <textarea name="detail_task" rows="2" placeholder="Detail deskripsi kegiatan..." class="form-control text-sm w-full rounded-xl border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-white" required></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Task To</label>
                        <select name="task_to" class="form-select text-sm w-full rounded-xl border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="Vendor">Vendor</option>
                            <option value="Client">Client</option>
                            <option value="Both" selected>Both</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Order Number</label>
                        <input type="number" name="order_num" value="{{ ($masterWbs->max('order_num') ?? 0) + 1 }}" class="form-control text-sm w-full rounded-xl border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-white" required>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-750 text-white font-extrabold text-xs rounded-xl border-0 shadow-sm transition-colors uppercase tracking-wider">
                        Simpan Task Template
                    </button>
                </form>
            </div>

            <!-- RIGHT PANEL: LIST -->
            <div class="glass-card p-6 lg:col-span-2 flex flex-col h-[700px]">
                <h3 class="text-base font-extrabold text-slate-850 dark:text-white flex items-center gap-1.5 mb-4 border-b pb-2 flex-shrink-0">
                    <i class="bi bi-list-task text-indigo-500"></i> List Template WBS ({{ $masterWbs->count() }} Items)
                </h3>
                
                <div class="flex-grow overflow-y-auto pr-1 custom-scrollbar">
                    <div class="overflow-x-auto">
                        <table class="table align-middle text-sm text-slate-700 dark:text-slate-300 w-full">
                            <thead>
                                <tr class="text-xs font-extrabold text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                                    <th class="py-2.5">WBS Code</th>
                                    <th>Struktur</th>
                                    <th>Detail Task</th>
                                    <th>Task To</th>
                                    <th>Order</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($masterWbs as $wbs)
                                    <tr class="border-b border-slate-100 dark:border-slate-800/50 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                                        <td class="font-bold text-slate-900 dark:text-white py-3">{{ $wbs->wbs_code }}</td>
                                        <td>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                {{ $wbs->jenis_struktur }}
                                            </span>
                                        </td>
                                        <td class="font-semibold text-xs leading-relaxed max-w-[200px] truncate" title="{{ $wbs->detail_task }}">{{ $wbs->detail_task }}</td>
                                        <td>
                                            <span class="inline-flex items-center gap-1 text-[10px] font-extrabold px-2 py-0.5 rounded
                                                {{ $wbs->task_to === 'Vendor' ? 'bg-amber-50 text-amber-700 border border-amber-200' : ($wbs->task_to === 'Client' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-indigo-50 text-indigo-750 border border-indigo-200') }}">
                                                {{ $wbs->task_to }}
                                            </span>
                                        </td>
                                        <td class="font-mono text-xs">{{ $wbs->order_num }}</td>
                                        <td class="text-end">
                                            <div class="flex gap-1.5 justify-end">
                                                <button type="button" 
                                                        onclick="openEditModal('{{ $wbs->id }}', '{{ $wbs->jenis_struktur }}', '{{ $wbs->wbs_code }}', '{{ addslashes($wbs->detail_task) }}', '{{ $wbs->task_to }}', '{{ $wbs->order_num }}')" 
                                                        class="px-2 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded border-0 transition-colors text-xs font-semibold flex items-center gap-1">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </button>
                                                <form action="{{ route('wbs.master.destroy', $wbs->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus task template ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded border-0 transition-colors text-xs font-semibold flex items-center gap-1">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-12 text-slate-500">
                                            <i class="bi bi-info-circle text-2xl block mb-1"></i>
                                            <span class="text-xs font-semibold">Belum ada template WBS. Silakan tambah data.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT MASTER TASK -->
    <dialog id="edit-master-modal" class="p-0 rounded-2xl border-0 shadow-2xl w-full max-w-sm bg-white dark:bg-slate-900 dark:text-white">
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between border-b pb-3 border-slate-100 dark:border-slate-800">
                <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-1.5 m-0">
                    <i class="bi bi-pencil-square text-indigo-500"></i> Edit Master Task
                </h3>
                <button type="button" onclick="document.getElementById('edit-master-modal').close()" class="bg-transparent border-0 text-slate-400 hover:text-slate-750 text-xl font-bold p-0 leading-none">&times;</button>
            </div>
            
            <form id="edit-master-form" method="POST" action="" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Jenis Struktur</label>
                    <select name="jenis_struktur" id="edit-jenis-struktur" class="form-select text-sm w-full rounded-xl border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-white" required>
                        <option value="Kick-Off Meeting">Kick-Off Meeting</option>
                        <option value="Master Data">Master Data</option>
                        <option value="Assesment">Assesment</option>
                        <option value="Instalasi Sistem">Instalasi Sistem</option>
                        <option value="Training">Training</option>
                        <option value="Bridging">Bridging</option>
                        <option value="Development & Quick Customize">Development & Quick Customize</option>
                        <option value="Go - Live">Go - Live</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">WBS Code</label>
                    <input type="text" name="wbs_code" id="edit-wbs-code" class="form-control text-sm w-full rounded-xl border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-white" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Detail Task</label>
                    <textarea name="detail_task" id="edit-detail-task" rows="2" class="form-control text-sm w-full rounded-xl border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-white" required></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Task To</label>
                    <select name="task_to" id="edit-task-to" class="form-select text-sm w-full rounded-xl border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-white" required>
                        <option value="Vendor">Vendor</option>
                        <option value="Client">Client</option>
                        <option value="Both">Both</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Order Number</label>
                    <input type="number" name="order_num" id="edit-order-num" class="form-control text-sm w-full rounded-xl border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-white" required>
                </div>

                <div class="flex items-center justify-end gap-2 border-t pt-3 border-slate-100 dark:border-slate-800">
                    <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg border-0 shadow-sm uppercase tracking-wider">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="document.getElementById('edit-master-modal').close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-750 dark:bg-slate-800 dark:text-slate-300 text-xs font-semibold rounded-lg border-0 shadow-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function openEditModal(id, jenis, code, detail, to, order) {
            const form = document.getElementById('edit-master-form');
            form.action = `/wbs-master/` + id;
            
            document.getElementById('edit-jenis-struktur').value = jenis;
            document.getElementById('edit-wbs-code').value = code;
            document.getElementById('edit-detail-task').value = detail;
            document.getElementById('edit-task-to').value = to;
            document.getElementById('edit-order-num').value = order;
            
            const dialog = document.getElementById('edit-master-modal');
            dialog.showModal();
        }
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-slate-800 dark:text-slate-200 leading-tight flex items-center gap-2">
            <i class="bi bi-ui-checks text-indigo-650 dark:text-indigo-400"></i>
            Master Modul & Checklist Mapping
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6" x-data="{ selectedModulId: '{{ $moduls->first()?->id ?? '' }}' }">

        <!-- Toast Alerts -->
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- CREATE MODULE FORM -->
            <div class="col-span-1">
                <form method="POST" action="{{ route('modul.master.store') }}" class="premium-card p-6 space-y-4 dark:bg-slate-900 dark:border-slate-800">
                    @csrf
                    <div class="border-b pb-3 flex items-center gap-2 border-slate-105 dark:border-slate-800">
                        <i class="bi bi-folder-plus text-indigo-500 text-xl"></i>
                        <h3 class="text-base font-bold text-slate-800 m-0 dark:text-white font-sans">Tambah Modul Baru</h3>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Nama Modul</label>
                        <input type="text" name="nama_modul" required class="form-control text-sm w-full border-slate-300 dark:border-slate-700 dark:bg-slate-855 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: Farmasi, Rawat Jalan">
                    </div>

                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2.5 bg-indigo-650 hover:bg-indigo-755 text-white text-xs font-extrabold rounded-xl shadow-sm transition-all border-0 uppercase tracking-wider">
                        <i class="bi bi-save"></i> Simpan Modul
                    </button>
                </form>
            </div>

            <!-- MODULES & CHECKLIST DETAILS LIST -->
            <div class="lg:col-span-2 space-y-6">
                <div class="premium-card p-6 dark:bg-slate-900 dark:border-slate-800">
                    <div class="border-b pb-3 mb-4 flex flex-col sm:flex-row sm:items-center justify-between border-slate-105 dark:border-slate-800 gap-3">
                        <h3 class="text-base font-extrabold text-slate-800 dark:text-white flex items-center gap-2 m-0">
                            <i class="bi bi-ui-checks text-indigo-500"></i> Detail Checklist Modul
                        </h3>
                        <div class="flex items-center gap-2 min-w-[200px]">
                            <span class="text-xs font-bold text-slate-550 whitespace-nowrap"><i class="bi bi-folder-check text-indigo-550"></i> Modul:</span>
                            <select x-model="selectedModulId" class="form-select form-select-sm border-slate-300 dark:border-slate-700 dark:bg-slate-850 dark:text-slate-105 focus:border-indigo-500 focus:ring-indigo-500 text-xs py-1 px-2.5 rounded-lg shadow-xs font-bold w-full">
                                <option value="">-- Pilih Modul --</option>
                                @foreach($moduls as $modul)
                                    <option value="{{ $modul->id }}">{{ $modul->nama_modul }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @forelse($moduls as $modul)
                            <div x-show="selectedModulId === '{{ $modul->id }}'" class="border border-slate-200 dark:border-slate-850 rounded-2xl overflow-hidden shadow-xs hover:border-slate-350 transition-colors">
                                <!-- Module Title Bar -->
                                <div class="bg-slate-50 dark:bg-slate-800/60 px-4 py-3 flex items-center justify-between border-b border-slate-200 dark:border-slate-800">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-folder-fill text-indigo-550"></i>
                                        <span class="text-sm font-black text-slate-800 dark:text-slate-100">{{ $modul->nama_modul }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <!-- Add Detail Trigger -->
                                        <button type="button" 
                                                class="px-2.5 py-1.5 bg-indigo-650 hover:bg-indigo-755 text-white font-bold rounded-lg text-[10px] border-0 transition-colors flex items-center gap-1"
                                                onclick="setAddDetailModal('{{ $modul->id }}', '{{ addslashes($modul->nama_modul) }}')">
                                            <i class="bi bi-plus-circle"></i> Tambah Item
                                        </button>
                                        <!-- Edit Modul Trigger -->
                                        <button type="button" 
                                                class="p-1 bg-amber-500 hover:bg-amber-600 text-white rounded-lg border-0 transition-colors w-7 h-7 flex items-center justify-center shadow-xs"
                                                onclick="setEditModulData('{{ $modul->id }}', '{{ addslashes($modul->nama_modul) }}')">
                                            <i class="bi bi-pencil text-xs"></i>
                                        </button>
                                        <!-- Delete Modul -->
                                        <a href="{{ route('modul.master.delete', $modul->id) }}" 
                                           class="p-1 bg-rose-500 hover:bg-rose-600 text-white rounded-lg border-0 transition-colors w-7 h-7 flex items-center justify-center shadow-xs no-underline"
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus modul ini beserta seluruh checklist detailnya?')">
                                            <i class="bi bi-trash text-xs"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- Checklist items under module -->
                                <div class="p-4 bg-white dark:bg-slate-900">
                                    <table class="w-full text-xs text-left align-middle">
                                        <thead>
                                            <tr class="text-slate-400 font-bold uppercase border-b pb-2 text-[10px]">
                                                <th class="py-2 w-10 text-center">No</th>
                                                <th class="py-2">Item Pekerjaan Checklist</th>
                                                <th class="py-2 w-24 text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($modul->details as $idx => $detail)
                                                <tr class="border-b border-slate-50 dark:border-slate-800/60 hover:bg-slate-50/50 dark:hover:bg-slate-850/30">
                                                    <td class="py-2.5 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                                    <td class="py-2.5 font-semibold text-slate-800 dark:text-slate-200">{{ $detail->nama_detail }}</td>
                                                    <td class="py-2.5 text-center">
                                                        <div class="flex items-center justify-center gap-1.5">
                                                            <!-- Edit Detail Trigger -->
                                                            <button type="button" 
                                                                    class="p-1 bg-amber-500 hover:bg-amber-600 text-white rounded-md border-0 w-6 h-6 flex items-center justify-center shadow-xs"
                                                                    onclick="setEditDetailData('{{ $detail->id }}', '{{ addslashes($detail->nama_detail) }}')">
                                                                <i class="bi bi-pencil text-[10px]"></i>
                                                            </button>
                                                            <!-- Delete Detail -->
                                                            <a href="{{ route('modul.master.detail.delete', $detail->id) }}" 
                                                               class="p-1 bg-rose-500 hover:bg-rose-600 text-white rounded-md border-0 w-6 h-6 flex items-center justify-center shadow-xs no-underline"
                                                               onclick="return confirm('Hapus item checklist ini?')">
                                                                <i class="bi bi-trash text-[10px]"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4 text-slate-400 italic">
                                                        Belum ada item checklist. Silakan tambahkan item checklist di modul ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-slate-400">
                                <i class="bi bi-inbox text-4xl block mb-2 text-slate-300"></i>
                                Belum ada master modul terdaftar.
                            </div>
                        @endforelse

                        <!-- No Module Selected Placeholder -->
                        <div x-show="!selectedModulId" class="glass-card p-12 text-center text-slate-500">
                            <i class="bi bi-folder2-open text-4xl block mb-2 text-slate-350"></i>
                            <span class="text-xs font-bold">Silakan pilih modul terlebih dahulu dari dropdown.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT MODUL -->
    <dialog id="edit-modul-modal" class="p-0 rounded-2xl border-0 shadow-2xl w-full max-w-sm bg-white dark:bg-slate-900 dark:text-white">
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between border-b pb-3 border-slate-100 dark:border-slate-800">
                <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-1.5 m-0">
                    <i class="bi bi-pencil-square text-indigo-550"></i> Edit Nama Modul
                </h3>
                <button type="button" onclick="document.getElementById('edit-modul-modal').close()" class="bg-transparent border-0 text-slate-400 hover:text-slate-750 text-xl font-bold p-0 leading-none">&times;</button>
            </div>
            <form id="edit-modul-form" method="POST" action="" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Nama Modul</label>
                    <input type="text" name="nama_modul" id="edit-modul-nama" required class="form-control text-sm w-full border-slate-300 dark:border-slate-700 dark:bg-slate-850 dark:text-white rounded-xl">
                </div>
                <div class="flex items-center justify-end gap-2 border-t pt-3 border-slate-105 dark:border-slate-800">
                    <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg border-0 shadow-sm uppercase tracking-wider">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="document.getElementById('edit-modul-modal').close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-750 dark:bg-slate-800 dark:text-slate-300 text-xs font-semibold rounded-lg border-0 shadow-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- MODAL ADD DETAIL ITEM -->
    <dialog id="add-detail-modal" class="p-0 rounded-2xl border-0 shadow-2xl w-full max-w-sm bg-white dark:bg-slate-900 dark:text-white">
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between border-b pb-3 border-slate-100 dark:border-slate-800">
                <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-1.5 m-0">
                    <i class="bi bi-plus-circle text-indigo-550"></i> Tambah Item Checklist
                </h3>
                <button type="button" onclick="document.getElementById('add-detail-modal').close()" class="bg-transparent border-0 text-slate-400 hover:text-slate-750 text-xl font-bold p-0 leading-none">&times;</button>
            </div>
            <form id="add-detail-form" method="POST" action="" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Modul</label>
                    <div id="add-detail-modul-name" class="text-sm font-bold text-slate-800 dark:text-slate-200"></div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Item Pekerjaan / Checklist</label>
                    <input type="text" name="nama_detail" required class="form-control text-sm w-full border-slate-300 dark:border-slate-700 dark:bg-slate-850 dark:text-white rounded-xl" placeholder="Contoh: Setting & Uji Coba Server">
                </div>
                <div class="flex items-center justify-end gap-2 border-t pt-3 border-slate-105 dark:border-slate-800">
                    <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg border-0 shadow-sm uppercase tracking-wider">
                        Simpan Item
                    </button>
                    <button type="button" onclick="document.getElementById('add-detail-modal').close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-750 dark:bg-slate-800 dark:text-slate-300 text-xs font-semibold rounded-lg border-0 shadow-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- MODAL EDIT DETAIL ITEM -->
    <dialog id="edit-detail-modal" class="p-0 rounded-2xl border-0 shadow-2xl w-full max-w-sm bg-white dark:bg-slate-900 dark:text-white">
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between border-b pb-3 border-slate-100 dark:border-slate-800">
                <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-1.5 m-0">
                    <i class="bi bi-pencil-square text-indigo-550"></i> Edit Item Checklist
                </h3>
                <button type="button" onclick="document.getElementById('edit-detail-modal').close()" class="bg-transparent border-0 text-slate-400 hover:text-slate-750 text-xl font-bold p-0 leading-none">&times;</button>
            </div>
            <form id="edit-detail-form" method="POST" action="" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Item Pekerjaan / Checklist</label>
                    <input type="text" name="nama_detail" id="edit-detail-nama" required class="form-control text-sm w-full border-slate-300 dark:border-slate-700 dark:bg-slate-855 dark:text-white rounded-xl">
                </div>
                <div class="flex items-center justify-end gap-2 border-t pt-3 border-slate-105 dark:border-slate-800">
                    <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg border-0 shadow-sm uppercase tracking-wider">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="document.getElementById('edit-detail-modal').close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-750 dark:bg-slate-800 dark:text-slate-300 text-xs font-semibold rounded-lg border-0 shadow-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function setEditModulData(id, name) {
            document.getElementById('edit-modul-form').action = `/master-modul/update/${id}`;
            document.getElementById('edit-modul-nama').value = name;
            document.getElementById('edit-modul-modal').showModal();
        }

        function setAddDetailModal(modulId, modulName) {
            document.getElementById('add-detail-form').action = `/master-modul/detail/store/${modulId}`;
            document.getElementById('add-detail-modul-name').innerText = modulName;
            document.getElementById('add-detail-modal').showModal();
        }

        function setEditDetailData(id, name) {
            document.getElementById('edit-detail-form').action = `/master-modul/detail/update/${id}`;
            document.getElementById('edit-detail-nama').value = name;
            document.getElementById('edit-detail-modal').showModal();
        }
    </script>
</x-app-layout>

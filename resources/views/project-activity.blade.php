<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-slate-800 dark:text-slate-200 leading-tight flex items-center gap-2">
            <i class="bi bi-activity text-indigo-600 dark:text-indigo-400"></i>
            Project Activity & WBS
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6" x-data="{ activeTab: '{{ request()->has('won_id') ? 'wbs-sheet' : 'activity-log' }}' }">

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

        <!-- TABS NAVIGATION -->
        <div class="flex border-b border-slate-200 dark:border-slate-800 gap-4 mb-6">
            <button @click="activeTab = 'activity-log'"
                    :class="activeTab === 'activity-log' ? 'border-indigo-650 text-indigo-605 dark:border-indigo-400 dark:text-indigo-400 font-extrabold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-350 font-bold'"
                    class="pb-3 px-2 border-b-2 text-sm transition-all focus:outline-none flex items-center gap-2 bg-transparent">
                <i class="bi bi-card-list"></i>
                Log Aktivitas Proyek
            </button>
            <button @click="activeTab = 'wbs-sheet'"
                    :class="activeTab === 'wbs-sheet' ? 'border-indigo-655 text-indigo-605 dark:border-indigo-400 dark:text-indigo-400 font-extrabold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-350 font-bold'"
                    class="pb-3 px-2 border-b-2 text-sm transition-all focus:outline-none flex items-center gap-2 bg-transparent">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                Work Breakdown Structure (WBS)
            </button>
        </div>

        <!-- TAB 1: ACTIVITY LOG -->
        <div x-show="activeTab === 'activity-log'" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- LIST ACTIVITIES -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="premium-card p-6 dark:bg-slate-900 dark:border-slate-800">
                        <div class="border-b pb-3 mb-4 flex items-center justify-between border-slate-100 dark:border-slate-800">
                            <h3 class="text-base font-extrabold text-slate-800 dark:text-white m-0 flex items-center gap-2">
                                <i class="bi bi-activity text-indigo-500"></i> Daftar Project Activity
                            </h3>
                            <span class="text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-850 text-indigo-700 dark:text-indigo-300 px-2.5 py-1 rounded-lg">
                                Total: {{ $projectActivities->total() }} Kegiatan
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800 align-middle">
                                <thead class="bg-slate-50 dark:bg-slate-800 text-slate-655 dark:text-slate-300">
                                    <tr class="text-xs font-bold uppercase tracking-wider">
                                        <th scope="col" class="px-4 py-3 text-left">Aksi</th>
                                        <th scope="col" class="px-4 py-3 text-left min-w-[280px]">Task</th>
                                        <th scope="col" class="px-4 py-3 text-left">Site / PIC</th>
                                        <th scope="col" class="px-4 py-3 text-left">Tanggal Masuk</th>
                                        <th scope="col" class="px-4 py-3 text-left">Deadline</th>
                                        <th scope="col" class="px-4 py-3 text-left">Prioritas</th>
                                        <th scope="col" class="px-4 py-3 text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900 text-sm text-slate-700 dark:text-slate-300">
                                    @forelse ($projectActivities as $pa)
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center gap-1.5">
                                                    <a href="{{ route('project.activity.delete', $pa->id) }}" 
                                                       class="inline-flex items-center justify-center w-7 h-7 bg-rose-500 hover:bg-rose-600 text-white rounded-lg shadow-sm border-0 transition-colors"
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus activity ini?')"
                                                       title="Hapus Activity">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-slate-800 dark:text-slate-200 font-bold min-w-[280px] break-words" title="{{ $pa->task }}">
                                                {{ $pa->task }}
                                            </td>
                                            <td class="px-4 py-3 space-y-1">
                                                <div>
                                                    <span class="text-[10px] font-extrabold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-850 px-2 py-0.5 rounded">
                                                        {{ $pa->site->namasite ?? '-' }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-1 mt-1 text-xs">
                                                    <i class="bi bi-person text-slate-400"></i>
                                                    <span class="text-slate-600 dark:text-slate-400 font-semibold">{{ $pa->pic->namapegawai ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap font-semibold">
                                                {{ \Carbon\Carbon::parse($pa->tgl_masuk)->format('d M Y') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap font-semibold">
                                                @php
                                                    $isOverdue = (strtotime($pa->tgl_deadline) < time());
                                                @endphp
                                                <div class="flex flex-col gap-0.5">
                                                    <span class="{{ $isOverdue ? 'text-rose-600 dark:text-rose-400 font-bold' : '' }}">
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
                                                        'Urgent' => 'bg-rose-100 text-rose-800 border-rose-200 dark:bg-rose-955/30 dark:text-rose-400',
                                                        'High' => 'bg-orange-100 text-orange-850 border-orange-200 dark:bg-orange-955/30 dark:text-orange-400',
                                                        'Medium' => 'bg-amber-100 text-amber-850 border-amber-200 dark:bg-amber-955/30 dark:text-amber-400',
                                                        default => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-955/30 dark:text-emerald-400',
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
                                                        'Done' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-955/30 dark:text-emerald-400',
                                                        default => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-955/30 dark:text-indigo-400',
                                                    };
                                                @endphp
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statClass }}">
                                                    {{ $statName }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-12 text-slate-405">
                                                <i class="bi bi-inbox text-3xl block mb-2 text-slate-350"></i>
                                                Belum ada project activity yang terdaftar.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                            <div class="text-xs text-slate-500 font-medium">
                                @if($projectActivities->total() > 0)
                                    Menampilkan {{ $projectActivities->firstItem() }}-{{ $projectActivities->lastItem() }} dari {{ $projectActivities->total() }} data
                                @else
                                    Tidak ada data
                                @endif
                            </div>
                            @if($projectActivities->hasPages())
                                <div class="premium-pagination">
                                    {{ $projectActivities->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- CREATE ACTIVITY FORM -->
                <div class="col-span-1">
                    <form method="POST" action="{{ route('project.activity.save') }}" class="premium-card p-6 space-y-4 hover-lift dark:bg-slate-900 dark:border-slate-800">
                        @csrf
                        <div class="border-b pb-3 flex items-center gap-2 border-slate-100 dark:border-slate-800">
                            <i class="bi bi-file-earmark-plus text-indigo-500 text-xl"></i>
                            <h3 class="text-base font-bold text-slate-800 m-0 font-sans dark:text-white">Tambah Activity Baru</h3>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Prioritas</label>
                            <select name="prioritas_id" required class="form-select text-sm w-full border-slate-300 dark:border-slate-700 dark:bg-slate-855 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500">
                                <option value="">-- Pilih Prioritas --</option>
                                @foreach ($priorities as $p)
                                    <option value="{{ $p->id }}">{{ $p->namaprioritas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Site</label>
                            <select name="site_id" required class="form-select text-sm w-full border-slate-300 dark:border-slate-700 dark:bg-slate-855 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500">
                                <option value="">-- Pilih Site --</option>
                                @foreach ($sites as $s)
                                    <option value="{{ $s->id }}">{{ $s->namasite }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">PIC</label>
                            <select name="pic_id" required class="form-select text-sm w-full border-slate-300 dark:border-slate-700 dark:bg-slate-855 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500">
                                <option value="">-- Pilih PIC --</option>
                                @foreach ($pics as $pic)
                                    <option value="{{ $pic->id }}">{{ $pic->namapegawai }} ({{ $pic->jenispegawai }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Tgl Masuk</label>
                                <input type="date" name="tgl_masuk" required class="form-control text-sm w-full border-slate-300 dark:border-slate-700 dark:bg-slate-855 dark:text-white rounded-xl" value="{{ date('Y-m-d') }}">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Deadline</label>
                                <input type="date" name="tgl_deadline" required class="form-control text-sm w-full border-slate-300 dark:border-slate-700 dark:bg-slate-855 dark:text-white rounded-xl" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Status</label>
                            <select name="status" required class="form-select text-sm w-full border-slate-300 dark:border-slate-700 dark:bg-slate-855 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500">
                                <option value="Open">Open</option>
                                <option value="Done">Done</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Task</label>
                            <textarea name="task" required class="form-control text-sm w-full border-slate-300 dark:border-slate-700 dark:bg-slate-855 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500" rows="3.5" placeholder="Tulis detail aktifitas/tugas..."></textarea>
                        </div>

                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2.5 bg-indigo-600 hover:bg-indigo-755 text-white text-xs font-extrabold rounded-xl shadow-sm transition-all border-0 uppercase tracking-wider">
                            <i class="bi bi-save"></i> Simpan Activity
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TAB 2: WBS SHEET -->
        <div x-show="activeTab === 'wbs-sheet'" class="space-y-6">
            <!-- PROJECT WON SELECT FILTER -->
            <div class="glass-card p-6 dark:bg-slate-900 dark:border-slate-800">
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-md-5">
                        <label class="form-label text-slate-850 dark:text-slate-300 font-extrabold text-sm flex items-center gap-1.5 mb-1.5">
                            <i class="bi bi-rocket-takeoff text-indigo-500"></i> Pilih Proyek Won untuk Monitoring WBS
                        </label>
                        <select onchange="window.location.href='{{ route('project.activity') }}?won_id=' + this.value" class="form-select border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-slate-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 rounded-xl shadow-xs font-bold">
                            <option value="" disabled {{ !$selectedWonId ? 'selected' : '' }}>-- Pilih Proyek --</option>
                            @foreach ($wons as $w)
                                <option value="{{ $w->id }}" {{ $selectedWonId == $w->id ? 'selected' : '' }}>
                                    {{ $w->project_name }} ({{ $w->site->namasite }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if ($selectedWonId)
                        @php
                            $selectedWon = $wons->where('id', $selectedWonId)->first();
                            
                            $wbsTasksTotal = $projectWbs->count();
                            $wbsTasksDone = $projectWbs->where('status', 'DONE')->count();
                            $wbsCompletePercentage = $wbsTasksTotal > 0 ? round(($wbsTasksDone / $wbsTasksTotal) * 100) : 0;
                        @endphp
                        <div class="col-12 col-md-7 text-md-end mt-md-4">
                            <div class="inline-flex flex-col text-left bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-750 px-4 py-2.5 rounded-2xl shadow-xs">
                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Koordinator Project</span>
                                <span class="text-sm font-black text-slate-800 dark:text-white mt-0.5">{{ $selectedWon->picKoordinator->namapegawai ?? '-' }}</span>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <div class="w-32 bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $wbsCompletePercentage }}%"></div>
                                    </div>
                                    <span class="text-[11px] font-black text-emerald-700 dark:text-emerald-400">{{ $wbsCompletePercentage }}% WBS Done</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- WBS INTERACTIVE SPREADSHEET CARD -->
            @if ($selectedWonId)
                @php
                    $structures = [
                        'Kick-Off Meeting',
                        'Master Data',
                        'Assesment',
                        'Instalasi Sistem',
                        'Training',
                        'Bridging',
                        'Development & Quick Customize',
                        'Go - Live'
                    ];
                    $timelinePoints = [];
                    foreach ($structures as $struct) {
                        $minStart = $projectWbs->where('jenis_struktur', $struct)
                                               ->whereNotNull('start_date')
                                               ->min('start_date');
                        $timelinePoints[] = [
                            'name' => $struct,
                            'date' => $minStart ? \Carbon\Carbon::parse($minStart)->format('d M Y') : 'TBD',
                            'raw_date' => $minStart,
                            'has_date' => !is_null($minStart)
                        ];
                    }

                    // Sort timeline points chronologically by start date, keeping TBDs at the end
                    usort($timelinePoints, function($a, $b) {
                        if ($a['has_date'] && !$b['has_date']) return -1;
                        if (!$a['has_date'] && $b['has_date']) return 1;
                        if ($a['has_date'] && $b['has_date']) {
                            return strtotime($a['raw_date']) <=> strtotime($b['raw_date']);
                        }
                        return 0;
                    });
                @endphp

                <!-- ROADMAP TIMELINE -->
                <div class="glass-card p-6 dark:bg-slate-900 dark:border-slate-800 space-y-4">
                    <h4 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5 m-0">
                        <i class="bi bi-calendar3-range text-indigo-500"></i> Project Roadmap Timeline (Awal Setiap Struktur)
                    </h4>
                    
                    <div class="flex items-center justify-between overflow-x-auto pb-2 gap-4">
                        @foreach ($timelinePoints as $index => $point)
                            <div class="flex items-center flex-1 min-w-[120px] relative">
                                <!-- Node -->
                                <div class="flex flex-col items-center text-center z-10 w-full">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs transition-colors {{ $point['has_date'] ? 'bg-indigo-650 text-white shadow-md' : 'bg-slate-200 text-slate-500 dark:bg-slate-850 dark:text-slate-400' }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="text-[11px] font-extrabold text-slate-800 dark:text-slate-200 mt-2 line-clamp-1" title="{{ $point['name'] }}">
                                        {{ $point['name'] }}
                                    </span>
                                    <span class="text-[10px] font-bold mt-0.5 {{ $point['has_date'] ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}">
                                        {{ $point['date'] }}
                                    </span>
                                </div>

                                <!-- Connecting Line -->
                                @if ($index < count($timelinePoints) - 1)
                                    <div class="absolute top-4 left-[50%] right-[-50%] h-0.5 bg-slate-200 dark:bg-slate-800 -z-0">
                                        <div class="h-full bg-indigo-500 transition-all duration-300" style="width: {{ $point['has_date'] && $timelinePoints[$index+1]['has_date'] ? '100%' : '0%' }}"></div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <form id="wbs-bulk-form" method="POST" action="{{ route('project.activity.wbs.bulk_save', $selectedWonId) }}" enctype="multipart/form-data" class="premium-card p-6 overflow-hidden dark:bg-slate-900 dark:border-slate-800 flex flex-col gap-4">
                    @csrf
                    
                    <div class="border-b pb-3 flex flex-col sm:flex-row sm:items-center justify-between border-slate-105 dark:border-slate-800 gap-4 flex-shrink-0">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-800 dark:text-white flex items-center gap-2 m-0">
                                <i class="bi bi-grid-3x3-gap-fill text-indigo-500"></i>
                                Work Breakdown Structure sheet — <span class="text-indigo-900 dark:text-indigo-300 font-black">{{ $selectedWon->project_name }}</span>
                            </h3>
                            <span class="text-xs font-semibold text-slate-500">Silakan sesuaikan formula predecessor, durasi, tanggal, file upload, dan simpan sekaligus.</span>
                        </div>
                        <button type="submit" class="btn btn-indigo flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-755 text-white text-xs font-extrabold rounded-xl shadow-md border-0 transition-all uppercase tracking-wider self-start sm:self-center">
                            <i class="bi bi-cloud-upload-fill text-sm"></i> Simpan Seluruh Perubahan WBS
                        </button>
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-xs text-left align-middle border-collapse table-fixed min-w-[1380px]">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-b border-slate-200 dark:border-slate-750 font-extrabold uppercase tracking-wider text-[10px]">
                                    <th class="p-2.5 w-14 text-center">WBS</th>
                                    <th class="p-2.5 w-64">Detail Task</th>
                                    <th class="p-2.5 w-24">Task To</th>
                                    <th class="p-2.5 w-36">Predecessor</th>
                                    <th class="p-2.5 w-40">JMT PIC (Vendor)</th>
                                    <th class="p-2.5 w-32">Client PIC</th>
                                    <th class="p-2.5 w-32">Start Date</th>
                                    <th class="p-2.5 w-32">Due Date</th>
                                    <th class="p-2.5 w-24 text-center">Dur (Days)</th>
                                    <th class="p-2.5 w-36">Status</th>
                                    <th class="p-2.5 w-32">Finish Date</th>
                                    <th class="p-2.5 w-44">Keterangan</th>
                                    <th class="p-2.5 w-48">Link / Upload File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Group tasks dynamically by jenis_struktur (excluding headers to ensure correct grouping style)
                                    $subtasks = $projectWbs->filter(function($item) {
                                        return str_contains($item->wbs_code, '.');
                                    });
                                    $groupedWbs = $subtasks->groupBy('jenis_struktur');
                                @endphp

                                @foreach ($groupedWbs as $jenisStruktur => $tasks)
                                    @php
                                        $headerColorClass = match($jenisStruktur) {
                                            'Kick-Off Meeting' => 'bg-sky-50 dark:bg-sky-955/30 text-sky-855 dark:text-sky-300 border-sky-200 dark:border-sky-900',
                                            'Master Data' => 'bg-orange-50 dark:bg-orange-955/30 text-orange-900 dark:text-orange-300 border-orange-200 dark:border-orange-900',
                                            'Assesment' => 'bg-amber-50 dark:bg-amber-955/30 text-amber-900 dark:text-amber-300 border-amber-200 dark:border-amber-900',
                                            'Instalasi Sistem' => 'bg-slate-700 text-white dark:bg-slate-800 dark:text-slate-100 border-slate-650',
                                            'Training' => 'bg-indigo-50 dark:bg-indigo-955/30 text-indigo-900 dark:text-indigo-300 border-indigo-200 dark:border-indigo-900',
                                            'Bridging' => 'bg-teal-50 dark:bg-teal-955/30 text-teal-855 dark:text-teal-300 border-teal-200 dark:border-teal-900',
                                            'Development & Quick Customize' => 'bg-rose-50 dark:bg-rose-955/30 text-rose-900 dark:text-rose-300 border-rose-200 dark:border-rose-900',
                                            'Go - Live' => 'bg-emerald-50 dark:bg-emerald-955/30 text-emerald-900 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900',
                                            default => 'bg-slate-100 dark:bg-slate-750 text-slate-850 dark:text-slate-200 border-slate-250'
                                        };
                                    @endphp

                                    <!-- Group Structure Header Row -->
                                    <tr class="{{ $headerColorClass }} font-bold border-b text-[11px]">
                                        <td class="p-3 text-center font-black">-</td>
                                        <td class="p-3 uppercase tracking-wider font-extrabold" colspan="12">{{ $jenisStruktur }}</td>
                                    </tr>

                                    <!-- Group Subtasks -->
                                    @foreach ($tasks as $wbs)
                                        @php
                                             $currentIndex = $subtasks->values()->search(fn($item) => $item->id === $wbs->id);
                                             $predecessorOptions = $subtasks->values()->take($currentIndex !== false ? $currentIndex : 0);
                                        @endphp
                                        <tr class="border-b border-slate-100 dark:border-slate-800/80 hover:bg-slate-50/50 dark:hover:bg-slate-850/40" id="wbs-row-{{ $wbs->id }}">
                                            <td class="p-2 text-center font-bold text-slate-550">{{ $wbs->wbs_code }}</td>
                                            <td class="p-2 font-semibold text-slate-800 dark:text-slate-200 leading-snug break-words">
                                                {{ $wbs->detail_task }}
                                                <input type="hidden" name="wbs[{{ $wbs->id }}][id]" value="{{ $wbs->id }}">
                                            </td>
                                            <td class="p-1">
                                                <select name="wbs[{{ $wbs->id }}][task_to]" class="form-select form-select-sm p-1 text-[11px] font-bold border-slate-250 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-lg w-full">
                                                    <option value="Vendor" {{ $wbs->task_to === 'Vendor' ? 'selected' : '' }}>Vendor</option>
                                                    <option value="Client" {{ $wbs->task_to === 'Client' ? 'selected' : '' }}>Client</option>
                                                    <option value="Both" {{ $wbs->task_to === 'Both' ? 'selected' : '' }}>Both</option>
                                                </select>
                                            </td>
                                            <td class="p-1">
                                                <select name="wbs[{{ $wbs->id }}][predecessor_id]" class="form-select form-select-sm p-1 text-[10px] font-semibold border-slate-250 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-lg w-full">
                                                    <option value="">-- No Pred --</option>
                                                    @foreach ($predecessorOptions as $popt)
                                                        <option value="{{ $popt->id }}" {{ $wbs->predecessor_id == $popt->id ? 'selected' : '' }}>
                                                            {{ $popt->wbs_code }} {{ substr($popt->detail_task, 0, 20) }}...
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="p-1">
                                                <select name="wbs[{{ $wbs->id }}][jmt_pic_id]" class="form-select form-select-sm p-1 text-[11px] font-bold border-slate-250 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-lg w-full">
                                                    <option value="">-- JMT PIC --</option>
                                                    @foreach ($programmers as $prog)
                                                        <option value="{{ $prog->id }}" {{ $wbs->jmt_pic_id == $prog->id ? 'selected' : '' }}>{{ $prog->namapegawai }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="p-1">
                                                <input type="text" name="wbs[{{ $wbs->id }}][client_pic]" value="{{ $wbs->client_pic }}" class="form-control form-control-sm p-1 text-[11px] font-semibold border-slate-250 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-lg w-full" placeholder="Client PIC">
                                            </td>
                                            <td class="p-1">
                                                <input type="date" name="wbs[{{ $wbs->id }}][start_date]" value="{{ $wbs->start_date ? $wbs->start_date->format('Y-m-d') : '' }}" class="form-control form-control-sm p-1 text-[11px] border-slate-250 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-lg w-full" {{ $wbs->predecessor_id ? 'disabled' : '' }}>
                                            </td>
                                            <td class="p-1">
                                                <input type="date" name="wbs[{{ $wbs->id }}][due_date]" value="{{ $wbs->due_date ? $wbs->due_date->format('Y-m-d') : '' }}" class="form-control form-control-sm p-1 text-[11px] border-slate-250 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-lg w-full">
                                            </td>
                                            <td class="p-1 text-center">
                                                <input type="number" name="wbs[{{ $wbs->id }}][duration]" value="{{ $wbs->duration ?? 0 }}" class="form-control form-control-sm p-1 text-[11px] font-bold text-center border-slate-250 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-lg w-full" placeholder="Dur">
                                            </td>
                                            <td class="p-1">
                                                @php
                                                    $bgStatusClass = match($wbs->status) {
                                                        'DONE' => 'bg-emerald-50 text-emerald-855 dark:bg-emerald-955/20 dark:text-emerald-400',
                                                        'IN PROGRESS' => 'bg-amber-50 text-amber-855 dark:bg-amber-955/20 dark:text-amber-400',
                                                        'NEED REVIEW' => 'bg-sky-50 text-sky-855 dark:bg-sky-955/20 dark:text-sky-400',
                                                        'DELAYED' => 'bg-rose-50 text-rose-855 dark:bg-rose-955/20 dark:text-rose-400',
                                                        default => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300'
                                                    };
                                                @endphp
                                                <select name="wbs[{{ $wbs->id }}][status]" class="form-select form-select-sm p-1 text-[11px] font-black rounded-lg w-full {{ $bgStatusClass }} border-slate-250 dark:border-slate-700">
                                                    <option value="NOT STARTED" {{ $wbs->status === 'NOT STARTED' ? 'selected' : '' }}>NOT STARTED</option>
                                                    <option value="IN PROGRESS" {{ $wbs->status === 'IN PROGRESS' ? 'selected' : '' }}>IN PROGRESS</option>
                                                    <option value="DONE" {{ $wbs->status === 'DONE' ? 'selected' : '' }}>DONE</option>
                                                    <option value="NEED REVIEW" {{ $wbs->status === 'NEED REVIEW' ? 'selected' : '' }}>NEED REVIEW</option>
                                                    <option value="DELAYED" {{ $wbs->status === 'DELAYED' ? 'selected' : '' }}>DELAYED</option>
                                                </select>
                                            </td>
                                            <td class="p-1">
                                                <input type="date" name="wbs[{{ $wbs->id }}][finish_date]" value="{{ $wbs->finish_date ? $wbs->finish_date->format('Y-m-d') : '' }}" class="form-control form-control-sm p-1 text-[11px] border-slate-250 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-lg w-full">
                                            </td>
                                            <td class="p-1">
                                                <input type="text" name="wbs[{{ $wbs->id }}][keterangan]" value="{{ $wbs->keterangan }}" class="form-control form-control-sm p-1 text-[11px] border-slate-250 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-lg w-full" placeholder="Keterangan">
                                            </td>
                                            <td class="p-1">
                                                <input type="text" name="wbs[{{ $wbs->id }}][link_file]" value="{{ $wbs->link_file }}" class="form-control form-control-sm p-1 text-[11px] border-slate-250 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-lg w-full mb-1" placeholder="Link / URL">
                                                <input type="file" name="wbs[{{ $wbs->id }}][file_upload]" class="form-control form-control-sm p-0.5 text-[9px] border-slate-200 dark:border-slate-700 dark:bg-slate-850 dark:text-white rounded-lg w-full" style="font-size: 9px;">
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-end mt-4 border-t pt-4 border-slate-105 dark:border-slate-800">
                        <button type="submit" class="btn btn-indigo flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-755 text-white text-sm font-extrabold rounded-xl shadow-md border-0 transition-all uppercase tracking-wider">
                            <i class="bi bi-cloud-upload-fill"></i> Simpan Seluruh Perubahan WBS
                        </button>
                    </div>
                </form>
            @else
                <div class="glass-card p-12 text-center text-slate-505">
                    <i class="bi bi-rocket-takeoff-fill text-4xl block mb-2 text-slate-355"></i>
                    <span class="text-xs font-bold">Pilih proyek terlebih dahulu untuk menampilkan data Work Breakdown Structure (WBS).</span>
                </div>
            @endif
        </div>

    </div>

    <!-- INLINE SAVE WBS SCRIPT -->
    <script>
        function propagateSuccessors(wbsId) {
            const row = document.getElementById(`wbs-row-${wbsId}`);
            if (!row) return;

            const dueDateInput = row.querySelector('[name="wbs[' + wbsId + '][due_date]"]');
            const newDueDateVal = dueDateInput.value;
            if (!newDueDateVal) return;

            // Find successor rows that depend on this row
            const predecessorSelects = document.querySelectorAll('select[name$="[predecessor_id]"]');
            predecessorSelects.forEach(select => {
                if (select.value === wbsId) {
                    const successorRow = select.closest('tr');
                    const succWbsId = successorRow.id.replace('wbs-row-', '');
                    
                    const startDateInput = successorRow.querySelector('[name="wbs[' + succWbsId + '][start_date]"]');
                    const succDueDateInput = successorRow.querySelector('[name="wbs[' + succWbsId + '][due_date]"]');
                    const durationInput = successorRow.querySelector('[name="wbs[' + succWbsId + '][duration]"]');

                    // Calculate successor start_date = predecessor due_date + 1 day
                    const date = new Date(newDueDateVal);
                    date.setDate(date.getDate() + 1);
                    
                    const yyyy = date.getFullYear();
                    const mm = String(date.getMonth() + 1).padStart(2, '0');
                    const dd = String(date.getDate()).padStart(2, '0');
                    
                    const newStartDateVal = `${yyyy}-${mm}-${dd}`;
                    startDateInput.value = newStartDateVal;

                    // Recalculate successor due_date from duration
                    const durVal = parseInt(durationInput.value) || 0;
                    if (durVal > 0) {
                        const start = new Date(newStartDateVal);
                        start.setDate(start.getDate() + (durVal - 1));
                        
                        const dueYyyy = start.getFullYear();
                        const dueMm = String(start.getMonth() + 1).padStart(2, '0');
                        const dueDd = String(start.getDate()).padStart(2, '0');
                        
                        const newDueDateVal2 = `${dueYyyy}-${dueMm}-${dueDd}`;
                        succDueDateInput.value = newDueDateVal2;

                        // Propagate recursively down the chain
                        propagateSuccessors(succWbsId);
                    }
                }
            });
        }

        function initWbsCalculations(wbsId) {
            const row = document.getElementById(`wbs-row-${wbsId}`);
            if (!row) return;

            const startDateInput = row.querySelector('[name="wbs[' + wbsId + '][start_date]"]');
            const dueDateInput = row.querySelector('[name="wbs[' + wbsId + '][due_date]"]');
            const durationInput = row.querySelector('[name="wbs[' + wbsId + '][duration]"]');
            const predecessorSelect = row.querySelector('[name="wbs[' + wbsId + '][predecessor_id]"]');

            // Predecessor change logic
            predecessorSelect.addEventListener('change', () => {
                const predId = predecessorSelect.value;
                if (predId) {
                    startDateInput.disabled = true;
                    const predRow = document.getElementById(`wbs-row-${predId}`);
                    if (predRow) {
                        const predDueInput = predRow.querySelector('[name="wbs[' + predId + '][due_date]"]');
                        if (predDueInput && predDueInput.value) {
                            const date = new Date(predDueInput.value);
                            date.setDate(date.getDate() + 1);
                            const yyyy = date.getFullYear();
                            const mm = String(date.getMonth() + 1).padStart(2, '0');
                            const dd = String(date.getDate()).padStart(2, '0');
                            startDateInput.value = `${yyyy}-${mm}-${dd}`;
                            recalculateFromDuration();
                        }
                    }
                } else {
                    startDateInput.disabled = false;
                }
            });

            function recalculateFromDuration() {
                const startVal = startDateInput.value;
                const durVal = parseInt(durationInput.value);
                if (startVal && durVal > 0) {
                    const start = new Date(startVal);
                    start.setDate(start.getDate() + (durVal - 1));
                    
                    const yyyy = start.getFullYear();
                    const mm = String(start.getMonth() + 1).padStart(2, '0');
                    const dd = String(start.getDate()).padStart(2, '0');
                    dueDateInput.value = `${yyyy}-${mm}-${dd}`;
                    
                    // Propagate to successors
                    propagateSuccessors(wbsId);
                }
            }

            function recalculateFromDates() {
                const startVal = startDateInput.value;
                const dueVal = dueDateInput.value;
                if (startVal && dueVal) {
                    const start = new Date(startVal);
                    const due = new Date(dueVal);
                    const diffTime = due - start;
                    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    if (diffDays < 0) diffDays = 0;
                    durationInput.value = diffDays;
                    
                    // Propagate to successors
                    propagateSuccessors(wbsId);
                }
            }

            startDateInput.addEventListener('change', () => {
                if (durationInput.value) recalculateFromDuration();
                else if (dueDateInput.value) recalculateFromDates();
            });

            durationInput.addEventListener('input', recalculateFromDuration);
            dueDateInput.addEventListener('change', recalculateFromDates);

            // Automatically set status to DONE when finish date is filled
            const finishDateInput = row.querySelector('[name="wbs[' + wbsId + '][finish_date]"]');
            const statusSelect = row.querySelector('[name="wbs[' + wbsId + '][status]"]');
            finishDateInput.addEventListener('change', () => {
                if (finishDateInput.value) {
                    statusSelect.value = 'DONE';
                    statusSelect.className = "form-select form-select-sm p-1 text-[11px] font-black rounded-lg w-full border-slate-250 dark:border-slate-700 bg-emerald-50 text-emerald-855 dark:bg-emerald-955/20 dark:text-emerald-400";
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if ($selectedWonId)
                @foreach ($projectWbs as $wbs)
                    @if (str_contains($wbs->wbs_code, '.'))
                        initWbsCalculations('{{ $wbs->id }}');
                    @endif
                @endforeach
            @endif
        });
    </script>
</x-app-layout>

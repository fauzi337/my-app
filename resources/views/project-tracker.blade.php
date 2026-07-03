<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 py-2">
            <div>
                <h2 class="font-bold text-slate-800 dark:text-white leading-tight flex items-center gap-2" style="font-size: 28px">
                    <i class="bi bi-pie-chart-fill text-indigo-600 dark:text-indigo-400"></i>
                    {{ __('Project Tracker & Monitor') }}
                </h2>
                <p class="text-xs text-slate-655 dark:text-slate-400 mt-1 font-semibold">
                    Pantau progres kegiatan dan timeline request per site secara real-time.
                </p>
            </div>
        </div>
    </x-slot>

    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Project Tracker</title>

        <!-- Tailwind CSS (provided via Vite) -->
        @vite('resources/css/app.css')

        <!-- Bootstrap 5.3 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <style>
            .tracker-container {
                font-family: 'Outfit', sans-serif;
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(203, 213, 225, 0.8);
                border-radius: 20px;
                box-shadow: 0 4px 18px 0 rgba(148, 163, 184, 0.08);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .dark .glass-card {
                background: rgba(30, 41, 59, 0.7);
                border-color: rgba(71, 85, 105, 0.3);
                box-shadow: 0 4px 18px 0 rgba(0, 0, 0, 0.2);
            }
            .metric-card {
                border-radius: 16px;
                border: 1px solid rgba(203, 213, 225, 0.8);
                padding: 1.5rem;
                background: #ffffff;
                box-shadow: 0 4px 12px 0 rgba(148, 163, 184, 0.05);
                position: relative;
                overflow: hidden;
            }
            .dark .metric-card {
                background: rgba(30, 41, 59, 0.85);
                border-color: rgba(71, 85, 105, 0.3);
                box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.15);
            }
            .metric-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 4px;
                height: 100%;
            }
            .metric-total::before { background: #6366f1; }
            .metric-done::before { background: #10b981; }
            .metric-undone::before { background: #f59e0b; }

            .task-card {
                border-left: 4px solid #94a3b8;
                transition: all 0.2s ease;
            }
            .task-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            }
            .task-critical { border-left-color: #ef4444; }
            .task-high { border-left-color: #f97316; }
            .task-medium { border-left-color: #eab308; }
            .task-low { border-left-color: #10b981; }

            /* Custom scrollbars */
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 8px;
            }
            .dark .custom-scrollbar::-webkit-scrollbar-track {
                background: #1e293b;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 8px;
            }
            .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #475569;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
            .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #64748b;
            }
        </style>
    </head>

    <div class="tracker-container p-6 max-w-7xl mx-auto space-y-6" x-data="{ activeTab: 'active-projects' }">

        <!-- Toast Notification Success -->
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

        <!-- TABS NAVIGATION -->
        <div class="flex border-b border-slate-200 dark:border-slate-800 gap-4 mb-6">
            <button @click="activeTab = 'active-projects'"
                    :class="activeTab === 'active-projects' ? 'border-indigo-650 text-indigo-605 dark:border-indigo-400 dark:text-indigo-400 font-extrabold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-350 font-bold'"
                    class="pb-3 px-2 border-b-2 text-sm transition-all focus:outline-none flex items-center gap-2 bg-transparent">
                <i class="bi bi-play-circle-fill"></i>
                Active Projects & Tasks
            </button>
            <button @click="activeTab = 'project-initiation'"
                    :class="activeTab === 'project-initiation' ? 'border-indigo-655 text-indigo-605 dark:border-indigo-400 dark:text-indigo-400 font-extrabold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-350 font-bold'"
                    class="pb-3 px-2 border-b-2 text-sm transition-all focus:outline-none flex items-center gap-2 bg-transparent">
                <i class="bi bi-plus-square-fill"></i>
                Inisiasi Project (Won)
            </button>
        </div>

        <!-- TAB 1: ACTIVE PROJECTS & TASKS -->
        <div x-show="activeTab === 'active-projects'" class="space-y-6">
            <!-- SITE FILTER SECTION -->
            <div class="glass-card p-6">
                <form id="site-filter-form" method="GET" action="{{ route('project.tracker') }}" class="row g-3 align-items-center">
                    <div class="col-12 col-md-4">
                        <label class="form-label text-slate-800 dark:text-slate-300 font-bold text-sm flex items-center gap-1.5 mb-1">
                            <i class="bi bi-hospital text-indigo-500"></i> Pilih Site Monitoring
                        </label>
                        <select name="site_id" onchange="this.form.submit()" class="form-select border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-slate-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 rounded-xl shadow-xs font-semibold">
                            @foreach ($sites as $site)
                                <option value="{{ $site->id }}" {{ $selectedSiteId == $site->id ? 'selected' : '' }}>
                                    {{ trim($site->namasite) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-8 text-md-end mt-md-4">
                        @php
                            $currentSiteName = $sites->where('id', $selectedSiteId)->first()?->namasite ?? '-';
                        @endphp
                        <span class="text-xs font-bold bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-250 dark:border-indigo-850 text-indigo-700 dark:text-indigo-300 px-3.5 py-2.5 rounded-xl shadow-xs">
                            Monitoring: <strong class="text-indigo-950 dark:text-indigo-100 font-extrabold">{{ trim($currentSiteName) }}</strong>
                        </span>
                    </div>
                </form>
            </div>

            <!-- METRICS DASHBOARD -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Combined Total -->
                <div class="metric-card metric-total">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Tugas</span>
                            <h3 class="text-3xl font-black text-slate-800 dark:text-slate-100 mt-2">{{ $combinedTotal }}</h3>
                        </div>
                        <div class="bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 p-3 rounded-2xl">
                            <i class="bi bi-list-task text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-xs text-slate-600 dark:text-slate-455 mt-3 font-semibold">
                        Gabungan Project Activity & Timeline Request
                    </div>
                </div>

                <!-- Combined Done -->
                <div class="metric-card metric-done">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Selesai (Done)</span>
                            <h3 class="text-3xl font-black text-emerald-700 dark:text-emerald-455 mt-2">{{ $combinedDone }}</h3>
                        </div>
                        <div class="bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 p-3 rounded-2xl">
                            <i class="bi bi-check2-circle text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-xs mt-3 flex items-center justify-between">
                        <span class="text-slate-600 dark:text-slate-400 font-semibold">Persentase Selesai</span>
                        <span class="font-extrabold text-emerald-700 dark:text-emerald-400 text-sm">{{ $donePercentage }}%</span>
                    </div>
                </div>

                <!-- Combined Undone -->
                <div class="metric-card metric-undone">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Belum Selesai (Undone)</span>
                            <h3 class="text-3xl font-black text-amber-700 dark:text-amber-455 mt-2">{{ $combinedUndone }}</h3>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 p-3 rounded-2xl">
                            <i class="bi bi-hourglass-split text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-xs mt-3 flex items-center justify-between">
                        <span class="text-slate-600 dark:text-slate-400 font-semibold">Persentase Belum Selesai</span>
                        <span class="font-extrabold text-amber-700 dark:text-amber-450 text-sm">{{ $undonePercentage }}%</span>
                    </div>
                </div>
            </div>

            <!-- PROGRESS BAR DISPLAY -->
            <div class="glass-card p-6">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-300 m-0">Progres Penyelesaian Project / Implementasi</h4>
                    <span class="text-xs font-extrabold text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 px-2.5 py-1 rounded-lg">{{ $donePercentage }}% Complete</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-4 overflow-hidden border border-slate-200/50 dark:border-slate-700/50 p-0.5">
                    <div class="bg-gradient-to-r from-amber-400 via-indigo-500 to-emerald-500 h-2.5 rounded-full transition-all duration-750 ease-out shadow-xs" style="width: {{ $donePercentage }}%"></div>
                </div>
                <div class="flex justify-between text-[11px] text-slate-555 dark:text-slate-500 font-bold mt-2">
                    <span>0%</span>
                    <span>Tengah Jalan</span>
                    <span>100% Selesai</span>
                </div>
            </div>

            <!-- LIST UNFINISHED TASKS (2-COLUMN GRID) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- LEFT COLUMN: UNFINISHED PROJECT ACTIVITY -->
                <div class="glass-card p-6 flex flex-col h-[600px]">
                    <div class="border-b border-slate-150 dark:border-slate-700/50 pb-3 mb-4 flex items-center justify-between flex-shrink-0">
                        <h3 class="text-base font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2 m-0">
                            <i class="bi bi-activity text-indigo-500"></i>
                            Project Activity <span class="text-xs font-bold text-slate-550 dark:text-slate-550">(Belum Selesai)</span>
                        </h3>
                        <span class="inline-flex items-center justify-center bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-850 text-[10px] font-extrabold px-2 py-1 rounded-lg shadow-xs">
                            {{ $unfinishedPA->count() }} Item
                        </span>
                    </div>

                    <div class="flex-grow overflow-y-auto pr-1 space-y-4 custom-scrollbar">
                        @forelse ($unfinishedPA as $pa)
                            @php
                                $priName = trim($pa->prioritas->namaprioritas ?? 'Low');
                                $borderClass = match($priName) {
                                    'Very High', 'Critical' => 'task-critical',
                                    'High' => 'task-high',
                                    'Medium' => 'task-medium',
                                    default => 'task-low',
                                };
                                $badgeClass = match($priName) {
                                    'Very High', 'Critical' => 'bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-900',
                                    'High' => 'bg-orange-50 dark:bg-orange-950/30 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-900',
                                    'Medium' => 'bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900',
                                    default => 'bg-green-50 dark:bg-green-950/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-900',
                                };
                            @endphp
                            <div class="p-4 bg-white dark:bg-slate-850 border border-slate-150 dark:border-slate-700/60 rounded-2xl task-card {{ $borderClass }} shadow-xs space-y-3">
                                <div class="flex justify-between items-start">
                                    <span class="inline-flex items-center px-2 py-1 text-[10px] font-extrabold rounded-lg border {{ $badgeClass }}">
                                        {{ $priName }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 text-[10px] font-extrabold rounded-lg bg-slate-50 dark:bg-slate-750 text-slate-700 dark:text-slate-350 border border-slate-200 dark:border-slate-650">
                                        Status: {{ $pa->status }}
                                    </span>
                                </div>

                                <p class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-relaxed m-0">
                                    {{ $pa->task }}
                                </p>

                                <div class="border-t border-slate-150 dark:border-slate-750 pt-2.5 flex justify-between items-center text-[10px] text-slate-550 dark:text-slate-555 font-bold">
                                    <div class="flex items-center gap-1">
                                        <i class="bi bi-person-fill text-slate-500"></i>
                                        <span>PIC: <strong class="text-slate-700 dark:text-slate-300">{{ $pa->pic->namapegawai ?? '-' }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span>Masuk: <strong class="text-slate-700 dark:text-slate-300">{{ $pa->tgl_masuk }}</strong></span>
                                        @php
                                            $isOverdue = strtotime($pa->tgl_deadline) < time();
                                        @endphp
                                        <span class="{{ $isOverdue ? 'text-red-600 dark:text-red-400 font-bold' : '' }}">
                                            Deadline: <strong class="{{ $isOverdue ? 'text-red-700 dark:text-red-455' : 'text-slate-700 dark:text-slate-300' }}">{{ $pa->tgl_deadline }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 text-slate-500 dark:text-slate-500 bg-white dark:bg-slate-850/50 border border-dashed border-slate-200 dark:border-slate-700 rounded-2xl my-auto">
                                <i class="bi bi-clipboard-check text-3xl block mb-2 text-emerald-600 dark:text-emerald-455"></i>
                                <span class="text-xs font-bold">Semua Project Activity Selesai!</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- RIGHT COLUMN: UNFINISHED TIMELINE REQUEST (JADWAL) -->
                <div class="glass-card p-6 flex flex-col h-[600px]">
                    <div class="border-b border-slate-150 dark:border-slate-700/50 pb-3 mb-4 flex items-center justify-between flex-shrink-0">
                        <h3 class="text-base font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2 m-0">
                            <i class="bi bi-clock-history text-indigo-500"></i>
                            Timeline Request <span class="text-xs font-bold text-slate-550 dark:text-slate-550">(Belum Selesai)</span>
                        </h3>
                        <span class="inline-flex items-center justify-center bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-850 text-[10px] font-extrabold px-2 py-1 rounded-lg shadow-xs">
                            {{ $unfinishedTR->count() }} Item
                        </span>
                    </div>

                    <div class="flex-grow overflow-y-auto pr-1 space-y-4 custom-scrollbar">
                        @forelse ($unfinishedTR as $tr)
                            @php
                                $priName = trim($tr->namaprioritas ?? 'Low');
                                $borderClass = match($priName) {
                                    'Very High', 'Critical' => 'task-critical',
                                    'High' => 'task-high',
                                    'Medium' => 'task-medium',
                                    default => 'task-low',
                                };
                                $badgeClass = match($priName) {
                                    'Very High', 'Critical' => 'bg-red-50 dark:bg-red-955/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-900',
                                    'High' => 'bg-orange-50 dark:bg-orange-955/30 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-900',
                                    'Medium' => 'bg-amber-50 dark:bg-amber-955/30 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900',
                                    default => 'bg-green-50 dark:bg-green-955/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-900',
                                };
                            @endphp
                            <div class="p-4 bg-white dark:bg-slate-850 border border-slate-150 dark:border-slate-700/60 rounded-2xl task-card {{ $borderClass }} shadow-xs space-y-3">
                                <div class="flex justify-between items-start">
                                    <span class="inline-flex items-center px-2 py-1 text-[10px] font-extrabold rounded-lg border {{ $badgeClass }}">
                                        {{ $priName }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 text-[10px] font-extrabold rounded-lg bg-slate-50 dark:bg-slate-750 text-slate-700 dark:text-slate-350 border border-slate-200 dark:border-slate-650">
                                        Status: {{ $tr->final_status ?? 'Not Yet' }}
                                    </span>
                                </div>

                                <p class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-relaxed m-0">
                                    {{ $tr->task }}
                                </p>

                                <div class="border-t border-slate-150 dark:border-slate-750 pt-2.5 flex flex-col gap-1.5 text-[10px] text-slate-550 dark:text-slate-555 font-bold">
                                    <div class="flex justify-between">
                                        <div class="flex items-center gap-1">
                                            <i class="bi bi-person-fill text-slate-500"></i>
                                            <span>Req: <strong class="text-slate-700 dark:text-slate-300">{{ $tr->pic_requestor ?? '-' }}</strong></span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <i class="bi bi-laptop text-slate-500"></i>
                                            <span>Dev: <strong class="text-slate-700 dark:text-slate-300">{{ $tr->pic_developer ?? '-' }}</strong></span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center pt-1">
                                        <span>Masuk: <strong class="text-slate-700 dark:text-slate-300">{{ $tr->tgl_masuk }}</strong></span>
                                        @php
                                            $isOverdue = strtotime($tr->tgl_deadline) < time();
                                        @endphp
                                        <span class="{{ $isOverdue ? 'text-red-500 dark:text-red-400 font-bold' : '' }}">
                                            Deadline: <strong class="{{ $isOverdue ? 'text-red-700 dark:text-red-455' : 'text-slate-700 dark:text-slate-300' }}">{{ $tr->tgl_deadline }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 text-slate-500 dark:text-slate-500 bg-white dark:bg-slate-850/50 border border-dashed border-slate-200 dark:border-slate-700 rounded-2xl my-auto">
                                <i class="bi bi-clipboard-check text-3xl block mb-2 text-emerald-600 dark:text-emerald-455"></i>
                                <span class="text-xs font-bold">Semua Timeline Request Selesai!</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: PROJECT INITIATION (INISIASI PROJECT WON) -->
        <div x-show="activeTab === 'project-initiation'" class="space-y-6">
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 mb-2">
                    <i class="bi bi-rocket-takeoff text-indigo-650 dark:text-indigo-400"></i>
                    Inisiasi Project Baru
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 font-semibold">
                    Modul-modul hasil deal kontrak marketing yang akan diimplementasikan. Delegasikan PIC Implementator proyek sekali di tingkat proyek, dan pantau progres detail checklist masing-masing modul di bawah ini.
                </p>
            </div>

            @forelse ($wonProjects as $won)
                <div class="glass-card p-6 space-y-4">
                    <!-- Project Header -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between border-b pb-3 mb-2 gap-4 border-slate-100 dark:border-slate-800">
                        <div>
                            <h4 class="text-md font-extrabold text-indigo-900 dark:text-indigo-300 flex items-center gap-2 m-0">
                                {{ $won->project_name }}
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $won->status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 border border-indigo-200' }}">
                                    {{ $won->status }}
                                </span>
                            </h4>
                            <div class="text-[11px] text-slate-500 font-bold mt-1 mb-0 flex flex-wrap items-center gap-1.5">
                                <span>Site: <strong class="text-slate-700 dark:text-slate-350">{{ $won->site->namasite }}</strong></span> | 
                                <span>Koordinator: <strong class="text-slate-700 dark:text-slate-350">{{ $won->picKoordinator->namapegawai }}</strong></span> | 
                                <span>Target Go-Live: <strong class="text-slate-700 dark:text-slate-350">{{ \Carbon\Carbon::parse($won->target_go_live)->format('d M Y') }}</strong></span> |
                                
                                <!-- Project-level Delegation (Implementator) -->
                                <span>Implementator: 
                                    @php
                                        $userPegawaiId = Auth::user()->pegawai_id;
                                        $userRole = Auth::user()->role;
                                        $isKoordinator = ($won->pic_koordinator_id == $userPegawaiId);
                                        $canDelegate = in_array($userRole, ['admin', 'manager']) || $isKoordinator;
                                    @endphp
                                    
                                    @if ($won->picImplementator)
                                        <strong class="text-indigo-650 dark:text-indigo-400 font-black">{{ $won->picImplementator->namapegawai }}</strong>
                                        @if ($canDelegate)
                                            <button type="button" onclick="toggleAssignForm('{{ $won->id }}')" class="p-0 border-0 bg-transparent text-[10px] text-indigo-500 hover:text-indigo-700 font-bold ml-1.5 focus:outline-none"><i class="bi bi-pencil-fill"></i> Ubah</button>
                                        @endif
                                    @else
                                        <span class="text-rose-600 dark:text-rose-455 font-bold"><i class="bi bi-exclamation-triangle"></i> Belum Ditugaskan</span>
                                        @if ($canDelegate)
                                            <button type="button" onclick="toggleAssignForm('{{ $won->id }}')" class="p-0 border-0 bg-transparent text-[10px] text-indigo-500 hover:text-indigo-700 font-bold ml-1.5 focus:outline-none"><i class="bi bi-person-plus-fill"></i> Tugaskan</button>
                                        @endif
                                    @endif
                                </span>
                            </div>

                            @if ($canDelegate)
                                <form id="assign-implementator-form-{{ $won->id }}" method="POST" action="{{ route('project.tracker.assign_implementator', $won->id) }}" class="hidden mt-2 max-w-sm flex items-center gap-1.5 bg-slate-50 dark:bg-slate-800/40 p-2.5 rounded-xl border border-slate-150 dark:border-slate-700">
                                    @csrf
                                    <select name="pic_implementator_id" required class="form-select form-select-sm p-1 text-[11px] font-bold border-slate-350 dark:border-slate-700 dark:bg-slate-850 dark:text-white rounded-lg">
                                        <option value="" disabled selected>-- Pilih Implementator --</option>
                                        @foreach($programmers as $prog)
                                            <option value="{{ $prog->id }}" {{ $won->pic_implementator_id == $prog->id ? 'selected' : '' }}>{{ $prog->namapegawai }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1 bg-indigo-600 text-white font-extrabold text-[10px] rounded-lg border-0 shadow-sm transition-all hover:bg-indigo-700 uppercase tracking-wider">Simpan</button>
                                    <button type="button" onclick="toggleAssignForm('{{ $won->id }}')" class="px-3 py-1 bg-slate-200 text-slate-750 font-semibold text-[10px] rounded-lg border-0 hover:bg-slate-300">Batal</button>
                                </form>
                            @endif

                            @php
                                $totalWbs = $won->wbs->count();
                                $doneWbs = $won->wbs->where('status', 'DONE')->count();
                                $wbsProgress = $totalWbs > 0 ? round(($doneWbs / $totalWbs) * 100) : 0;
                            @endphp
                            <div class="mt-2.5 flex items-center gap-2.5">
                                <span class="text-[10px] font-extrabold text-slate-550 uppercase tracking-wider">Overall WBS Progress:</span>
                                <div class="w-36 bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden border border-slate-200 dark:border-slate-705">
                                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2 rounded-full transition-all" style="width: {{ $wbsProgress }}%"></div>
                                </div>
                                <span class="text-[11px] font-black text-emerald-655 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900 px-2 py-0.5 rounded-md">{{ $wbsProgress }}%</span>
                            </div>
                        </div>
                        <div class="text-md-end text-xs font-bold text-slate-655 dark:text-slate-400">
                            Nilai Kontrak: <strong class="text-emerald-700 dark:text-emerald-400 text-sm">Rp {{ number_format($won->nilai_kontrak, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <!-- List of Modules (Won Details) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($won->details as $detail)
                            <div class="bg-white dark:bg-slate-850 border border-slate-150 dark:border-slate-750 p-4 rounded-xl shadow-xs space-y-3" id="module-card-{{ $detail->id }}">
                                <div class="flex justify-between items-start">
                                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-200 m-0">{{ $detail->modul_name }}</h5>
                                    <span id="status-badge-{{ $detail->id }}" class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold 
                                        {{ $detail->status === 'Done' ? 'bg-green-100 text-green-800' : ($detail->status === 'On Progress' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-800') }}">
                                        {{ $detail->status }}
                                    </span>
                                </div>

                                <!-- Progress Bar -->
                                <div class="space-y-1">
                                    <div class="flex justify-between text-[10px] text-slate-550 font-bold">
                                        <span>Progress</span>
                                        <span id="progress-text-{{ $detail->id }}">{{ $detail->progress }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                        <div id="progress-bar-{{ $detail->id }}" class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ $detail->progress }}%"></div>
                                    </div>
                                </div>

                                <!-- Assignment & Actions -->
                                <div class="border-t border-slate-100 dark:border-slate-800 pt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center gap-1.5 text-slate-655 dark:text-slate-400 font-semibold">
                                        <i class="bi bi-check2-square text-indigo-500"></i>
                                        @php
                                            $totalChecklists = $detail->checklists->count();
                                            $checkedChecklists = $detail->checklists->where('is_checked', true)->count();
                                        @endphp
                                        <span id="checklist-count-{{ $detail->id }}" class="font-bold text-slate-700 dark:text-slate-300">
                                            Checklist: {{ $checkedChecklists }}/{{ $totalChecklists }} Selesai
                                        </span>
                                    </div>

                                    <div class="flex gap-1.5 justify-end">
                                        <button type="button" 
                                                data-checklists="{{ json_encode($detail->checklists) }}"
                                                onclick="openDetailModulModal(this, '{{ $detail->id }}', '{{ addslashes($detail->modul_name) }}')" 
                                                class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-705 font-bold rounded text-[10px] border-0 transition-colors flex items-center gap-1">
                                            <i class="bi bi-card-checklist text-xs"></i> Detail Modul
                                        </button>
                                    </div>
                                </div>

                                <!-- Notes/Keterangan if any -->
                                @if ($detail->keterangan)
                                    <div class="bg-slate-50 dark:bg-slate-800/40 p-2 rounded text-[10px] border border-slate-100 dark:border-slate-800 text-slate-655 dark:text-slate-400 font-semibold">
                                        <strong>Ket:</strong> {{ $detail->keterangan }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="glass-card p-12 text-center text-slate-500">
                    <i class="bi bi-rocket-takeoff-fill text-4xl block mb-2 text-slate-350"></i>
                    <span class="text-xs font-bold">Belum ada proyek yang diserahterimakan (Won).</span>
                </div>
            @endforelse
        </div>

    </div>

    <!-- MODAL DETAIL MODUL CHECKLIST -->
    <dialog id="detail-modul-modal" class="p-0 rounded-2xl border-0 shadow-2xl w-full max-w-md bg-white dark:bg-slate-900 dark:text-white">
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between border-b pb-3 border-slate-100 dark:border-slate-800">
                <div class="flex flex-col">
                    <h3 class="text-base font-extrabold text-slate-800 dark:text-white flex items-center gap-1.5 m-0" id="detail-modul-modal-name">
                        <i class="bi bi-card-checklist text-indigo-550"></i> Detail Checklist Modul
                    </h3>
                    <span class="text-[10px] text-slate-500 font-semibold mt-0.5">Tandai checklist pekerjaan untuk memperbarui progres otomatis.</span>
                </div>
                <button type="button" onclick="document.getElementById('detail-modul-modal').close()" class="bg-transparent border-0 text-slate-400 hover:text-slate-750 text-xl font-bold p-0 leading-none">&times;</button>
            </div>
            
            <div id="detail-modul-checklists-container" class="space-y-2 max-h-96 overflow-y-auto pr-1">
                <!-- Populated dynamically via Javascript -->
            </div>

            <div class="flex items-center justify-end gap-2 border-t pt-3 border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('detail-modul-modal').close()" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-750 dark:bg-slate-800 dark:text-slate-300 text-xs font-extrabold rounded-xl border-0 shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </dialog>

    <script>
        function toggleAssignForm(wonId) {
            const form = document.getElementById(`assign-implementator-form-${wonId}`);
            if (form) {
                form.classList.toggle('hidden');
            }
        }

        let activeDetailButton = null;

        function openDetailModulModal(button, detailId, modulName) {
            activeDetailButton = button;
            document.getElementById('detail-modul-modal-name').innerHTML = `<i class="bi bi-card-checklist text-indigo-550"></i> ${modulName}`;
            
            const rawChecklists = button.getAttribute('data-checklists');
            const checklists = JSON.parse(rawChecklists || '[]');
            
            const container = document.getElementById('detail-modul-checklists-container');
            container.innerHTML = '';

            if (checklists.length === 0) {
                container.innerHTML = `<div class="text-center py-4 text-slate-400 italic">Tidak ada detail checklist untuk modul ini.</div>`;
            } else {
                checklists.forEach(item => {
                    const checkedAttr = item.is_checked ? 'checked' : '';
                    const dateVal = item.checked_at ? new Date(item.checked_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}) : '-';
                    
                    const rowDiv = document.createElement('div');
                    rowDiv.className = "flex items-start justify-between p-3.5 bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-750 rounded-xl gap-3";
                    rowDiv.innerHTML = `
                        <div class="flex items-start gap-2.5">
                            <input type="checkbox" 
                                   data-checklist-id="${item.id}" 
                                   data-detail-id="${detailId}"
                                   onchange="toggleChecklistItem(this)" 
                                   ${checkedAttr}
                                   class="form-check-input mt-1 accent-indigo-600 w-4.5 h-4.5 border-slate-300 dark:border-slate-600 rounded-md">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-snug">${item.nama_detail}</span>
                                <span class="text-[9px] text-slate-400 font-semibold mt-0.5" id="date-label-${item.id}">Selesai: ${dateVal}</span>
                            </div>
                        </div>
                    `;
                    container.appendChild(rowDiv);
                });
            }

            document.getElementById('detail-modul-modal').showModal();
        }

        function toggleChecklistItem(checkbox) {
            const checklistId = checkbox.getAttribute('data-checklist-id');
            const detailId = checkbox.getAttribute('data-detail-id');
            const isChecked = checkbox.checked ? 1 : 0;

            checkbox.disabled = true;

            fetch('{{ route("project.tracker.checklist.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    checklist_id: checklistId,
                    is_checked: isChecked
                })
            })
            .then(res => res.json())
            .then(data => {
                checkbox.disabled = false;
                if (data.success) {
                    // Update check date label in modal
                    const dateLabel = document.getElementById(`date-label-${checklistId}`);
                    if (dateLabel) {
                        dateLabel.innerText = `Selesai: ${data.checked_at}`;
                    }

                    // Update parent card in the UI
                    const progressText = document.getElementById(`progress-text-${detailId}`);
                    const progressBar = document.getElementById(`progress-bar-${detailId}`);
                    const statusBadge = document.getElementById(`status-badge-${detailId}`);
                    const checklistCountLabel = document.getElementById(`checklist-count-${detailId}`);

                    if (progressText) progressText.innerText = `${data.progress}%`;
                    if (progressBar) progressBar.style.width = `${data.progress}%`;
                    
                    if (statusBadge) {
                        statusBadge.innerText = data.status;
                        statusBadge.className = `inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold ` +
                            (data.status === 'Done' ? 'bg-green-100 text-green-800' : (data.status === 'On Progress' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-800'));
                    }

                    // Recalculate checked count from modal checkboxes
                    const modalCheckboxes = document.querySelectorAll('#detail-modul-checklists-container input[type="checkbox"]');
                    const checkedCount = Array.from(modalCheckboxes).filter(cb => cb.checked).length;
                    const totalCount = modalCheckboxes.length;
                    
                    if (checklistCountLabel) {
                        checklistCountLabel.innerText = `Checklist: ${checkedCount}/${totalCount} Selesai`;
                    }

                    // Update data-checklists JSON on opening button to stay in sync
                    if (activeDetailButton) {
                        const checklists = JSON.parse(activeDetailButton.getAttribute('data-checklists') || '[]');
                        const item = checklists.find(c => c.id === checklistId);
                        if (item) {
                            item.is_checked = checkbox.checked;
                            item.checked_at = data.checked_at !== '-' ? new Date() : null;
                            activeDetailButton.setAttribute('data-checklists', JSON.stringify(checklists));
                        }
                    }
                }
            })
            .catch(err => {
                checkbox.disabled = false;
                checkbox.checked = !checkbox.checked; // Revert checkbox state
                console.error(err);
            });
        }
    </script>
</x-app-layout>

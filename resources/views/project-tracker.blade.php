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

    <div class="tracker-container p-6 max-w-7xl mx-auto space-y-6">

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
                <div class="text-xs text-slate-600 dark:text-slate-450 mt-3 font-semibold">
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
            <div class="flex justify-between text-[11px] text-slate-550 dark:text-slate-500 font-bold mt-2">
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

                            <div class="border-t border-slate-150 dark:border-slate-750 pt-2.5 flex justify-between items-center text-[10px] text-slate-550 dark:text-slate-550 font-bold">
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
                                        Deadline: <strong class="{{ $isOverdue ? 'text-red-700 dark:text-red-400' : 'text-slate-700 dark:text-slate-300' }}">{{ $pa->tgl_deadline }}</strong>
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
                                'Very High', 'Critical' => 'bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-900',
                                'High' => 'bg-orange-50 dark:bg-orange-950/30 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-900',
                                'Medium' => 'bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900',
                                default => 'bg-green-50 dark:bg-green-950/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-900',
                            };
                            $finalStatus = trim($tr->final_status ?? 'Not Yet');
                            $statusBadgeClass = match($finalStatus) {
                                'Pending' => 'bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900',
                                'Revisi' => 'bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-900',
                                'Request Production' => 'bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-900',
                                default => 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-650',
                            };
                        @endphp
                        <div class="p-4 bg-white dark:bg-slate-850 border border-slate-150 dark:border-slate-700/60 rounded-2xl task-card {{ $borderClass }} shadow-xs space-y-3">
                            <div class="flex justify-between items-center">
                                <div class="flex gap-1.5 items-center">
                                    <span class="inline-flex items-center px-2 py-1 text-[10px] font-extrabold rounded-lg border {{ $badgeClass }}">
                                        {{ $priName }}
                                    </span>
                                    <span class="text-[10px] font-mono font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded">
                                        {{ $tr->kd_list }}-{{ $tr->nourut }}
                                    </span>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 text-[10px] font-extrabold rounded-lg border {{ $statusBadgeClass }}">
                                    Status: {{ $finalStatus }}
                                </span>
                            </div>

                            <p class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-relaxed m-0">
                                {{ $tr->task }}
                            </p>

                            @if($tr->timeline_name)
                                <div class="text-[10px] text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-850 rounded-lg px-2.5 py-1 font-bold inline-block shadow-xs">
                                    <i class="bi bi-flag-fill me-1"></i> Timeline: {{ $tr->timeline_name }}
                                </div>
                            @endif

                            <div class="border-t border-slate-150 dark:border-slate-750 pt-2.5 space-y-1 text-[10px] text-slate-555 dark:text-slate-555 font-bold">
                                <div class="flex justify-between items-center">
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
</x-app-layout>

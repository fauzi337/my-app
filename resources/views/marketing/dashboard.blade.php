<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lime-600 dark:text-lime-400 text-xl leading-tight" style="font-size: 32px">
            {{ __('Marketing & Sales Pipeline') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">
        
        <!-- Toast Notifications -->
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300 px-4 py-3 rounded-xl shadow-md border border-emerald-300 dark:border-emerald-800 transition-all duration-300">
                <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- TOP STATS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Revenue Pipeline Card -->
            <div class="premium-card p-6 flex items-center justify-between hover-lift">
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Revenue Pipeline</span>
                    <h3 class="text-2xl font-bold text-slate-800">Rp {{ number_format($revenuePipeline, 0, ',', '.') }}</h3>
                    <p class="text-xs text-slate-450 mt-1">Estimasi nilai dari lead aktif</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-lime-500/10 flex items-center justify-center text-lime-600 text-xl font-bold">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>

            <!-- Aging Leads Card -->
            <div class="premium-card p-6 flex items-center justify-between hover-lift">
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Lead Aging (>14 Hari)</span>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $agingLeadsCount }} Lead</h3>
                    <p class="text-xs text-slate-450 mt-1">Butuh interaksi/follow-up segera</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600 text-xl font-bold">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>

            <!-- Follow-up Hari Ini Card -->
            <div class="premium-card p-6 flex items-center justify-between hover-lift">
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Follow-up Hari Ini</span>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $followupsToday->count() }} Lead</h3>
                    <p class="text-xs text-slate-450 mt-1">Jadwal kontak hari ini</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 text-xl font-bold">
                    <i class="bi bi-telephone-outbound"></i>
                </div>
            </div>

            <!-- Conversion Rate Card -->
            <div class="premium-card p-6 flex items-center justify-between hover-lift">
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Conversion Rate</span>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $conversionRate }}%</h3>
                    <p class="text-xs text-slate-450 mt-1">Persentase Qualified → Won</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 text-xl font-bold">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>

        </div>

        <!-- PIPELINE FUNNEL VISUALIZATION -->
        <div class="premium-card p-6">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2 mb-4">
                <i class="bi bi-filter-square text-lime-500"></i> Pipeline Funnel (Jumlah Lead per Stage)
            </h3>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4 text-center">
                @php
                    $stagesMeta = [
                        'New' => ['bg' => 'bg-slate-100 dark:bg-slate-800', 'text' => 'text-slate-700 dark:text-slate-300', 'border' => 'border-slate-200 dark:border-slate-700'],
                        'Qualified' => ['bg' => 'bg-indigo-50 dark:bg-indigo-950/30', 'text' => 'text-indigo-700 dark:text-indigo-300', 'border' => 'border-indigo-200 dark:border-indigo-900/30'],
                        'Demo' => ['bg' => 'bg-blue-50 dark:bg-blue-950/30', 'text' => 'text-blue-700 dark:text-blue-300', 'border' => 'border-blue-200 dark:border-blue-900/30'],
                        'Proposal' => ['bg' => 'bg-purple-50 dark:bg-purple-950/30', 'text' => 'text-purple-700 dark:text-purple-300', 'border' => 'border-purple-200 dark:border-purple-900/30'],
                        'Negotiation' => ['bg' => 'bg-amber-50 dark:bg-amber-950/30', 'text' => 'text-amber-700 dark:text-amber-300', 'border' => 'border-amber-250 dark:border-amber-900/30'],
                        'Won' => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/30', 'text' => 'text-emerald-700 dark:text-emerald-300', 'border' => 'border-emerald-250 dark:border-emerald-900/30'],
                        'Lost' => ['bg' => 'bg-red-50 dark:bg-red-950/30', 'text' => 'text-red-700 dark:text-red-300', 'border' => 'border-red-250 dark:border-red-900/30'],
                        'Nurture' => ['bg' => 'bg-teal-50 dark:bg-teal-950/30', 'text' => 'text-teal-700 dark:text-teal-300', 'border' => 'border-teal-250 dark:border-teal-900/30'],
                    ];
                @endphp

                @foreach($funnelStats as $status => $count)
                    <div class="p-3 rounded-xl border {{ $stagesMeta[$status]['border'] }} {{ $stagesMeta[$status]['bg'] }} flex flex-col justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider {{ $stagesMeta[$status]['text'] }}">{{ $status }}</span>
                        <span class="text-2xl font-extrabold mt-2 text-slate-800">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- FILTER BAR & ACTIVE LEADS -->
        <div class="premium-card p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-800 m-0">
                        Daftar Lead Aktif
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Daftar calon faskes dalam sales pipeline</p>
                </div>
                <a href="{{ route('marketing.leads.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-lime-600 hover:bg-lime-700 text-white text-xs font-bold rounded-lg shadow-sm transition-all uppercase tracking-wider no-underline">
                    <i class="bi bi-plus-lg"></i> Tambah Lead Baru
                </a>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('marketing.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 bg-slate-50 p-4 rounded-xl mb-6 border border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Status Pipeline</label>
                    <select name="status" class="form-select text-xs mt-1 border-slate-300 rounded-lg w-full">
                        <option value="">-- Semua Status --</option>
                        @foreach($allStatuses as $st)
                            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">PIC Internal</label>
                    <select name="pic" class="form-select text-xs mt-1 border-slate-300 rounded-lg w-full">
                        <option value="">-- Semua PIC --</option>
                        @foreach($pics as $p)
                            <option value="{{ $p->id }}" {{ request('pic') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Provinsi</label>
                    <select name="provinsi" class="form-select text-xs mt-1 border-slate-300 rounded-lg w-full">
                        <option value="">-- Semua Provinsi --</option>
                        @foreach($provinsis as $prov)
                            <option value="{{ $prov }}" {{ request('provinsi') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Cari Kota</label>
                    <input type="text" name="kota" class="form-control text-xs mt-1 border-slate-300 rounded-lg w-full" placeholder="Nama kota..." value="{{ request('kota') }}">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-all uppercase tracking-wider border-0 shadow-sm">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('marketing.dashboard') }}" class="w-full flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-lg transition-all uppercase tracking-wider no-underline shadow-sm">
                        Reset
                    </a>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-100 align-middle">
                    <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider font-bold">
                        <tr>
                            <th class="px-4 py-3 text-center w-24">Aksi</th>
                            <th class="px-4 py-3 text-left">Nama Institusi</th>
                            <th class="px-4 py-3 text-left">Jenis</th>
                            <th class="px-4 py-3 text-left">Kota/Provinsi</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-right">Nilai Kontrak (Est)</th>
                            <th class="px-4 py-3 text-left">PIC Internal</th>
                            <th class="px-4 py-3 text-left">Follow-up</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white text-xs">
                        @forelse($activeLeads as $lead)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                                    <a href="{{ route('marketing.leads.show', $lead->id) }}" class="inline-flex items-center justify-center w-7 h-7 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-colors" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('marketing.leads.edit', $lead->id) }}" class="inline-flex items-center justify-center w-7 h-7 bg-amber-500 hover:bg-amber-600 text-white rounded-lg shadow-sm transition-colors" title="Edit Lead">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                                <td class="px-4 py-3 font-semibold text-slate-800 whitespace-nowrap">{{ $lead->nama_institusi }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 border border-slate-200 text-slate-700">
                                        {{ str_replace('_', ' ', $lead->jenis_institusi) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-600">{{ $lead->kota }}, {{ $lead->provinsi }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $badges = [
                                            'New' => 'bg-slate-100 text-slate-800 border-slate-200',
                                            'Qualified' => 'bg-indigo-50 text-indigo-700 border-indigo-250',
                                            'Demo' => 'bg-blue-50 text-blue-700 border-blue-250',
                                            'Proposal' => 'bg-purple-50 text-purple-700 border-purple-250',
                                            'Negotiation' => 'bg-amber-50 text-amber-700 border-amber-250',
                                            'Won' => 'bg-emerald-50 text-emerald-700 border-emerald-250',
                                            'Lost' => 'bg-red-50 text-red-700 border-red-250',
                                            'Nurture' => 'bg-teal-50 text-teal-700 border-teal-250',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badges[$lead->pipeline_status] }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        {{ $lead->pipeline_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-slate-800 whitespace-nowrap">Rp {{ number_format($lead->estimasi_nilai, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-700">{{ $lead->picInternal->name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($lead->tanggal_followup_berikutnya)
                                        <div class="flex items-center gap-1 text-slate-600 font-semibold">
                                            <i class="bi bi-calendar-event text-xs"></i>
                                            <span>{{ \Carbon\Carbon::parse($lead->tanggal_followup_berikutnya)->format('Y-m-d') }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-slate-400">
                                    <i class="bi bi-inbox text-4xl block mb-2 text-slate-350"></i>
                                    <span class="font-semibold">Belum ada Lead yang terdaftar.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</x-app-layout>
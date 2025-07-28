<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-orange-600/75 text-xl text-gray-800 leading-tight" style="font-size: 50px">
            {{ __('Timeline Request') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta name="description" content="Aplikasi Agenda dan Manajemen Jadwal.">
                <meta name="author" content="Nama Anda / Tim Developer">
                <title>Dashboard Timeline</title>

                <!-- Favicon -->
                <link rel="icon" type="image/png" href="/favicon.png" />

                <!-- Tailwind CSS -->
                @vite('resources/css/app.css')

                <!-- Bootstrap 5.3 -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

                <!-- Bootstrap Icons -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

                <!-- Alpine.js -->
                <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

                <!-- Optional: Custom Font (Google Fonts) -->
                <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

                <!-- Optional: Dark mode theme or custom style -->
                <style>
                    body {
                        font-family: 'Inter', sans-serif;
                        background-color: #f9fafb;
                    }
                </style>
            </head>

            <form method="POST" action="{{ route('dashboard.jadwalpost') }}" class="p-6 bg-white shadow-md rounded-md space-y-4">
                @csrf

                {{-- <h2 class="text-6xl font-semibold text-orange-600/75">Timeline Request</h2> --}}
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Prioritas</label>
                        <select name="prioritas" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                            @foreach ($prioritas as $itemprioritas)
                                {{-- <option value="{{ $pagawe->kdjenispegawai }}"> - {{ $pagawe->namapegawai }}</option> --}}
                                <option value="{{ $itemprioritas->id }}">{{ $itemprioritas->namaprioritas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Jenis Task</label>
                        <select name="jenistask" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                            @foreach ($jenisTask as $itemJenisTask)
                                {{-- <option value="{{ $pagawe->kdjenispegawai }}"> - {{ $pagawe->namapegawai }}</option> --}}
                                <option value="{{ $itemJenisTask->id }}">{{ $itemJenisTask->jenistask }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Site</label>
                        <select name="site" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                            @foreach ($site as $itemSite)
                                {{-- <option value="{{ $pagawe->kdjenispegawai }}"> - {{ $pagawe->namapegawai }}</option> --}}
                                <option value="{{ $itemSite->id }}">{{ $itemSite->namasite }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Pengerjaan</label>
                        <select name="timeline" id="timelineSelect" class="w-full border-gray-300 rounded-lg p-2 focus:ring-lime-500 focus:border-lime-500">
                            @foreach ($timeline as $itemTimeline)
                                {{-- <option value="{{ $pagawe->kdjenispegawai }}"> - {{ $pagawe->namapegawai }}</option> --}}
                                <option data-deadline="{{ \Carbon\Carbon::parse($itemTimeline->tgl_deadline)->format('Y-m-d') }}" value="{{ $itemTimeline->id }}">{{ $itemTimeline->week }} {{ $itemTimeline->month }} {{ $itemTimeline->year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Task Masuk</label>
                        {{-- <input type="date" name="task_masuk" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500"> --}}
                        <input type="date" name="task_masuk" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500 @error('task_masuk') @enderror" value="{{ old('task_masuk',date('Y-m-d')) }}">

                    </div>
                    {{-- <div class="relative max-w-sm">
                        <label class="block text-sm font-medium">Task Masuk</label>
                        <input datepicker-autohide type="text" class="datepicker-input border border-gray-300 rounded-lg p-2 w-full focus:ring-indigo-500 focus:border-indigo-500" placeholder="Pilih tanggal" />
                    </div> --}}
                    <div>
                        <label class="block text-sm font-medium">Task Deadline</label>
                        {{-- <input type="date" name="task_deadline" class="w-full border-gray-300 rounded-lg p-2 focus:ring-lime-500 focus:border-lime-500"> --}}
                        <input type="date" name="task_deadline" id="taskDeadline" class="w-full border-gray-300 rounded-lg p-2 focus:ring-lime-500 focus:border-lime-500" 
                        value="{{ old('task_deadline', isset($selectedTimeline) ? \Carbon\Carbon::parse($selectedTimeline->tgl_deadline)->format('Y-m-d') : '') }}">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Task</label>
                    <textarea name="task" class="datepicker-input border border-gray-300 rounded-lg p-2 w-full focus:ring-red-500 focus:border-red-500" rows="3" placeholder="Isi Task..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-sm font-medium">PIC Request</label>
                        <select name="picrequest" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                            @foreach ($picReq as $itemPicReq)
                                {{-- <option value="{{ $pagawe->kdjenispegawai }}"> - {{ $pagawe->namapegawai }}</option> --}}
                                <option value="{{ $itemPicReq->id }}">{{ $itemPicReq->namalengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">PIC Developer</label>
                        <select name="pegawai" class="w-full border-gray-300 rounded-lg p-2 focus:ring-lime-500 focus:border-lime-500">
                            @foreach ($pegawai as $pagawe)
                                {{-- <option value="{{ $pagawe->kdjenispegawai }}"> - {{ $pagawe->namapegawai }}</option> --}}
                                <option value="{{ $pagawe->id }}">{{ $pagawe->kdjenispegawai }} - {{ $pagawe->namapegawai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: none">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" class="w-full border-gray-300 rounded-lg p-2 focus:ring-yellow-500 focus:border-yellow-500">
                            </div>
                            <div class="col-span-1">
                                <label class="block text-sm font-medium">Developer Status</label>
                                <select name="devst" class="w-full border-gray-300 rounded-lg p-2 focus:ring-yellow-500 focus:border-yellow-500">
                                    @foreach ($statusDev as $itemStatusDev)
                                        <option value="{{ $itemStatusDev->id }}">{{ $itemStatusDev->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div>
                            <label class="block text-sm font-medium">Server Status</label>
                            <select name="servetst" class="w-full border-gray-300 rounded-lg p-2 focus:ring-yellow-500 focus:border-yellow-500">
                                @foreach ($statusServer as $itemStatusServer)
                                    <option value="{{ $itemStatusServer->id }}">{{ $itemStatusServer->status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">PIC Req Status</label>
                            <select name="picreqst" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                                @foreach ($statusPicReq as $itemStatusPicReq)
                                    <option value="{{ $itemStatusPicReq->id }}">{{ $itemStatusPicReq->status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Final Status</label>
                            <select name="finalst" class="w-full border-gray-300 rounded-lg p-2 focus:ring-red-500 focus:border-red-500">
                                @foreach ($statusFinal as $itemStatusFinal)
                                    <option value="{{ $itemStatusFinal->id }}">{{ $itemStatusFinal->status }}</option>
                                @endforeach
                            </select>
                        </div>  
                    </div>
                    
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <button type="submit" class="flex items-center gap-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 ease-in-out transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan
                    </button>
                    {{-- <a href="{{ route('antrian.index') }}" class="flex items-center gap-1 px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-sm no-underline font-semibold rounded-lg shadow-sm transition-all duration-200 ease-in-out transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Halaman Utama
                    </a> --}}
                    <a href="{{ route('dashboard.dev') }}" class="flex items-center gap-1 px-3 py-1.5 bg-blue-400 hover:bg-blue-600 text-white text-sm no-underline font-semibold rounded-lg shadow-sm transition-all duration-200 ease-in-out transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                        </svg>
                        Dashboard Developer
                    </a>
                    <a href="{{ route('dashboard.picreq') }}" class="flex items-center gap-1 px-3 py-1.5 bg-blue-400 hover:bg-blue-600 text-white text-sm no-underline font-semibold rounded-lg shadow-sm transition-all duration-200 ease-in-out transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                        </svg>
                        Dashboard PIC Request
                    </a>
                </div>
                {{-- <div class="grid grid-cols-1 md:grid-cols-8 gap-4">
                <div class="grid-col-2">
                        <a href="{{ route('antrian.index') }}" class="px-4 py-2 bg-orange-400 text-white rounded-md hover:bg-gray-700">Halaman Utama</a>
                    </div>
                    <div class="grid-col-2">
                        <a href="{{ route('dashboard.dev') }}" class="px-4 py-2 bg-blue-400 text-white rounded-md hover:bg-gray-700">Dashboard Developer</a>
                    </div>
                    <div class="grid-col-2">
                        <a href="{{ route('dashboard.picreq') }}" class="px-4 py-2 bg-blue-400 text-white rounded-md hover:bg-gray-700">Dashboard PIC Request</a>
                    </div>
                </div> --}}

            
                <div class="flex items-center col-2">
                    
                </div>

            </form>
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
                    class="fixed top-5 right-5 z-50 flex items-start gap-3 bg-green-100 text-green-800 px-4 py-3 rounded-lg shadow-md border border-green-300"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
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
                    class="fixed top-5 right-5 z-50 flex items-start gap-3 bg-red-100 text-red-800 px-4 py-3 rounded-lg shadow-md border border-red-300"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="text-sm font-medium">{{ $errors->first() }}</span>
                </div>
            @endif

            <script src="https://unpkg.com/flowbite@1.6.5/dist/datepicker.js"></script>
            <script>
            document.getElementById('timelineSelect').addEventListener('change', function () { 
                debugger
                const selectedOption = this.options[this.selectedIndex];
                const deadline = selectedOption.getAttribute('data-deadline');

                if (deadline) {
                    document.getElementById('taskDeadline').value = deadline;
                }
            });
            </script>

            </html>

            <h2 class="text-xl font-semibold mt-8 mb-4">Daftar Timeline Request</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">KD-List</th>
                            <th class="px-4 py-2 text-left">Prioritas</th>
                            <th class="px-4 py-2 text-left">Jenis Task</th>
                            <th class="px-4 py-2 text-left">Site</th>
                            <th class="px-4 py-2 text-left">Task</th>
                            <th class="px-4 py-2 text-left">Timeline</th>
                            <th class="px-4 py-2 text-left">Tanggal Masuk</th>
                            <th class="px-4 py-2 text-left">Tanggal Deadline</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($daftarJadwal as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->kd_list }}</td>
                                <td class="px-4 py-2">{{ $item->namaprioritas }}</td>
                                <td class="px-4 py-2">{{ $item->jenistask }}</td>
                                <td class="px-4 py-2">{{ $item->namasite }}</td>
                                <td class="px-4 py-2">{{ $item->task }}</td>
                                <td class="px-4 py-2">{{ $item->gabung }}</td>
                                <td class="px-4 py-2">{{ $item->tgl_masuk ? \Carbon\Carbon::parse($item->tgl_masuk)->format('Y-m-d') : '-' }}</td>
                                <td class="px-4 py-2">{{ $item->tgl_deadline ? \Carbon\Carbon::parse($item->tgl_deadline)->format('Y-m-d') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
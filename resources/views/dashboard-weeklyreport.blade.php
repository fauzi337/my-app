<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Aplikasi Agenda dan Manajemen Jadwal.">
    <meta name="author" content="Nama Anda / Tim Developer">
    <title>Weekly Report</title>

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
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css"> --}}

    <!-- Optional: Dark mode theme or custom style -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
    </style>
</head>

<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-lg rounded-xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-5xl font-bold text-orange-600">📋 Weekly Report</h2>
        </div>

        
           <div class="flex gap-3 text-sm">
                @if(empty($resultWeeklyReport))
                    <div class="flex items-center px-4 py-2 rounded-full font-semibold text-red-700 bg-[#ffafaf] shadow-sm">
                        <span class="text-base font-semibold">Report</span>
                    </div>
                @else
                    <div class="flex items-center px-4 py-2 rounded-full font-semibold text-green-800 bg-[#bbffbe] shadow-sm">
                        <span class="text-base font-semibold">Result</span>
                    </div>
                    
                    <div class="flex items-center px-4 py-2 rounded-full font-semibold text-red-800 bg-[#ff8080] shadow-sm">
                        <span class="text-base font-semibold">Follow-Up</span>
                    </div>
                @endif
            </div>
            <div class="overflow-x-auto">
                @if(empty($resultWeeklyReport))
                <form method="POST" action="{{ route('save.weekly') }}" class="p-6 bg-white shadow-md rounded-md space-y-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        @if(empty($resultWeeklyReport))
                            <button type="submit" class="flex items-center gap-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 ease-in-out transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan
                            </button>
                             <a href="{{ route('dashboard.picreq') }}"
                                class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-3 py-1.5 rounded-md no-underline shadow-sm transition duration-200 ease-in-out hover:scale-105">
                                <!-- Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </a>
                        @endif
                    </div>
                    @csrf
                    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                {{-- <th class="px-4 py-2 text-left">KD-List</th> --}}
                                <th class="px-4 py-2 text-left">Kegiatan</th>
                                <th class="px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($weeklyReport as $index => $item)
                                <tr>
                                    {{-- <td class="px-4 py-2">
                                        {{ $item->kd_list }}
                                        
                                    </td> --}}
                                    <td class="px-4 py-2">
                                        {{ $item->dailyreport }}
                                        <input type="hidden" name="data[{{ $index }}][dailyreport]" value="{{ $item->dailyreport }}">
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $item->status }}
                                        <input type="hidden" name="data[{{ $index }}][status]" value="{{ $item->status }}">
                                        <input type="hidden" name="data[{{ $index }}][kd_list]" value="{{ $item->kd_list }}">
                                        <input type="hidden" name="data[{{ $index }}][week]" value="{{ $item->week }}">
                                        <input type="hidden" name="data[{{ $index }}][month]" value="{{ $item->month }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>    
                @else
                {{-- Tombol kembali dengan margin top dan desain menarik --}}
                <div class="mt-6 flex justify-start">
                    <a href="{{ route('dashboard.picreq') }}"
                        class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-3 py-1.5 rounded-md no-underline shadow-sm transition duration-200 ease-in-out hover:scale-105">
                        <!-- Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                </div>
                <div class="mt-6 flex justify-start">
                    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Kegiatan</th>
                                <th class="px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($resultWeeklyReport as $item)
                             @php
                                $cleanStatus = trim(strtolower($item->status));
                            @endphp
                                <tr @if(!in_array($cleanStatus, ['done', 'selesai'])) style="background-color: #ff8080;" @endif>
                                    <td class="px-4 py-2">{{ $item->weeklyreport }}</td>
                                    <td class="px-4 py-2">{{ $item->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

            </div>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
</html>


</main>
{{-- <script>
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if (session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

    @if (session('info'))
        toastr.info("{{ session('info') }}");
    @endif
</script> --}}
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

@if (session('error'))
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
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
@endif

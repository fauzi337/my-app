<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Antrian Sembako</title>
    @vite('resources/css/app.css') <!-- Ini untuk load Tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</head>
<body class="bg-gray-100 min-h-screen p-8">
    {{-- <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md p-6"> --}}
    {{-- <div class="grid grid-cols-1 gap-4"> --}}
        {{-- <div class="flex justify-between items-center mb-6"> --}}
    <div class="flex gap-4 mb-6">
        {{-- <a href="{{ route('antrian.jumlah') }}" class="px-4 py-2 bg-blue-400 text-white rounded-md hover:bg-gray-700">
            Jumlah Antrian
        </a> --}}
        <a href="{{ route('dashboard.jadwal') }}" class="px-4 py-2 bg-lime-400 text-white rounded-md hover:bg-gray-700">
            Jadwal
        </a>
         <a href="{{ route('dashboard.agenda') }}" class="px-4 py-2 bg-lime-400 text-white rounded-md hover:bg-gray-700">
            Agenda
        </a>
    </div>


        {{-- <h1 class="text-2xl font-bold mb-6">Form Antrian Sembako</h1>

        <form method="POST" action="/antrian" class="grid grid-cols-1 md:grid-cols-2 gap-6">
              @if (session('success'))
                    <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
              @endif
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="nama" placeholder="Nama"
                    value="{{ old('nama') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>


           <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="kategori" class="mt-1 block w-full bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <optgroup label="Usia Lanjut" class="text-red-600">
                        <option value="orang_tua" >Orang Tua</option>
                    </optgroup>
                    <optgroup label="Generasi Muda" class="text-green-600">
                        <option value="anak_muda" >Anak Muda</option>
                    </optgroup>
                </select>
            </div>


            <div class="md:col-span-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Ambil Antrian</button>
            </div>
        </form> --}}

        {{-- @if ($errors->any())
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 4000)"
                x-show="show"
                x-transition.opacity.duration.300ms
                class="fixed top-5 right-5 z-50 w-full max-w-sm bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg"
                style="pointer-events: auto">
                <div class="flex items-start justify-between">
                    <div>
                        <strong class="font-semibold">Kesalahan:</strong>
                        <ul class="mt-1 text-sm list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button @click="show = false" class="text-xl leading-none ml-4">&times;</button>
                </div>
            </div>
        @endif --}}


        @if (session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition
                class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                    <h2 class="text-lg font-semibold text-green-600 mb-2">Berhasil</h2>
                    <p class="text-sm text-gray-700 mb-4">{{ session('success') }}</p>
                    <div class="text-right">
                        <button
                            @click="show = false"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                        >Tutup</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- <h2 class="text-xl font-semibold mt-8 mb-4">Daftar Antrian</h2>
       <ul class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($antrians as $antrian)
                <li class="bg-gray-50 p-3 rounded shadow-sm">
                    {{ $antrian->formatted_nomor_antrian }} - {{ $antrian->nama }}
                </li>
                <div>
                    <a href="delete-antrian/{{$antrian->id}}" type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-gray-700">Delete</a>
                </div>
            @endforeach
        </ul> --}}

    </div>
</body>

<!-- TOAST ERROR -->
@if ($errors->any())
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 4000)"
        x-show="show"
        x-transition.opacity.duration.300ms
        class="fixed top-5 right-5 z-50 w-[320px] bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg"
    >
        <div class="flex justify-between items-start">
            <div class="text-sm">
                <strong class="font-semibold text-red-600">Peringatan:</strong>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        {{-- <li>{{ $error }}</li> --}}
                        <li>{{ "Nama Mohon di Isi !" }}</li>
                    @endforeach
                </ul>
            </div>
            <button @click="show = false" class="ml-3 text-lg font-bold leading-none hover:text-red-900">&times;</button>
        </div>
    </div>
@endif

</html> 
<style>
    display: flex;
    align-items: center;
    justify-content: center;
</style>

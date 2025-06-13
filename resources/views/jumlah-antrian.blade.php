<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jumlah Antrian</title>
    @vite('resources/css/app.css') <!-- Ini untuk load Tailwind -->
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Jumlah Antrian Sembako</h1>

        <div class="bg-gray-50 p-4 rounded-md shadow-sm">
            <p class="text-lg font-semibold">Total Antrian: <span class="text-indigo-600">{{ $jumlahAntrian }}</span></p>
        </div>

        <div class="mt-6">
            <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-800">Kembali</a>
        </div>
    </div>
</body>
</html>

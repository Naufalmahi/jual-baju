<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa - Toko & Koperasi Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-indigo-900 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
        <!-- HEADER LOGIN -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full mb-3">
                <i class="fas fa-user-graduate text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Portal Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">Masukkan Nomor Induk Siswa (NIS)</p>
        </div>

        <!-- NOTIFIKASI ERROR -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm rounded">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- FORM LOGIN SISWA -->
        <form action="{{ route('login.siswa.process') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">NIS (Angka)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-id-card"></i>
                    </span>
                    <!-- inputmode="numeric" & pattern="[0-9]*" memaksa keyboard HP menampilkan tombol angka -->
                    <input type="text" name="nisn" inputmode="numeric" pattern="[0-9]*" value="{{ old('nisn') }}" required autofocus 
                        placeholder="Contoh: 202610123" 
                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" required 
                        placeholder="••••••••" 
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <button type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg text-sm transition shadow-md">
                Masuk Portal Siswa
            </button>
        </form>

        <div class="text-center mt-6">
            <!-- <a href="{{ route('login.petugas') }}" class="text-xs text-indigo-600 hover:underline font-semibold">
                Login sebagai Petugas Toko / Admin? <i class="fas fa-arrow-right ml-1"></i>
            </a> -->
        </div>
    </div>

</body>
</html>
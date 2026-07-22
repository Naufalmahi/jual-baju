@extends($layout)

@section('title', 'Halaman Dalam Pemeliharaan')
@section('page_title', 'Pemeliharaan Sistem')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] bg-white rounded-xl shadow p-8 text-center">
    <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-4xl mb-4">
        <i class="fas fa-tools"></i>
    </div>

    <span class="px-3 py-1 bg-amber-100 text-amber-800 font-mono font-bold text-xs rounded-full mb-3">
        HTTP 503 - SERVICE UNAVAILABLE
    </span>

    <h2 class="text-2xl font-bold text-gray-800 mb-2">Halaman Sedang Dalam Pemeliharaan</h2>
    <p class="text-gray-500 text-sm max-w-md mb-6">
        Akses untuk fitur/halaman <span class="font-bold text-gray-700 capitalize">{{ $feature }}</span> saat ini sedang dinonaktifkan sementara oleh Super Admin.
    </p>

    <div class="flex items-center gap-3">
        <a href="javascript:history.back()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>
@endsection
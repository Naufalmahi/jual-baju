@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')
@section('page_title', 'Pengaturan Profil Sekolah')

@section('content')
<div class="max-w-2xl bg-white p-6 rounded-xl shadow">
    <form action="{{ route('superadmin.settings.update') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-600 mb-1">Nama Sekolah / Koperasi</label>
            <input type="text" name="school_name" value="{{ $settings['school_name'] ?? '' }}" placeholder="Contoh: Koperasi SMK Negeri 1" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 mb-1">Alamat Sekolah</label>
            <textarea name="school_address" rows="3" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">{{ $settings['school_address'] ?? '' }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 mb-1">Nomor Telepon / Kontak</label>
            <input type="text" name="school_phone" value="{{ $settings['school_phone'] ?? '' }}" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>

        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg text-sm transition">
            Simpan Pengaturan
        </button>
    </form>
</div>
@endsection
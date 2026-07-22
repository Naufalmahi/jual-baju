@extends('layouts.admin')

@section('title', 'Dashboard Admin Toko')
@section('page_title', 'Dashboard Admin Toko / Koperasi')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow border-l-4 border-emerald-500 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase">Total Produk</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalProducts }}</p>
        </div>
        <i class="fas fa-boxes text-emerald-500 text-3xl"></i>
    </div>

    <div class="bg-white p-6 rounded-xl shadow border-l-4 border-amber-500 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase">Stok Menipis (<=5)</p>
            <p class="text-2xl font-bold text-gray-800">{{ $lowStockProducts->count() }}</p>
        </div>
        <i class="fas fa-exclamation-triangle text-amber-500 text-3xl"></i>
    </div>

    <div class="bg-white p-6 rounded-xl shadow border-l-4 border-blue-500 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase">Total Kasir</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalKasir }}</p>
        </div>
        <i class="fas fa-cash-register text-blue-500 text-3xl"></i>
    </div>

    <div class="bg-white p-6 rounded-xl shadow border-l-4 border-purple-500 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase">Total Siswa</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalSiswa }}</p>
        </div>
        <i class="fas fa-user-graduate text-purple-500 text-3xl"></i>
    </div>
</div>

@if($lowStockProducts->count() > 0)
<div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl shadow mb-6">
    <h3 class="text-sm font-bold text-amber-800 mb-2"><i class="fas fa-bell mr-1"></i> Peringatan Stok Barang Menipis</h3>
    <ul class="list-disc list-inside text-xs text-amber-700 space-y-1">
        @foreach($lowStockProducts as $item)
            <li><strong>{{ $item->name }}</strong> — Sisa Stok: <span class="font-bold text-red-600">{{ $item->stock }} {{ $item->unit }}</span></li>
        @endforeach
    </ul>
</div>
@endif
@endsection
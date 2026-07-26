<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $query = Order::whereIn('status', ['Selesai', 'selesai', 'SELESAI'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if ($request->filled('payment_method') && $request->payment_method !== 'semua') {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->get();

        $totalTransaksi = $orders->count();
        $totalPendapatan = $orders->sum('total_amount');
        $rataRata = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;
        
        $orderIds = $orders->pluck('id');
        $totalProdukTerjual = OrderItem::whereIn('order_id', $orderIds)->sum('quantity');

        // Produk Terlaris
        $topProducts = OrderItem::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(price * quantity) as total_revenue'))
            ->whereIn('order_id', $orderIds)
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('kasir.reports.index', compact(
            'startDate', 'endDate', 'totalTransaksi', 'totalPendapatan', 'rataRata', 'totalProdukTerjual', 'topProducts'
        ));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $orders = Order::with('user')
            ->whereIn('status', ['Selesai', 'selesai', 'SELESAI'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();

        $filename = "Laporan_Penjualan_{$startDate}_sampai_{$endDate}.csv";
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No. Pesanan', 'Siswa', 'Metode', 'Total Transaksi', 'Tanggal Selesai']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_code,
                    $order->user->name ?? 'Siswa',
                    strtoupper($order->payment_method),
                    $order->total_amount,
                    $order->updated_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
<?php

namespace App\Http\Controllers\superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Testimoni;
use App\Models\Link;

class DashboardSuperAdminController extends Controller
{
    public function index()
    {
        $pelanggan = User::where('role', 'user')->count();
        $totalProduk = \App\Models\Produk::count();
        $totalKategori = \App\Models\KategoriProduk::count();
        $totalPesanan = \App\Models\Pesanan::count();
        $pendapatan = \App\Models\Pesanan::whereIn('status', ['settlement', 'capture', 'success'])->sum('total_harga');

        // Data for chart (e.g., Pesanan per bulan in current year)
        $chartData = \App\Models\Pesanan::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')->toArray();

        // Fill empty months with 0
        $pesananPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $pesananPerBulan[] = $chartData[$i] ?? 0;
        }

        return view('pagesuperadmin.dashboard.index', compact(
            'pelanggan', 
            'totalProduk', 
            'totalKategori', 
            'totalPesanan', 
            'pendapatan',
            'pesananPerBulan'
        ));
    }
}

<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $end_date = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        // Hanya pesanan yang LUNAS
        $pesanans = Pesanan::with('user')
            ->where('status', '!=', 'UNPAID')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->latest()
            ->get();

        $totalPendapatan = $pesanans->sum('total_harga');
        
        return view('pagesuperadmin.laporan.index', compact('pesanans', 'start_date', 'end_date', 'totalPendapatan'));
    }

    public function print(Request $request)
    {
        $start_date = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $end_date = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        $pesanans = Pesanan::with('user')
            ->where('status', '!=', 'UNPAID')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->latest()
            ->get();

        $totalPendapatan = $pesanans->sum('total_harga');

        return view('pagesuperadmin.laporan.print', compact('pesanans', 'start_date', 'end_date', 'totalPendapatan'));
    }
}

<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with('user')->latest()->get();
        return view('pagesuperadmin.pesanan.index', compact('pesanans'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with('user')->findOrFail($id);
        return view('pagesuperadmin.pesanan.detail', compact('pesanan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_pengiriman' => 'required|string|in:Pesanan Sedang Diproses,Pesanan Diantar,Pesanan Telah Sampai'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status_pengiriman = $request->status_pengiriman;
        $pesanan->save();

        return redirect()->back()->with('success', 'Status pengiriman berhasil diperbarui!');
    }
}

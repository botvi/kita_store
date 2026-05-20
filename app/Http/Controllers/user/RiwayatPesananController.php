<?php

namespace App\Http\Controllers\user;

use App\Models\Pesanan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class RiwayatPesananController extends Controller
{
    public function index()
    {
        if(!Auth::check()) return redirect()->route('login');

        $pesanans = Pesanan::where('user_id', Auth::id())->latest()->get();
        return view('pageuser.riwayat_transaksi.index', compact('pesanans'));
    }

    public function cancel(Request $request, $id)
    {
        $pesanan = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $details = is_string($pesanan->produk_id) ? json_decode($pesanan->produk_id, true) : $pesanan->produk_id;
        
        if (is_array($details)) {
            foreach ($details as $detail) {
                if (isset($detail['id'])) {
                    $produk = \App\Models\Produk::find($detail['id']);
                    if ($produk) {
                        $produk->stok += $detail['qty'];
                        $produk->save();
                    }
                }
            }
        }

        $pesanan->delete();

        return response()->json(['status' => 'success', 'message' => 'Pesanan berhasil dibatalkan.']);
    }
}

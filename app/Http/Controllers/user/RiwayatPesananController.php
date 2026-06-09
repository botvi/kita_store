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

        // Ambil ID produk unik dari semua pesanan untuk dimuat sekaligus
        $produkIds = [];
        foreach ($pesanans as $pesanan) {
            $details = is_string($pesanan->produk_id) ? json_decode($pesanan->produk_id, true) : $pesanan->produk_id;
            if (is_array($details)) {
                foreach ($details as $detail) {
                    if (isset($detail['id'])) {
                        $produkIds[] = $detail['id'];
                    }
                }
            }
        }
        $produkIds = array_unique($produkIds);
        $produks = \App\Models\Produk::whereIn('id', $produkIds)->get()->keyBy('id');

        // Ambil semua ulasan yang pernah ditulis oleh user ini
        $ulasans = \App\Models\Ulasan::where('user_id', Auth::id())->get()->groupBy(function($item) {
            return $item->pesanan_id . '-' . $item->produk_id;
        });

        return view('pageuser.riwayat_transaksi.index', compact('pesanans', 'produks', 'ulasans'));
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

    public function storeUlasan(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'produk_id' => 'required|exists:produks,id',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|max:1000',
        ]);

        $pesanan = Pesanan::where('id', $request->pesanan_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Validasi status pengiriman harus selesai
        if ($pesanan->status_pengiriman != 'Pesanan Telah Sampai') {
            return redirect()->back()->with('error', 'Anda hanya dapat memberikan ulasan pada pesanan yang sudah selesai/telah sampai.');
        }

        // Validasi produk memang ada di dalam pesanan ini
        $details = is_string($pesanan->produk_id) ? json_decode($pesanan->produk_id, true) : $pesanan->produk_id;
        $produkExist = false;
        if (is_array($details)) {
            foreach ($details as $detail) {
                if (isset($detail['id']) && $detail['id'] == $request->produk_id) {
                    $produkExist = true;
                    break;
                }
            }
        }

        if (!$produkExist) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan dalam pesanan ini.');
        }

        // Cek jika sudah pernah memberikan ulasan untuk produk ini pada pesanan ini
        $existing = \App\Models\Ulasan::where('user_id', Auth::id())
            ->where('pesanan_id', $request->pesanan_id)
            ->where('produk_id', $request->produk_id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini pada transaksi ini.');
        }

        \App\Models\Ulasan::create([
            'user_id' => Auth::id(),
            'pesanan_id' => $request->pesanan_id,
            'produk_id' => $request->produk_id,
            'rating' => $request->rating,
            'ulasan' => $request->ulasan,
        ]);

        return redirect()->back()->with('success', 'Ulasan Anda berhasil dikirim, terima kasih!');
    }
}

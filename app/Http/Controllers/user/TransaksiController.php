<?php

namespace App\Http\Controllers\user;

use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\APIMidtrans;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TransaksiController extends Controller
{
    public function summary()
    {
        $keranjangs = Keranjang::where('user_id', Auth::id())->get();
        if ($keranjangs->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $totalHarga = 0;
        foreach ($keranjangs as $item) {
            if ($item->model_produk) {
                $produkDetail = is_string($item->produk_id) ? json_decode($item->produk_id, true) : $item->produk_id;
                $hargaSatuan = $produkDetail['harga_satuan'] ?? $item->model_produk->harga;
                $totalHarga += $hargaSatuan * $item->qty;
            }
        }

        return view('pageuser.pesanan.summary', compact('keranjangs', 'totalHarga'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string|max:1000'
        ]);

        $keranjangs = Keranjang::where('user_id', Auth::id())->get();
        
        if ($keranjangs->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $totalHarga = 0;
        $produkList = [];

        foreach ($keranjangs as $item) {
            $model_produk = $item->model_produk;
            if (!$model_produk) continue; // Produk mungkin sudah dihapus

            // Pindahkan detail produk dan pilihan variasi ke array
            $produkDetail = is_string($item->produk_id) ? json_decode($item->produk_id, true) : $item->produk_id;
            
            $hargaSatuan = $produkDetail['harga_satuan'] ?? $model_produk->harga;
            $harga_item = $hargaSatuan * $item->qty;
            $totalHarga += $harga_item;
            
            $produkList[] = [
                'id' => $model_produk->id,
                'nama_produk' => $model_produk->nama_produk,
                'varian' => $produkDetail['varian'] ?? '',
                'ukuran' => $produkDetail['ukuran'] ?? '',
                'harga_satuan' => $hargaSatuan,
                'qty' => $item->qty,
                'subtotal' => $harga_item,
                'foto_custom' => $produkDetail['foto_custom'] ?? ''
            ];

            // Kurangi stok
            if ($model_produk->stok !== null) {
                $model_produk->stok = max(0, $model_produk->stok - $item->qty);
                $model_produk->save();
            }
        }

        if (empty($produkList)) {
            return redirect()->route('keranjang.index')->with('error', 'Produk dalam keranjang tidak valid.');
        }

        $orderId = 'ORD-' . time() . '-' . Auth::id();

        // Get Midtrans Config
        $midtransConfig = APIMidtrans::first();
        $serverKey = $midtransConfig ? $midtransConfig->server_key : '';
        
        $snapToken = null;

        if ($serverKey) {
            $payload = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $totalHarga,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                'item_details' => array_map(function($item) {
                    return [
                        'id' => $item['id'],
                        'price' => $item['harga_satuan'],
                        'quantity' => $item['qty'],
                        'name' => mb_substr($item['nama_produk'] . ' ' . $item['varian'] . ' ' . $item['ukuran'], 0, 50)
                    ];
                }, $produkList)
            ];

            $response = Http::withBasicAuth($serverKey, '')
                ->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $payload);

            if ($response->successful()) {
                $snapToken = $response->json('token');
            }
        }

        // Buat pesanan. produk_id akan otomatis dicast ke JSON oleh Model.
        $pesanan = Pesanan::create([
            'user_id' => Auth::id(),
            'order_id' => $orderId,
            'produk_id' => $produkList, 
            'alamat' => $request->alamat,
            'total_harga' => $totalHarga,
            'status' => 'UNPAID', // Default
            'snap_token' => $snapToken
        ]);

        // Bersihkan keranjang
        Keranjang::where('user_id', Auth::id())->delete();

        // Arahkan ke midtrans/payment form jika ada, atau ke riwayat pesanan (kini diarahkan ke riwayat)
        return redirect()->route('riwayat-pesanan.index')->with('success', 'Checkout berhasil. Silakan selesaikan pembayaran.');
    }

    public function successFrontend(Request $request)
    {
        $orderId = $request->order_id;
        $pesanan = Pesanan::where('order_id', $orderId)->first();
        if ($pesanan && $pesanan->status == 'UNPAID') {
            $pesanan->status = 'PAID';
            $pesanan->save();
        }
        return response()->json(['status' => 'ok']);
    }

    public function callbackMidtrans(Request $request)
    {
        $midtransConfig = APIMidtrans::first();
        $serverKey = $midtransConfig ? $midtransConfig->server_key : '';

        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $pesanan = Pesanan::where('order_id', $request->order_id)->first();
                if ($pesanan) {
                    $pesanan->status = 'PAID';
                    $pesanan->save();
                }
            }
        }
        
        return response()->json(['message' => 'ok']);
    }
}

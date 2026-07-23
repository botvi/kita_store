<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with('user')->where('status', '!=', 'UNPAID');

        $start_date = null;
        $end_date = null;

        if ($request->filled('start_date')) {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $query->where('created_at', '>=', $start_date);
        }
        if ($request->filled('end_date')) {
            $end_date = Carbon::parse($request->end_date)->endOfDay();
            $query->where('created_at', '<=', $end_date);
        }

        $pesanans = $query->latest()->get();
        $totalPendapatan = $pesanans->sum('total_harga');
        $produks = Produk::all();

        return view('pagesuperadmin.laporan.index', compact('pesanans', 'start_date', 'end_date', 'totalPendapatan', 'produks'));
    }

    public function print(Request $request)
    {
        $query = Pesanan::with('user')->where('status', '!=', 'UNPAID');

        $start_date = null;
        $end_date = null;

        if ($request->filled('start_date')) {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $query->where('created_at', '>=', $start_date);
        }
        if ($request->filled('end_date')) {
            $end_date = Carbon::parse($request->end_date)->endOfDay();
            $query->where('created_at', '<=', $end_date);
        }

        $pesanans = $query->latest()->get();
        $totalPendapatan = $pesanans->sum('total_harga');

        return view('pagesuperadmin.laporan.print', compact('pesanans', 'start_date', 'end_date', 'totalPendapatan'));
    }

    public function storeOffline(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'nullable|string|max:255',
            'items'        => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.qty'       => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $totalHarga = 0;
            $produkList = [];

            foreach ($request->items as $item) {
                $produk = Produk::find($item['produk_id']);
                if (!$produk) continue;

                $qty = (int) ($item['qty'] ?? 1);
                $ukuranPilihan = (isset($item['ukuran']) && $item['ukuran'] !== 'undefined' && $item['ukuran'] !== null) ? trim((string)$item['ukuran']) : '';
                $varianPilihan = (isset($item['varian']) && $item['varian'] !== 'undefined' && $item['varian'] !== null) ? trim((string)$item['varian']) : '';

                $hargaSatuan = isset($item['harga_satuan']) ? (float) $item['harga_satuan'] : 0;
                if ($hargaSatuan <= 0) {
                    if ($ukuranPilihan !== '' && is_array($produk->harga_per_ukuran) && !empty($produk->harga_per_ukuran[$ukuranPilihan])) {
                        $hargaSatuan = (float) $produk->harga_per_ukuran[$ukuranPilihan];
                    } else {
                        $hargaSatuan = (float) $produk->harga;
                    }
                }

                $subtotal = $hargaSatuan * $qty;
                $totalHarga += $subtotal;

                $gambarPath = (is_array($produk->gambar) && count($produk->gambar) > 0) ? $produk->gambar[0] : null;

                $produkList[] = [
                    'id'           => $produk->id,
                    'nama_produk'  => $produk->nama_produk,
                    'gambar'       => $gambarPath,
                    'varian'       => $varianPilihan,
                    'ukuran'       => $ukuranPilihan,
                    'harga_satuan' => $hargaSatuan,
                    'qty'          => $qty,
                    'subtotal'     => $subtotal,
                ];

                // Kurangi stok produk sesuai ukuran
                $stokKey = ($ukuranPilihan !== '' && is_array($produk->stok_per_ukuran) && isset($produk->stok_per_ukuran[$ukuranPilihan]))
                    ? $ukuranPilihan
                    : 'default';
                $produk->kurangiStok($stokKey, $qty);
            }

            if (empty($produkList)) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Produk tidak valid.');
            }

            $orderId = 'OFFLINE-' . time() . '-' . rand(100, 999);
            $namaPembeli = !empty($request->nama_pembeli) ? $request->nama_pembeli : 'Pembeli Offline';

            Pesanan::create([
                'user_id'           => Auth::id(),
                'nama_pembeli'      => $namaPembeli,
                'order_id'          => $orderId,
                'produk_id'         => $produkList,
                'alamat'            => 'Transaksi Offline (Di Toko)',
                'total_harga'       => $totalHarga,
                'status'            => 'PAID',
                'status_pengiriman' => 'Pesanan Telah Sampai',
            ]);

            DB::commit();

            return redirect()->route('laporan.index')->with('success', 'Penjualan offline berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }
}

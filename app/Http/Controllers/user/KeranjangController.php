<?php

namespace App\Http\Controllers\user;

use App\Models\Produk;
use App\Models\Keranjang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        $keranjangs = Keranjang::where('user_id', Auth::id())->latest()->get();
        $total = 0;
        foreach ($keranjangs as $item) {
            if ($item->model_produk) {
                $produkDetail = is_string($item->produk_id) ? json_decode($item->produk_id, true) : $item->produk_id;
                $hargaSatuan = $produkDetail['harga_satuan'] ?? $item->model_produk->harga;
                $total += $hargaSatuan * $item->qty;
            }
        }
        return view('pageuser.keranjang.index', compact('keranjangs', 'total'));
    }

    public function add(Request $request, $id)
    {
        $request->validate([
            'foto_custom' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048'
        ]);

        $produk = Produk::findOrFail($id);
        
        $varian = $request->varian ?? '';
        $ukuran = $request->ukuran ?? '';
        
        $fotoPath = '';
        if ($request->hasFile('foto_custom')) {
            $file = $request->file('foto_custom');
            $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/custom'), $fileName);
            $fotoPath = 'uploads/custom/' . $fileName;
        }
        
        $produkIdJson = [
            'id' => $produk->id,
            'varian' => $varian,
            'ukuran' => $ukuran,
            'foto_custom' => $fotoPath
        ];

        // Cek jika produk dgn varian dan ukuran sama sudah ada di keranjang
        $existingKeranjang = Keranjang::where('user_id', Auth::id())->get()->filter(function($keranjang) use ($produkIdJson) {
            
            // if produk_id is string from db, maybe it returns array or string based on cast.
            $k_produk_id = is_string($keranjang->produk_id) ? json_decode($keranjang->produk_id, true) : $keranjang->produk_id;
            
            // Jika memilik foto custom, lebih baik buat baris baru (keranjang baru khusus). 
            // Namun kalau foto_custom sama persis, bisa distack.
            return isset($k_produk_id['id']) && 
                   $k_produk_id['id'] == $produkIdJson['id'] && 
                   ($k_produk_id['varian'] ?? '') == $produkIdJson['varian'] && 
                   ($k_produk_id['ukuran'] ?? '') == $produkIdJson['ukuran'] &&
                   ($k_produk_id['foto_custom'] ?? '') == $produkIdJson['foto_custom'];
        })->first();

        if ($existingKeranjang) {
            $existingKeranjang->qty += $request->qty;
            $existingKeranjang->save();
        } else {
            Keranjang::create([
                'user_id' => Auth::id(),
                'produk_id' => $produkIdJson, // Model cast as array, do not json_encode
                'qty' => $request->qty
            ]);
        }

        return redirect()->route('keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function remove($id)
    {
        $keranjang = Keranjang::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $keranjang->delete();

        return redirect()->route('keranjang.index')->with('success', 'Produk dihapus dari keranjang');
    }
}

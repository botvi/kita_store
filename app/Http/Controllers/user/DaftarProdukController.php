<?php

namespace App\Http\Controllers\user;

use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DaftarProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori_produk');
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_produk_id', $request->kategori);
        }
        $produks = $query->latest()->get();
        $kategoris = KategoriProduk::all();

        return view('pageuser.daftar_produk.index', compact('produks', 'kategoris'));
    }

    public function show($id)
    {
        $produk = Produk::with('kategori_produk')->findOrFail($id);
        return view('pageuser.daftar_produk.show', compact('produk'));
    }
}

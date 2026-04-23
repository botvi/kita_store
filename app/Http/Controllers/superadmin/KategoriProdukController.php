<?php

namespace App\Http\Controllers\superadmin;

use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;


class KategoriProdukController extends Controller
{
    public function index()
    {
        $kategori_produks = KategoriProduk::all();
        return view('pagesuperadmin.kategori_produk.index', compact('kategori_produks'));
    }

    public function create()
    {
        return view('pagesuperadmin.kategori_produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required',
        ]);

        KategoriProduk::create($request->all());

        Alert::success('Success', 'Kategori produk berhasil ditambahkan');
        return redirect()->route('kategori-produk.index');
    }

    public function edit($id)
    {
        $kategoriproduk = KategoriProduk::findOrFail($id);
        return view('pagesuperadmin.kategori_produk.edit', compact('kategoriproduk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required',
        ]);

        $kategoriproduk = KategoriProduk::findOrFail($id);
        $kategoriproduk->update($request->all());

        Alert::success('Success', 'Kategori produk berhasil diperbarui');
        return redirect()->route('kategori-produk.index');
    }

    public function destroy($id)
    {
        $kategoriproduk = KategoriProduk::findOrFail($id);
        $kategoriproduk->delete();

        Alert::success('Success', 'Kategori produk berhasil dihapus');
        return redirect()->route('kategori-produk.index');
    }
}

<?php

namespace App\Http\Controllers\superadmin;

use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\File;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori_produk')->get();
        return view('pagesuperadmin.produk.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = KategoriProduk::all();
        return view('pagesuperadmin.produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_produk_id' => 'required',
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'harga_custom' => 'nullable|numeric',
            'stok' => 'nullable|numeric',
            'gambar' => 'nullable|array',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'varian' => 'nullable|array',
            'ukuran' => 'nullable|array'
        ]);

        $data = $request->except('gambar');
        $data['is_custom'] = $request->has('is_custom') ? 1 : 0;

        $gambarPaths = [];
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/produk'), $fileName);
                $gambarPaths[] = 'uploads/produk/' . $fileName;
            }
        }
        $data['gambar'] = $gambarPaths;

        Produk::create($data);

        Alert::success('Success', 'Produk berhasil ditambahkan');
        return redirect()->route('produk.index');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = KategoriProduk::all();
        return view('pagesuperadmin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_produk_id' => 'required',
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'harga_custom' => 'nullable|numeric',
            'stok' => 'nullable|numeric',
            'gambar' => 'nullable|array',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'varian' => 'nullable|array',
            'ukuran' => 'nullable|array'
        ]);

        $produk = Produk::findOrFail($id);
        $data = $request->except('gambar');
        $data['is_custom'] = $request->has('is_custom') ? 1 : 0;

        if ($request->hasFile('gambar')) {
            if (is_array($produk->gambar)) {
                foreach ($produk->gambar as $oldGambar) {
                    if ($oldGambar && File::exists(public_path($oldGambar))) {
                        File::delete(public_path($oldGambar));
                    }
                }
            }

            $gambarPaths = [];
            foreach ($request->file('gambar') as $file) {
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/produk'), $fileName);
                $gambarPaths[] = 'uploads/produk/' . $fileName;
            }
            $data['gambar'] = $gambarPaths;
        } else {
            $data['gambar'] = $produk->gambar;
        }

        $produk->update($data);

        Alert::success('Success', 'Produk berhasil diperbarui');
        return redirect()->route('produk.index');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        
        if (is_array($produk->gambar)) {
            foreach ($produk->gambar as $oldGambar) {
                if ($oldGambar && File::exists(public_path($oldGambar))) {
                    File::delete(public_path($oldGambar));
                }
            }
        }
        
        $produk->delete();

        Alert::success('Success', 'Produk berhasil dihapus');
        return redirect()->route('produk.index');
    }
}

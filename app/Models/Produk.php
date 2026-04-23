<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;


    protected $fillable = [
        'kategori_produk_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'gambar',
        'varian',
        'ukuran',
        'is_custom',
        'harga_custom',
    ];

    protected $casts = [
        'gambar' => 'array',
        'varian' => 'array',
        'ukuran' => 'array',
        'is_custom' => 'boolean',
    ];

    public function kategori_produk()
    {
        return $this->belongsTo(KategoriProduk::class);
    }
}

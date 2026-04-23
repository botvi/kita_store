<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'produk_id', 'qty'];

    protected $casts = [
        'produk_id' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi default belongsTo mungkin gagal jika produk_id berupa array,
    // Kita buatkan accessor untuk mengambil data produk
    public function getModelProdukAttribute()
    {
        $id = is_array($this->produk_id) ? ($this->produk_id['id'] ?? null) : $this->produk_id;
        return $id ? Produk::find($id) : null;
    }
}

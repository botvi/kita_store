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
        'stok_per_ukuran',
        'gambar',
        'varian',
        'ukuran',
        'is_custom',
        'harga_custom',
    ];

    protected $casts = [
        'gambar'          => 'array',
        'varian'          => 'array',
        'ukuran'          => 'array',
        'stok_per_ukuran' => 'array',
        'is_custom'       => 'boolean',
    ];

    public function kategori_produk()
    {
        return $this->belongsTo(KategoriProduk::class);
    }

    /**
     * Ambil stok untuk ukuran tertentu.
     * Gunakan 'default' jika produk tidak punya ukuran.
     */
    public function getStokUkuran(string $ukuran = 'default'): int
    {
        $data = $this->stok_per_ukuran;
        if (!is_array($data)) return 0;
        return (int) ($data[$ukuran] ?? 0);
    }

    /**
     * Total stok semua ukuran (untuk tampilan admin).
     */
    public function getTotalStok(): int
    {
        $data = $this->stok_per_ukuran;
        if (!is_array($data)) return (int) ($this->stok ?? 0);
        return array_sum($data);
    }

    /**
     * Kurangi stok untuk ukuran tertentu sebesar $qty.
     * Tidak akan kurangi di bawah 0.
     */
    public function kurangiStok(string $ukuran = 'default', int $qty = 1): void
    {
        $data = $this->stok_per_ukuran;
        if (!is_array($data)) $data = ['default' => (int) ($this->stok ?? 0)];

        $key = isset($data[$ukuran]) ? $ukuran : 'default';
        $data[$key] = max(0, (int) ($data[$key] ?? 0) - $qty);

        $this->stok_per_ukuran = $data;
        // Sinkronkan juga kolom stok lama
        $this->stok = array_sum($data);
        $this->save();
    }
}


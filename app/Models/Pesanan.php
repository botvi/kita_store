<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_id', 'produk_id', 'alamat', 'total_harga', 'status', 'status_pengiriman', 'snap_token', 'pdf_url', 'nama_pembeli'];

    protected $casts = [
        'produk_id' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function getNamaPelangganAttribute()
    {
        if (!empty($this->nama_pembeli)) {
            return $this->nama_pembeli;
        }
        return $this->user->name ?? 'User Dihapus';
    }
}

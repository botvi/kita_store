<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->json('stok_per_ukuran')->nullable()->after('stok');
        });

        // Migrasi data lama: isi stok_per_ukuran dari stok global dan ukuran existing
        $produks = DB::table('produks')->get();
        foreach ($produks as $produk) {
            $ukuranArr = [];
            if (!empty($produk->ukuran)) {
                $decoded = json_decode($produk->ukuran, true);
                if (is_array($decoded)) {
                    $ukuranArr = $decoded;
                }
            }

            $stokLama = $produk->stok ?? 0;

            if (count($ukuranArr) > 0) {
                // Bagi stok lama rata ke semua ukuran
                $stokPerUkuran = [];
                foreach ($ukuranArr as $ukuran) {
                    $stokPerUkuran[$ukuran] = $stokLama;
                }
            } else {
                // Tidak ada ukuran, simpan sebagai default
                $stokPerUkuran = ['default' => $stokLama];
            }

            DB::table('produks')->where('id', $produk->id)->update([
                'stok_per_ukuran' => json_encode($stokPerUkuran),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn('stok_per_ukuran');
        });
    }
};

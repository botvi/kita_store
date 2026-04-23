<?php

namespace App\Http\Controllers\user;

use App\Models\Pesanan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RiwayatPesananController extends Controller
{
    public function index()
    {
        if(!Auth::check()) return redirect()->route('login');

        $pesanans = Pesanan::where('user_id', Auth::id())->latest()->get();
        return view('pageuser.riwayat_transaksi.index', compact('pesanans'));
    }
}

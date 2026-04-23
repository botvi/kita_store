<?php

namespace App\Http\Controllers\user;

use App\Models\Produk;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $latestProduks = Produk::latest()->take(8)->get();
        return view('pageuser.landing.index', compact('latestProduks'));
    }
}
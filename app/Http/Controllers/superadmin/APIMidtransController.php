<?php

namespace App\Http\Controllers\superadmin;

use App\Models\APIMidtrans;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class APIMidtransController extends Controller
{
    public function index()
    {
        $api = APIMidtrans::first();
        return view('pagesuperadmin.api_midtrans.index', compact('api'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'merchant_id' => 'required',
            'client_key' => 'required',
            'server_key' => 'required',
        ]);

        if (APIMidtrans::count() > 0) {
            Alert::error('Error', 'API Midtrans sudah dikonfigurasi, silakan gunakan update.');
            return redirect()->back();
        }

        APIMidtrans::create($request->all());

        Alert::success('Success', 'API Midtrans berhasil ditambahkan');
        return redirect()->route('api-midtrans.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'merchant_id' => 'required',
            'client_key' => 'required',
            'server_key' => 'required',
        ]);

        $api = APIMidtrans::findOrFail($id);
        $api->update($request->all());

        Alert::success('Success', 'API Midtrans berhasil diperbarui');
        return redirect()->route('api-midtrans.index');
    }
}

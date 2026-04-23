<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilUserController extends Controller
{
    public function index()
    {
        if(!Auth::check()) return redirect()->route('login');
        $user = Auth::user();
        return view('pageuser.profil.index', compact('user'));
    }

    public function update(Request $request)
    {
        if(!Auth::check()) return redirect()->route('login');
        
        $request->validate([
            'name' => 'required',
            'no_wa' => 'required',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'no_wa' => $request->no_wa
        ]);

        return redirect()->route('profil.index')->with('success', 'Profil berhasil diupdate');
    }
}

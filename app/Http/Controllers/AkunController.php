<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Carbon\Carbon;

class AkunController extends Controller
{
    public function index()
    {
        $asset = Akun::all()->where('jenis', 'asset');
        $liabilitas = Akun::all()->where('jenis', 'liabilitas');
        $equitas = Akun::all()->where('jenis', 'equitas');
        $p = Carbon::now();
        $periode = $p->format('F Y');

        return view('neraca.index', compact('asset', 'periode', 'equitas', 'liabilitas'));
    }
}

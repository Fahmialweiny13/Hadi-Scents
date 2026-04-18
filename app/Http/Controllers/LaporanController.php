<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KasMasuk;
use App\Models\KasKeluar;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
{
    $bulan = $request->bulan ?? date('m');
    $tahun = $request->tahun ?? date('Y');

    $kasMasuk = KasMasuk::whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->get();

    $kasKeluar = KasKeluar::whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->get();

    $totalMasuk = $kasMasuk->sum('jumlah');
    $totalKeluar = $kasKeluar->sum('jumlah');
    $saldo = $totalMasuk - $totalKeluar;

    return view('laporan.index', compact(
        'kasMasuk',
        'kasKeluar',
        'totalMasuk',
        'totalKeluar',
        'saldo',
        'bulan',
        'tahun'
    ));
}
}

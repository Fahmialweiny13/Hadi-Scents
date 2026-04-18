<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use App\Models\KasKeluar;

class LaporanController extends Controller
{
    public function index(Request $request)
{
    $bulan = $request->bulan ?? date('m');
    $tahun = $request->tahun ?? date('Y');
    $pembayaran = strtolower((string) $request->pembayaran);

    if (!in_array($pembayaran, ['cash', 'qris'], true)) {
        $pembayaran = null;
    }

    $kasMasukQuery = KasMasuk::whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun);

    if ($pembayaran !== null) {
        $kasMasukQuery->whereRaw('LOWER(sumber) = ?', [$pembayaran]);
    }

    $kasMasuk = $kasMasukQuery->get();

    $kasKeluarQuery = KasKeluar::whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun);

    if ($pembayaran !== null) {
        $kasKeluarQuery->whereRaw('LOWER(tujuan) = ?', [$pembayaran]);
    }

    $kasKeluar = $kasKeluarQuery->get();

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
        'tahun',
        'pembayaran'
    ));
}
}

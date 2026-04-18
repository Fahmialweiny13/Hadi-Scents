<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // TOTAL
        $totalMasuk = DB::table('kas_masuk')->sum('jumlah');
        $totalKeluar = DB::table('kas_keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        // GRAFIK KAS MASUK
        $masukChart = DB::table('kas_masuk')
            ->select(
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
                DB::raw('SUM(jumlah) as total')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // GRAFIK KAS KELUAR
        $keluarChart = DB::table('kas_keluar')
            ->select(
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
                DB::raw('SUM(jumlah) as total')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // RIWAYAT TRANSAKSI (5 TERAKHIR)
        $kasMasuk = DB::table('kas_masuk')
            ->select(
                'tanggal',
                'sumber as pihak',
                'keterangan',
                'jumlah',
                DB::raw("'Masuk' as jenis")
            );

        $kasKeluar = DB::table('kas_keluar')
            ->select(
                'tanggal',
                'tujuan as pihak',
                'keterangan',
                'jumlah',
                DB::raw("'Keluar' as jenis")
            );

        $riwayat = $kasMasuk
            ->unionAll($kasKeluar)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalMasuk',
            'totalKeluar',
            'saldo',
            'masukChart',
            'keluarChart',
            'riwayat'
        ));
    }
}
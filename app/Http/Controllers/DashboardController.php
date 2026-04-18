<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // samakan format filter dengan halaman laporan: bulan + tahun
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        if ($bulan < 1 || $bulan > 12) {
            $bulan = (int) now()->month;
        }

        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = (int) now()->year;
        }

        $bulanTerpilih = sprintf('%04d-%02d', $tahun, $bulan);

        // total hanya untuk 1 bulan terpilih
        $totalMasuk = DB::table('kas_masuk')
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih])
            ->sum('jumlah');

        $totalKeluar = DB::table('kas_keluar')
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih])
            ->sum('jumlah');

        $saldo = $totalMasuk - $totalKeluar;

        // grafik kas masuk untuk bulan terpilih
        $masukChart = DB::table('kas_masuk')
            ->select(
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
                DB::raw('SUM(jumlah) as total')
            )
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // grafik kas keluar untuk bulan terpilih
        $keluarChart = DB::table('kas_keluar')
            ->select(
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
                DB::raw('SUM(jumlah) as total')
            )
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // riwayat transaksi (5 terakhir) untuk bulan terpilih
        $kasMasuk = DB::table('kas_masuk')
            ->select(
                'tanggal',
                'sumber as pihak',
                'keterangan',
                'jumlah',
                DB::raw("'Masuk' as jenis")
            )
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);

        $kasKeluar = DB::table('kas_keluar')
            ->select(
                'tanggal',
                'tujuan as pihak',
                'keterangan',
                'jumlah',
                DB::raw("'Keluar' as jenis")
            )
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);

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
            'riwayat',
            'bulanTerpilih',
            'bulan',
            'tahun'
        ));
    }
}
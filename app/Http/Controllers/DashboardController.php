<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // kalau bulan dan tahun diisi, berarti kita pakai mode filter
        $isFiltered = $request->filled('bulan') && $request->filled('tahun');
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        // amankan nilai bulan biar tetap valid
        if ($bulan < 1 || $bulan > 12) {
            $bulan = (int) now()->month;
        }

        // amankan nilai tahun biar tidak keluar batas
        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = (int) now()->year;
        }

        // siapkan label periode buat ditampilkan di dashboard
        $bulanTerpilih = sprintf('%04d-%02d', $tahun, $bulan);
        $periodeLabel = $isFiltered
            ? now()->setDate($tahun, $bulan, 1)->translatedFormat('F Y')
            : 'Keseluruhan';

        // mulai query total kas masuk dan keluar
        $totalMasukQuery = DB::table('kas_masuk');
        $totalKeluarQuery = DB::table('kas_keluar');

        // kalau filter aktif, batasi data sesuai bulan dan tahun
        if ($isFiltered) {
            $totalMasukQuery->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            $totalKeluarQuery->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }

        $totalMasuk = $totalMasukQuery->sum('jumlah');

        $totalKeluar = $totalKeluarQuery->sum('jumlah');

        $saldo = $totalMasuk - $totalKeluar;

        // ambil data grafik kas masuk
        $masukChart = DB::table('kas_masuk')
            ->select(
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
                DB::raw('SUM(jumlah) as total')
            )
            ->when($isFiltered, function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            })
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // ambil data grafik kas keluar
        $keluarChart = DB::table('kas_keluar')
            ->select(
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
                DB::raw('SUM(jumlah) as total')
            )
            ->when($isFiltered, function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            })
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // siapkan query riwayat kas masuk
        $kasMasuk = DB::table('kas_masuk')
            ->select(
                'tanggal',
                'sumber as pihak',
                'keterangan',
                'jumlah',
                DB::raw("'Masuk' as jenis")
            );

        if ($isFiltered) {
            $kasMasuk->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }

        // siapkan query riwayat kas keluar
        $kasKeluar = DB::table('kas_keluar')
            ->select(
                'tanggal',
                'tujuan as pihak',
                'keterangan',
                'jumlah',
                DB::raw("'Keluar' as jenis")
            );

        if ($isFiltered) {
            $kasKeluar->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }

        // gabungkan jadi satu riwayat terbaru
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
            'periodeLabel',
            'isFiltered',
            'bulan',
            'tahun'
        ));
    }
}
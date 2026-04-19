<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // filter aktif jika user mengisi bulan atau tahun dari form filter
        $rawBulan = $request->query('bulan');
        $rawTahun = $request->query('tahun');

        $hasBulanFilter = filled($rawBulan);
        $hasTahunFilter = filled($rawTahun);

        $bulan = $hasBulanFilter ? (int) $rawBulan : null;
        $tahun = $hasTahunFilter ? (int) $rawTahun : (int) now()->year;

        $isFiltered = $hasBulanFilter || $hasTahunFilter;

        // amankan nilai bulan biar tetap valid
        if (!is_null($bulan) && ($bulan < 1 || $bulan > 12)) {
            $bulan = (int) now()->month;
            $hasBulanFilter = true;
            $isFiltered = true;
        }

        // amankan nilai tahun biar tidak keluar batas
        if ($hasTahunFilter && ($tahun < 2000 || $tahun > 2100)) {
            $tahun = (int) now()->year;
            $hasTahunFilter = true;
            $isFiltered = true;
        }

        // siapkan label periode buat ditampilkan di dashboard
        $bulanTerpilih = !is_null($bulan) ? sprintf('%04d-%02d', $tahun, $bulan) : null;
        $periodeLabel = 'Keseluruhan';

        if ($isFiltered && $hasBulanFilter && $hasTahunFilter) {
            $periodeLabel = now()->setDate($tahun, $bulan, 1)->translatedFormat('F Y');
        } elseif ($isFiltered && $hasTahunFilter) {
            $periodeLabel = 'Tahun ' . $tahun;
        } elseif ($isFiltered && $hasBulanFilter) {
            $periodeLabel = now()->setDate((int) now()->year, $bulan, 1)->translatedFormat('F');
        }

        // mulai query total kas masuk dan keluar
        $totalMasukQuery = DB::table('kas_masuk');
        $totalKeluarQuery = DB::table('kas_keluar');

        // kalau filter aktif, batasi data sesuai bulan dan tahun
        if ($isFiltered) {
            if ($hasBulanFilter) {
                $totalMasukQuery->whereMonth('tanggal', $bulan);
                $totalKeluarQuery->whereMonth('tanggal', $bulan);
            }

            if ($hasTahunFilter) {
                $totalMasukQuery->whereYear('tanggal', $tahun);
                $totalKeluarQuery->whereYear('tanggal', $tahun);
            }
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
            ->when($isFiltered, function ($query) use ($hasBulanFilter, $hasTahunFilter, $bulan, $tahun) {
                if ($hasBulanFilter) {
                    $query->whereMonth('tanggal', $bulan);
                }

                if ($hasTahunFilter) {
                    $query->whereYear('tanggal', $tahun);
                }
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
            ->when($isFiltered, function ($query) use ($hasBulanFilter, $hasTahunFilter, $bulan, $tahun) {
                if ($hasBulanFilter) {
                    $query->whereMonth('tanggal', $bulan);
                }

                if ($hasTahunFilter) {
                    $query->whereYear('tanggal', $tahun);
                }
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
            if ($hasBulanFilter) {
                $kasMasuk->whereMonth('tanggal', $bulan);
            }

            if ($hasTahunFilter) {
                $kasMasuk->whereYear('tanggal', $tahun);
            }
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
            if ($hasBulanFilter) {
                $kasKeluar->whereMonth('tanggal', $bulan);
            }

            if ($hasTahunFilter) {
                $kasKeluar->whereYear('tanggal', $tahun);
            }
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
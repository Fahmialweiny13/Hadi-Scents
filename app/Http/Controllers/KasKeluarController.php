<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasKeluarController extends Controller
{
    public function index()
    {
        $data = DB::table('kas_keluar')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('kas_keluar.index', compact('data'));
    }

    public function store(Request $request)
    {
        DB::table('kas_keluar')->insert([
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'tujuan' => $request->tujuan, 
            'keterangan' => $request->keterangan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/kas-keluar');
    }

    public function update(Request $request, $id)
    {
        DB::table('kas_keluar')->where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'tujuan' => $request->tujuan, 
            'keterangan' => $request->keterangan,
            'updated_at' => now(),
        ]);

        return redirect('/kas-keluar')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        DB::table('kas_keluar')->where('id', $id)->delete();

        return redirect('/kas-keluar')->with('success', 'Data berhasil dihapus');
    }
}
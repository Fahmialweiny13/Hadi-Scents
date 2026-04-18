<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasMasukController extends Controller
{
    public function index()
    {
        $data = DB::table('kas_masuk')->orderBy('tanggal', 'desc')->get();
        return view('kas_masuk.index', compact('data'));
    }

    public function store(Request $request)
    {
        DB::table('kas_masuk')->insert([
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'sumber' => $request->sumber,
            'keterangan' => $request->keterangan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/kas-masuk');
    }
    public function update(Request $request, $id)
{
    DB::table('kas_masuk')->where('id', $id)->update([
        'tanggal' => $request->tanggal,
        'jumlah' => $request->jumlah,
        'sumber' => $request->sumber,
        'keterangan' => $request->keterangan,
        'updated_at' => now(),
    ]);

    return redirect('/kas-masuk')->with('success', 'Data berhasil diperbarui');
}

public function destroy($id)
{
    DB::table('kas_masuk')->where('id', $id)->delete();

    return redirect('/kas-masuk')->with('success', 'Data berhasil dihapus');
}

}


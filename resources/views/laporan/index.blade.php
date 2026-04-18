@extends('layouts.app')

@section('title', 'Laporan Arus Kas')

@section('content')
<h3 class="mb-4">Laporan Arus Kas Bulanan</h3>

{{-- FILTER --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="/laporan">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Bulan</label>
                    <select name="bulan" class="form-select">
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$b,1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tahun</label>
                    <input type="number"
                           name="tahun"
                           class="form-control"
                           value="{{ request('tahun', date('Y')) }}">
                </div>

                <div class="col-md-4">
                    <button class="btn btn-gold-outline w-100 fw-semibold">
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- RINGKASAN --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <small class="fw-semibold text-muted">Total Kas Masuk</small>
                <h5 class="text-success fw-bold">
                    Rp {{ number_format($totalMasuk) }}
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <small class="fw-semibold text-muted">Total Kas Keluar</small>
                <h5 class="text-danger fw-bold">
                    Rp {{ number_format($totalKeluar) }}
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <small class="fw-semibold text-muted">Saldo</small>
                <h5 class="fw-bold">
                    Rp {{ number_format($saldo) }}
                </h5>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    <!-- RINCIAN KAS MASUK -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
        <h6 class="fw-semibold mb-3">📥 Rincian Kas Masuk</h6>

        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 150px">Tanggal</th>
                    <th style="width: 150px">Sumber</th>
                    <th style="width: 100px">Jumlah</th>
                </tr>
            </thead>
            <tbody>
            @forelse($kasMasuk as $item)
                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->sumber }}</td>
                    <td class="text-success">
                        Rp {{ number_format($item->jumlah) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
             </div>
        </div>
    </div>
    

{{-- DETAIL KAS KELUAR --}}
<div class="col-md-6">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
        <h6 class="fw-semibold mb-3">📤 Rincian Kas Keluar</h6>

        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 150px">Tanggal</th>
                    <th style="width: 150px">Tujuan</th>
                    <th style="width: 100px">Jumlah</th>
                </tr>
            </thead>
            <tbody>
            @forelse($kasKeluar as $item)
                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->tujuan }}</td>
                    <td class="text-danger">
                        Rp {{ number_format($item->jumlah) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
         </div>
        </div>
    </div>
</div>
@endsection
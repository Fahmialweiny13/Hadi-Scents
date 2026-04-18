@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold mb-1">Dashboard</h2>
    <p class="text-muted mb-0">Ringkasan arus kas Hadi Scents per bulan</p>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="bulan" class="form-label fw-semibold">Bulan</label>
                    <select id="bulan" name="bulan" class="form-select">
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}" {{ (int) $bulan === $b ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$b,1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="tahun" class="form-label fw-semibold">Tahun</label>
                    <input id="tahun"
                           type="number"
                           name="tahun"
                           class="form-control"
                           value="{{ $tahun }}">
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

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="height:120px;">
            <div class="card-body">
                <h6>Total Kas Masuk</h6>
                <h4 class="text-success">
                    Rp {{ number_format($totalMasuk) }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="height:120px;">
            <div class="card-body">
                <h6>Total Kas Keluar</h6>
                <h4 class="text-danger">
                    Rp {{ number_format($totalKeluar) }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="height:120px;">
            <div class="card-body">
                <h6>Saldo</h6>
                <h4>
                    Rp {{ number_format($saldo) }}
                </h4>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="mb-3">Grafik Kas Masuk & Kas Keluar ({{ \Carbon\Carbon::createFromFormat('Y-m', $bulanTerpilih)->translatedFormat('F Y') }})</h5>
    
            <div style="height:320px;">
                <canvas id="kasChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="fw-semibold mb-0"></h5>
                <small class="text-muted"></small>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-semibold mb-0">Riwayat Transaksi {{ \Carbon\Carbon::createFromFormat('Y-m', $bulanTerpilih)->translatedFormat('F Y') }}</h5>
            
                <a href="/laporan" class="btn btn-sm btn-gold-outline">
                    Lihat Semua
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle table-fixed">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Pembayaran</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $item)
                            <tr>
                                <td>{{ $item->tanggal }}</td>
                                <td>
                                    @if($item->jenis == 'Masuk')
                                        <span class="badge bg-success">Kas Masuk</span>
                                    @else
                                        <span class="badge bg-danger">Kas Keluar</span>
                                    @endif
                                </td>
                                <td>{{ strtolower((string) $item->pihak) === 'qris' ? 'QRIS' : 'Cash' }}</td>
                                <td>{{ $item->keterangan ?? '-' }}</td>
                                <td class="fw-semibold
                                    {{ $item->jenis == 'Masuk' ? 'text-success' : 'text-danger' }}">
                                    {{ $item->jenis == 'Masuk' ? '+' : '-' }}
                                    Rp {{ number_format($item->jumlah) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Belum ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = {!! json_encode($masukChart->pluck('bulan')) !!};
const dataMasuk = {!! json_encode($masukChart->pluck('total')) !!};
const dataKeluar = {!! json_encode($keluarChart->pluck('total')) !!};

new Chart(document.getElementById('kasChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Kas Masuk',
                data: dataMasuk,
                backgroundColor: 'rgba(25,135,84,0.7)',
                barThickness: 30,
                maxBarThickness: 35
            },
            {
                label: 'Kas Keluar',
                data: dataKeluar,
                backgroundColor: 'rgba(220,53,69,0.7)',
                barThickness: 30,
                maxBarThickness: 35
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>


@endsection


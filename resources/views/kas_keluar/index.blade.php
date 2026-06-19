@extends('layouts.app')

@section('title', 'Kas Keluar')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h3 class="mb-0">Kas Keluar</h3>
    {{-- tombol tambah pakai modal biar tampilan lebih rapi --}}
    <button type="button" class="btn btn-sm btn-gold-outline px-4 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#tambahKasKeluarModal">
        <i class="bi bi-dash-circle me-1"></i>
        Tambah Kas Keluar
    </button>
</div>

<!-- TABEL -->
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0 w-100">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Pembayaran</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th class="text-center pe-4" style="width:120px">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($data as $item)
                    <tr>
                        <td class="ps-4">{{ $item->tanggal }}</td>
                        <td>{{ strtolower((string) $item->tujuan) === 'qris' ? 'QRIS' : 'Cash' }}</td>
                        <td class="text-danger fw-semibold">
                            Rp {{ number_format($item->jumlah) }}
                        </td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#edit{{ $item->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="/kas-keluar/{{ $item->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus data?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="text-center text-muted py-4">
                            Belum ada data
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- modal dirender di luar tabel karena elemen .modal di dalam <tbody> bisa membuat glitch/backdrop tidak interaktif. --}}
@foreach($data as $item)
    <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
            <form method="POST" action="/kas-keluar/{{ $item->id }}" class="w-100">
                @csrf
                @method('PUT')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kas Keluar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="date"
                               name="tanggal"
                               class="form-control mb-2"
                               value="{{ $item->tanggal }}" required>

                           {{-- pilih metode pembayaran biar input konsisten --}}
                           <select name="tujuan" class="form-select mb-2" required>
                            <option value="cash" {{ strtolower((string) $item->tujuan) === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="qris" {{ strtolower((string) $item->tujuan) === 'qris' ? 'selected' : '' }}>QRIS</option>
                           </select>

                        <input type="number"
                               name="jumlah"
                               class="form-control mb-2"
                               value="{{ $item->jumlah }}" required>

                        <textarea name="keterangan"
                                  class="form-control"
                                  rows="3">{{ $item->keterangan }}</textarea>
                    </div>

                    <div class="modal-footer">
                        <div class="d-grid gap-2 d-sm-flex w-100 justify-content-sm-end">
                            <button type="button" class="btn btn-secondary w-100 w-sm-auto" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-danger w-100 w-sm-auto">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

<div class="modal fade" id="tambahKasKeluarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
        <form method="POST" action="/kas-keluar" class="w-100">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kas Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="date"
                           name="tanggal"
                           class="form-control mb-2"
                           required>

                      {{-- pilih metode pembayaran biar input konsisten --}}
                      <select name="tujuan" class="form-select mb-2" required>
                       <option value="" selected disabled>pilih pembayaran</option>
                       <option value="cash">Cash</option>
                       <option value="qris">QRIS</option>
                      </select>

                    <input type="number"
                           name="jumlah"
                           class="form-control mb-2"
                           placeholder="Rp ..."
                           required>

                    <textarea name="keterangan"
                              class="form-control"
                              rows="3"
                              placeholder="Catatan tambahan... (opsional)"></textarea>
                </div>

                <div class="modal-footer">
                    <div class="d-grid gap-2 d-sm-flex w-100 justify-content-sm-end">
                        <button type="button" class="btn btn-secondary w-100 w-sm-auto" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger w-100 w-sm-auto">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
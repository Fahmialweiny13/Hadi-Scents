@extends('layouts.app')

@section('title', 'Kas Masuk')

@section('content')
<h3 class="mb-4">Kas Masuk</h3>

<!-- FORM TAMBAH -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <h6 class="fw-semibold mb-3 text-secondary">
            Tambah Kas Masuk
        </h6>

        <form method="POST" action="/kas-masuk">
            @csrf

            <!-- BARIS 1 -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date"
                           name="tanggal"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sumber</label>
                    <input type="text"
                           name="sumber"
                           class="form-control"
                           placeholder="Sumber Pendapatan"
                           required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Jumlah</label>
                    <input type="number"
                           name="jumlah"
                           class="form-control"
                           placeholder="Rp ..."
                           required>
                </div>

            </div>

            <!-- BARIS 2 -->
           <div class="row g-2 mb-2">
            <div class="row mb-4">
                <div class="col-md-8">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan"
                              class="form-control"
                              rows="2"
                              placeholder="Catatan tambahan... (opsional)"></textarea>
                </div>
            </div>

            <!-- TOMBOL -->
            <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-gold-outline px-4 py-2 fw-semibold">
                    <i class="bi bi-plus-circle me-1"></i>
                    Tambah Kas
                </button>
            </div>
             </div>

        </form>
    </div>
</div>

<!-- TABEL -->
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0 w-100">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Sumber</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th class="text-center pe-4" style="width:120px">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($data as $item)
                    <tr>
                        <td class="ps-4">{{ $item->tanggal }}</td>
                        <td>{{ $item->sumber }}</td>
                        <td class="text-success fw-semibold">
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

                                <form action="/kas-masuk/{{ $item->id }}" method="POST">
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
                    <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" action="/kas-masuk/{{ $item->id }}">
                                @csrf
                                @method('PUT')
                    
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Kas Masuk</h5>
                                        <button type="button" class="btn-close"
                                                data-bs-dismiss="modal"></button>
                                    </div>
                    
                                    <div class="modal-body">
                                        <input type="date" name="tanggal"
                                               class="form-control mb-2"
                                               value="{{ $item->tanggal }}" required>
                    
                                        <input type="number" name="jumlah"
                                               class="form-control mb-2"
                                               value="{{ $item->jumlah }}" required>
                    
                                        <input type="text" name="sumber"
                                               class="form-control mb-2"
                                               value="{{ $item->sumber }}" required>
                    
                                        <textarea name="keterangan"
                                                  class="form-control"
                                                  rows="3">{{ $item->keterangan }}</textarea>
                                    </div>
                    
                                    <div class="modal-footer">
                                        <button type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <button type="submit"
                                                class="btn btn-success">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>  
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Belum ada data
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection






@extends('layouts.admin')

@section('title', 'Kelola Jurusan - Umindify')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Jurusan UMKU</h1>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Jurusan Baru</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.store-jurusan') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_jurusan" class="form-label">Nama Jurusan</label>
                            <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" required>
                        </div>
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode Jurusan</label>
                            <input type="text" class="form-control" id="kode" name="kode" required>
                            <small class="text-muted">Contoh: TI, SI, MNJ, AKT</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Jurusan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Jurusan</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Jurusan</th>
                                    <th>Jumlah Mentor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jurusans as $jurusan)
                                <tr>
                                    <td><strong>{{ $jurusan->kode }}</strong></td>
                                    <td>{{ $jurusan->nama_jurusan }}</td>
                                    <td>{{ $jurusan->users_count ?? 0 }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="#" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Hapus jurusan ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
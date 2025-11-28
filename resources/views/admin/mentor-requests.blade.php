@extends('layouts.admin')

@section('title', 'Mentor Requests')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Permohonan Mentor</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Permohonan Menjadi Mentor</h6>
        </div>
        <div class="card-body">
            @if($mentor_requests->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>NIM</th>
                            <th>Jurusan</th>
                            <th>Pengalaman</th>
                            <th>CV</th>
                            <th>Tanggal Apply</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mentor_requests as $request)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->user->email }}</td>
                            <td>{{ $request->user->nim ?? '-' }}</td>
                            <td>{{ $request->jurusan->nama_jurusan ?? '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                        data-bs-target="#experienceModal{{ $request->id }}">
                                    Lihat
                                </button>
                            </td>
                            <td>
                                @if($request->cv_path)
                                <a href="{{ asset('storage/' . $request->cv_path) }}" 
                                   target="_blank" class="btn btn-sm btn-success">
                                    Download CV
                                </a>
                                @else
                                <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <form action="{{ route('admin.approve-mentor', $request->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" 
                                                onclick="return confirm('Approve mentor ini?')">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reject-mentor', $request->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Tolak mentor ini?')">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal untuk pengalaman -->
                        <div class="modal fade" id="experienceModal{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Pengalaman - {{ $request->user->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6>Pengalaman Mengajar:</h6>
                                        <p>{{ $request->pengalaman }}</p>
                                        
                                        <h6 class="mt-3">Deskripsi Diri:</h6>
                                        <p>{{ $request->deskripsi_diri }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5>Tidak ada permohonan mentor yang pending</h5>
                <p class="text-muted">Semua permohonan sudah diproses.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
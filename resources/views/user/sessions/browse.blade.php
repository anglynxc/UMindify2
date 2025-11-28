@extends('layouts.user')

@section('title', 'Cari Les')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filter</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('user.sessions.browse') }}">
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ request('kategori_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipe</label>
                            <select name="tipe" class="form-select">
                                <option value="">Semua Tipe</option>
                                <option value="online" {{ request('tipe') == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="offline" {{ request('tipe') == 'offline' ? 'selected' : '' }}>Offline</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pencarian</label>
                            <input type="text" name="search" class="form-control" 
                                   value="{{ request('search') }}" placeholder="Cari judul atau deskripsi...">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
                        <a href="{{ route('user.sessions.browse') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Reset
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sessions List -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Sesi Les Tersedia</h1>
                <span class="text-muted">{{ $sessions->total() }} sesi ditemukan</span>
            </div>

            @if($sessions->count() > 0)
            <div class="row">
                @foreach($sessions as $session)
                <div class="col-xl-4 col-lg-6 mb-4">
                    <div class="card session-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-{{ $session->tipe == 'online' ? 'info' : 'warning' }}">
                                    {{ $session->tipe }}
                                </span>
                                <span class="badge bg-{{ $session->isFull() ? 'danger' : 'success' }}">
                                    {{ $session->terisi }}/{{ $session->kuota }}
                                </span>
                            </div>
                            
                            <h5 class="card-title">{{ Str::limit($session->judul, 50) }}</h5>
                            <p class="card-text text-muted small">
                                <i class="fas fa-user me-1"></i>{{ $session->mentor->name }}
                            </p>
                            <p class="card-text small">{{ Str::limit($session->deskripsi, 100) }}</p>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-tag me-1"></i>{{ $session->kategori->nama_kategori }}
                                </small>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-primary">
                                    Rp {{ number_format($session->harga, 0, ',', '.') }}
                                </span>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $session->durasi }} menit
                                </small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $session->tanggal->format('d M Y') }}
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $session->jam_mulai }}
                                </small>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('user.sessions.detail', $session->id) }}" 
                               class="btn btn-primary btn-sm w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $sessions->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Tidak ada sesi ditemukan</h4>
                <p class="text-muted">Coba ubah filter pencarian Anda.</p>
                <a href="{{ route('user.sessions.browse') }}" class="btn btn-primary">
                    Tampilkan Semua Sesi
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
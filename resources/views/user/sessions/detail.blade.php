<!-- resources/views/user/sessions/detail.blade.php -->
@extends('layouts.user')

@section('title', $session->judul)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.sessions.browse') }}">Cari Les</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($session->judul, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Session Details -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="h3 mb-0">{{ $session->judul }}</h1>
                        <div>
                            <span class="badge bg-{{ $session->tipe == 'online' ? 'info' : 'warning' }} me-1">
                                {{ $session->tipe }}
                            </span>
                            <span class="badge bg-{{ $session->status == 'active' ? 'success' : 'secondary' }}">
                                {{ $session->status }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="lead">{{ $session->deskripsi }}</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-calendar me-2"></i>Tanggal:</strong><br>
                            {{ $session->tanggal->format('l, d F Y') }}</p>
                            
                            <p><strong><i class="fas fa-clock me-2"></i>Waktu:</strong><br>
                            {{ $session->jam_mulai }} ({{ $session->durasi }} menit)</p>
                            
                            <p><strong><i class="fas fa-tag me-2"></i>Kategori:</strong><br>
                            {{ $session->kategori->nama_kategori }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-users me-2"></i>Kuota:</strong><br>
                            {{ $session->terisi }} / {{ $session->kuota }} peserta</p>
                            
                            <p><strong><i class="fas fa-money-bill-wave me-2"></i>Harga:</strong><br>
                            <span class="h5 text-primary">Rp {{ number_format($session->harga, 0, ',', '.') }}</span></p>
                            
                            @if($session->tipe == 'online')
                            <p><strong><i class="fas fa-video me-2"></i>Platform:</strong><br>
                            <a href="{{ $session->link_meeting }}" target="_blank">{{ $session->link_meeting }}</a></p>
                            @else
                            <p><strong><i class="fas fa-map-marker-alt me-2"></i>Lokasi:</strong><br>
                            {{ $session->lokasi_offline }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mentor Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tentang Mentor</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-circle fa-3x text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $session->mentor->name }}</h5>
                            <p class="text-muted mb-1">{{ $session->mentor->email }}</p>
                            @if($session->mentor->mentorProfile->jurusan)
                            <span class="badge bg-secondary">
                                {{ $session->mentor->mentorProfile->jurusan->nama_jurusan }}
                            </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($session->mentor->mentorProfile->deskripsi_diri)
                    <div class="mt-3">
                        <h6>Deskripsi Mentor</h6>
                        <p class="text-muted">{{ $session->mentor->mentorProfile->deskripsi_diri }}</p>
                    </div>
                    @endif

                    @if($session->mentor->mentorProfile->pengalaman)
                    <div class="mt-3">
                        <h6>Pengalaman</h6>
                        <p class="text-muted">{{ $session->mentor->mentorProfile->pengalaman }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Registration Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Les</h5>
                </div>
                <div class="card-body">
                    @if($session->status != 'active')
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Sesi ini tidak aktif untuk pendaftaran.
                    </div>
                    @elseif($session->isFull())
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i>
                        Maaf, kuota sesi ini sudah penuh.
                    </div>
                    @elseif($is_registered)
                    <div class="alert alert-info">
                        <i class="fas fa-check-circle"></i>
                        Anda sudah terdaftar di sesi ini.
                    </div>
                    <a href="{{ route('user.sessions.my') }}" class="btn btn-outline-primary w-100">
                        Lihat Les Saya
                    </a>
                    @else
                    <form action="{{ route('user.sessions.register', $session->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <p class="text-muted">Dengan mendaftar, Anda setuju untuk mengikuti sesi les ini.</p>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-paper-plane me-2"></i>Daftar Sekarang
                        </button>
                    </form>
                    @endif

                    <hr>

                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Pendaftaran perlu persetujuan mentor
                        </small>
                    </div>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6>Informasi Penting</h6>
                    <ul class="list-unstyled small text-muted">
                        <li><i class="fas fa-check text-success me-2"></i>Mentor berkualitas</li>
                        <li><i class="fas fa-check text-success me-2"></i>Materi terstruktur</li>
                        <li><i class="fas fa-check text-success me-2"></i>Flexible scheduling</li>
                        <li><i class="fas fa-check text-success me-2"></i>Support penuh</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
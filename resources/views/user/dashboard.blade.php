@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">Tingkatkan Skillmu dengan Les Terbaik</h1>
                <p class="lead">Temukan mentor berkualitas dari mahasiswa UM untuk membantumu menguasai berbagai bidang keahlian.</p>
                <a href="{{ route('user.sessions.browse') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-search me-2"></i>Cari Les Sekarang
                </a>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-graduation-cap fa-10x text-white-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Alert Jadi Mentor -->
@auth
    @if(Auth::user()->role == 'user')
    <div class="container mt-4">
        <div class="alert alert-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="alert-heading mb-1">Ingin Berbagi Ilmu?</h6>
                    <p class="mb-0">Daftar menjadi mentor dan mulailah mengajar di Umindify!</p>
                </div>
                <a href="{{ route('user.become-mentor') }}" class="btn btn-outline-primary">
                    Jadi Mentor <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endif
@endauth

<!-- Featured Sessions -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0">Sesi Les Terbaru</h2>
            <a href="{{ route('user.sessions.browse') }}" class="btn btn-outline-primary">
                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row">
            @foreach($featured_sessions as $session)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card session-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-{{ $session->tipe == 'online' ? 'info' : 'warning' }}">
                                {{ $session->tipe }}
                            </span>
                            <span class="badge bg-{{ $session->status == 'active' ? 'success' : 'secondary' }}">
                                {{ $session->status }}
                            </span>
                        </div>
                        
                        <h5 class="card-title">{{ Str::limit($session->judul, 50) }}</h5>
                        <p class="card-text text-muted small">
                            <i class="fas fa-user me-1"></i>{{ $session->mentor->name }}
                        </p>
                        <p class="card-text">{{ Str::limit($session->deskripsi, 100) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">
                                Rp {{ number_format($session->harga, 0, ',', '.') }}
                            </span>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $session->tanggal->format('d M Y') }}
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

        @if($featured_sessions->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">Belum ada sesi les tersedia</h4>
            <p class="text-muted">Silakan cek kembali nanti.</p>
        </div>
        @endif
    </div>
</section>

<!-- My Upcoming Sessions -->
@auth
    @if(isset($my_sessions) && $my_sessions->count() > 0)
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="h3 mb-4">Les yang Akan Datang</h2>
            <div class="row">
                @foreach($my_sessions as $registration)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $registration->session->judul }}</h5>
                            <p class="card-text">
                                <i class="fas fa-user me-1"></i>{{ $registration->session->mentor->name }}<br>
                                <i class="fas fa-calendar me-1"></i>{{ $registration->session->tanggal->format('d M Y') }} - {{ $registration->session->jam_mulai }}
                            </p>
                            <span class="badge bg-{{ $registration->status == 'approved' ? 'success' : 'warning' }}">
                                Status: {{ $registration->status }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endauth
@endsection
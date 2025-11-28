<!-- resources/views/user/sessions/my-sessions.blade.php -->
@extends('layouts.user')

@section('title', 'Les Saya')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Les yang Saya Ikuti</h1>
        <a href="{{ route('user.sessions.browse') }}" class="btn btn-primary">
            <i class="fas fa-search me-1"></i> Cari Les Lain
        </a>
    </div>

    @if($registrations->count() > 0)
    <div class="row">
        @foreach($registrations as $registration)
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title">{{ $registration->session->judul }}</h5>
                        <span class="badge bg-{{ 
                            $registration->status == 'approved' ? 'success' : 
                            ($registration->status == 'pending' ? 'warning' : 'danger') 
                        }}">
                            {{ $registration->status }}
                        </span>
                    </div>
                    
                    <p class="card-text">
                        <i class="fas fa-user me-1 text-muted"></i>
                        <strong>Mentor:</strong> {{ $registration->session->mentor->name }}
                    </p>
                    
                    <p class="card-text">
                        <i class="fas fa-calendar me-1 text-muted"></i>
                        <strong>Tanggal:</strong> {{ $registration->session->tanggal->format('d M Y') }}
                    </p>
                    
                    <p class="card-text">
                        <i class="fas fa-clock me-1 text-muted"></i>
                        <strong>Waktu:</strong> {{ $registration->session->jam_mulai }}
                    </p>
                    
                    <p class="card-text">
                        <i class="fas fa-tag me-1 text-muted"></i>
                        <strong>Kategori:</strong> {{ $registration->session->kategori->nama_kategori }}
                    </p>

                    <div class="mt-3">
                        @if($registration->status == 'pending')
                        <form action="{{ route('user.registrations.cancel', $registration->id) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                    onclick="return confirm('Batalkan pendaftaran?')">
                                <i class="fas fa-times me-1"></i> Batalkan
                            </button>
                        </form>
                        @endif
                        
                        <a href="{{ route('user.sessions.detail', $registration->session->id) }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">Belum ada les yang diikuti</h4>
        <p class="text-muted">Temukan les yang sesuai dengan minat Anda dan daftar sekarang!</p>
        <a href="{{ route('user.sessions.browse') }}" class="btn btn-primary">
            <i class="fas fa-search me-1"></i> Cari Les
        </a>
    </div>
    @endif
</div>
@endsection
<!-- resources/views/mentor/sessions/participants.blade.php -->
@extends('layouts.mentor')

@section('title', 'Kelola Peserta - ' . $session->judul)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Peserta</h1>
        <div>
            <a href="{{ route('mentor.sessions') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Sesi
            </a>
        </div>
    </div>

    <!-- Session Info -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Info Sesi</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Judul:</strong> {{ $session->judul }}</p>
                    <p><strong>Tanggal:</strong> {{ $session->tanggal->format('d M Y') }}</p>
                    <p><strong>Waktu:</strong> {{ $session->jam_mulai }}</p>
                    <p><strong>Durasi:</strong> {{ $session->durasi }} menit</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tipe:</strong> 
                        <span class="badge bg-{{ $session->tipe == 'online' ? 'info' : 'warning' }}">
                            {{ $session->tipe }}
                        </span>
                    </p>
                    <p><strong>Kuota:</strong> {{ $session->terisi }} / {{ $session->kuota }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $session->status == 'active' ? 'success' : 'secondary' }}">
                            {{ $session->status }}
                        </span>
                    </p>
                    <p><strong>Harga:</strong> Rp {{ number_format($session->harga, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Participants List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Peserta</h6>
            <span class="badge bg-primary">{{ $participants->count() }} Pendaftar</span>
        </div>
        <div class="card-body">
            @if($participants->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Peserta</th>
                            <th>Email</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $participant)
                        <tr>
                            <td>{{ $participant->user->name }}</td>
                            <td>{{ $participant->user->email }}</td>
                            <td>{{ $participant->created_at->format('d M Y H:i') }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$participant->status] }}">
                                    {{ $participant->status }}
                                </span>
                            </td>
                            <td>
                                @if($participant->status == 'pending')
                                <div class="btn-group">
                                    <form action="{{ route('mentor.participants.approve', $participant->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                                {{ $session->isFull() ? 'disabled' : '' }}
                                                onclick="return confirm('Setujui peserta ini?')">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('mentor.participants.reject', $participant->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Tolak peserta ini?')">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                                @elseif($participant->status == 'approved')
                                <span class="text-success">
                                    <i class="fas fa-check-circle"></i> Disetujui
                                </span>
                                @else
                                <span class="text-danger">
                                    <i class="fas fa-times-circle"></i> Ditolak
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada peserta yang mendaftar</h5>
                <p class="text-muted">Peserta akan muncul di sini setelah mendaftar ke sesi Anda.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Session Status Actions -->
    @if($session->status == 'draft')
    <div class="alert alert-warning">
        <i class="fas fa-info-circle"></i>
        Sesi masih dalam status draft. Aktifkan sesi agar peserta bisa mendaftar.
    </div>
    @elseif($session->status == 'active' && $session->isFull())
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        Kuota sesi sudah penuh. Tidak bisa menerima peserta baru.
    </div>
    @endif
</div>
@endsection
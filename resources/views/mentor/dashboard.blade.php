@extends('layouts.mentor')

@section('title', 'Dashboard Mentor')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Mentor</h1>
        <a href="{{ route('mentor.sessions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Buat Sesi Baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Sesi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_sessions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Sesi Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_sessions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Selesai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_sessions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($stats['total_earnings'], 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Sessions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Sesi Mendatang</h6>
                    <a href="{{ route('mentor.sessions') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($upcoming_sessions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Tipe</th>
                                    <th>Kuota</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcoming_sessions as $session)
                                <tr>
                                    <td>{{ $session->judul }}</td>
                                    <td>{{ $session->tanggal->format('d M Y') }} - {{ $session->jam_mulai }}</td>
                                    <td>
                                        <span class="badge bg-{{ $session->tipe == 'online' ? 'info' : 'warning' }}">
                                            {{ $session->tipe }}
                                        </span>
                                    </td>
                                    <td>{{ $session->terisi }} / {{ $session->kuota }}</td>
                                    <td>
                                        <span class="badge bg-{{ $session->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ $session->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada sesi mendatang</h5>
                        <p class="text-muted">Buat sesi baru untuk memulai mengajar.</p>
                        <a href="{{ route('mentor.sessions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Buat Sesi Pertama
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
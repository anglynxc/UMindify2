@extends('layouts.mentor')

@section('title', 'Sesi Les Saya')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sesi Les Saya</h1>
        <a href="{{ route('mentor.sessions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Buat Sesi Baru
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Tanggal & Waktu</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Kuota</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $session)
                        <tr>
                            <td>{{ $session->judul }}</td>
                            <td>{{ $session->kategori->nama_kategori }}</td>
                            <td>{{ $session->tanggal->format('d M Y') }} - {{ $session->jam_mulai }}</td>
                            <td>
                                <span class="badge bg-{{ $session->tipe == 'online' ? 'info' : 'warning' }}">
                                    {{ $session->tipe }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($session->harga, 0, ',', '.') }}</td>
                            <td>{{ $session->terisi }} / {{ $session->kuota }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'active' => 'success',
                                        'full' => 'warning',
                                        'completed' => 'info',
                                        'cancelled' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$session->status] }}">
                                    {{ $session->status }}
                                </span>
                            </td>
<td>
    <div class="btn-group">
        <a href="{{ route('mentor.sessions.participants', $session->id) }}" 
           class="btn btn-info btn-sm" title="Kelola Peserta">
            <i class="fas fa-users"></i>
        </a>
        <a href="{{ route('mentor.sessions.edit', $session->id) }}" 
           class="btn btn-warning btn-sm" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        <form action="{{ route('mentor.sessions.delete', $session->id) }}" 
              method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" 
                    onclick="return confirm('Hapus sesi ini?')" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </div>

    <!-- Tombol Activate/Complete -->
    <div class="mt-1">
        @if($session->status == 'draft')
        <form action="{{ route('mentor.sessions.activate', $session->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success btn-sm" 
                    onclick="return confirm('Aktifkan sesi ini?')">
                <i class="fas fa-play"></i> Aktifkan
            </button>
        </form>
        @elseif($session->status == 'active')
        <form action="{{ route('mentor.sessions.complete', $session->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-info btn-sm" 
                    onclick="return confirm('Tandai sesi sebagai selesai?')">
                <i class="fas fa-check"></i> Selesai
            </button>
        </form>
        @endif
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
@endsection
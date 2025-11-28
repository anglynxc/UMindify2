@extends('layouts.admin')

@section('title', 'Semua Sesi - Umindify')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Semua Sesi Les</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Mentor</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Tanggal</th>
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
                            <td>{{ $session->mentor->name }}</td>
                            <td>{{ $session->kategori->nama_kategori }}</td>
                            <td>
                                <span class="badge badge-{{ $session->tipe == 'online' ? 'info' : 'warning' }}">
                                    {{ $session->tipe }}
                                </span>
                            </td>
                            <td>{{ $session->tanggal->format('d M Y') }} - {{ $session->jam_mulai }}</td>
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
                                <span class="badge badge-{{ $statusColors[$session->status] ?? 'secondary' }}">
                                    {{ $session->status }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                        data-bs-target="#sessionModal{{ $session->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="sessionModal{{ $session->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Sesi: {{ $session->judul }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Mentor:</strong> {{ $session->mentor->name }}</p>
                                                <p><strong>Kategori:</strong> {{ $session->kategori->nama_kategori }}</p>
                                                <p><strong>Tipe:</strong> {{ $session->tipe }}</p>
                                                @if($session->tipe == 'online')
                                                <p><strong>Link Meeting:</strong> 
                                                    <a href="{{ $session->link_meeting }}" target="_blank">{{ $session->link_meeting }}</a>
                                                </p>
                                                @else
                                                <p><strong>Lokasi:</strong> {{ $session->lokasi_offline }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Tanggal:</strong> {{ $session->tanggal->format('d M Y') }}</p>
                                                <p><strong>Jam:</strong> {{ $session->jam_mulai }}</p>
                                                <p><strong>Durasi:</strong> {{ $session->durasi }} menit</p>
                                                <p><strong>Harga:</strong> Rp {{ number_format($session->harga, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <strong>Deskripsi:</strong>
                                            <p>{{ $session->deskripsi }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
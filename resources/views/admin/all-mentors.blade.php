@extends('layouts.admin')

@section('title', 'Semua Mentor - Umindify')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Semua Mentor</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>NIM</th>
                            <th>Jurusan</th>
                            <th>Rating</th>
                            <th>Total Sesi</th>
                            <th>Status</th>
                            <th>Tanggal Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mentors as $mentor)
                        <tr>
                            <td>{{ $mentor->name }}</td>
                            <td>{{ $mentor->email }}</td>
                            <td>{{ $mentor->nim ?? '-' }}</td>
                            <td>{{ $mentor->mentorProfile->jurusan->nama_jurusan ?? '-' }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star"></i> {{ $mentor->mentorProfile->rating }}
                                </span>
                            </td>
                            <td>{{ $mentor->mentorProfile->total_sessions }}</td>
                            <td>
                                <span class="badge badge-{{ $mentor->mentorProfile->status == 'approved' ? 'success' : 'warning' }}">
                                    {{ $mentor->mentorProfile->status }}
                                </span>
                            </td>
                            <td>{{ $mentor->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
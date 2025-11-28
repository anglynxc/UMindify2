@extends('layouts.admin')

@section('title', 'Semua User - Umindify')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Semua User</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Tanggal Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'mentor' ? 'success' : 'primary') }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $user->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ $user->status }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                @if($user->role != 'admin')
                                <div class="btn-group">
                                    @if($user->status == 'active')
                                    <form action="#" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" 
                                                onclick="return confirm('Nonaktifkan user ini?')">
                                            <i class="fas fa-ban"></i> Nonaktifkan
                                        </button>
                                    </form>
                                    @else
                                    <form action="#" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" 
                                                onclick="return confirm('Aktifkan user ini?')">
                                            <i class="fas fa-check"></i> Aktifkan
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
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
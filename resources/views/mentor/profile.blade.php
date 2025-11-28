<!-- resources/views/mentor/profile.blade.php -->
@extends('layouts.mentor')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil Saya</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Profil</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('mentor.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name', $mentor->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nim" name="nim" 
                                           value="{{ old('nim', $mentor->nim) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="jurusan_id" class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <select class="form-control" id="jurusan_id" name="jurusan_id" required>
                                <option value="">Pilih Jurusan</option>
                                @foreach($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}" 
                                    {{ old('jurusan_id', $mentor->mentorProfile->jurusan_id) == $jurusan->id ? 'selected' : '' }}>
                                    {{ $jurusan->nama_jurusan }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="pengalaman" class="form-label">Pengalaman Mengajar <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="pengalaman" name="pengalaman" rows="3" required>{{ old('pengalaman', $mentor->mentorProfile->pengalaman) }}</textarea>
                            <small class="text-muted">Ceritakan pengalaman mengajar atau keahlian Anda</small>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi_diri" class="form-label">Deskripsi Diri <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="deskripsi_diri" name="deskripsi_diri" rows="3" required>{{ old('deskripsi_diri', $mentor->mentorProfile->deskripsi_diri) }}</textarea>
                            <small class="text-muted">Perkenalkan diri Anda kepada calon peserta</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Profil</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Profile Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-chalkboard-teacher fa-3x text-primary"></i>
                        </div>
                        <h5>{{ $mentor->name }}</h5>
                        <p class="text-muted">{{ $mentor->email }}</p>
                        
                        <div class="mt-4">
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $mentor->mentorProfile->status == 'approved' ? 'success' : 'warning' }}">
                                    {{ $mentor->mentorProfile->status }}
                                </span>
                            </p>
                            <p><strong>Rating:</strong> 
                                <span class="text-warning">
                                    <i class="fas fa-star"></i> {{ $mentor->mentorProfile->rating }}
                                </span>
                            </p>
                            <p><strong>Total Sesi:</strong> {{ $mentor->mentorProfile->total_sessions }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
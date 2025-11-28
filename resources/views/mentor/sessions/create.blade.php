@extends('layouts.mentor')

@section('title', 'Buat Sesi Baru')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Sesi Les Baru</h1>
        <a href="{{ route('mentor.sessions') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('mentor.sessions.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Sesi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul" name="judul" 
                                   value="{{ old('judul') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-control" id="kategori_id" name="kategori_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('kategori_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipe Sesi <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipe" 
                                           id="online" value="online" {{ old('tipe') == 'online' ? 'checked' : 'checked' }}>
                                    <label class="form-check-label" for="online">Online</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipe" 
                                           id="offline" value="offline" {{ old('tipe') == 'offline' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="offline">Offline</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="online-field" style="display: none;">
                            <label for="link_meeting" class="form-label">Link Meeting <span class="text-danger">*</span></label>
                            <input type="url" class="form-control" id="link_meeting" name="link_meeting" 
                                   value="{{ old('link_meeting') }}" placeholder="https://zoom.us/j/...">
                        </div>

                        <div class="mb-3" id="offline-field" style="display: none;">
                            <label for="lokasi_offline" class="form-label">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lokasi_offline" name="lokasi_offline" 
                                   value="{{ old('lokasi_offline') }}" placeholder="Alamat lengkap...">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                   value="{{ old('tanggal') }}" min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" 
                                   value="{{ old('jam_mulai') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="durasi" class="form-label">Durasi (menit) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="durasi" name="durasi" 
                                   value="{{ old('durasi', 60) }}" min="30" step="15" required>
                        </div>

                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="harga" name="harga" 
                                   value="{{ old('harga') }}" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="kuota" class="form-label">Kuota Peserta <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="kuota" name="kuota" 
                                   value="{{ old('kuota', 10) }}" min="1" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi Sesi <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Buat Sesi</button>
                <a href="{{ route('mentor.sessions') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle online/offline fields
    function toggleSessionType() {
        const onlineField = document.getElementById('online-field');
        const offlineField = document.getElementById('offline-field');
        const onlineRadio = document.getElementById('online');
        
        if (onlineRadio.checked) {
            onlineField.style.display = 'block';
            offlineField.style.display = 'none';
        } else {
            onlineField.style.display = 'none';
            offlineField.style.display = 'block';
        }
    }

    // Initial toggle
    toggleSessionType();

    // Add event listeners
    document.getElementById('online').addEventListener('change', toggleSessionType);
    document.getElementById('offline').addEventListener('change', toggleSessionType);
</script>
@endpush
@endsection
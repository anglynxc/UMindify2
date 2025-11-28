@extends('layouts.user')

@section('title', 'Daftar Jadi Mentor')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Daftar Menjadi Mentor</h4>
                </div>
                <div class="card-body">
@if(auth()->user()->role == 'mentor' && auth()->user()->isApprovedMentor())
<div class="alert alert-success">
    <i class="fas fa-check-circle me-2"></i>
    Anda sudah terdaftar sebagai mentor. 
    <a href="{{ route('mentor.dashboard') }}" class="alert-link">Kunjungi dashboard mentor</a>.
</div>
@elseif(auth()->user()->hasPendingMentorApplication())
<div class="alert alert-warning">
    <i class="fas fa-clock me-2"></i>
    Permohonan menjadi mentor Anda sedang menunggu persetujuan admin. 
    Anda akan mendapat notifikasi via email ketika sudah disetujui.
</div>
@else
                    <!-- Progress Steps -->
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-center">
                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="mt-2">
                                    <small class="fw-bold">Data Diri</small>
                                </div>
                            </div>
                            <div class="flex-grow-1 border-top border-primary mx-3"></div>
                            <div class="text-center">
                                <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Berkas</small>
                                </div>
                            </div>
                            <div class="flex-grow-1 border-top border-secondary mx-3"></div>
                            <div class="text-center">
                                <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Selesai</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('user.submit-mentor-application') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Personal Information -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">Informasi Pribadi</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" 
                                               value="{{ auth()->user()->name }}" disabled>
                                        <small class="text-muted">Nama tidak dapat diubah</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" 
                                               value="{{ auth()->user()->email }}" disabled>
                                        <small class="text-muted">Email tidak dapat diubah</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nim" class="form-label">NIM UM <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nim') is-invalid @enderror" 
                                               id="nim" name="nim" value="{{ old('nim') }}" 
                                               placeholder="Contoh: 202151000" required>
                                        @error('nim')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Pastikan NIM Anda valid</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jurusan_id" class="form-label">Jurusan <span class="text-danger">*</span></label>
                                        <select class="form-control @error('jurusan_id') is-invalid @enderror" 
                                                id="jurusan_id" name="jurusan_id" required>
                                            <option value="">Pilih Jurusan</option>
                                            @foreach($jurusans as $jurusan)
                                            <option value="{{ $jurusan->id }}" 
                                                {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                                {{ $jurusan->nama_jurusan }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('jurusan_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Teaching Experience -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">Pengalaman Mengajar</h5>
                            <div class="mb-3">
                                <label for="pengalaman" class="form-label">
                                    Pengalaman Mengajar atau Keahlian <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('pengalaman') is-invalid @enderror" 
                                          id="pengalaman" name="pengalaman" rows="4" 
                                          placeholder="Ceritakan pengalaman mengajar, pelatihan, atau keahlian khusus yang Anda miliki..." 
                                          required>{{ old('pengalaman') }}</textarea>
                                @error('pengalaman')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimal 50 karakter. Jelaskan secara detail.</small>
                                <div class="mt-1">
                                    <small class="text-muted">
                                        Karakter: <span id="pengalaman-count">0</span>/50
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Description -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">Deskripsi Diri</h5>
                            <div class="mb-3">
                                <label for="deskripsi_diri" class="form-label">
                                    Perkenalkan Diri Anda <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('deskripsi_diri') is-invalid @enderror" 
                                          id="deskripsi_diri" name="deskripsi_diri" rows="4" 
                                          placeholder="Perkenalkan diri Anda, metode mengajar, dan apa yang membuat Anda cocok menjadi mentor..." 
                                          required>{{ old('deskripsi_diri') }}</textarea>
                                @error('deskripsi_diri')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimal 50 karakter. Buat calon peserta tertarik.</small>
                                <div class="mt-1">
                                    <small class="text-muted">
                                        Karakter: <span id="deskripsi-count">0</span>/50
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">Berkas Pendukung</h5>
                            <div class="mb-3">
                                <label for="cv" class="form-label">Curriculum Vitae (CV)</label>
                                <input type="file" class="form-control @error('cv') is-invalid @enderror" 
                                       id="cv" name="cv" accept=".pdf,.doc,.docx">
                                @error('cv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Format: PDF, DOC, DOCX (Maksimal 2MB). Opsional tapi direkomendasikan.
                                </small>
                            </div>
                        </div>
<!-- resources/views/user/become-mentor.blade.php - pastikan ada field terms -->
<div class="mb-4">
    <div class="form-check">
        <input class="form-check-input @error('terms') is-invalid @enderror" 
               type="checkbox" id="terms" name="terms" value="1" 
               {{ old('terms') ? 'checked' : '' }} required>
        <label class="form-check-label" for="terms">
            Saya menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Syarat dan Ketentuan</a> 
            menjadi mentor di Umindify <span class="text-danger">*</span>
        </label>
        @error('terms')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Permohonan
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Additional Info -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Penting</h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check text-success me-2"></i>Hanya mahasiswa UM yang bisa menjadi mentor</li>
                        <li><i class="fas fa-check text-success me-2"></i>Permohonan akan ditinjau dalam 1-3 hari kerja</li>
                        <li><i class="fas fa-check text-success me-2"></i>Anda akan mendapat notifikasi via email</li>
                        <li><i class="fas fa-check text-success me-2"></i>Setelah disetujui, Anda bisa langsung membuat sesi les</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Syarat dan Ketentuan Menjadi Mentor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Persyaratan:</h6>
                <ul>
                    <li>Merupakan mahasiswa aktif UM dengan NIM yang valid</li>
                    <li>Memiliki pengalaman atau keahlian dalam bidang yang akan diajarkan</li>
                    <li>Berkomitmen untuk memberikan pengajaran yang berkualitas</li>
                    <li>Bersedia mengikuti aturan dan panduan dari Umindify</li>
                </ul>

                <h6>Kewajiban Mentor:</h6>
                <ul>
                    <li>Menyiapkan materi les yang terstruktur</li>
                    <li>Hadir tepat waktu sesuai jadwal yang ditentukan</li>
                    <li>Memberikan pelayanan terbaik kepada peserta</li>
                    <li>Menjaga etika dan profesionalisme selama mengajar</li>
                </ul>

                <h6>Hak Mentor:</h6>
                <ul>
                    <li>Mendapatkan penghasilan dari sesi les yang diadakan</li>
                    <li>Membangun portfolio mengajar</li>
                    <li>Mendapatkan review dan rating dari peserta</li>
                    <li>Mengembangkan kemampuan mengajar dan leadership</li>
                </ul>

                <p class="text-muted mt-3">
                    Dengan mencentang kotak persetujuan, Anda menyetujui semua syarat dan ketentuan di atas.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Character counter for textareas
    document.getElementById('pengalaman').addEventListener('input', function() {
        document.getElementById('pengalaman-count').textContent = this.value.length;
    });

    document.getElementById('deskripsi_diri').addEventListener('input', function() {
        document.getElementById('deskripsi-count').textContent = this.value.length;
    });

    // Initialize counts
    document.getElementById('pengalaman-count').textContent = 
        document.getElementById('pengalaman').value.length;
    document.getElementById('deskripsi-count').textContent = 
        document.getElementById('deskripsi_diri').value.length;

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const pengalaman = document.getElementById('pengalaman').value;
        const deskripsi = document.getElementById('deskripsi_diri').value;
        
        if (pengalaman.length < 50) {
            e.preventDefault();
            alert('Pengalaman mengajar minimal 50 karakter.');
            return;
        }
        
        if (deskripsi.length < 50) {
            e.preventDefault();
            alert('Deskripsi diri minimal 50 karakter.');
            return;
        }
    });
</script>
@endpush
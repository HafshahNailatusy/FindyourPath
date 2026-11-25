
@extends('layouts.mahasiswa')

@section('title', 'My Profile')
@section('menu', 'profile')

@push('styles')
    @vite(['resources/css/ProfileUser.css'])
@endpush

@section('content')
<div class="page-content">

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert-success">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-success">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- PROFILE HEADER --}}
    <section class="profile-header content-card">
        <div class="profile-avatar-large">
            {{ strtoupper(substr($profile['nama'] ?? 'M', 0, 1)) }}
        </div>

        <div class="profile-main-info">
            <h2>{{ $profile['nama'] }}</h2>
            <p>{{ $profile['prodi'] ?? '-' }}</p>
            <p class="profile-location">
                {{ $profile['domisili'] ?? '-' }}
            </p>
        </div>

        <div class="profile-header-action">
            <button class="btn-primary" type="button" onclick="toggleEdit()">
                Edit Profile
            </button>
        </div>
    </section>

    {{-- EDIT PROFILE --}}
    <section id="editProfile" class="content-card edit-box hidden">
        <div class="section-header">
            <div>
                <h2>Edit Profile</h2>
                <p>Ubah informasi profil kamu.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('mahasiswa.profile.update') }}"
              class="profile-form">
            @csrf
            @method('PUT')

            <input type="hidden" name="action" value="update_profile">

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama"
                       value="{{ old('nama', $profile['nama']) }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" value="{{ $profile['email'] }}" readonly>
            </div>

            <div class="form-group">
                <label>NIM</label>
                <input type="text" name="nim"
                       value="{{ old('nim', $profile['nim']) }}" required>
            </div>

            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="gender" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Perempuan"
                        @selected(old('gender', $profile['gender']) === 'Perempuan')>
                        Perempuan
                    </option>
                    <option value="Laki-laki"
                        @selected(old('gender', $profile['gender']) === 'Laki-laki')>
                        Laki-laki
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="lahir"
                       value="{{ old('lahir', $profile['lahir']) }}">
            </div>

            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" name="telepon"
                       value="{{ old('telepon', $profile['telepon']) }}">
            </div>

            <div class="form-group">
                <label>Domisili</label>
                <input type="text" name="domisili"
                       value="{{ old('domisili', $profile['domisili']) }}">
            </div>

            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="toggleEdit()">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </section>

    {{-- DATA DIRI --}}
    <section class="content-card">
        <div class="section-header">
            <div>
                <h2>Data Diri</h2>
                <p>Informasi pribadi pengguna</p>
            </div>
        </div>

        <div class="profile-grid">
            <div class="info-item">
                <span>Nama Lengkap</span>
                <strong>{{ $profile['nama'] }}</strong>
            </div>
            <div class="info-item">
                <span>NIM</span>
                <strong>{{ $profile['nim'] }}</strong>
            </div>
            <div class="info-item">
                <span>Email</span>
                <strong>{{ $profile['email'] }}</strong>
            </div>
            <div class="info-item">
                <span>Nomor Telepon</span>
                <strong>{{ $profile['telepon'] ?? '-' }}</strong>
            </div>
            <div class="info-item">
                <span>Jenis Kelamin</span>
                <strong>{{ $profile['gender'] ?? '-' }}</strong>
            </div>
            <div class="info-item">
                <span>Tanggal Lahir</span>
                <strong>{{ $profile['lahir'] ?? '-' }}</strong>
            </div>
            <div class="info-item">
                <span>Domisili</span>
                <strong>{{ $profile['domisili'] ?? '-' }}</strong>
            </div>
        </div>
    </section>

    {{-- DATA AKADEMIK --}}
    <section class="content-card">
        <div class="section-header">
            <div>
                <h2>Data Akademik</h2>
                <p>Informasi pendidikan pengguna</p>
            </div>

            <button type="button" class="btn-small" onclick="openAcademicModal()">
                {{ empty($profile['universitas']) ? 'Lengkapi Data' : 'Edit Data' }}
            </button>
        </div>

        <div class="profile-grid">
            <div class="info-item">
                <span>Universitas</span>
                <strong>{{ $profile['universitas'] ?? '-' }}</strong>
            </div>
            <div class="info-item">
                <span>Fakultas</span>
                <strong>{{ $profile['fakultas'] ?? '-' }}</strong>
            </div>
            <div class="info-item">
                <span>Program Studi</span>
                <strong>{{ $profile['prodi'] ?? '-' }}</strong>
            </div>
            <div class="info-item">
                <span>Angkatan</span>
                <strong>{{ $profile['angkatan'] ?? '-' }}</strong>
            </div>
            <div class="info-item">
                <span>Semester</span>
                <strong>{{ $profile['semester'] ?? '-' }}</strong>
            </div>
            <div class="info-item">
                <span>IPK</span>
                <strong>{{ $profile['ipk'] ?? '-' }}</strong>
            </div>
        </div>
    </section>

</div>

{{-- MODAL DATA AKADEMIK --}}
<div id="academicModal" class="modal-overlay hidden">
    <div class="academic-modal">
        <div class="modal-header">
            <div>
                <h2>Lengkapi Data Akademik</h2>
                <p>Isi informasi akademik kamu.</p>
            </div>
            <button type="button" class="modal-close"
                    onclick="closeAcademicModal()">×</button>
        </div>

        <form method="POST" action="{{ route('mahasiswa.profile.update') }}"
              class="academic-form">
            @csrf
            @method('PUT')

            <input type="hidden" name="action" value="update_academic">

            <div class="form-group">
                <label>Universitas</label>
                <input type="text" name="universitas"
                       value="{{ $profile['universitas'] ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Fakultas</label>
                <input type="text" name="fakultas"
                       value="{{ $profile['fakultas'] ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Program Studi</label>
                <input type="text" name="prodi"
                       value="{{ $profile['prodi'] ?? '' }}" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Angkatan</label>
                    <input type="number" name="angkatan"
                           value="{{ $profile['angkatan'] ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label>Semester</label>
                    <input type="number" name="semester" min="1" max="14"
                           value="{{ $profile['semester'] ?? '' }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>IPK</label>
                <input type="number" name="ipk" min="0" max="4" step="0.01"
                       value="{{ $profile['ipk'] ?? '' }}" required>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary"
                        onclick="closeAcademicModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
 @vite(['resources/js/ProfileUser.js'])
@endpush

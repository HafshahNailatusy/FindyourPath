<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - FindYourPath</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/adminProfile.css', 'resources/js/adminProfile.js'])
</head>
<body>
    <div class="layout-wrapper">
        <x-sidebar-admin active="profile" />

        <div class="main-wrapper">
            <header class="header">
                <div class="header-left">
                    <button type="button" class="mobile-toggle-btn" id="sidebarToggleBtn" aria-label="Menu">
                        <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <h1 class="header-title">Profil Admin</h1>
                </div>
                <div class="header-right">
                    <button class="header-notif" aria-label="Notifikasi">
                       <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </button>
                    <div class="header-user">
                        @if (!empty($user->admin?->avatar) && file_exists(public_path($user->admin->avatar)))
                            <img src="{{ asset($user->admin->avatar) }}" class="header-avatar" style="object-fit:cover; border-radius: 50%; width: 36px; height: 36px;" alt="Avatar">
                        @else
                            <div class="header-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        @endif
                        <div class="header-userinfo">
                            <span class="header-username">{{ $user->name }}</span>
                            <span class="header-userrole">{{ $user->admin?->position ?? 'Administrator' }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="main-content">
                @if (session('success'))
                    <div class="toast-alert show success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="toast-alert show error">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="profile-card profile-card-top">
                    <div class="profile-card-header">
                        <div class="profile-user-info">
                            @if (!empty($user->admin?->avatar) && file_exists(public_path($user->admin->avatar)))
                                <img src="{{ asset($user->admin->avatar) }}" class="profile-avatar-placeholder" style="object-fit: cover;" alt="Foto profil">
                            @else
                                <div class="profile-avatar-placeholder">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="profile-name-group">
                                <h2 class="profile-name">{{ $user->name }}</h2>
                                <p class="profile-role">{{ $user->admin?->position ?? 'Administrator' }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-edit-profile" id="btnOpenEditModal">Edit Profil</button>
                    </div>
                </div>

                <div class="profile-card">
                    <h3 class="card-section-title">Data Diri</h3>
                    <p class="card-section-subtitle">Informasi pribadi admin</p>
                    <div class="data-table-wrapper">
                        <table class="data-table">
                            <tbody>
                                <tr><td class="col-label">Email</td><td class="col-value">{{ $user->email }}</td></tr>
                                <tr><td class="col-label">No. HP</td><td class="col-value">{{ $user->admin?->phone ?? '-' }}</td></tr>
                                <tr><td class="col-label">Alamat</td><td class="col-value">{{ $user->admin?->address ?? '-' }}</td></tr>
                                <tr><td class="col-label">Tanggal Lahir</td><td class="col-value">{{ $user->admin?->birth_date ? date('d F Y', strtotime($user->admin->birth_date)) : '-' }}</td></tr>
                                <tr><td class="col-label">Jenis Kelamin</td><td class="col-value">{{ $user->admin?->gender ?? '-' }}</td></tr>
                                <tr><td class="col-label">ID Admin</td><td class="col-value">{{ $user->admin?->admin_id ?? 'ADM-1024' }}</td></tr>
                                <tr><td class="col-label">Jabatan</td><td class="col-value">{{ $user->admin?->position ?? 'Administrator' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="profile-card">
                    <h3 class="card-section-title">Informasi Akun</h3>
                    <p class="card-section-subtitle">Detail akun dan hak akses</p>
                    <div class="data-table-wrapper">
                        <table class="data-table">
                            <tbody>
                                <tr><td class="col-label">Username</td><td class="col-value">{{ $user->username }}</td></tr>
                                <tr><td class="col-label">Role Akses</td><td class="col-value">{{ $user->admin?->position ?? 'Administrator' }}</td></tr>
                                <tr><td class="col-label">Status Akun</td><td class="col-value"><span class="badge-status-active">Aktif</span></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>

        <div class="modal-overlay" id="editProfileModal">
            <div class="modal-box">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Data Diri</h3>
                    <button type="button" class="modal-close-btn" id="btnCloseEditModal">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputAvatar">Foto Profil</label>
                            <input type="file" id="inputAvatar" name="avatar" accept="image/png,image/jpeg,image/webp">
                            @if (!empty($user->admin?->avatar))
                                <button type="submit" form="deleteAvatarForm" class="btn-delete-avatar" onclick="return confirm('Hapus foto profil?');">Hapus foto profil</button>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="inputName">Nama Lengkap</label>
                            <input type="text" id="inputName" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="inputEmail">Email</label>
                                <input type="email" id="inputEmail" name="email" value="{{ $user->email }}" required>
                            </div>
                            <div class="form-group">
                                <label for="inputPhone">No. HP</label>
                                <input type="text" id="inputPhone" name="phone" value="{{ $user->admin?->phone }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Alamat</label>
                            <textarea id="inputAddress" name="address" rows="2">{{ $user->admin?->address }}</textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="inputBirthDate">Tanggal Lahir</label>
                                <input type="date" id="inputBirthDate" name="birth_date" value="{{ $user->admin?->birth_date }}">
                            </div>
                            <div class="form-group">
                                <label for="inputGender">Jenis Kelamin</label>
                                <select id="inputGender" name="gender">
                                    <option value="Perempuan" {{ ($user->admin?->gender === 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                                    <option value="Laki-laki" {{ ($user->admin?->gender === 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="inputAdminId">ID Admin</label>
                                <input type="text" id="inputAdminId" name="admin_id" value="{{ $user->admin?->admin_id ?? 'ADM-1024' }}">
                            </div>
                            <div class="form-group">
                                <label for="inputPosition">Jabatan</label>
                                <input type="text" id="inputPosition" name="position" value="{{ $user->admin?->position ?? 'Administrator' }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" id="btnCancelEdit">Batal</button>
                        <button type="submit" class="btn-save">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <form id="deleteAvatarForm" method="POST" action="{{ route('admin.profile.avatar.delete') }}" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</body>
</html>
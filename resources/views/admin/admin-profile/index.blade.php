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
        @include('components.sidebar-admin', ['activeMenu' => 'profile'])

        <div class="main-wrapper">
            <header class="header">
                <h1 class="header-title">Profil Admin</h1>
                <div class="header-right">
                    <button class="header-notif" aria-label="Notifikasi">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="..." stroke="#27566A" stroke-width="1.8"/></svg>
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

                {{-- Card Data Diri --}}
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

                {{-- Card Info Akun --}}
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

        {{-- Modal Edit Profil --}}
        <div class="modal-overlay" id="editProfileModal">
            <div class="modal-box">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Data Diri</h3>
                    <button type="button" class="modal-close-btn" id="btnCloseEditModal">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
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
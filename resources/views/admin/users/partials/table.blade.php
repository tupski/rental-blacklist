<!-- Desktop Table -->
<div class="d-none d-lg-block">
    <table class="table table-hover mb-0">
        <thead class="bg-dark text-white">
            <tr>
                <th width="5%" class="text-center">ID</th>
                <th width="20%">Nama</th>
                <th width="25%">Email</th>
                <th width="15%" class="text-center">Role</th>
                <th width="15%" class="text-center">Status Email</th>
                <th width="12%" class="text-center">Tanggal Daftar</th>
                <th width="8%" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="table-row-hover">
                <td class="text-center font-weight-bold text-dark">{{ $user->id }}</td>
                <td>
                    <strong class="text-dark">{{ $user->name }}</strong>
                    @if($user->nik)
                        <br><small class="text-secondary">NIK: {{ $user->nik }}</small>
                    @endif
                </td>
                <td>
                    <span class="text-dark font-weight-medium">{{ $user->email }}</span>
                    @if($user->no_hp)
                        <br><small class="text-secondary">HP: {{ $user->no_hp }}</small>
                    @endif
                </td>
                <td class="text-center">
                    @if($user->role === 'admin')
                        <span class="badge badge-danger px-3 py-2">Admin</span>
                    @elseif($user->role === 'pengusaha_rental')
                        <span class="badge badge-warning px-3 py-2">Pengusaha Rental</span>
                    @else
                        <span class="badge badge-info px-3 py-2">User Biasa</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($user->email_verified_at)
                        <span class="badge badge-success px-3 py-2">
                            <i class="fas fa-check-circle"></i> Terverifikasi
                        </span>
                    @else
                        <span class="badge badge-secondary px-3 py-2">
                            <i class="fas fa-clock"></i> Belum Verifikasi
                        </span>
                    @endif
                </td>
                <td class="text-center text-dark font-weight-medium">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button"
                                data-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item text-dark font-weight-medium" href="{{ route('admin.pengguna.tampil', $user) }}">
                                <i class="fas fa-eye text-info"></i> Lihat Detail
                            </a>
                            <a class="dropdown-item text-dark font-weight-medium" href="{{ route('admin.pengguna.edit', $user) }}">
                                <i class="fas fa-edit text-warning"></i> Edit Data
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('admin.pengguna.reset-kata-sandi', $user) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-dark font-weight-medium"
                                        onclick="return confirm('Reset password user ini ke \'password123\'?')">
                                    <i class="fas fa-key text-secondary"></i> Reset Password
                                </button>
                            </form>
                            @if($user->role !== 'admin')
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('admin.pengguna.hapus', $user) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger font-weight-medium"
                                            onclick="return confirm('HAPUS user ini secara permanen?\n\nData yang dihapus tidak dapat dikembalikan!')">
                                        <i class="fas fa-trash"></i> Hapus Permanen
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada data user</h5>
                    <p class="text-muted">Coba ubah filter pencarian Anda</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Cards -->
<div class="d-lg-none">
    @forelse($users as $user)
    <div class="card mb-3 border-left-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <h6 class="card-title mb-1 text-dark font-weight-bold">{{ $user->name }}</h6>
                    <p class="card-text">
                        <small class="text-dark">
                            <i class="fas fa-envelope text-primary"></i> <strong>{{ $user->email }}</strong><br>
                            @if($user->nik)
                                <i class="fas fa-id-card text-info"></i> <strong>{{ $user->nik }}</strong><br>
                            @endif
                            @if($user->no_hp)
                                <i class="fas fa-phone text-success"></i> <strong>{{ $user->no_hp }}</strong><br>
                            @endif
                            <i class="fas fa-calendar text-warning"></i> <strong>{{ $user->created_at->format('d/m/Y H:i') }}</strong>
                        </small>
                    </p>
                </div>
                <div class="col-4 text-right">
                    @if($user->role === 'admin')
                        <span class="badge badge-danger mb-2 px-3 py-2">Admin</span>
                    @elseif($user->role === 'pengusaha_rental')
                        <span class="badge badge-warning mb-2 px-3 py-2">Pengusaha Rental</span>
                    @else
                        <span class="badge badge-info mb-2 px-3 py-2">User Biasa</span>
                    @endif
                    <br>
                    @if($user->email_verified_at)
                        <span class="badge badge-success px-3 py-2">
                            <i class="fas fa-check-circle"></i> Terverifikasi
                        </span>
                    @else
                        <span class="badge badge-secondary px-3 py-2">
                            <i class="fas fa-clock"></i> Belum Verifikasi
                        </span>
                    @endif
                    <br><br>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button"
                                data-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item text-dark font-weight-medium" href="{{ route('admin.pengguna.tampil', $user) }}">
                                <i class="fas fa-eye text-info"></i> Lihat Detail
                            </a>
                            <a class="dropdown-item text-dark font-weight-medium" href="{{ route('admin.pengguna.edit', $user) }}">
                                <i class="fas fa-edit text-warning"></i> Edit Data
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('admin.pengguna.reset-kata-sandi', $user) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-dark font-weight-medium"
                                        onclick="return confirm('Reset password user ini ke \'password123\'?')">
                                    <i class="fas fa-key text-secondary"></i> Reset Password
                                </button>
                            </form>
                            @if($user->role !== 'admin')
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('admin.pengguna.hapus', $user) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger font-weight-medium"
                                            onclick="return confirm('HAPUS user ini secara permanen?\n\nData yang dihapus tidak dapat dikembalikan!')">
                                        <i class="fas fa-trash"></i> Hapus Permanen
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="fas fa-users fa-4x text-muted mb-4"></i>
        <h4 class="text-muted">Tidak ada data user</h4>
        <p class="text-muted">Coba ubah filter pencarian Anda</p>
    </div>
    @endforelse
</div>

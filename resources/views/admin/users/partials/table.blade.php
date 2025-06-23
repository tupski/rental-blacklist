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
                    @if($user->isBanned())
                        <span class="badge badge-danger px-3 py-2">
                            <i class="fas fa-ban"></i> BANNED
                        </span>
                    @elseif($user->account_status === 'active')
                        <span class="badge badge-success px-3 py-2 cursor-pointer"
                              data-toggle="modal" data-target="#statusModal{{ $user->id }}"
                              title="Klik untuk ubah status">
                            <i class="fas fa-check-circle"></i> Aktif
                        </span>
                    @elseif($user->account_status === 'pending')
                        <span class="badge badge-warning px-3 py-2 cursor-pointer"
                              data-toggle="modal" data-target="#statusModal{{ $user->id }}"
                              title="Klik untuk ubah status">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                    @elseif($user->account_status === 'suspended')
                        <span class="badge badge-danger px-3 py-2 cursor-pointer"
                              data-toggle="modal" data-target="#statusModal{{ $user->id }}"
                              title="Klik untuk ubah status">
                            <i class="fas fa-pause"></i> Suspend
                        </span>
                    @else
                        <span class="badge badge-secondary px-3 py-2 cursor-pointer"
                              data-toggle="modal" data-target="#statusModal{{ $user->id }}"
                              title="Klik untuk ubah status">
                            <i class="fas fa-question"></i> {{ ucfirst($user->account_status) }}
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
                    @if($user->isBanned())
                        <span class="badge badge-danger px-3 py-2">
                            <i class="fas fa-ban"></i> BANNED
                        </span>
                    @elseif($user->account_status === 'active')
                        <span class="badge badge-success px-3 py-2 cursor-pointer"
                              data-toggle="modal" data-target="#statusModal{{ $user->id }}"
                              title="Klik untuk ubah status">
                            <i class="fas fa-check-circle"></i> Aktif
                        </span>
                    @elseif($user->account_status === 'pending')
                        <span class="badge badge-warning px-3 py-2 cursor-pointer"
                              data-toggle="modal" data-target="#statusModal{{ $user->id }}"
                              title="Klik untuk ubah status">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                    @elseif($user->account_status === 'suspended')
                        <span class="badge badge-danger px-3 py-2 cursor-pointer"
                              data-toggle="modal" data-target="#statusModal{{ $user->id }}"
                              title="Klik untuk ubah status">
                            <i class="fas fa-pause"></i> Suspend
                        </span>
                    @else
                        <span class="badge badge-secondary px-3 py-2 cursor-pointer"
                              data-toggle="modal" data-target="#statusModal{{ $user->id }}"
                              title="Klik untuk ubah status">
                            <i class="fas fa-question"></i> {{ ucfirst($user->account_status) }}
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
                                @if($user->isBanned())
                                    <form action="{{ route('admin.pengguna.unban', $user) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-dark font-weight-medium"
                                                onclick="return confirm('Unban user ini?')">
                                            <i class="fas fa-unlock text-success"></i> Unban User
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="dropdown-item text-dark font-weight-medium"
                                            data-toggle="modal" data-target="#banModal{{ $user->id }}">
                                        <i class="fas fa-ban text-danger"></i> Ban User
                                    </button>
                                @endif
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

<!-- Status Change Modals -->
@foreach($users as $user)
    @if($user->role !== 'admin')
        <div class="modal fade" id="statusModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel{{ $user->id }}">Ubah Status: {{ $user->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.pengguna.ubah-status', $user) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="status{{ $user->id }}">Status Akun <span class="text-danger">*</span></label>
                                <select name="account_status" id="status{{ $user->id }}" class="form-control" required>
                                    <option value="pending" {{ $user->account_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ $user->account_status === 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="suspended" {{ $user->account_status === 'suspended' ? 'selected' : '' }}>Suspend</option>
                                </select>
                            </div>

                            <div class="form-group" id="suspendReason{{ $user->id }}" style="display: {{ $user->account_status === 'suspended' ? 'block' : 'none' }};">
                                <label for="reason{{ $user->id }}">Alasan Suspend</label>
                                <textarea name="reason" id="reason{{ $user->id }}" class="form-control" rows="3"
                                          placeholder="Masukkan alasan suspend...">{{ $user->suspension_reason }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

<!-- Ban User Modals -->
@foreach($users as $user)
    @if($user->role !== 'admin' && !$user->isBanned())
        <div class="modal fade" id="banModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="banModalLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="banModalLabel{{ $user->id }}">Ban User: {{ $user->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.pengguna.ban', $user) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Peringatan!</strong> User yang dibanned tidak akan bisa mengakses sistem dan akan menerima email notifikasi.
                            </div>

                            <div class="form-group">
                                <label for="banReason{{ $user->id }}">Alasan Ban <span class="text-danger">*</span></label>
                                <textarea name="reason" id="banReason{{ $user->id }}" class="form-control" rows="4"
                                          placeholder="Masukkan alasan mengapa user ini dibanned..." required></textarea>
                                <small class="form-text text-muted">Alasan ini akan dikirimkan ke email user.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-ban"></i> Ban User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

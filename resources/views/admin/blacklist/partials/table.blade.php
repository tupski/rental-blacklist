<!-- Desktop Table -->
<div class="d-none d-lg-block">
    <table class="table table-hover mb-0">
        <thead class="bg-dark text-white">
            <tr>
                <th width="5%" class="text-center">ID</th>
                <th width="20%">Nama Lengkap</th>
                <th width="15%">NIK</th>
                <th width="12%">No. HP</th>
                <th width="12%">Jenis Rental</th>
                <th width="8%" class="text-center">Laporan</th>
                <th width="10%" class="text-center">Status</th>
                <th width="10%" class="text-center">Tanggal</th>
                <th width="8%" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($blacklists as $blacklist)
            <tr class="table-row-hover">
                <td class="text-center font-weight-bold text-dark">{{ $blacklist->id }}</td>
                <td>
                    <strong class="text-dark">{{ $blacklist->nama_lengkap }}</strong>
                    <br><small class="text-secondary">{{ $blacklist->user->name ?? 'N/A' }}</small>
                </td>
                <td><code class="bg-light text-dark px-2 py-1 rounded">{{ $blacklist->nik }}</code></td>
                <td class="text-dark font-weight-medium">{{ $blacklist->no_hp }}</td>
                <td>
                    <span class="badge badge-info px-3 py-2">{{ $blacklist->jenis_rental }}</span>
                </td>
                <td class="text-center">
                    <span class="badge badge-secondary px-3 py-2">
                        {{ $reportCounts[$blacklist->nik] ?? 0 }} laporan
                    </span>
                </td>
                <td class="text-center">
                    @if($blacklist->status_validitas === 'Valid')
                        <span class="badge badge-success px-3 py-2">Valid</span>
                    @elseif($blacklist->status_validitas === 'Invalid')
                        <span class="badge badge-danger px-3 py-2">Invalid</span>
                    @else
                        <span class="badge badge-warning px-3 py-2">Pending</span>
                    @endif
                </td>
                <td class="text-center text-dark font-weight-medium">{{ $blacklist->created_at->format('d/m/Y') }}</td>
                <td class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button"
                                data-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item text-dark font-weight-medium" href="{{ route('admin.daftar-hitam.tampil', $blacklist->id) }}">
                                <i class="fas fa-eye text-info"></i> Lihat Detail
                            </a>
                            <a class="dropdown-item text-dark font-weight-medium" href="{{ route('admin.daftar-hitam.edit', $blacklist->id) }}">
                                <i class="fas fa-edit text-warning"></i> Edit Data
                            </a>
                            @if($blacklist->status_validitas === 'Pending')
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('admin.daftar-hitam.validasi', $blacklist->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-dark font-weight-medium"
                                            onclick="return confirm('Validasi data ini sebagai VALID?')">
                                        <i class="fas fa-check text-success"></i> Validasi
                                    </button>
                                </form>
                                <form action="{{ route('admin.daftar-hitam.batalkan', $blacklist->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-dark font-weight-medium"
                                            onclick="return confirm('Tandai data ini sebagai INVALID?')">
                                        <i class="fas fa-times text-danger"></i> Tolak
                                    </button>
                                </form>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('admin.daftar-hitam.hapus', $blacklist->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger font-weight-medium"
                                        onclick="return confirm('HAPUS data blacklist ini secara permanen?\n\nData yang dihapus tidak dapat dikembalikan!')">
                                    <i class="fas fa-trash"></i> Hapus Permanen
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada data blacklist</h5>
                    <p class="text-muted">Coba ubah filter pencarian Anda</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Cards -->
<div class="d-lg-none">
    @forelse($blacklists as $blacklist)
    <div class="card mb-3 border-left-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <h6 class="card-title mb-1 text-dark font-weight-bold">{{ $blacklist->nama_lengkap }}</h6>
                    <p class="card-text">
                        <small class="text-dark">
                            <i class="fas fa-id-card text-primary"></i> <strong>{{ $blacklist->nik }}</strong><br>
                            <i class="fas fa-phone text-success"></i> <strong>{{ $blacklist->no_hp }}</strong><br>
                            <i class="fas fa-car text-info"></i> <strong>{{ $blacklist->jenis_rental }}</strong><br>
                            <i class="fas fa-flag text-warning"></i> <strong>{{ $reportCounts[$blacklist->nik] ?? 0 }} laporan</strong>
                        </small>
                    </p>
                </div>
                <div class="col-4 text-right">
                    @if($blacklist->status_validitas === 'Valid')
                        <span class="badge badge-success mb-2 px-3 py-2">Valid</span>
                    @elseif($blacklist->status_validitas === 'Invalid')
                        <span class="badge badge-danger mb-2 px-3 py-2">Invalid</span>
                    @else
                        <span class="badge badge-warning mb-2 px-3 py-2">Pending</span>
                    @endif
                    <br>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button"
                                data-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item text-dark font-weight-medium" href="{{ route('admin.daftar-hitam.tampil', $blacklist->id) }}">
                                <i class="fas fa-eye text-info"></i> Lihat Detail
                            </a>
                            <a class="dropdown-item text-dark font-weight-medium" href="{{ route('admin.daftar-hitam.edit', $blacklist->id) }}">
                                <i class="fas fa-edit text-warning"></i> Edit Data
                            </a>
                            @if($blacklist->status_validitas === 'Pending')
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('admin.daftar-hitam.validasi', $blacklist->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-dark font-weight-medium"
                                            onclick="return confirm('Validasi data ini sebagai VALID?')">
                                        <i class="fas fa-check text-success"></i> Validasi
                                    </button>
                                </form>
                                <form action="{{ route('admin.daftar-hitam.batalkan', $blacklist->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-dark font-weight-medium"
                                            onclick="return confirm('Tandai data ini sebagai INVALID?')">
                                        <i class="fas fa-times text-danger"></i> Tolak
                                    </button>
                                </form>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('admin.daftar-hitam.hapus', $blacklist->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger font-weight-medium"
                                        onclick="return confirm('HAPUS data blacklist ini secara permanen?\n\nData yang dihapus tidak dapat dikembalikan!')">
                                    <i class="fas fa-trash"></i> Hapus Permanen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <small class="text-dark">
                        <i class="fas fa-user text-primary"></i> <strong>{{ $blacklist->user->name ?? 'N/A' }}</strong> •
                        <i class="fas fa-calendar text-success"></i> <strong>{{ $blacklist->created_at->format('d/m/Y H:i') }}</strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="fas fa-search fa-4x text-muted mb-4"></i>
        <h4 class="text-muted">Tidak ada data blacklist</h4>
        <p class="text-muted">Coba ubah filter pencarian Anda</p>
    </div>
    @endforelse
</div>

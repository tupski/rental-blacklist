<!-- Desktop Table -->
<div class="d-none d-lg-block">
    <table class="table table-hover mb-0">
        <thead class="bg-dark text-white">
            <tr>
                <th width="5%" class="text-center">ID</th>
                <th width="20%">Donatur</th>
                <th width="15%">Email/Perusahaan</th>
                <th width="10%" class="text-center">Jumlah</th>
                <th width="10%" class="text-center">Status</th>
                <th width="15%" class="text-center">Tanggal</th>
                <th width="25%">Pesan</th>
                <th width="10%" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donations as $donation)
            <tr>
                <td class="text-center font-weight-bold">{{ $donation->id }}</td>
                <td>
                    <strong>{{ $donation->donor_name }}</strong>
                    <br><small class="text-muted">{{ ucfirst($donation->donor_type) }}</small>
                </td>
                <td>
                    <span>{{ $donation->donor_email }}</span>
                    @if($donation->company_name)
                        <br><small class="text-muted">{{ $donation->company_name }}</small>
                    @endif
                </td>
                <td class="text-center">
                    <strong class="text-primary">{{ $donation->formatted_amount }}</strong>
                </td>
                <td class="text-center">
                    @if($donation->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif($donation->status === 'confirmed')
                        <span class="badge badge-success">Terkonfirmasi</span>
                    @else
                        <span class="badge badge-danger">Dibatalkan</span>
                    @endif
                </td>
                <td class="text-center">{{ $donation->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if($donation->message)
                        <small>{{ Str::limit($donation->message, 50) }}</small>
                    @else
                        <small class="text-muted">-</small>
                    @endif
                </td>
                <td class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button"
                                data-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('admin.donasi.tampil', $donation) }}">
                                <i class="fas fa-eye text-info"></i> Lihat Detail
                            </a>
                            @if($donation->status === 'pending')
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('admin.donasi.konfirmasi', $donation) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item"
                                            onclick="return confirm('Konfirmasi donasi ini?')">
                                        <i class="fas fa-check text-success"></i> Konfirmasi
                                    </button>
                                </form>
                                <form action="{{ route('admin.donasi.tolak', $donation) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item"
                                            onclick="return confirm('Tolak donasi ini?')">
                                        <i class="fas fa-times text-warning"></i> Tolak
                                    </button>
                                </form>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('admin.donasi.hapus', $donation) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger"
                                        onclick="return confirm('Hapus donasi ini secara permanen?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada data donasi</h5>
                    <p class="text-muted">Belum ada donasi yang masuk</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Cards -->
<div class="d-lg-none">
    @forelse($donations as $donation)
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <h6 class="card-title mb-1">{{ $donation->donor_name }}</h6>
                    <p class="card-text">
                        <small>
                            <i class="fas fa-envelope text-primary"></i> {{ $donation->donor_email }}<br>
                            @if($donation->company_name)
                                <i class="fas fa-building text-info"></i> {{ $donation->company_name }}<br>
                            @endif
                            <i class="fas fa-calendar text-warning"></i> {{ $donation->created_at->format('d/m/Y H:i') }}
                        </small>
                    </p>
                </div>
                <div class="col-4 text-right">
                    <h5 class="text-primary">{{ $donation->formatted_amount }}</h5>
                    @if($donation->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif($donation->status === 'confirmed')
                        <span class="badge badge-success">Terkonfirmasi</span>
                    @else
                        <span class="badge badge-danger">Dibatalkan</span>
                    @endif
                </div>
            </div>
            @if($donation->message)
                <div class="mt-2">
                    <small class="text-muted">{{ Str::limit($donation->message, 100) }}</small>
                </div>
            @endif
            <div class="mt-2">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button"
                            data-toggle="dropdown">
                        <i class="fas fa-cog"></i> Aksi
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.donasi.tampil', $donation) }}">
                            <i class="fas fa-eye text-info"></i> Lihat Detail
                        </a>
                        @if($donation->status === 'pending')
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('admin.donasi.konfirmasi', $donation) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item"
                                        onclick="return confirm('Konfirmasi donasi ini?')">
                                    <i class="fas fa-check text-success"></i> Konfirmasi
                                </button>
                            </form>
                            <form action="{{ route('admin.donasi.tolak', $donation) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item"
                                        onclick="return confirm('Tolak donasi ini?')">
                                    <i class="fas fa-times text-warning"></i> Tolak
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="fas fa-heart fa-4x text-muted mb-4"></i>
        <h4 class="text-muted">Tidak ada data donasi</h4>
        <p class="text-muted">Belum ada donasi yang masuk</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($donations->hasPages())
<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        <small class="text-muted">
            Menampilkan {{ $donations->firstItem() }} sampai {{ $donations->lastItem() }}
            dari {{ $donations->total() }} data
        </small>
    </div>
    <div>
        {{ $donations->links() }}
    </div>
</div>
@endif

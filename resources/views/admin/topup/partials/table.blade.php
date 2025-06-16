<!-- Desktop Table -->
<div class="d-none d-lg-block">
    <table class="table table-hover mb-0">
        <thead class="bg-dark text-white">
            <tr>
                <th width="8%" class="text-center">ID</th>
                <th width="15%">Invoice</th>
                <th width="20%">User</th>
                <th width="12%" class="text-center">Jumlah</th>
                <th width="12%" class="text-center">Metode</th>
                <th width="10%" class="text-center">Status</th>
                <th width="13%" class="text-center">Tanggal</th>
                <th width="10%" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topups as $topup)
            <tr class="table-row-hover">
                <td class="text-center font-weight-bold text-dark">{{ $topup->id }}</td>
                <td>
                    <strong class="text-primary invoice-number">{{ $topup->invoice_number }}</strong>
                    <br><small class="text-muted">{{ $topup->created_at->format('d/m/Y H:i') }}</small>
                </td>
                <td>
                    <strong class="text-dark">{{ $topup->user->name }}</strong>
                    <br><small class="text-muted">{{ $topup->user->email }}</small>
                </td>
                <td class="text-center">
                    <strong class="text-success">Rp {{ number_format($topup->amount, 0, ',', '.') }}</strong>
                </td>
                <td class="text-center">
                    <span class="badge badge-info">{{ ucfirst($topup->payment_method) }}</span>
                    @if($topup->payment_channel)
                        <br><small class="text-muted">{{ $topup->payment_channel }}</small>
                    @endif
                </td>
                <td class="text-center">
                    <span class="badge badge-{{ $topup->status_color }}">{{ $topup->status_text }}</span>
                </td>
                <td class="text-center text-dark">
                    {{ $topup->created_at->format('d/m/Y') }}
                    @if($topup->confirmed_at)
                        <br><small class="text-success">Dikonfirmasi</small>
                    @endif
                </td>
                <td class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button"
                                data-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item text-dark" href="{{ route('admin.isi-saldo.tampil', $topup->id) }}">
                                <i class="fas fa-eye text-info"></i> Lihat Detail
                            </a>
                            
                            @if($topup->status === 'pending')
                                <div class="dropdown-divider"></div>
                                <button type="button" class="dropdown-item text-dark"
                                        onclick="approveTopup({{ $topup->id }})">
                                    <i class="fas fa-check text-success"></i> Approve
                                </button>
                                <button type="button" class="dropdown-item text-dark"
                                        onclick="rejectTopup({{ $topup->id }})">
                                    <i class="fas fa-times text-danger"></i> Reject
                                </button>
                            @endif
                            
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('admin.isi-saldo.hapus', $topup->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger"
                                        onclick="return confirm('Hapus data topup ini?')">
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
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada data topup</h5>
                        <p class="text-muted">Belum ada permintaan topup yang sesuai dengan filter</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Cards -->
<div class="d-lg-none">
    @forelse($topups as $topup)
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <h6 class="card-title mb-1">
                        <strong class="text-primary">{{ $topup->invoice_number }}</strong>
                    </h6>
                    <p class="text-muted mb-2">{{ $topup->user->name }}</p>
                    <p class="text-success font-weight-bold mb-2">
                        Rp {{ number_format($topup->amount, 0, ',', '.') }}
                    </p>
                    <div class="mb-2">
                        <span class="badge badge-{{ $topup->status_color }}">{{ $topup->status_text }}</span>
                        <span class="badge badge-info ml-1">{{ ucfirst($topup->payment_method) }}</span>
                    </div>
                    <small class="text-muted">{{ $topup->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <div class="col-4 text-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('admin.isi-saldo.tampil', $topup->id) }}">
                                <i class="fas fa-eye text-info"></i> Detail
                            </a>
                            
                            @if($topup->status === 'pending')
                                <div class="dropdown-divider"></div>
                                <button type="button" class="dropdown-item"
                                        onclick="approveTopup({{ $topup->id }})">
                                    <i class="fas fa-check text-success"></i> Approve
                                </button>
                                <button type="button" class="dropdown-item"
                                        onclick="rejectTopup({{ $topup->id }})">
                                    <i class="fas fa-times text-danger"></i> Reject
                                </button>
                            @endif
                            
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('admin.isi-saldo.hapus', $topup->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger"
                                        onclick="return confirm('Hapus data topup ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">Tidak ada data topup</h5>
        <p class="text-muted">Belum ada permintaan topup yang sesuai dengan filter</p>
    </div>
    @endforelse
</div>

<style>
.table-row-hover:hover {
    background-color: #f8f9fa;
}
.invoice-number {
    font-family: 'Courier New', monospace;
    font-weight: bold;
}
.empty-state {
    padding: 2rem;
}
</style>

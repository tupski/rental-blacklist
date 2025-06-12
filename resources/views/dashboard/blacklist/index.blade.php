@extends('layouts.main')

@section('title', 'Kelola Laporan Blacklist')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-list text-red-600 mr-3"></i>
                Kelola Laporan Blacklist
            </h1>
            <p class="text-gray-600 mt-2">Kelola semua laporan blacklist rental</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('dashboard.blacklist.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-200">
                <i class="fas fa-plus mr-2"></i>
                Tambah Laporan
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-filter text-blue-500 mr-2"></i>
            Filter & Pencarian
        </h3>
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                <input 
                    type="text" 
                    id="searchFilter" 
                    name="search"
                    placeholder="NIK atau Nama"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Rental</label>
                <select id="jenisRentalFilter" name="jenis_rental" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua</option>
                    <option value="Mobil">Mobil</option>
                    <option value="Motor">Motor</option>
                    <option value="Kamera">Kamera</option>
                    <option value="Alat Elektronik">Alat Elektronik</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua</option>
                    <option value="Valid">Valid</option>
                    <option value="Pending">Pending</option>
                    <option value="Invalid">Invalid</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Loading -->
    <div id="loading" class="text-center py-8 hidden">
        <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-blue-500">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memuat data...
        </div>
    </div>

    <!-- Results -->
    <div id="results">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-database mr-2"></i>
                    Data Laporan
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Rental</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="blacklistTableBody" class="bg-white divide-y divide-gray-200">
                        @forelse($blacklists as $blacklist)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $blacklist->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">{{ $blacklist->no_hp }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $blacklist->nik }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $blacklist->jenis_rental }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($blacklist->status_validitas === 'Valid') bg-green-100 text-green-800
                                    @elseif($blacklist->status_validitas === 'Pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $blacklist->status_validitas }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $blacklist->user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $blacklist->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="showDetail({{ $blacklist->id }})" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($blacklist->user_id === Auth::id())
                                        <a href="{{ route('dashboard.blacklist.edit', $blacklist->id) }}" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteBlacklist({{ $blacklist->id }})" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Belum ada data laporan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($blacklists->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $blacklists->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Detail Laporan
                </h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="detailContent">
                <!-- Detail content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Filter form submission
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        loadBlacklists();
    });

    // Auto-filter on input change
    $('#searchFilter, #jenisRentalFilter, #statusFilter').on('change input', function() {
        clearTimeout(window.filterTimeout);
        window.filterTimeout = setTimeout(function() {
            loadBlacklists();
        }, 500);
    });

    function loadBlacklists() {
        $('#loading').removeClass('hidden');
        
        const formData = {
            search: $('#searchFilter').val(),
            jenis_rental: $('#jenisRentalFilter').val(),
            status: $('#statusFilter').val()
        };

        $.ajax({
            url: '{{ route("dashboard.blacklist.index") }}',
            method: 'GET',
            data: formData,
            success: function(response) {
                if (response.success) {
                    updateTable(response.data);
                    updatePagination(response.pagination);
                }
            },
            error: function(xhr) {
                console.error('Load error:', xhr);
                alert('Terjadi kesalahan saat memuat data');
            },
            complete: function() {
                $('#loading').addClass('hidden');
            }
        });
    }

    function updateTable(data) {
        let html = '';
        if (data.length > 0) {
            data.forEach(function(item) {
                const statusClass = item.status_validitas === 'Valid' ? 'bg-green-100 text-green-800' : 
                                   item.status_validitas === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800';
                
                const canEdit = item.user_id === {{ Auth::id() }};
                
                html += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${item.nama_lengkap}</div>
                            <div class="text-sm text-gray-500">${item.no_hp}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${item.nik}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${item.jenis_rental}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                ${item.status_validitas}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${item.user.name}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">${new Date(item.created_at).toLocaleDateString('id-ID')}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="showDetail(${item.id})" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${canEdit ? `
                                    <a href="/dashboard/blacklist/${item.id}/edit" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteBlacklist(${item.id})" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            html = `
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data ditemukan
                    </td>
                </tr>
            `;
        }
        
        $('#blacklistTableBody').html(html);
    }

    // Global functions
    window.showDetail = function(id) {
        $.ajax({
            url: `/dashboard/blacklist/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    let jenisLaporanHtml = '';
                    data.jenis_laporan.forEach(function(jenis) {
                        jenisLaporanHtml += `<span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full mr-2 mb-2 inline-block">${formatJenisLaporan(jenis)}</span>`;
                    });
                    
                    let buktiHtml = '';
                    if (data.bukti && data.bukti.length > 0) {
                        data.bukti.forEach(function(file) {
                            buktiHtml += `<a href="/storage/${file}" target="_blank" class="text-blue-600 hover:text-blue-800 mr-3">${file.split('/').pop()}</a>`;
                        });
                    } else {
                        buktiHtml = '<span class="text-gray-500">Tidak ada bukti</span>';
                    }
                    
                    $('#detailContent').html(`
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.nama_lengkap}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">NIK</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.nik}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">No HP</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.no_hp}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Rental</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.jenis_rental}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <p class="mt-1"><span class="px-2 py-1 text-xs font-medium rounded-full ${data.status_validitas === 'Valid' ? 'bg-green-100 text-green-800' : data.status_validitas === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">${data.status_validitas}</span></p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <p class="mt-1 text-sm text-gray-900">${data.alamat}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Laporan</label>
                                <div class="mt-1">${jenisLaporanHtml}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kronologi</label>
                                <p class="mt-1 text-sm text-gray-900">${data.kronologi}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Kejadian</label>
                                <p class="mt-1 text-sm text-gray-900">${new Date(data.tanggal_kejadian).toLocaleDateString('id-ID')}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Bukti</label>
                                <div class="mt-1">${buktiHtml}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pelapor</label>
                                <p class="mt-1 text-sm text-gray-900">${data.user.name}</p>
                            </div>
                        </div>
                    `);
                    $('#detailModal').removeClass('hidden');
                }
            },
            error: function(xhr) {
                console.error('Detail error:', xhr);
                alert('Terjadi kesalahan saat mengambil detail');
            }
        });
    };

    window.closeDetailModal = function() {
        $('#detailModal').addClass('hidden');
    };

    window.deleteBlacklist = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus laporan ini?')) {
            $.ajax({
                url: `/dashboard/blacklist/${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        loadBlacklists();
                    }
                },
                error: function(xhr) {
                    console.error('Delete error:', xhr);
                    alert('Terjadi kesalahan saat menghapus data');
                }
            });
        }
    };

    function formatJenisLaporan(jenis) {
        const mapping = {
            'percobaan_penipuan': 'Percobaan Penipuan',
            'penipuan': 'Penipuan',
            'tidak_mengembalikan_barang': 'Tidak Mengembalikan Barang',
            'identitas_palsu': 'Identitas Palsu',
            'sindikat': 'Sindikat',
            'merusak_barang': 'Merusak Barang'
        };
        return mapping[jenis] || jenis;
    }
});
</script>
@endpush

<!-- Modal content template for JavaScript -->
<script type="text/template" id="detailModalTemplate">
<div class="container-fluid">
    <div class="row g-4">
        <!-- 1. Informasi Penyewa -->
        <div class="col-12">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fas fa-user text-primary me-2"></i>
                Informasi Penyewa
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Nama Lengkap</label>
                    <p class="mb-0 fw-bold">{nama_lengkap}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">NIK</label>
                    <p class="mb-0">{nik}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Jenis Kelamin</label>
                    <p class="mb-0">{jenis_kelamin_formatted}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">No. HP</label>
                    <p class="mb-0">{no_hp}</p>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium text-muted">Alamat</label>
                    <p class="mb-0">{alamat}</p>
                </div>
            </div>
        </div>

        <!-- 2. Foto Penyewa -->
        <div class="col-12">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fas fa-camera text-primary me-2"></i>
                Foto Penyewa
            </h6>
            <div id="fotoPenyewaContainer">
                {foto_penyewa_html}
            </div>
        </div>

        <!-- 3. Foto KTP/SIM -->
        <div class="col-12">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fas fa-id-card text-primary me-2"></i>
                Foto KTP/SIM
            </h6>
            <div id="fotoKtpSimContainer">
                {foto_ktp_sim_html}
            </div>
        </div>

        <!-- 4. Informasi Pelapor -->
        <div class="col-12">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fas fa-building text-primary me-2"></i>
                Informasi Pelapor
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Nama Perusahaan Rental</label>
                    <p class="mb-0">{nama_perusahaan_rental}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Nama Penanggung Jawab</label>
                    <p class="mb-0">{nama_penanggung_jawab}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">No. WhatsApp</label>
                    <p class="mb-0">{no_wa_pelapor}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Email</label>
                    <p class="mb-0">{email_pelapor}</p>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium text-muted">Alamat Usaha</label>
                    <p class="mb-0">{alamat_usaha}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Website</label>
                    <p class="mb-0">{website_usaha_link}</p>
                </div>
            </div>
        </div>

        <!-- 5. Detail Masalah -->
        <div class="col-12">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fas fa-car text-primary me-2"></i>
                Detail Masalah
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Kategori Rental</label>
                    <p class="mb-0"><span class="badge bg-info">{jenis_rental}</span></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Tanggal Sewa</label>
                    <p class="mb-0">{tanggal_sewa_formatted}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Tanggal Kejadian</label>
                    <p class="mb-0">{tanggal_kejadian_formatted}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Jenis Kendaraan/Barang</label>
                    <p class="mb-0">{jenis_kendaraan}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Nomor Polisi</label>
                    <p class="mb-0">{nomor_polisi}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Nilai Kerugian</label>
                    <p class="mb-0">{nilai_kerugian_formatted}</p>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium text-muted">Jenis Laporan</label>
                    <div>{jenis_laporan_html}</div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-medium text-muted">Kronologi</label>
                    <p class="mb-0">{kronologi}</p>
                </div>
            </div>
        </div>

        <!-- 6. Status Penanganan -->
        <div class="col-12">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fas fa-tasks text-primary me-2"></i>
                Status Penanganan
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Status Penanganan</label>
                    <div>{status_penanganan_html}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Status Lainnya</label>
                    <p class="mb-0">{status_lainnya}</p>
                </div>
            </div>
        </div>

        <!-- 7. Bukti Pendukung -->
        <div class="col-12">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fas fa-paperclip text-primary me-2"></i>
                Bukti Pendukung
            </h6>
            <div id="buktiContainer">
                {bukti_html}
            </div>
        </div>

        <!-- 8. Persetujuan -->
        <div class="col-12">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fas fa-check-circle text-primary me-2"></i>
                Persetujuan dan Tanda Tangan
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Persetujuan</label>
                    <p class="mb-0">{persetujuan_formatted}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Nama Pelapor (TTD)</label>
                    <p class="mb-0">{nama_pelapor_ttd}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Tanggal Pelaporan</label>
                    <p class="mb-0">{tanggal_pelaporan_formatted}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Tipe Pelapor</label>
                    <p class="mb-0">{tipe_pelapor_formatted}</p>
                </div>
            </div>
        </div>

        <!-- 9. Informasi Sistem -->
        <div class="col-12">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fas fa-info text-primary me-2"></i>
                Informasi Sistem
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Status Validitas</label>
                    <p class="mb-0">{status_validitas_badge}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Jumlah Laporan (NIK ini)</label>
                    <p class="mb-0"><span class="badge bg-success">{jumlah_laporan} laporan</span></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Pelapor</label>
                    <p class="mb-0">{pelapor}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium text-muted">Tanggal Dibuat</label>
                    <p class="mb-0">{created_at}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</script>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle">Lihat Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" style="max-height: 70vh;">
            </div>
            <div class="modal-footer">
                <a id="downloadImageLink" href="" target="_blank" class="btn btn-primary">
                    <i class="fas fa-download me-2"></i>Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

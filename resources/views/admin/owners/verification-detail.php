<!-- Admin - Detail Verifikasi Owner -->
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <a href="<?= url('/admin/verification') ?>" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <div class="row">
                <!-- Owner Information -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user"></i> Informasi Owner</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Nama:</th>
                                    <td><?= htmlspecialchars($owner['name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?= htmlspecialchars($owner['email']) ?></td>
                                </tr>
                                <tr>
                                    <th>Telepon:</th>
                                    <td><?= htmlspecialchars($owner['phone']) ?></td>
                                </tr>
                                <tr>
                                    <th>Alamat:</th>
                                    <td><?= htmlspecialchars($owner['address'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <th>Tgl Daftar:</th>
                                    <td><?= date('d M Y H:i', strtotime($owner['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Status User:</th>
                                    <td>
                                        <?php if ($owner['status'] === 'active'): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php elseif ($owner['status'] === 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><?= ucfirst($owner['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>

                            <?php if ($owner['profile_photo']): ?>
                                <div class="mt-3">
                                    <strong>Foto Profil:</strong><br>
                                    <img src="<?= url($owner['profile_photo']) ?>" alt="Profile" class="img-thumbnail mt-2" style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- KTP & Verification Status -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-id-card"></i> Foto KTP</h5>
                        </div>
                        <div class="card-body text-center">
                            <?php if (!empty($owner['ktp_photo'])): ?>
                                <img src="<?= url($owner['ktp_photo']) ?>" alt="KTP" class="img-fluid" style="max-height: 400px;">
                                <div class="mt-3">
                                    <a href="<?= url($owner['ktp_photo']) ?>" target="_blank" class="btn btn-info">
                                        <i class="fas fa-expand"></i> Lihat Ukuran Penuh
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Belum ada foto KTP yang diupload
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-check-circle"></i> Status Verifikasi</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Status:</th>
                                    <td>
                                        <?php if ($owner['verification_status'] === 'pending'): ?>
                                            <span class="badge bg-warning">Menunggu Verifikasi</span>
                                        <?php elseif ($owner['verification_status'] === 'approved'): ?>
                                            <span class="badge bg-success">Disetujui</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if ($owner['verified_at']): ?>
                                    <tr>
                                        <th>Diverifikasi:</th>
                                        <td><?= date('d M Y H:i', strtotime($owner['verified_at'])) ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($owner['verification_note']): ?>
                                    <tr>
                                        <th>Catatan:</th>
                                        <td><?= htmlspecialchars($owner['verification_note']) ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>

                            <hr>

                            <!-- Action Buttons -->
                            <?php if ($owner['verification_status'] === 'pending'): ?>
                                <form method="POST" action="<?= url('/admin/verification/approve/' . $owner['id']) ?>" class="d-inline">
                                    <div class="mb-3">
                                        <label for="note" class="form-label">Catatan (opsional)</label>
                                        <textarea class="form-control" id="note" name="note" rows="2"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Setujui verifikasi owner ini?')">
                                        <i class="fas fa-check"></i> Setujui Verifikasi
                                    </button>
                                </form>

                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times"></i> Tolak Verifikasi
                                </button>

                            <?php else: ?>
                                <form method="POST" action="<?= url('/admin/verification/reset/' . $owner['id']) ?>" class="d-inline">
                                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Reset status verifikasi?')">
                                        <i class="fas fa-redo"></i> Reset Verifikasi
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= url('/admin/verification/reject/' . $owner['id']) ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Verifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Alasan Penolakan *</label>
                        <textarea class="form-control" id="reason" name="reason" rows="4" required placeholder="Contoh: Foto KTP tidak jelas / Data tidak sesuai"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

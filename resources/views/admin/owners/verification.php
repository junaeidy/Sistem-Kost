<!-- Admin - Verifikasi Owner -->
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-check"></i> Verifikasi Owner</h5>
                </div>
                <div class="card-body">
                    <!-- Filter Tabs -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#pending">
                                Menunggu Verifikasi
                                <span class="badge bg-warning ms-1">
                                    <?= count(array_filter($owners, fn($o) => $o['verification_status'] === 'pending')) ?>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#approved">
                                Disetujui
                                <span class="badge bg-success ms-1">
                                    <?= count(array_filter($owners, fn($o) => $o['verification_status'] === 'approved')) ?>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#rejected">
                                Ditolak
                                <span class="badge bg-danger ms-1">
                                    <?= count(array_filter($owners, fn($o) => $o['verification_status'] === 'rejected')) ?>
                                </span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Pending -->
                        <div class="tab-pane fade show active" id="pending">
                            <?php 
                            $pendingOwners = array_filter($owners, fn($o) => $o['verification_status'] === 'pending');
                            ?>
                            <?php if (empty($pendingOwners)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Tidak ada owner yang menunggu verifikasi.
                                </div>
                            <?php else: ?>
                                <?php include '_owner_table.php'; $tableOwners = $pendingOwners; $tableStatus = 'pending'; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Approved -->
                        <div class="tab-pane fade" id="approved">
                            <?php 
                            $approvedOwners = array_filter($owners, fn($o) => $o['verification_status'] === 'approved');
                            ?>
                            <?php if (empty($approvedOwners)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Belum ada owner yang disetujui.
                                </div>
                            <?php else: ?>
                                <?php include '_owner_table.php'; $tableOwners = $approvedOwners; $tableStatus = 'approved'; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Rejected -->
                        <div class="tab-pane fade" id="rejected">
                            <?php 
                            $rejectedOwners = array_filter($owners, fn($o) => $o['verification_status'] === 'rejected');
                            ?>
                            <?php if (empty($rejectedOwners)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Belum ada owner yang ditolak.
                                </div>
                            <?php else: ?>
                                <?php include '_owner_table.php'; $tableOwners = $rejectedOwners; $tableStatus = 'rejected'; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include the table partial
function renderOwnerTable($owners, $status) {
    ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Foto KTP</th>
                    <th>Tgl Daftar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($owners as $owner): ?>
                    <tr>
                        <td><?= htmlspecialchars($owner['name']) ?></td>
                        <td><?= htmlspecialchars($owner['email']) ?></td>
                        <td><?= htmlspecialchars($owner['phone']) ?></td>
                        <td>
                            <?php if (!empty($owner['ktp_photo'])): ?>
                                <a href="<?= url($owner['ktp_photo']) ?>" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Belum upload</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d M Y', strtotime($owner['registered_at'])) ?></td>
                        <td>
                            <?php if ($owner['verification_status'] === 'pending'): ?>
                                <span class="badge bg-warning">Menunggu</span>
                            <?php elseif ($owner['verification_status'] === 'approved'): ?>
                                <span class="badge bg-success">Disetujui</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Ditolak</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= url('/admin/verification/show/' . $owner['id']) ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            
                            <?php if ($owner['verification_status'] === 'pending'): ?>
                                <button type="button" class="btn btn-sm btn-success" onclick="approveOwner(<?= $owner['id'] ?>)">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="showRejectModal(<?= $owner['id'] ?>)">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            <?php elseif ($owner['verification_status'] === 'approved' || $owner['verification_status'] === 'rejected'): ?>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="resetVerification(<?= $owner['id'] ?>)">
                                    <i class="fas fa-redo"></i> Reset
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Render tables
if (isset($tableOwners) && isset($tableStatus)) {
    renderOwnerTable($tableOwners, $tableStatus);
}
?>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Verifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Alasan Penolakan *</label>
                        <textarea class="form-control" id="reason" name="reason" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function approveOwner(ownerId) {
    if (!confirm('Setujui verifikasi owner ini?')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= url('/admin/verification/approve/') ?>' + ownerId;
    document.body.appendChild(form);
    form.submit();
}

function showRejectModal(ownerId) {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    const form = document.getElementById('rejectForm');
    form.action = '<?= url('/admin/verification/reject/') ?>' + ownerId;
    modal.show();
}

function resetVerification(ownerId) {
    if (!confirm('Reset status verifikasi owner ini?')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= url('/admin/verification/reset/') ?>' + ownerId;
    document.body.appendChild(form);
    form.submit();
}
</script>

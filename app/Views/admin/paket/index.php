<?php $pakets = $pakets ?? []; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:700;color:#1A2B3C;margin:0">Daftar Paket Wisata</h5>
        <p class="text-muted mb-0" style="font-size:.85rem">Total <?= count($pakets) ?> paket terdaftar</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/paket/create" class="btn btn-primary rounded-3 px-4">
        <i class="bi bi-plus-lg me-1"></i>Tambah Paket
    </a>
</div>

<div class="stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
            <thead style="background:#F8FAFC">
                <tr>
                    <th class="py-3 ps-3" style="font-weight:600;color:#64748B">#</th>
                    <th style="font-weight:600;color:#64748B">Foto</th>
                    <th style="font-weight:600;color:#64748B">Nama Paket</th>
                    <th style="font-weight:600;color:#64748B">Destinasi</th>
                    <th style="font-weight:600;color:#64748B">Harga</th>
                    <th style="font-weight:600;color:#64748B">Durasi</th>
                    <th style="font-weight:600;color:#64748B">Status</th>
                    <th style="font-weight:600;color:#64748B">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($pakets)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-map" style="font-size:2rem;display:block;margin-bottom:8px"></i>
                        Belum ada paket wisata. <a href="<?= BASE_URL ?>/admin/paket/create">Tambahkan sekarang</a>.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($pakets as $i => $p): ?>
                <tr>
                    <td class="ps-3 text-muted"><?= $i + 1 ?></td>
                    <td>
                        <?php if ($p['foto']): ?>
                        <img src="<?= BASE_URL ?>/uploads/paket/<?= htmlspecialchars($p['foto']) ?>"
                             alt="foto" class="rounded-2"
                             style="width:54px;height:40px;object-fit:cover">
                        <?php else: ?>
                        <div class="rounded-2 d-flex align-items-center justify-content-center"
                             style="width:54px;height:40px;background:#F1F5F9;color:#94A3B8">
                            <i class="bi bi-image"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($p['nama_paket']) ?></strong></td>
                    <td><i class="bi bi-geo-alt text-primary me-1"></i><?= htmlspecialchars($p['destinasi']) ?></td>
                    <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                    <td><?= $p['durasi_hari'] ?> Hari</td>
                    <td>
                        <span class="badge <?= $p['status']==='aktif' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?> rounded-pill">
                            <?= ucfirst($p['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/paket/<?= $p['id'] ?>/edit"
                           class="btn btn-sm btn-outline-primary rounded-2 me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="<?= BASE_URL ?>/admin/paket/<?= $p['id'] ?>/delete"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus paket ini?')">
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-2">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

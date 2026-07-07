<div class="mb-4">
    <a href="<?= BASE_URL ?>/admin/paket" class="text-decoration-none text-muted" style="font-size:.85rem">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke daftar paket
    </a>
</div>

<div class="stat-card" style="max-width:700px">
    <h5 class="mb-4" style="font-weight:700;color:#1A2B3C">Tambah Paket Wisata</h5>

    <form action="<?= BASE_URL ?>/admin/paket/store" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-500">Nama Paket <span class="text-danger">*</span></label>
                <input type="text" name="nama_paket" class="form-control" required
                       placeholder="e.g. Bali Surga Tropis">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-500">Status</label>
                <select name="status" class="form-select">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500">Destinasi <span class="text-danger">*</span></label>
                <input type="text" name="destinasi" class="form-control" required
                       placeholder="e.g. Bali, Indonesia">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-500">Harga (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga" class="form-control" min="0" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-500">Durasi (Hari)</label>
                <input type="number" name="durasi_hari" class="form-control" min="1" value="1">
            </div>
            <div class="col-md-12">
                <label class="form-label fw-500">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"
                          placeholder="Deskripsi singkat paket wisata..."></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500">Kuota Peserta</label>
                <input type="number" name="kuota" class="form-control" min="1" value="10">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500">Foto Paket</label>
                <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp"
                       onchange="previewFoto(this)">
                <div class="form-text">Maks. 2 MB. Format: JPG, PNG, WebP.</div>
                <img id="fotoPreview" src="" alt="Preview" class="mt-2 rounded-2 d-none"
                     style="max-width:100%;max-height:160px;object-fit:cover">
            </div>
        </div>

        <hr class="my-4">
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4 rounded-3">
                <i class="bi bi-save me-1"></i>Simpan Paket
            </button>
            <a href="<?= BASE_URL ?>/admin/paket" class="btn btn-outline-secondary rounded-3">Batal</a>
        </div>
    </form>
</div>

<script>
function previewFoto(input) {
    const preview = document.getElementById('fotoPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<div class="card">
    <div class="card-header">Tambah Permission</div>
    <div class="card-body">
        <form action="<?= e(base_url('permissions/store')) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="grid">
                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Nama Permission</label>
                    <input type="text" name="name" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                </div>

                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Code Permission</label>
                    <input type="text" name="code" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                </div>

                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Module</label>
                    <input type="text" name="module" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                </div>

                <div class="col-12">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Description</label>
                    <textarea name="description" rows="4" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;"></textarea>
                </div>

                <div class="col-12">
                    <label style="display:inline-flex; align-items:center; gap:10px; font-weight:700;">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Permission aktif
                    </label>
                </div>

                <div class="col-12">
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button type="submit" style="border:none; padding:12px 18px; border-radius:14px; background:linear-gradient(90deg,#f59e0b,#f97316); color:#fff; font-weight:700; cursor:pointer;">
                            Simpan Permission
                        </button>
                        <a href="<?= e(base_url('permissions')) ?>" style="display:inline-flex; align-items:center; padding:12px 18px; border-radius:14px; border:1px solid #dbeafe; color:inherit;">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
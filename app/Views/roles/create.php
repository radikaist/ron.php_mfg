<div class="card">
    <div class="card-header">Tambah Role</div>
    <div class="card-body">
        <form action="<?= e(base_url('roles/store')) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="grid">
                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Nama Role</label>
                    <input type="text" name="name" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                </div>

                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Code Role</label>
                    <input type="text" name="code" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                </div>

                <div class="col-12">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Description</label>
                    <textarea name="description" rows="4" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;"></textarea>
                </div>

                <div class="col-12">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Permissions</label>
                    <select name="permission_ids[]" multiple style="width:100%; min-height:220px; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                        <?php foreach ($permissions as $permission): ?>
                            <option value="<?= e((string) $permission['id']) ?>">
                                [<?= e($permission['module']) ?>] <?= e($permission['name']) ?> (<?= e($permission['code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label style="display:inline-flex; align-items:center; gap:10px; font-weight:700;">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Role aktif
                    </label>
                </div>

                <div class="col-12">
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button type="submit" style="border:none; padding:12px 18px; border-radius:14px; background:linear-gradient(90deg,#22c55e,#16a34a); color:#fff; font-weight:700; cursor:pointer;">
                            Simpan Role
                        </button>
                        <a href="<?= e(base_url('roles')) ?>" style="display:inline-flex; align-items:center; padding:12px 18px; border-radius:14px; border:1px solid #dbeafe; color:inherit;">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
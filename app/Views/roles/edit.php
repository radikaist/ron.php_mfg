<div class="card">
    <div class="card-header">Edit Role</div>
    <div class="card-body">
        <form action="<?= e(base_url('roles/update')) ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e((string) $roleData['id']) ?>">

            <div class="grid">
                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Nama Role</label>
                    <input type="text" name="name" value="<?= e($roleData['name']) ?>" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                </div>

                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Code Role</label>
                    <input type="text" name="code" value="<?= e($roleData['code']) ?>" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                </div>

                <div class="col-12">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Description</label>
                    <textarea name="description" rows="4" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;"><?= e($roleData['description']) ?></textarea>
                </div>

                <div class="col-12">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Permissions</label>
                    <select name="permission_ids[]" multiple style="width:100%; min-height:220px; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                        <?php foreach ($permissions as $permission): ?>
                            <option value="<?= e((string) $permission['id']) ?>" <?= in_array((int) $permission['id'], $roleData['permission_ids'], true) ? 'selected' : '' ?>>
                                [<?= e($permission['module']) ?>] <?= e($permission['name']) ?> (<?= e($permission['code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label style="display:inline-flex; align-items:center; gap:10px; font-weight:700;">
                        <input type="checkbox" name="is_active" value="1" <?= (int) $roleData['is_active'] === 1 ? 'checked' : '' ?>>
                        Role aktif
                    </label>
                </div>

                <div class="col-12">
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button type="submit" style="border:none; padding:12px 18px; border-radius:14px; background:linear-gradient(90deg,#ec4899,#8b5cf6); color:#fff; font-weight:700; cursor:pointer;">
                            Update Role
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
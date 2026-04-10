<div class="card">
    <div class="card-header">Tambah Role</div>
    <div class="card-body">
        <form action="<?= e(base_url('roles/store')) ?>" method="POST" data-confirm="Simpan role baru?">
            <?= csrf_field() ?>

            <div class="grid">
                <div class="col-6">
                    <label class="form-label">Nama Role</label>
                    <input type="text" name="name" value="<?= e(old_or('name')) ?>" class="form-control">
                </div>

                <div class="col-6">
                    <label class="form-label">Code Role</label>
                    <input type="text" name="code" value="<?= e(old_or('code')) ?>" class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-textarea"><?= e(old_or('description')) ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Permissions</label>
                    <?php $oldPermissionIds = old_or('permission_ids', []); if (!is_array($oldPermissionIds)) $oldPermissionIds = []; ?>
                    <select name="permission_ids[]" multiple class="form-select" style="min-height:220px;">
                        <?php foreach ($permissions as $permission): ?>
                            <option value="<?= e((string) $permission['id']) ?>" <?= in_array((string) $permission['id'], array_map('strval', $oldPermissionIds), true) ? 'selected' : '' ?>>
                                [<?= e($permission['module']) ?>] <?= e($permission['name']) ?> (<?= e($permission['code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?= old_or('is_active', 1) ? 'checked' : '' ?>>
                        Role aktif
                    </label>
                </div>

                <div class="col-12">
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button type="submit" class="btn btn-success">Simpan Role</button>
                        <a href="<?= e(base_url('roles')) ?>" class="btn-outline">Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">Edit Role</div>
    <div class="card-body">
        <form action="<?= e(base_url('roles/update')) ?>" method="POST" data-confirm="Update role ini?">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e((string) $roleData['id']) ?>">

            <div class="grid">
                <div class="col-6">
                    <label class="form-label">Nama Role</label>
                    <input type="text" name="name" value="<?= e(old_or('name', $roleData['name'])) ?>" class="form-control">
                </div>

                <div class="col-6">
                    <label class="form-label">Code Role</label>
                    <input type="text" name="code" value="<?= e(old_or('code', $roleData['code'])) ?>" class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-textarea"><?= e(old_or('description', $roleData['description'])) ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Permissions</label>

                    <?php
                    $selectedPermissionIds = old_or('permission_ids', $roleData['permission_ids']);
                    if (!is_array($selectedPermissionIds)) {
                        $selectedPermissionIds = [];
                    }
                    $selectedPermissionIds = array_map('strval', $selectedPermissionIds);
                    ?>

                    <div class="checkbox-grid">
                        <?php foreach ($permissions as $permission): ?>
                            <?php $checked = in_array((string) $permission['id'], $selectedPermissionIds, true); ?>
                            <label class="checkbox-card">
                                <input type="checkbox" name="permission_ids[]" value="<?= e((string) $permission['id']) ?>" <?= $checked ? 'checked' : '' ?>>
                                <div>
                                    <div class="checkbox-card-title">
                                        <?= e($permission['name']) ?>
                                    </div>
                                    <div class="checkbox-card-meta">
                                        Module: <strong><?= e($permission['module']) ?></strong><br>
                                        Code: <strong><?= e($permission['code']) ?></strong>
                                    </div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-hint">Centang atau hilangkan centang untuk mengatur permission role ini.</div>
                </div>

                <div class="col-12">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?= old_or('is_active', $roleData['is_active']) ? 'checked' : '' ?>>
                        Role aktif
                    </label>
                </div>

                <div class="col-12">
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button type="submit" class="btn btn-pink">Update Role</button>
                        <a href="<?= e(base_url('roles')) ?>" class="btn-outline">Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
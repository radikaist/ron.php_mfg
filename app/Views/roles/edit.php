<?php
$selectedPermissionIds = old_or('permission_ids', $roleData['permission_ids']);
if (!is_array($selectedPermissionIds)) {
    $selectedPermissionIds = [];
}
$selectedPermissionIds = array_map('strval', $selectedPermissionIds);

$groupedPermissions = [];
foreach ($permissions as $permission) {
    $module = $permission['module'] ?: 'general';
    $groupedPermissions[$module][] = $permission;
}
ksort($groupedPermissions);
?>
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

                    <div data-permission-panel>
                        <div class="permission-tools">
                            <button type="button" class="permission-tool-btn" data-check-all>Check All</button>
                            <button type="button" class="permission-tool-btn" data-uncheck-all>Uncheck All</button>
                            <div class="permission-counter" data-permission-counter>0 permission dipilih</div>
                        </div>

                        <div class="permission-group-wrap">
                            <?php foreach ($groupedPermissions as $module => $modulePermissions): ?>
                                <div class="permission-group">
                                    <div class="permission-group-header" data-group-toggle>
                                        <div class="permission-group-title"><?= e($module) ?></div>
                                        <div class="permission-group-meta">
                                            <span><?= e((string) count($modulePermissions)) ?> item</span>
                                            <span>▼</span>
                                        </div>
                                    </div>

                                    <div class="permission-group-body">
                                        <?php foreach ($modulePermissions as $permission): ?>
                                            <?php
                                            $permissionId = (string) $permission['id'];
                                            $isChecked = in_array($permissionId, $selectedPermissionIds, true);
                                            ?>
                                            <label class="checkbox-item">
                                                <input
                                                    type="checkbox"
                                                    name="permission_ids[]"
                                                    value="<?= e($permissionId) ?>"
                                                    data-permission-checkbox
                                                    <?= $isChecked ? 'checked' : '' ?>
                                                >
                                                <span class="checkbox-item-text">
                                                    <span class="checkbox-item-title">
                                                        <?= e($permission['name']) ?>
                                                    </span>
                                                    <span class="checkbox-item-desc">
                                                        Code: <?= e($permission['code']) ?><?= $permission['description'] ? ' • ' . e($permission['description']) : '' ?>
                                                    </span>
                                                </span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-hint">
                        Centang atau hilangkan centang permission sesuai kebutuhan role.
                    </div>
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
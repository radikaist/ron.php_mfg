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

                    $groupedPermissions = [];
                    foreach ($permissions as $permission) {
                        $module = $permission['module'] ?? 'general';
                        $groupedPermissions[$module][] = $permission;
                    }
                    ?>

                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-bottom:12px; flex-wrap:wrap;">
                        <button type="button" id="checkAllPermissions" class="btn-outline" style="cursor:pointer;">Check All</button>
                        <button type="button" id="uncheckAllPermissions" class="btn-outline" style="cursor:pointer;">Uncheck All</button>
                    </div>

                    <div style="
                        border:1px solid var(--line);
                        border-radius:18px;
                        padding:18px;
                        background:var(--input-bg);
                    ">
                        <?php if (!empty($groupedPermissions)): ?>
                            <?php foreach ($groupedPermissions as $module => $modulePermissions): ?>
                                <div style="margin-bottom:22px;">
                                    <div style="
                                        font-weight:800;
                                        font-size:14px;
                                        color:var(--text);
                                        margin-bottom:10px;
                                        text-transform:uppercase;
                                        letter-spacing:.4px;
                                    ">
                                        <?= e($module) ?>
                                    </div>

                                    <div style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:10px;">
                                        <?php foreach ($modulePermissions as $permission): ?>
                                            <?php $permissionId = (string) $permission['id']; ?>
                                            <label style="
                                                display:flex;
                                                align-items:flex-start;
                                                gap:12px;
                                                padding:12px 14px;
                                                border:1px solid var(--line);
                                                border-radius:14px;
                                                background:rgba(255,255,255,.35);
                                                cursor:pointer;
                                            ">
                                                <input
                                                    type="checkbox"
                                                    name="permission_ids[]"
                                                    value="<?= e($permissionId) ?>"
                                                    class="permission-checkbox"
                                                    <?= in_array($permissionId, array_map('strval', $selectedPermissionIds), true) ? 'checked' : '' ?>
                                                    style="margin-top:3px;"
                                                >

                                                <div>
                                                    <div style="font-weight:700; color:var(--text);">
                                                        <?= e($permission['name']) ?>
                                                    </div>
                                                    <div class="muted" style="font-size:12px; margin-top:4px;">
                                                        <?= e($permission['code']) ?>
                                                    </div>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="muted">Belum ada permission tersedia.</div>
                        <?php endif; ?>
                    </div>

                    <div class="form-hint">
                        Centang atau hapus centang permission untuk role ini.
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

<script>
    (function () {
        const checkAllBtn = document.getElementById('checkAllPermissions');
        const uncheckAllBtn = document.getElementById('uncheckAllPermissions');
        const checkboxes = document.querySelectorAll('.permission-checkbox');

        if (checkAllBtn) {
            checkAllBtn.addEventListener('click', function () {
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = true;
                });
            });
        }

        if (uncheckAllBtn) {
            uncheckAllBtn.addEventListener('click', function () {
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = false;
                });
            });
        }
    })();
</script>
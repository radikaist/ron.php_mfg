<div class="card">
    <div class="card-header">Edit Permission</div>
    <div class="card-body">
        <form action="<?= e(base_url('permissions/update')) ?>" method="POST" data-confirm="Update permission ini?">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e((string) $permissionData['id']) ?>">

            <div class="grid">
                <div class="col-6">
                    <label class="form-label">Nama Permission</label>
                    <input type="text" name="name" value="<?= e(old_or('name', $permissionData['name'])) ?>" class="form-control">
                </div>

                <div class="col-6">
                    <label class="form-label">Code Permission</label>
                    <input type="text" name="code" value="<?= e(old_or('code', $permissionData['code'])) ?>" class="form-control">
                </div>

                <div class="col-6">
                    <label class="form-label">Module</label>
                    <input type="text" name="module" value="<?= e(old_or('module', $permissionData['module'])) ?>" class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-textarea"><?= e(old_or('description', $permissionData['description'])) ?></textarea>
                </div>

                <div class="col-12">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?= old_or('is_active', $permissionData['is_active']) ? 'checked' : '' ?>>
                        Permission aktif
                    </label>
                </div>

                <div class="col-12">
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button type="submit" class="btn btn-pink">Update Permission</button>
                        <a href="<?= e(base_url('permissions')) ?>" class="btn-outline">Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
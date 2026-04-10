<div class="card">
    <div class="card-header">Edit Permission</div>
    <div class="card-body">
        <form action="<?= e(base_url('permissions/update')) ?>" method="POST" data-confirm="Update permission ini?">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e((string) $permissionData['id']) ?>">

            <div class="grid">
                <div class="col-6">
                    <label class="form-label">Nama Permission</label>
                    <input type="text" name="name" value="<?= e(old_or('name', $permissionData['name'])) ?>" class="form-control" placeholder="Contoh: View Dashboard">
                    <div class="form-hint">
                        Gunakan nama yang mudah dibaca manusia.<br>
                        <strong>Contoh:</strong> View Dashboard, Create User, Edit Role, Approve Production Order.
                    </div>
                </div>

                <div class="col-6">
                    <label class="form-label">Code Permission</label>
                    <input type="text" name="code" value="<?= e(old_or('code', $permissionData['code'])) ?>" class="form-control" placeholder="Contoh: dashboard.view">
                    <div class="form-hint">
                        Gunakan format unik sistem, disarankan <strong>module.action</strong>.<br>
                        <strong>Contoh:</strong> dashboard.view, users.create, roles.edit, qc.inspect.
                    </div>
                </div>

                <div class="col-6">
                    <label class="form-label">Module</label>
                    <input type="text" name="module" value="<?= e(old_or('module', $permissionData['module'])) ?>" class="form-control" placeholder="Contoh: dashboard">
                    <div class="form-hint">
                        Isi nama modul tempat permission ini dikelompokkan.<br>
                        <strong>Contoh:</strong> dashboard, users, roles, permissions, inventory, production_orders, qc.
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-textarea" placeholder="Contoh: Mengizinkan user melihat dashboard"><?= e(old_or('description', $permissionData['description'])) ?></textarea>
                    <div class="form-hint">
                        Isi penjelasan singkat fungsi permission agar mudah dipahami administrator.<br>
                        <strong>Contoh:</strong> Mengizinkan user melihat dashboard utama sistem.
                    </div>
                </div>

                <div class="col-12">
                    <div class="card" style="border-radius:18px;">
                        <div class="card-body" style="padding:16px 18px;">
                            <div style="font-weight:800; margin-bottom:8px;">Panduan format</div>
                            <div class="muted" style="line-height:1.8;">
                                <strong>Nama Permission</strong> = nama yang dibaca user/admin<br>
                                <strong>Code Permission</strong> = kode unik sistem, format <strong>module.action</strong><br>
                                <strong>Module</strong> = grup/menu tempat permission dikategorikan<br>
                                <strong>Description</strong> = penjelasan singkat fungsi permission
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?= old_or('is_active', $permissionData['is_active']) ? 'checked' : '' ?>>
                        Permission aktif
                    </label>
                    <div class="form-hint">
                        Jika dicentang, permission tetap aktif dan bisa dipakai oleh role.
                    </div>
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
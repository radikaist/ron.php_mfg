<div class="card">
    <div class="card-header">Tambah User</div>
    <div class="card-body">
        <form action="<?= e(base_url('users/store')) ?>" method="POST" data-confirm="Simpan user baru?">
            <?= csrf_field() ?>

            <div class="grid">
                <div class="col-6">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" value="<?= e(old_or('name')) ?>" class="form-control">
                </div>

                <div class="col-6">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" value="<?= e(old_or('username')) ?>" class="form-control">
                </div>

                <div class="col-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="<?= e(old_or('email')) ?>" class="form-control">
                </div>

                <div class="col-6">
                    <label class="form-label">Role</label>
                    <select name="role_ids[]" multiple class="form-select" style="min-height:130px;">
                        <?php
                        $oldRoleIds = old_or('role_ids', []);
                        if (!is_array($oldRoleIds)) {
                            $oldRoleIds = [];
                        }
                        ?>
                        <?php foreach ($roles as $role): ?>
                            <option
                                value="<?= e((string) $role['id']) ?>"
                                <?= in_array((string) $role['id'], array_map('strval', $oldRoleIds), true) ? 'selected' : '' ?>
                            >
                                <?= e($role['name']) ?> (<?= e($role['code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-hint">Tekan Ctrl / Cmd untuk memilih lebih dari satu role.</div>
                </div>

                <div class="col-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="col-6">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="col-12">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?= old_or('is_active', 1) ? 'checked' : '' ?>>
                        User aktif
                    </label>
                </div>

                <div class="col-12">
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button type="submit" class="btn btn-primary">Simpan User</button>
                        <a href="<?= e(base_url('users')) ?>" class="btn-outline">Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
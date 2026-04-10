<div class="card">
    <div class="card-header">Edit User</div>
    <div class="card-body">
        <form action="<?= e(base_url('users/update')) ?>" method="POST" data-confirm="Update data user ini?">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e((string) $userData['id']) ?>">

            <div class="grid">
                <div class="col-6">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" value="<?= e(old_or('name', $userData['name'])) ?>" class="form-control">
                </div>

                <div class="col-6">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" value="<?= e(old_or('username', $userData['username'])) ?>" class="form-control">
                </div>

                <div class="col-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="<?= e(old_or('email', $userData['email'])) ?>" class="form-control">
                </div>

                <div class="col-6">
                    <label class="form-label">Role</label>
                    <?php
                    $selectedRoleIds = old_or('role_ids', $userData['role_ids']);
                    if (!is_array($selectedRoleIds)) {
                        $selectedRoleIds = [];
                    }
                    ?>
                    <select name="role_ids[]" multiple class="form-select" style="min-height:130px;">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= e((string) $role['id']) ?>" <?= in_array((string) $role['id'], array_map('strval', $selectedRoleIds), true) ? 'selected' : '' ?>>
                                <?= e($role['name']) ?> (<?= e($role['code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-6">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control">
                    <div class="form-hint">Kosongkan jika tidak ingin mengganti password.</div>
                </div>

                <div class="col-6">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="col-12">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?= old_or('is_active', $userData['is_active']) ? 'checked' : '' ?>>
                        User aktif
                    </label>
                </div>

                <div class="col-12">
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="<?= e(base_url('users')) ?>" class="btn-outline">Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
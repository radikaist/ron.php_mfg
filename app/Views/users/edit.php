<div class="card">
    <div class="card-header">Edit User</div>
    <div class="card-body">
        <form action="<?= e(base_url('users/update')) ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e((string) $userData['id']) ?>">

            <div class="grid">
                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Nama</label>
                    <input type="text" name="name" value="<?= e($userData['name']) ?>" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                </div>

                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Username</label>
                    <input type="text" name="username" value="<?= e($userData['username']) ?>" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                </div>

                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Email</label>
                    <input type="email" name="email" value="<?= e($userData['email']) ?>" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                </div>

                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Role</label>
                    <select name="role_ids[]" multiple style="width:100%; min-height:130px; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= e((string) $role['id']) ?>" <?= in_array((int) $role['id'], $userData['role_ids'], true) ? 'selected' : '' ?>>
                                <?= e($role['name']) ?> (<?= e($role['code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Password Baru</label>
                    <input type="password" name="password" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                    <div class="muted" style="margin-top:6px; font-size:12px;">Kosongkan jika tidak ingin mengganti password.</div>
                </div>

                <div class="col-6">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" style="width:100%; padding:12px 14px; border:1px solid #dbeafe; border-radius:14px;">
                </div>

                <div class="col-12">
                    <label style="display:inline-flex; align-items:center; gap:10px; font-weight:700;">
                        <input type="checkbox" name="is_active" value="1" <?= (int) $userData['is_active'] === 1 ? 'checked' : '' ?>>
                        User aktif
                    </label>
                </div>

                <div class="col-12">
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button type="submit" style="border:none; padding:12px 18px; border-radius:14px; background:linear-gradient(90deg,#3b82f6,#06b6d4); color:#fff; font-weight:700; cursor:pointer;">
                            Update User
                        </button>
                        <a href="<?= e(base_url('users')) ?>" style="display:inline-flex; align-items:center; padding:12px 18px; border-radius:14px; border:1px solid #dbeafe; color:inherit;">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="quick-actions">
    <a class="quick-action" href="<?= e(base_url('roles/create')) ?>">
        <div class="quick-action-icon qa-green">➕</div>
        <div>
            <div class="quick-action-title">Tambah Role</div>
            <div class="quick-action-desc">Buat role baru dan assign permission.</div>
        </div>
    </a>
</div>

<div class="card">
    <div class="card-header">Daftar Role</div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>Nama</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th width="120">Users</th>
                    <th width="140">Permissions</th>
                    <th width="120">Status</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($roles)): ?>
                    <?php foreach ($roles as $row): ?>
                        <tr>
                            <td><?= e((string) $row['id']) ?></td>
                            <td><?= e($row['name']) ?></td>
                            <td><span class="badge badge-sky"><?= e($row['code']) ?></span></td>
                            <td><?= e($row['description'] ?: '-') ?></td>
                            <td><?= e((string) $row['total_users']) ?></td>
                            <td><?= e((string) $row['total_permissions']) ?></td>
                            <td>
                                <?php if ((int) $row['is_active'] === 1): ?>
                                    <span class="badge badge-green">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-orange">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= e(base_url('roles/edit?id=' . $row['id'])) ?>" class="badge badge-pink">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="muted">Belum ada data role.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
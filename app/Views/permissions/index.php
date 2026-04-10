<div class="card">
    <div class="card-header">Daftar Permission</div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>Nama</th>
                    <th>Code</th>
                    <th>Module</th>
                    <th>Description</th>
                    <th width="120">Roles</th>
                    <th width="120">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($permissions)): ?>
                    <?php foreach ($permissions as $row): ?>
                        <tr>
                            <td><?= e((string) $row['id']) ?></td>
                            <td><?= e($row['name']) ?></td>
                            <td><span class="badge badge-pink"><?= e($row['code']) ?></span></td>
                            <td><span class="badge badge-sky"><?= e($row['module']) ?></span></td>
                            <td><?= e($row['description'] ?: '-') ?></td>
                            <td><?= e((string) $row['total_roles']) ?></td>
                            <td>
                                <?php if ((int) $row['is_active'] === 1): ?>
                                    <span class="badge badge-green">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-orange">Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="muted">Belum ada data permission.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
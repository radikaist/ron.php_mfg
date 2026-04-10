<div class="quick-actions">
    <a class="quick-action" href="<?= e(base_url('permissions/create')) ?>">
        <div class="quick-action-icon qa-orange">➕</div>
        <div>
            <div class="quick-action-title">Tambah Permission</div>
            <div class="quick-action-desc">Buat permission baru untuk modul tertentu.</div>
        </div>
    </a>
</div>

<div class="card">
    <div class="card-header">Daftar Permission</div>
    <div class="card-body">
        <div class="toolbar">
            <div class="search-box">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Cari permission, code, module..."
                    data-table-filter="permissionsTable"
                >
            </div>
        </div>

        <table class="table" id="permissionsTable">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>Nama</th>
                    <th>Code</th>
                    <th>Module</th>
                    <th>Description</th>
                    <th width="120">Roles</th>
                    <th width="120">Status</th>
                    <th width="120">Action</th>
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
                            <td>
                                <a href="<?= e(base_url('permissions/edit?id=' . $row['id'])) ?>" class="badge badge-sky">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="muted">Belum ada data permission.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$totalRoles = count($roles ?? []);
$activeRoles = 0;
$totalMappedPermissions = 0;

foreach (($roles ?? []) as $row) {
    if ((int) $row['is_active'] === 1) {
        $activeRoles++;
    }
    $totalMappedPermissions += (int) $row['total_permissions'];
}
?>

<div class="grid">
    <div class="col-4">
        <div class="small-box bg-pink">
            <div class="label">Total Roles</div>
            <div class="value"><?= e((string) $totalRoles) ?></div>
            <div class="desc">Jumlah seluruh role yang tersedia.</div>
            <div class="mini">RBAC Role Layer</div>
        </div>
    </div>
    <div class="col-4">
        <div class="small-box bg-green">
            <div class="label">Active Roles</div>
            <div class="value"><?= e((string) $activeRoles) ?></div>
            <div class="desc">Role aktif yang dapat digunakan dalam assignment.</div>
            <div class="mini">Role Active</div>
        </div>
    </div>
    <div class="col-4">
        <div class="small-box bg-orange">
            <div class="label">Mapped Permissions</div>
            <div class="value"><?= e((string) $totalMappedPermissions) ?></div>
            <div class="desc">Total permission yang sudah dipetakan ke role.</div>
            <div class="mini">Permission Matrix</div>
        </div>
    </div>
</div>

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
        <div class="toolbar">
            <div class="search-box">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Cari role, code, description..."
                    data-table-filter="rolesTable"
                >
            </div>
            <div class="muted" style="font-size:13px;">
                Klik judul kolom untuk sorting.
            </div>
        </div>

        <table class="table sortable-table" id="rolesTable">
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
                    <tr class="empty-row">
                        <td colspan="8" class="muted">Belum ada data role.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
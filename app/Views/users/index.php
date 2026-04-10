<?php
$totalUsers = count($users ?? []);
$activeUsers = 0;
$inactiveUsers = 0;

foreach (($users ?? []) as $row) {
    if ((int) $row['is_active'] === 1) {
        $activeUsers++;
    } else {
        $inactiveUsers++;
    }
}
?>

<div class="grid">
    <div class="col-4">
        <div class="small-box bg-sky">
            <div class="label">Total Users</div>
            <div class="value"><?= e((string) $totalUsers) ?></div>
            <div class="desc">Jumlah seluruh user yang tersedia di sistem.</div>
            <div class="mini">Master User</div>
        </div>
    </div>
    <div class="col-4">
        <div class="small-box bg-green">
            <div class="label">Active Users</div>
            <div class="value"><?= e((string) $activeUsers) ?></div>
            <div class="desc">User aktif yang dapat menggunakan sistem.</div>
            <div class="mini">Status Active</div>
        </div>
    </div>
    <div class="col-4">
        <div class="small-box bg-orange">
            <div class="label">Inactive Users</div>
            <div class="value"><?= e((string) $inactiveUsers) ?></div>
            <div class="desc">User nonaktif yang tidak dapat login.</div>
            <div class="mini">Status Inactive</div>
        </div>
    </div>
</div>

<div class="quick-actions">
    <a class="quick-action" href="<?= e(base_url('users/create')) ?>">
        <div class="quick-action-icon qa-blue">➕</div>
        <div>
            <div class="quick-action-title">Tambah User</div>
            <div class="quick-action-desc">Buat user baru dan assign role.</div>
        </div>
    </a>
</div>

<div class="card">
    <div class="card-header">Daftar User</div>
    <div class="card-body">
        <div class="toolbar">
            <div class="search-box">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Cari user, username, email, role..."
                    data-table-filter="usersTable"
                >
            </div>
            <div class="muted" style="font-size:13px;">
                Klik judul kolom untuk sorting.
            </div>
        </div>

        <table class="table sortable-table" id="usersTable">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="120">Status</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $row): ?>
                        <tr>
                            <td><?= e((string) $row['id']) ?></td>
                            <td><?= e($row['name']) ?></td>
                            <td><?= e($row['username']) ?></td>
                            <td><?= e($row['email'] ?: '-') ?></td>
                            <td><?= e($row['role_names'] ?: '-') ?></td>
                            <td>
                                <?php if ((int) $row['is_active'] === 1): ?>
                                    <span class="badge badge-green">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-orange">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= e(base_url('users/edit?id=' . $row['id'])) ?>" class="badge badge-sky">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="empty-row">
                        <td colspan="7" class="muted">Belum ada data user.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
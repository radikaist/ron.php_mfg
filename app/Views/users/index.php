<?php
$totalUsers = $pagination['total'] ?? count($users ?? []);
$activeUsers = 0;
$inactiveUsers = 0;

foreach (($users ?? []) as $row) {
    if ((int) $row['is_active'] === 1) {
        $activeUsers++;
    } else {
        $inactiveUsers++;
    }
}

$currentPage = (int) ($pagination['page'] ?? 1);
$perPage = (int) ($pagination['per_page'] ?? 5);
$totalPages = (int) ($pagination['total_pages'] ?? 1);
$search = (string) ($pagination['search'] ?? '');

function users_page_url(int $page, int $perPage, string $search): string {
    return base_url('users?page=' . $page . '&per_page=' . $perPage . '&search=' . urlencode($search)) . '#users-table-section';
}
?>

<div class="grid">
    <div class="col-4">
        <div class="small-box bg-sky">
            <div class="label">Total Users</div>
            <div class="value"><?= e((string) $totalUsers) ?></div>
            <div class="desc">Jumlah hasil user sesuai filter pencarian.</div>
            <div class="mini">Filtered Result</div>
        </div>
    </div>
    <div class="col-4">
        <div class="small-box bg-green">
            <div class="label">Active Users</div>
            <div class="value"><?= e((string) $activeUsers) ?></div>
            <div class="desc">User aktif pada halaman saat ini.</div>
            <div class="mini">Current Page</div>
        </div>
    </div>
    <div class="col-4">
        <div class="small-box bg-orange">
            <div class="label">Inactive Users</div>
            <div class="value"><?= e((string) $inactiveUsers) ?></div>
            <div class="desc">User nonaktif pada halaman saat ini.</div>
            <div class="mini">Current Page</div>
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

<div class="card" id="users-table-section">
    <div class="card-header">Daftar User</div>
    <div class="card-body">
        <div class="toolbar">
            <form method="GET" action="<?= e(base_url('users')) ?>#users-table-section" style="display:flex; gap:10px; flex-wrap:wrap; width:100%;">
                <div class="search-box">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari user, username, email, role..."
                        name="search"
                        value="<?= e($search) ?>"
                    >
                </div>

                <select name="per_page" class="form-select" style="width:auto;">
                    <?php foreach ([5, 10, 20, 50, 100] as $size): ?>
                        <option value="<?= e((string) $size) ?>" <?= $perPage === $size ? 'selected' : '' ?>>
                            <?= e((string) $size) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="hidden" name="page" value="1">
                <button type="submit" class="btn btn-primary">Cari</button>

                <?php if ($search !== ''): ?>
                    <a href="<?= e(base_url('users')) ?>#users-table-section" class="btn-outline">Reset</a>
                <?php endif; ?>
            </form>
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
                    <th class="no-sort" width="120">Action</th>
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
                        <td colspan="7" class="muted">Tidak ada data yang cocok.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination-wrap">
            <div class="muted">
                Halaman <?= e((string) $currentPage) ?> dari <?= e((string) $totalPages) ?> • Total data <?= e((string) $totalUsers) ?>
            </div>

            <div class="pagination-links">
                <a href="<?= e(users_page_url(max(1, $currentPage - 1), $perPage, $search)) ?>" class="page-link <?= $currentPage <= 1 ? 'disabled' : '' ?>">Prev</a>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= e(users_page_url($i, $perPage, $search)) ?>" class="page-link <?= $i === $currentPage ? 'active' : '' ?>">
                        <?= e((string) $i) ?>
                    </a>
                <?php endfor; ?>

                <a href="<?= e(users_page_url(min($totalPages, $currentPage + 1), $perPage, $search)) ?>" class="page-link <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">Next</a>
            </div>
        </div>
    </div>
</div>
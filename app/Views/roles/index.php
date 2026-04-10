<?php
$totalRoles = $pagination['total'] ?? count($roles ?? []);
$activeRoles = 0;
$totalMappedPermissions = 0;

foreach (($roles ?? []) as $row) {
    if ((int) $row['is_active'] === 1) {
        $activeRoles++;
    }
    $totalMappedPermissions += (int) $row['total_permissions'];
}

$currentPage = (int) ($pagination['page'] ?? 1);
$perPage = (int) ($pagination['per_page'] ?? 5);
$totalPages = (int) ($pagination['total_pages'] ?? 1);
$search = (string) ($pagination['search'] ?? '');

function roles_page_url(int $page, int $perPage, string $search): string {
    return base_url('roles?page=' . $page . '&per_page=' . $perPage . '&search=' . urlencode($search));
}
?>

<div class="grid">
    <div class="col-4">
        <div class="small-box bg-pink">
            <div class="label">Total Roles</div>
            <div class="value"><?= e((string) $totalRoles) ?></div>
            <div class="desc">Jumlah hasil role sesuai filter pencarian.</div>
            <div class="mini">Filtered Result</div>
        </div>
    </div>
    <div class="col-4">
        <div class="small-box bg-green">
            <div class="label">Active Roles</div>
            <div class="value"><?= e((string) $activeRoles) ?></div>
            <div class="desc">Role aktif pada halaman saat ini.</div>
            <div class="mini">Current Page</div>
        </div>
    </div>
    <div class="col-4">
        <div class="small-box bg-orange">
            <div class="label">Mapped Permissions</div>
            <div class="value"><?= e((string) $totalMappedPermissions) ?></div>
            <div class="desc">Jumlah relasi permission pada halaman saat ini.</div>
            <div class="mini">Current Page</div>
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
            <form method="GET" action="<?= e(base_url('roles')) ?>" style="display:flex; gap:10px; flex-wrap:wrap; width:100%;">
                <div class="search-box">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari role, code, description..."
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
                    <a href="<?= e(base_url('roles')) ?>" class="btn-outline">Reset</a>
                <?php endif; ?>
            </form>
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
                    <th class="no-sort" width="120">Action</th>
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
                        <td colspan="8" class="muted">Tidak ada data yang cocok.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination-wrap">
            <div class="muted">
                Halaman <?= e((string) $currentPage) ?> dari <?= e((string) $totalPages) ?> • Total data <?= e((string) $totalRoles) ?>
            </div>

            <div class="pagination-links">
                <a href="<?= e(roles_page_url(max(1, $currentPage - 1), $perPage, $search)) ?>" class="page-link <?= $currentPage <= 1 ? 'disabled' : '' ?>">Prev</a>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= e(roles_page_url($i, $perPage, $search)) ?>" class="page-link <?= $i === $currentPage ? 'active' : '' ?>">
                        <?= e((string) $i) ?>
                    </a>
                <?php endfor; ?>

                <a href="<?= e(roles_page_url(min($totalPages, $currentPage + 1), $perPage, $search)) ?>" class="page-link <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">Next</a>
            </div>
        </div>
    </div>
</div>
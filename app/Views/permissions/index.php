<?php
$totalPermissions = $pagination['total'] ?? count($permissions ?? []);
$activePermissions = 0;
$totalRoleRelations = 0;

foreach (($permissions ?? []) as $row) {
    if ((int) $row['is_active'] === 1) {
        $activePermissions++;
    }
    $totalRoleRelations += (int) $row['total_roles'];
}

$currentPage = (int) ($pagination['page'] ?? 1);
$perPage = (int) ($pagination['per_page'] ?? 5);
$totalPages = (int) ($pagination['total_pages'] ?? 1);
$search = (string) ($pagination['search'] ?? '');

function permissions_page_url(int $page, int $perPage, string $search): string {
    return base_url('permissions?page=' . $page . '&per_page=' . $perPage . '&search=' . urlencode($search));
}
?>

<div class="grid">
    <div class="col-4">
        <div class="small-box bg-orange">
            <div class="label">Total Permissions</div>
            <div class="value"><?= e((string) $totalPermissions) ?></div>
            <div class="desc">Jumlah hasil permission sesuai filter pencarian.</div>
            <div class="mini">Filtered Result</div>
        </div>
    </div>
    <div class="col-4">
        <div class="small-box bg-green">
            <div class="label">Active Permissions</div>
            <div class="value"><?= e((string) $activePermissions) ?></div>
            <div class="desc">Permission aktif pada halaman saat ini.</div>
            <div class="mini">Current Page</div>
        </div>
    </div>
    <div class="col-4">
        <div class="small-box bg-sky">
            <div class="label">Role Relations</div>
            <div class="value"><?= e((string) $totalRoleRelations) ?></div>
            <div class="desc">Relasi permission-role pada halaman saat ini.</div>
            <div class="mini">Current Page</div>
        </div>
    </div>
</div>

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
            <form method="GET" action="<?= e(base_url('permissions')) ?>" style="display:flex; gap:10px; flex-wrap:wrap; width:100%;">
                <div class="search-box">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari permission, code, module..."
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
                    <a href="<?= e(base_url('permissions')) ?>" class="btn-outline">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <table class="table sortable-table" id="permissionsTable">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>Nama</th>
                    <th>Code</th>
                    <th>Module</th>
                    <th>Description</th>
                    <th width="120">Roles</th>
                    <th width="120">Status</th>
                    <th class="no-sort" width="120">Action</th>
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
                    <tr class="empty-row">
                        <td colspan="8" class="muted">Tidak ada data yang cocok.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination-wrap">
            <div class="muted">
                Halaman <?= e((string) $currentPage) ?> dari <?= e((string) $totalPages) ?> • Total data <?= e((string) $totalPermissions) ?>
            </div>

            <div class="pagination-links">
                <a href="<?= e(permissions_page_url(max(1, $currentPage - 1), $perPage, $search)) ?>" class="page-link <?= $currentPage <= 1 ? 'disabled' : '' ?>">Prev</a>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= e(permissions_page_url($i, $perPage, $search)) ?>" class="page-link <?= $i === $currentPage ? 'active' : '' ?>">
                        <?= e((string) $i) ?>
                    </a>
                <?php endfor; ?>

                <a href="<?= e(permissions_page_url(min($totalPages, $currentPage + 1), $perPage, $search)) ?>" class="page-link <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">Next</a>
            </div>
        </div>
    </div>
</div>
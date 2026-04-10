<?php
$routePagination = $routesWithoutPermissionPagination;
$permissionRoutePagination = $permissionsWithoutRoutePagination;
$controllerCheckPagination = $controllerActionsWithoutPermissionCheckPagination;
$mismatchPagination = $permissionMismatchInControllersPagination;
$controllerMissingPagination = $controllerPermissionNotRegisteredPagination;
$unusedPermissionPagination = $registeredButUnusedPermissionsPagination;
$roleNoPermissionPagination = $rolesWithoutPermissionPagination;
$userNoRolePagination = $usersWithoutRolePagination;
$inactiveRolePagination = $inactiveRolesUsedPagination;
$inactivePermissionPagination = $inactivePermissionsUsedPagination;
$filters = $filters ?? [];

function rbac_health_url(array $overrides, string $anchor): string {
    $params = array_merge($_GET, $overrides);
    return base_url('rbac/health?' . http_build_query($params)) . $anchor;
}

function render_rbac_filter_form(string $searchKey, string $anchor, string $value, string $pageKey): void {
    ?>
    <form method="GET" action="<?= e(base_url('rbac/health')) . $anchor ?>" style="display:flex; gap:10px; flex-wrap:wrap; width:100%; margin-bottom:16px;">
        <?php foreach ($_GET as $key => $val): ?>
            <?php if ($key !== $searchKey && $key !== $pageKey): ?>
                <input type="hidden" name="<?= e((string) $key) ?>" value="<?= e((string) $val) ?>">
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="search-box">
            <input
                type="text"
                class="form-control"
                name="<?= e($searchKey) ?>"
                value="<?= e($value) ?>"
                placeholder="Cari pada section ini..."
            >
        </div>

        <input type="hidden" name="<?= e($pageKey) ?>" value="1">
        <button type="submit" class="btn btn-primary">Cari</button>

        <?php if ($value !== ''): ?>
            <a href="<?= e(rbac_health_url([$searchKey => '', $pageKey => 1], $anchor)) ?>" class="btn-outline">Reset</a>
        <?php endif; ?>
    </form>
    <?php
}

function render_rbac_pagination(array $pagination, string $pageKey, string $perPageKey, string $anchor): void {
    $currentPage = (int) ($pagination['page'] ?? 1);
    $perPage = (int) ($pagination['per_page'] ?? 5);
    $totalPages = (int) ($pagination['total_pages'] ?? 1);
    $total = (int) ($pagination['total'] ?? 0);
    ?>
    <div class="pagination-wrap">
        <form method="GET" action="<?= e(base_url('rbac/health')) . $anchor ?>" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <?php foreach ($_GET as $key => $value): ?>
                <?php if ($key !== $pageKey && $key !== $perPageKey): ?>
                    <input type="hidden" name="<?= e((string) $key) ?>" value="<?= e((string) $value) ?>">
                <?php endif; ?>
            <?php endforeach; ?>

            <label class="muted">Tampilkan</label>
            <select name="<?= e($perPageKey) ?>" class="form-select" style="width:auto;" onchange="this.form.submit()">
                <?php foreach ([5, 10, 20, 50, 100] as $size): ?>
                    <option value="<?= e((string) $size) ?>" <?= $perPage === $size ? 'selected' : '' ?>>
                        <?= e((string) $size) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="<?= e($pageKey) ?>" value="1">
        </form>

        <div class="muted">
            Halaman <?= e((string) $currentPage) ?> dari <?= e((string) $totalPages) ?> • Total data <?= e((string) $total) ?>
        </div>

        <div class="pagination-links">
            <a href="<?= e(rbac_health_url([$pageKey => max(1, $currentPage - 1), $perPageKey => $perPage], $anchor)) ?>" class="page-link <?= $currentPage <= 1 ? 'disabled' : '' ?>">Prev</a>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="<?= e(rbac_health_url([$pageKey => $i, $perPageKey => $perPage], $anchor)) ?>" class="page-link <?= $i === $currentPage ? 'active' : '' ?>">
                    <?= e((string) $i) ?>
                </a>
            <?php endfor; ?>

            <a href="<?= e(rbac_health_url([$pageKey => min($totalPages, $currentPage + 1), $perPageKey => $perPage], $anchor)) ?>" class="page-link <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">Next</a>
        </div>
    </div>
    <?php
}
?>

<div class="toolbar" style="margin-bottom:22px;">
    <div class="muted">
        Halaman ini membantu audit dan otomatisasi sebagian masalah RBAC.
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <form action="<?= e(base_url('rbac/health/auto-generate-routes')) ?>" method="POST" data-confirm="Generate semua permission yang hilang dari route audit?">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-success">Auto Generate from Routes</button>
        </form>

        <form action="<?= e(base_url('rbac/health/auto-generate-controller-permissions')) ?>" method="POST" data-confirm="Generate semua permission yang dipakai di controller tapi belum terdaftar?">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-pink">Auto Generate from Controller Usage</button>
        </form>

        <a href="<?= e(base_url('rbac/health/export-csv')) ?>" class="btn-outline">Export CSV</a>
    </div>
</div>

<div class="grid">
    <div class="col-3">
        <div class="small-box bg-sky">
            <div class="label">Total Issues</div>
            <div class="value"><?= e((string) ($summary['total_issues'] ?? 0)) ?></div>
            <div class="desc">Jumlah total temuan inkonsistensi RBAC.</div>
            <div class="mini">Health Summary</div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-orange">
            <div class="label">Route Without Permission</div>
            <div class="value"><?= e((string) ($summary['routes_without_permission'] ?? 0)) ?></div>
            <div class="desc">Route yang belum punya permission.</div>
            <div class="mini">Route Audit</div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-pink">
            <div class="label">Mismatch in Controller</div>
            <div class="value"><?= e((string) ($summary['permission_mismatch_in_controllers'] ?? 0)) ?></div>
            <div class="desc">Expected permission berbeda dengan actual permission di controller.</div>
            <div class="mini">Mismatch Audit</div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-green">
            <div class="label">Controller Permission Missing</div>
            <div class="value"><?= e((string) ($summary['controller_permission_not_registered'] ?? 0)) ?></div>
            <div class="desc">Permission dipakai di controller tapi belum ada di database.</div>
            <div class="mini">Registration Audit</div>
        </div>
    </div>
</div>

<div class="card" id="rbac-routes-section">
    <div class="card-header">1. Route Tanpa Permission</div>
    <div class="card-body">
        <?php render_rbac_filter_form('route_search', '#rbac-routes-section', (string) ($filters['route_search'] ?? ''), 'route_page'); ?>
        <?php if (!empty($routePagination['data'])): ?>
            <table class="table sortable-table">
                <thead>
                    <tr>
                        <th width="100">Method</th>
                        <th>URI</th>
                        <th width="140">Module</th>
                        <th>Suggested Code</th>
                        <th>Suggested Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($routePagination['data'] as $row): ?>
                        <tr>
                            <td><span class="badge badge-sky"><?= e($row['method']) ?></span></td>
                            <td><?= e($row['uri']) ?></td>
                            <td><span class="badge badge-orange"><?= e($row['module']) ?></span></td>
                            <td><?= e($row['suggested_code']) ?></td>
                            <td><?= e($row['suggested_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php render_rbac_pagination($routePagination, 'route_page', 'route_per_page', '#rbac-routes-section'); ?>
        <?php else: ?>
            <div class="muted">Tidak ada data yang cocok.</div>
        <?php endif; ?>
    </div>
</div>

<div style="height:22px;"></div>

<div class="card" id="rbac-controller-check-section">
    <div class="card-header">2. Controller Action Tanpa Permission Check</div>
    <div class="card-body">
        <?php render_rbac_filter_form('controller_check_search', '#rbac-controller-check-section', (string) ($filters['controller_check_search'] ?? ''), 'controller_check_page'); ?>
        <?php if (!empty($controllerCheckPagination['data'])): ?>
            <table class="table sortable-table">
                <thead>
                    <tr>
                        <th width="100">Method</th>
                        <th>URI</th>
                        <th>Controller</th>
                        <th>Action</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($controllerCheckPagination['data'] as $row): ?>
                        <tr>
                            <td><span class="badge badge-sky"><?= e($row['method']) ?></span></td>
                            <td><?= e($row['uri']) ?></td>
                            <td><?= e($row['controller']) ?></td>
                            <td><?= e($row['action']) ?></td>
                            <td><?= e($row['note']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php render_rbac_pagination($controllerCheckPagination, 'controller_check_page', 'controller_check_per_page', '#rbac-controller-check-section'); ?>
        <?php else: ?>
            <div class="muted">Tidak ada data yang cocok.</div>
        <?php endif; ?>
    </div>
</div>

<div style="height:22px;"></div>

<div class="card" id="rbac-mismatch-section">
    <div class="card-header">3. Expected Permission vs Actual Permission di Controller</div>
    <div class="card-body">
        <?php render_rbac_filter_form('mismatch_search', '#rbac-mismatch-section', (string) ($filters['mismatch_search'] ?? ''), 'mismatch_page'); ?>
        <?php if (!empty($mismatchPagination['data'])): ?>
            <table class="table sortable-table">
                <thead>
                    <tr>
                        <th width="100">Method</th>
                        <th>URI</th>
                        <th>Controller</th>
                        <th>Action</th>
                        <th>Expected</th>
                        <th>Actual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mismatchPagination['data'] as $row): ?>
                        <tr>
                            <td><?= e($row['method']) ?></td>
                            <td><?= e($row['uri']) ?></td>
                            <td><?= e($row['controller']) ?></td>
                            <td><?= e($row['action']) ?></td>
                            <td><span class="badge badge-orange"><?= e($row['expected_permission']) ?></span></td>
                            <td><span class="badge badge-pink"><?= e($row['actual_permissions']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php render_rbac_pagination($mismatchPagination, 'mismatch_page', 'mismatch_per_page', '#rbac-mismatch-section'); ?>
        <?php else: ?>
            <div class="muted">Tidak ada data yang cocok.</div>
        <?php endif; ?>
    </div>
</div>

<div style="height:22px;"></div>

<div class="card" id="rbac-controller-missing-section">
    <div class="card-header">4. Permission Dipakai di Controller Tapi Belum Terdaftar</div>
    <div class="card-body">
        <?php render_rbac_filter_form('controller_missing_search', '#rbac-controller-missing-section', (string) ($filters['controller_missing_search'] ?? ''), 'controller_missing_page'); ?>
        <?php if (!empty($controllerMissingPagination['data'])): ?>
            <table class="table sortable-table">
                <thead>
                    <tr>
                        <th width="100">Method</th>
                        <th>URI</th>
                        <th>Controller</th>
                        <th>Action</th>
                        <th>Permission</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($controllerMissingPagination['data'] as $row): ?>
                        <tr>
                            <td><?= e($row['method']) ?></td>
                            <td><?= e($row['uri']) ?></td>
                            <td><?= e($row['controller']) ?></td>
                            <td><?= e($row['action']) ?></td>
                            <td><span class="badge badge-pink"><?= e($row['permission']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php render_rbac_pagination($controllerMissingPagination, 'controller_missing_page', 'controller_missing_per_page', '#rbac-controller-missing-section'); ?>
        <?php else: ?>
            <div class="muted">Tidak ada data yang cocok.</div>
        <?php endif; ?>
    </div>
</div>

<div style="height:22px;"></div>

<div class="card" id="rbac-unused-permission-section">
    <div class="card-header">5. Permission Terdaftar Tapi Belum Terdeteksi Dipakai di Controller</div>
    <div class="card-body">
        <?php render_rbac_filter_form('unused_permission_search', '#rbac-unused-permission-section', (string) ($filters['unused_permission_search'] ?? ''), 'unused_permission_page'); ?>
        <?php if (!empty($unusedPermissionPagination['data'])): ?>
            <table class="table sortable-table">
                <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th>Nama</th>
                        <th>Code</th>
                        <th>Module</th>
                        <th width="120">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($unusedPermissionPagination['data'] as $row): ?>
                        <tr>
                            <td><?= e((string) $row['id']) ?></td>
                            <td><?= e($row['name']) ?></td>
                            <td><?= e($row['code']) ?></td>
                            <td><?= e($row['module']) ?></td>
                            <td>
                                <?php if ((int) $row['is_active'] === 1): ?>
                                    <span class="badge badge-green">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-orange">Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php render_rbac_pagination($unusedPermissionPagination, 'unused_permission_page', 'unused_permission_per_page', '#rbac-unused-permission-section'); ?>
        <?php else: ?>
            <div class="muted">Tidak ada data yang cocok.</div>
        <?php endif; ?>
    </div>
</div>

<div style="height:22px;"></div>

<div class="card" id="rbac-permission-route-section">
    <div class="card-header">6. Permission Tanpa Route</div>
    <div class="card-body">
        <?php render_rbac_filter_form('permission_route_search', '#rbac-permission-route-section', (string) ($filters['permission_route_search'] ?? ''), 'permission_route_page'); ?>
        <?php if (!empty($permissionRoutePagination['data'])): ?>
            <table class="table sortable-table">
                <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th>Nama</th>
                        <th>Code</th>
                        <th>Module</th>
                        <th width="120">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($permissionRoutePagination['data'] as $row): ?>
                        <tr>
                            <td><?= e((string) $row['id']) ?></td>
                            <td><?= e($row['name']) ?></td>
                            <td><?= e($row['code']) ?></td>
                            <td><?= e($row['module']) ?></td>
                            <td>
                                <?php if ((int) $row['is_active'] === 1): ?>
                                    <span class="badge badge-green">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-orange">Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php render_rbac_pagination($permissionRoutePagination, 'permission_route_page', 'permission_route_per_page', '#rbac-permission-route-section'); ?>
        <?php else: ?>
            <div class="muted">Tidak ada data yang cocok.</div>
        <?php endif; ?>
    </div>
</div>

<div style="height:22px;"></div>

<div class="grid">
    <div class="col-6">
        <div class="card" id="rbac-role-no-permission-section">
            <div class="card-header">7. Role Tanpa Permission</div>
            <div class="card-body">
                <?php render_rbac_filter_form('role_no_permission_search', '#rbac-role-no-permission-section', (string) ($filters['role_no_permission_search'] ?? ''), 'role_no_permission_page'); ?>
                <?php if (!empty($roleNoPermissionPagination['data'])): ?>
                    <table class="table sortable-table">
                        <thead>
                            <tr>
                                <th width="80">ID</th>
                                <th>Nama</th>
                                <th>Code</th>
                                <th width="120">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roleNoPermissionPagination['data'] as $row): ?>
                                <tr>
                                    <td><?= e((string) $row['id']) ?></td>
                                    <td><?= e($row['name']) ?></td>
                                    <td><?= e($row['code']) ?></td>
                                    <td>
                                        <?php if ((int) $row['is_active'] === 1): ?>
                                            <span class="badge badge-green">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-orange">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php render_rbac_pagination($roleNoPermissionPagination, 'role_no_permission_page', 'role_no_permission_per_page', '#rbac-role-no-permission-section'); ?>
                <?php else: ?>
                    <div class="muted">Tidak ada data yang cocok.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card" id="rbac-user-no-role-section">
            <div class="card-header">8. User Tanpa Role</div>
            <div class="card-body">
                <?php render_rbac_filter_form('user_no_role_search', '#rbac-user-no-role-section', (string) ($filters['user_no_role_search'] ?? ''), 'user_no_role_page'); ?>
                <?php if (!empty($userNoRolePagination['data'])): ?>
                    <table class="table sortable-table">
                        <thead>
                            <tr>
                                <th width="80">ID</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th width="120">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userNoRolePagination['data'] as $row): ?>
                                <tr>
                                    <td><?= e((string) $row['id']) ?></td>
                                    <td><?= e($row['name']) ?></td>
                                    <td><?= e($row['username']) ?></td>
                                    <td>
                                        <?php if ((int) $row['is_active'] === 1): ?>
                                            <span class="badge badge-green">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-orange">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php render_rbac_pagination($userNoRolePagination, 'user_no_role_page', 'user_no_role_per_page', '#rbac-user-no-role-section'); ?>
                <?php else: ?>
                    <div class="muted">Tidak ada data yang cocok.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div style="height:22px;"></div>

<div class="grid">
    <div class="col-6">
        <div class="card" id="rbac-inactive-role-section">
            <div class="card-header">9. Role Nonaktif Tapi Masih Dipakai</div>
            <div class="card-body">
                <?php render_rbac_filter_form('inactive_role_search', '#rbac-inactive-role-section', (string) ($filters['inactive_role_search'] ?? ''), 'inactive_role_page'); ?>
                <?php if (!empty($inactiveRolePagination['data'])): ?>
                    <table class="table sortable-table">
                        <thead>
                            <tr>
                                <th width="80">ID</th>
                                <th>Nama</th>
                                <th>Code</th>
                                <th width="140">Total Users</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inactiveRolePagination['data'] as $row): ?>
                                <tr>
                                    <td><?= e((string) $row['id']) ?></td>
                                    <td><?= e($row['name']) ?></td>
                                    <td><?= e($row['code']) ?></td>
                                    <td><?= e((string) $row['total_users']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php render_rbac_pagination($inactiveRolePagination, 'inactive_role_page', 'inactive_role_per_page', '#rbac-inactive-role-section'); ?>
                <?php else: ?>
                    <div class="muted">Tidak ada data yang cocok.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card" id="rbac-inactive-permission-section">
            <div class="card-header">10. Permission Nonaktif Tapi Masih Dipakai</div>
            <div class="card-body">
                <?php render_rbac_filter_form('inactive_permission_search', '#rbac-inactive-permission-section', (string) ($filters['inactive_permission_search'] ?? ''), 'inactive_permission_page'); ?>
                <?php if (!empty($inactivePermissionPagination['data'])): ?>
                    <table class="table sortable-table">
                        <thead>
                            <tr>
                                <th width="80">ID</th>
                                <th>Nama</th>
                                <th>Code</th>
                                <th width="140">Total Roles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inactivePermissionPagination['data'] as $row): ?>
                                <tr>
                                    <td><?= e((string) $row['id']) ?></td>
                                    <td><?= e($row['name']) ?></td>
                                    <td><?= e($row['code']) ?></td>
                                    <td><?= e((string) $row['total_roles']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php render_rbac_pagination($inactivePermissionPagination, 'inactive_permission_page', 'inactive_permission_per_page', '#rbac-inactive-permission-section'); ?>
                <?php else: ?>
                    <div class="muted">Tidak ada data yang cocok.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
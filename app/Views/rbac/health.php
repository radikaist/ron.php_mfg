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

<div class="grid">
    <div class="col-4">
        <div class="small-box bg-green">
            <div class="label">Roles Without Permission</div>
            <div class="value"><?= e((string) ($summary['roles_without_permission'] ?? 0)) ?></div>
            <div class="desc">Role tanpa permission sama sekali.</div>
            <div class="mini">Role Audit</div>
        </div>
    </div>

    <div class="col-4">
        <div class="small-box bg-orange">
            <div class="label">Users Without Role</div>
            <div class="value"><?= e((string) ($summary['users_without_role'] ?? 0)) ?></div>
            <div class="desc">User belum memiliki role.</div>
            <div class="mini">User Audit</div>
        </div>
    </div>

    <div class="col-4">
        <div class="small-box bg-pink">
            <div class="label">Unused Registered Permissions</div>
            <div class="value"><?= e((string) ($summary['registered_but_unused_permissions'] ?? 0)) ?></div>
            <div class="desc">Permission terdaftar tetapi belum terdeteksi dipakai di controller.</div>
            <div class="mini">Usage Audit</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">1. Route Tanpa Permission</div>
    <div class="card-body">
        <?php if (!empty($routesWithoutPermission)): ?>
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
                    <?php foreach ($routesWithoutPermission as $row): ?>
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
        <?php else: ?>
            <div class="muted">Tidak ada route tanpa permission.</div>
        <?php endif; ?>
    </div>
</div>

<div style="height:22px;"></div>

<div class="card">
    <div class="card-header">2. Controller Action Tanpa Permission Check</div>
    <div class="card-body">
        <?php if (!empty($controllerActionsWithoutPermissionCheck)): ?>
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
                    <?php foreach ($controllerActionsWithoutPermissionCheck as $row): ?>
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
        <?php else: ?>
            <div class="muted">Semua controller action yang ter-audit sudah terdeteksi memiliki permission check.</div>
        <?php endif; ?>
    </div>
</div>

<div style="height:22px;"></div>

<div class="card">
    <div class="card-header">3. Expected Permission vs Actual Permission di Controller</div>
    <div class="card-body">
        <?php if (!empty($permissionMismatchInControllers)): ?>
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
                    <?php foreach ($permissionMismatchInControllers as $row): ?>
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
        <?php else: ?>
            <div class="muted">Tidak ditemukan mismatch antara expected permission dan actual permission di controller.</div>
        <?php endif; ?>
    </div>
</div>

<div style="height:22px;"></div>

<div class="card">
    <div class="card-header">4. Permission Dipakai di Controller Tapi Belum Terdaftar</div>
    <div class="card-body">
        <?php if (!empty($controllerPermissionNotRegistered)): ?>
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
                    <?php foreach ($controllerPermissionNotRegistered as $row): ?>
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
        <?php else: ?>
            <div class="muted">Tidak ada permission yang dipakai controller namun belum terdaftar di database.</div>
        <?php endif; ?>
    </div>
</div>

<div style="height:22px;"></div>

<div class="card">
    <div class="card-header">5. Permission Terdaftar Tapi Belum Terdeteksi Dipakai di Controller</div>
    <div class="card-body">
        <?php if (!empty($registeredButUnusedPermissions)): ?>
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
                    <?php foreach ($registeredButUnusedPermissions as $row): ?>
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
        <?php else: ?>
            <div class="muted">Semua permission terdaftar sudah terdeteksi dipakai di controller.</div>
        <?php endif; ?>
    </div>
</div>

<div style="height:22px;"></div>

<div class="grid">
    <div class="col-6">
        <div class="card">
            <div class="card-header">6. Role Tanpa Permission</div>
            <div class="card-body">
                <?php if (!empty($rolesWithoutPermission)): ?>
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
                            <?php foreach ($rolesWithoutPermission as $row): ?>
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
                <?php else: ?>
                    <div class="muted">Tidak ada role tanpa permission.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card">
            <div class="card-header">7. User Tanpa Role</div>
            <div class="card-body">
                <?php if (!empty($usersWithoutRole)): ?>
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
                            <?php foreach ($usersWithoutRole as $row): ?>
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
                <?php else: ?>
                    <div class="muted">Tidak ada user tanpa role.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div style="height:22px;"></div>

<div class="grid">
    <div class="col-6">
        <div class="card">
            <div class="card-header">8. Role Nonaktif Tapi Masih Dipakai</div>
            <div class="card-body">
                <?php if (!empty($inactiveRolesUsed)): ?>
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
                            <?php foreach ($inactiveRolesUsed as $row): ?>
                                <tr>
                                    <td><?= e((string) $row['id']) ?></td>
                                    <td><?= e($row['name']) ?></td>
                                    <td><?= e($row['code']) ?></td>
                                    <td><?= e((string) $row['total_users']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="muted">Tidak ada role nonaktif yang masih dipakai user.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card">
            <div class="card-header">9. Permission Nonaktif Tapi Masih Dipakai</div>
            <div class="card-body">
                <?php if (!empty($inactivePermissionsUsed)): ?>
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
                            <?php foreach ($inactivePermissionsUsed as $row): ?>
                                <tr>
                                    <td><?= e((string) $row['id']) ?></td>
                                    <td><?= e($row['name']) ?></td>
                                    <td><?= e($row['code']) ?></td>
                                    <td><?= e((string) $row['total_roles']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="muted">Tidak ada permission nonaktif yang masih dipakai role.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
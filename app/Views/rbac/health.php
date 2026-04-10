<div class="grid">
    <div class="col-4">
        <div class="small-box bg-sky">
            <div class="label">Total Issues</div>
            <div class="value"><?= e((string) ($summary['total_issues'] ?? 0)) ?></div>
            <div class="desc">Jumlah total temuan inkonsistensi RBAC.</div>
            <div class="mini">Health Summary</div>
        </div>
    </div>

    <div class="col-4">
        <div class="small-box bg-orange">
            <div class="label">Route Without Permission</div>
            <div class="value"><?= e((string) ($summary['routes_without_permission'] ?? 0)) ?></div>
            <div class="desc">Route yang belum memiliki permission terdaftar.</div>
            <div class="mini">Audit Route</div>
        </div>
    </div>

    <div class="col-4">
        <div class="small-box bg-pink">
            <div class="label">Permission Without Route</div>
            <div class="value"><?= e((string) ($summary['permissions_without_route'] ?? 0)) ?></div>
            <div class="desc">Permission yang tidak punya route yang cocok.</div>
            <div class="mini">Audit Permission</div>
        </div>
    </div>
</div>

<div class="grid">
    <div class="col-4">
        <div class="small-box bg-green">
            <div class="label">Roles Without Permission</div>
            <div class="value"><?= e((string) ($summary['roles_without_permission'] ?? 0)) ?></div>
            <div class="desc">Role tanpa permission terpasang.</div>
            <div class="mini">Role Mapping</div>
        </div>
    </div>

    <div class="col-4">
        <div class="small-box bg-orange">
            <div class="label">Users Without Role</div>
            <div class="value"><?= e((string) ($summary['users_without_role'] ?? 0)) ?></div>
            <div class="desc">User belum memiliki role apapun.</div>
            <div class="mini">User Mapping</div>
        </div>
    </div>

    <div class="col-4">
        <div class="small-box bg-pink">
            <div class="label">Inactive Mapping Issues</div>
            <div class="value"><?= e((string) (($summary['inactive_roles_used'] ?? 0) + ($summary['inactive_permissions_used'] ?? 0))) ?></div>
            <div class="desc">Role/permission nonaktif namun masih digunakan.</div>
            <div class="mini">Inactive Relation</div>
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
    <div class="card-header">2. Permission Tanpa Route</div>
    <div class="card-body">
        <?php if (!empty($permissionsWithoutRoute)): ?>
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
                    <?php foreach ($permissionsWithoutRoute as $row): ?>
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
            <div class="muted">Tidak ada permission yatim tanpa route.</div>
        <?php endif; ?>
    </div>
</div>

<div style="height:22px;"></div>

<div class="grid">
    <div class="col-6">
        <div class="card">
            <div class="card-header">3. Role Tanpa Permission</div>
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
            <div class="card-header">4. User Tanpa Role</div>
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
            <div class="card-header">5. Role Nonaktif Tapi Masih Dipakai</div>
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
            <div class="card-header">6. Permission Nonaktif Tapi Masih Dipakai</div>
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
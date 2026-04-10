<div class="grid">
    <div class="col-4">
        <div class="small-box bg-info">
            <div class="label">Current User Roles</div>
            <div class="value"><?= count($user['roles'] ?? []) ?></div>
            <div class="desc">Jumlah role yang dimiliki user login</div>
        </div>
    </div>

    <div class="col-4">
        <div class="small-box bg-success">
            <div class="label">Granted Permissions</div>
            <div class="value"><?= count($user['permissions'] ?? []) ?></div>
            <div class="desc">Jumlah permission aktif berdasarkan role</div>
        </div>
    </div>

    <div class="col-4">
        <div class="small-box bg-warning">
            <div class="label">Framework Status</div>
            <div class="value">OK</div>
            <div class="desc">MVC + Auth + Dynamic RBAC siap dikembangkan</div>
        </div>
    </div>
</div>

<div class="grid">
    <div class="col-6">
        <div class="card">
            <div class="card-header">Ringkasan User Login</div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="180">Nama</th>
                        <td><?= e($user['name'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><?= e($user['username'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= e($user['email'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card">
            <div class="card-header">Roles</div>
            <div class="card-body">
                <?php if (!empty($user['roles'])): ?>
                    <?php foreach ($user['roles'] as $role): ?>
                        <span class="badge badge-info"><?= e($role) ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="muted">User belum memiliki role.</div>
                <?php endif; ?>
            </div>
        </div>

        <div style="height:18px;"></div>

        <div class="card">
            <div class="card-header">Permissions</div>
            <div class="card-body">
                <?php if (!empty($user['permissions'])): ?>
                    <?php foreach ($user['permissions'] as $permission): ?>
                        <span class="badge badge-secondary"><?= e($permission) ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="muted">User belum memiliki permission.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="grid">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Roadmap Framework Manufaktur</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="220">Tahap</th>
                            <th>Deskripsi</th>
                            <th width="180">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Foundation MVC</td>
                            <td>Routing, controller, model, view renderer, database connection</td>
                            <td><span class="badge badge-info">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>Authentication</td>
                            <td>Login, logout, session, CSRF protection</td>
                            <td><span class="badge badge-info">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>Dynamic RBAC</td>
                            <td>Role, permission, user-role, role-permission</td>
                            <td><span class="badge badge-info">Selesai Dasar</span></td>
                        </tr>
                        <tr>
                            <td>Admin Panel UI</td>
                            <td>Dashboard layout, sidebar, topbar, cards, table style</td>
                            <td><span class="badge badge-info">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>Master Data</td>
                            <td>User, role, permission, departemen, gudang, item, supplier</td>
                            <td><span class="badge badge-secondary">Next Phase</span></td>
                        </tr>
                        <tr>
                            <td>Manufacturing Modules</td>
                            <td>Production order, inventory, QC, purchasing, reporting</td>
                            <td><span class="badge badge-secondary">Planned</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
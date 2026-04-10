<div class="welcome-panel">
    <div class="welcome-title">
        Selamat datang, <?= e($user['name'] ?? '-') ?> 👋
    </div>
    <div class="welcome-desc">
        Dashboard ini adalah fondasi awal framework manufaktur berbasis PHP Native, MVC, MySQL, dan Dynamic RBAC.
        Tampilan dibuat lebih cerah dan segar agar nyaman digunakan dalam operasional harian perusahaan manufaktur.
    </div>
</div>

<div class="grid">
    <div class="col-3">
        <div class="small-box bg-sky">
            <div class="label">Current User Roles</div>
            <div class="value"><?= count($user['roles'] ?? []) ?></div>
            <div class="desc">Jumlah role yang dimiliki user login saat ini.</div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-green">
            <div class="label">Granted Permissions</div>
            <div class="value"><?= count($user['permissions'] ?? []) ?></div>
            <div class="desc">Permission aktif yang diperoleh melalui role.</div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-orange">
            <div class="label">Framework Status</div>
            <div class="value">OK</div>
            <div class="desc">MVC, Auth, RBAC, dan UI siap untuk tahap modul berikutnya.</div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-pink">
            <div class="label">Manufacturing Scope</div>
            <div class="value">Ready</div>
            <div class="desc">Siap dikembangkan ke inventory, production, QC, purchasing, dan reporting.</div>
        </div>
    </div>
</div>

<div class="grid">
    <div class="col-7">
        <div class="card">
            <div class="card-header">Ringkasan User Login</div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <th width="220">Nama</th>
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
                        <tr>
                            <th>Role Count</th>
                            <td><?= count($user['roles'] ?? []) ?></td>
                        </tr>
                        <tr>
                            <th>Permission Count</th>
                            <td><?= count($user['permissions'] ?? []) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-5">
        <div class="card">
            <div class="card-header">Roles</div>
            <div class="card-body">
                <div class="info-list">
                    <?php if (!empty($user['roles'])): ?>
                        <?php foreach ($user['roles'] as $role): ?>
                            <span class="badge badge-sky"><?= e($role) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="muted">User belum memiliki role.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div style="height:22px;"></div>

        <div class="card">
            <div class="card-header">Quick Notes</div>
            <div class="card-body">
                <div class="muted" style="line-height:1.8;">
                    Tahap berikutnya yang disarankan adalah membangun master data
                    <strong>User</strong>, <strong>Role</strong>, dan <strong>Permission</strong>,
                    lalu lanjut ke modul manufaktur seperti inventory, production order, dan quality control.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Permissions</div>
            <div class="card-body">
                <div class="info-list">
                    <?php if (!empty($user['permissions'])): ?>
                        <?php
                        $badgeClasses = ['badge-green', 'badge-orange', 'badge-pink', 'badge-sky'];
                        foreach ($user['permissions'] as $i => $permission):
                            $badgeClass = $badgeClasses[$i % count($badgeClasses)];
                        ?>
                            <span class="badge <?= e($badgeClass) ?>"><?= e($permission) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="muted">User belum memiliki permission.</div>
                    <?php endif; ?>
                </div>
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
                            <th width="240">Tahap</th>
                            <th>Deskripsi</th>
                            <th width="190">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Foundation MVC</td>
                            <td>Routing, controller, model, view renderer, database connection</td>
                            <td><span class="badge badge-sky">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>Authentication</td>
                            <td>Login, logout, session, CSRF protection</td>
                            <td><span class="badge badge-green">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>Dynamic RBAC</td>
                            <td>Role, permission, user-role, role-permission</td>
                            <td><span class="badge badge-orange">Selesai Dasar</span></td>
                        </tr>
                        <tr>
                            <td>Bright Admin UI</td>
                            <td>Tampilan cerah, segar, modern, dan nyaman untuk penggunaan operasional</td>
                            <td><span class="badge badge-pink">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>Master Data</td>
                            <td>User, role, permission, departemen, gudang, item, supplier</td>
                            <td><span class="badge badge-sky">Next Phase</span></td>
                        </tr>
                        <tr>
                            <td>Manufacturing Modules</td>
                            <td>Production order, inventory, QC, purchasing, reporting</td>
                            <td><span class="badge badge-green">Planned</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="welcome-panel">
    <div class="welcome-title">
        Selamat datang, <?= e($user['name'] ?? '-') ?> 👋
    </div>
    <div class="welcome-desc">
        Dashboard ini adalah fondasi awal framework manufaktur berbasis PHP Native, MVC, MySQL, dan Dynamic RBAC.
        Pada fase ini, sistem sudah memiliki modul master dasar untuk user, role, dan permission.
    </div>
</div>

<div class="quick-actions">
    <a class="quick-action" href="<?= e(base_url('users')) ?>">
        <div class="quick-action-icon qa-blue">👤</div>
        <div>
            <div class="quick-action-title">Master User</div>
            <div class="quick-action-desc">Lihat dan kelola daftar user sistem.</div>
        </div>
    </a>

    <a class="quick-action" href="<?= e(base_url('users/create')) ?>">
        <div class="quick-action-icon qa-green">➕</div>
        <div>
            <div class="quick-action-title">Tambah User</div>
            <div class="quick-action-desc">Buat user baru dan assign role.</div>
        </div>
    </a>

    <a class="quick-action" href="<?= e(base_url('roles')) ?>">
        <div class="quick-action-icon qa-orange">🛡️</div>
        <div>
            <div class="quick-action-title">Master Role</div>
            <div class="quick-action-desc">Lihat struktur role dalam sistem.</div>
        </div>
    </a>

    <a class="quick-action" href="<?= e(base_url('permissions')) ?>">
        <div class="quick-action-icon qa-pink">🔑</div>
        <div>
            <div class="quick-action-title">Master Permission</div>
            <div class="quick-action-desc">Lihat seluruh permission yang tersedia.</div>
        </div>
    </a>
</div>

<div class="grid">
    <div class="col-3">
        <div class="small-box bg-sky">
            <div class="label">Current User Roles</div>
            <div class="value"><?= count($user['roles'] ?? []) ?></div>
            <div class="desc">Jumlah role yang dimiliki user login saat ini.</div>
            <div class="mini">Role-based access active</div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-green">
            <div class="label">Granted Permissions</div>
            <div class="value"><?= count($user['permissions'] ?? []) ?></div>
            <div class="desc">Permission aktif yang diperoleh berdasarkan role.</div>
            <div class="mini">Permission mapping ready</div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-orange">
            <div class="label">Framework Status</div>
            <div class="value">OK</div>
            <div class="desc">MVC, Auth, RBAC, layout, dan master data dasar berjalan baik.</div>
            <div class="mini">System stable</div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-pink">
            <div class="label">Manufacturing Scope</div>
            <div class="value">Ready</div>
            <div class="desc">Siap dikembangkan ke inventory, production, QC, purchasing, dan reporting.</div>
            <div class="mini">Module expansion ready</div>
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
                    Tahap berikutnya yang disarankan adalah menambahkan fitur edit user, assign permission ke role,
                    lalu mulai membangun master data manufaktur seperti gudang, item, supplier, dan work center.
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
                            <td>Tampilan cerah, segar, modern, sidebar toggle, footer, error page, theme switch</td>
                            <td><span class="badge badge-pink">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>Master User / Role / Permission</td>
                            <td>Daftar user, tambah user, daftar role, daftar permission</td>
                            <td><span class="badge badge-green">Selesai Dasar</span></td>
                        </tr>
                        <tr>
                            <td>Manufacturing Modules</td>
                            <td>Inventory, production order, QC, purchasing, reporting</td>
                            <td><span class="badge badge-sky">Next Phase</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
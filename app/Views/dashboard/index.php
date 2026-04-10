<div class="welcome-panel">
    <div class="welcome-title">
        Selamat datang, <?= e($user['name'] ?? '-') ?> 👋
    </div>
    <div class="welcome-desc">
        Dashboard ini menampilkan statistik aktual dari sistem RBAC yang sedang berjalan.
        Saat ini framework sudah memiliki fondasi MVC, authentication, dynamic RBAC, master user, role,
        dan permission management dengan tampilan admin yang lebih polished.
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
            <div class="quick-action-desc">Lihat dan kelola role sistem.</div>
        </div>
    </a>

    <a class="quick-action" href="<?= e(base_url('permissions')) ?>">
        <div class="quick-action-icon qa-pink">🔑</div>
        <div>
            <div class="quick-action-title">Master Permission</div>
            <div class="quick-action-desc">Lihat dan kelola permission sistem.</div>
        </div>
    </a>
</div>

<div class="grid">
    <div class="col-3">
        <div class="small-box bg-sky">
            <div class="label">Total Users</div>
            <div class="value"><?= e((string) ($stats['users'] ?? 0)) ?></div>
            <div class="desc">Jumlah seluruh user yang terdaftar dalam sistem.</div>
            <div class="mini">Active: <?= e((string) ($stats['active_users'] ?? 0)) ?></div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-green">
            <div class="label">Total Roles</div>
            <div class="value"><?= e((string) ($stats['roles'] ?? 0)) ?></div>
            <div class="desc">Role aktif dan nonaktif yang tersedia untuk kontrol akses.</div>
            <div class="mini">Active: <?= e((string) ($stats['active_roles'] ?? 0)) ?></div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-orange">
            <div class="label">Total Permissions</div>
            <div class="value"><?= e((string) ($stats['permissions'] ?? 0)) ?></div>
            <div class="desc">Seluruh permission yang menjadi dasar Dynamic RBAC.</div>
            <div class="mini">Active: <?= e((string) ($stats['active_permissions'] ?? 0)) ?></div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-pink">
            <div class="label">Framework Status</div>
            <div class="value">LIVE</div>
            <div class="desc">Panel admin dan RBAC sudah siap untuk lanjut ke modul manufaktur.</div>
            <div class="mini">Phase 5C Complete</div>
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
                            <td><?= e((string) count($user['roles'] ?? [])) ?></td>
                        </tr>
                        <tr>
                            <th>Permission Count</th>
                            <td><?= e((string) count($user['permissions'] ?? [])) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-5">
        <div class="card">
            <div class="card-header">Role Aktif User</div>
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
            <div class="card-header">Catatan Pengembangan</div>
            <div class="card-body">
                <div class="muted" style="line-height:1.8;">
                    Setelah fase ini, sistem sudah cukup matang untuk masuk ke master data manufaktur seperti
                    departemen, gudang, satuan, item/material, supplier, customer, dan work center.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Permissions User Login</div>
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
                            <td><span class="badge badge-orange">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>Master User / Role / Permission</td>
                            <td>CRUD user, role, permission, assign role, assign permission</td>
                            <td><span class="badge badge-pink">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>Admin Polishing</td>
                            <td>Toast, filter table, old input, confirmation, real count dashboard</td>
                            <td><span class="badge badge-green">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>Master Data Manufaktur</td>
                            <td>Departemen, gudang, satuan, item/material, supplier, customer, work center</td>
                            <td><span class="badge badge-sky">Next Phase</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
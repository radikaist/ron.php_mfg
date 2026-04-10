<div class="card">
    <h1>Dashboard</h1>
    <p>Selamat datang, <strong><?= e($user['name'] ?? '-') ?></strong>.</p>
    <p>Framework MVC PHP Native untuk manufaktur berhasil berjalan dengan pondasi Dynamic RBAC.</p>

    <hr style="margin:20px 0; border:none; border-top:1px solid #dee2e6;">

    <h3>Roles</h3>
    <?php if (!empty($user['roles'])): ?>
        <?php foreach ($user['roles'] as $role): ?>
            <span class="role-badge"><?= e($role) ?></span>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Tidak ada role.</p>
    <?php endif; ?>

    <h3 style="margin-top:24px;">Permissions</h3>
    <?php if (!empty($user['permissions'])): ?>
        <?php foreach ($user['permissions'] as $permission): ?>
            <span class="permission-badge"><?= e($permission) ?></span>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Tidak ada permission.</p>
    <?php endif; ?>
</div>
<?php
$totalMenus = count($menus ?? []);
$activeMenus = 0;
$parentMenus = 0;
$childMenus = 0;

foreach (($menus ?? []) as $row) {
    if ((int) $row['is_active'] === 1) {
        $activeMenus++;
    }

    if (empty($row['parent_id'])) {
        $parentMenus++;
    } else {
        $childMenus++;
    }
}
?>

<div class="grid">
    <div class="col-3">
        <div class="small-box bg-sky">
            <div class="label">Total Menus</div>
            <div class="value"><?= e((string) $totalMenus) ?></div>
            <div class="desc">Jumlah seluruh menu sidebar yang terdaftar.</div>
            <div class="mini">Sidebar Registry</div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-green">
            <div class="label">Active Menus</div>
            <div class="value"><?= e((string) $activeMenus) ?></div>
            <div class="desc">Menu aktif yang dapat digunakan pada sidebar.</div>
            <div class="mini">Active Layout</div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-orange">
            <div class="label">Parent Menus</div>
            <div class="value"><?= e((string) $parentMenus) ?></div>
            <div class="desc">Jumlah menu level utama.</div>
            <div class="mini">Top Level</div>
        </div>
    </div>

    <div class="col-3">
        <div class="small-box bg-pink">
            <div class="label">Child Menus</div>
            <div class="value"><?= e((string) $childMenus) ?></div>
            <div class="desc">Jumlah submenu / child menu.</div>
            <div class="mini">Nested Menu</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Daftar Menu Sidebar</div>
    <div class="card-body">
        <div class="muted" style="margin-bottom:16px;">
            Foundation menu management sudah aktif. Pada tahap berikutnya kita bisa tambah create, edit, urutkan, dan nested layout management.
        </div>

        <table class="table sortable-table">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th width="100">Parent ID</th>
                    <th>Parent</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th width="100">Icon</th>
                    <th>Permission</th>
                    <th width="100">Order</th>
                    <th width="120">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($menus)): ?>
                    <?php foreach ($menus as $row): ?>
                        <tr>
                            <td><?= e((string) $row['id']) ?></td>
                            <td><?= e($row['parent_id'] !== null ? (string) $row['parent_id'] : '-') ?></td>
                            <td><?= e($row['parent_title'] ?: '-') ?></td>
                            <td><?= e($row['title']) ?></td>
                            <td><?= e($row['url']) ?></td>
                            <td><?= e($row['icon'] ?: '-') ?></td>
                            <td><?= e($row['permission_code'] ?: '-') ?></td>
                            <td><?= e((string) $row['sort_order']) ?></td>
                            <td>
                                <?php if ((int) $row['is_active'] === 1): ?>
                                    <span class="badge badge-green">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-orange">Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="empty-row">
                        <td colspan="9" class="muted">Belum ada data menu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
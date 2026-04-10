<?php
$suggestedName = $suggested['name'] ?? '';
$suggestedCode = $suggested['code'] ?? '';
$suggestedModule = $suggested['module'] ?? '';
$suggestedDescription = $suggested['description'] ?? '';
?>
<div class="grid">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Audit Route Belum Terdaftar Permission</div>
            <div class="card-body">
                <?php if (!empty($undocumentedRoutes)): ?>
                    <div class="toolbar">
                        <div class="muted">
                            Ditemukan <strong><?= e((string) count($undocumentedRoutes)) ?></strong> route yang belum memiliki permission terdaftar.
                        </div>

                        <form action="<?= e(base_url('permissions/auto-store-all')) ?>" method="POST" data-confirm="Generate semua permission yang belum terdaftar?">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-success">Generate All Missing Permissions</button>
                        </form>
                    </div>

                    <table class="table sortable-table" id="undocumentedRoutesTable">
                        <thead>
                            <tr>
                                <th width="100">Method</th>
                                <th>URI</th>
                                <th width="140">Module</th>
                                <th>Suggested Code</th>
                                <th>Suggested Name</th>
                                <th class="no-sort" width="260">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($undocumentedRoutes as $route): ?>
                                <tr>
                                    <td><span class="badge badge-sky"><?= e($route['method']) ?></span></td>
                                    <td><?= e($route['uri']) ?></td>
                                    <td><span class="badge badge-orange"><?= e($route['module']) ?></span></td>
                                    <td><?= e($route['suggested_code']) ?></td>
                                    <td><?= e($route['suggested_name']) ?></td>
                                    <td>
                                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                            <a
                                                href="<?= e(base_url('permissions/create'
                                                    . '?suggest_name=' . urlencode($route['suggested_name'])
                                                    . '&suggest_code=' . urlencode($route['suggested_code'])
                                                    . '&suggest_module=' . urlencode($route['module'])
                                                    . '&suggest_description=' . urlencode($route['description'])
                                                )) ?>"
                                                class="badge badge-green"
                                            >
                                                Gunakan Saran
                                            </a>

                                            <form action="<?= e(base_url('permissions/auto-store')) ?>" method="POST" data-confirm="Generate permission ini sekarang?">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="name" value="<?= e($route['suggested_name']) ?>">
                                                <input type="hidden" name="code" value="<?= e($route['suggested_code']) ?>">
                                                <input type="hidden" name="module" value="<?= e($route['module']) ?>">
                                                <input type="hidden" name="description" value="<?= e($route['description']) ?>">
                                                <button type="submit" class="badge badge-pink" style="border:none; cursor:pointer;">Generate Sekarang</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="form-hint" style="margin-top:12px;">
                        Gunakan <strong>Generate Sekarang</strong> untuk satu item, atau <strong>Generate All Missing Permissions</strong> untuk semuanya.
                    </div>
                <?php else: ?>
                    <div class="muted">
                        Tidak ada route baru yang terdeteksi tanpa permission. Semua route yang ter-audit sudah memiliki permission yang sesuai.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">Tambah Permission Manual</div>
            <div class="card-body">
                <form action="<?= e(base_url('permissions/store')) ?>" method="POST" data-confirm="Simpan permission baru?">
                    <?= csrf_field() ?>

                    <div class="grid">
                        <div class="col-6">
                            <label class="form-label">Nama Permission</label>
                            <input
                                type="text"
                                name="name"
                                value="<?= e(old_or('name', $suggestedName)) ?>"
                                class="form-control"
                            >
                        </div>

                        <div class="col-6">
                            <label class="form-label">Code Permission</label>
                            <input
                                type="text"
                                name="code"
                                value="<?= e(old_or('code', $suggestedCode)) ?>"
                                class="form-control"
                            >
                        </div>

                        <div class="col-6">
                            <label class="form-label">Module</label>
                            <input
                                type="text"
                                name="module"
                                value="<?= e(old_or('module', $suggestedModule)) ?>"
                                class="form-control"
                            >
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-textarea"><?= e(old_or('description', $suggestedDescription)) ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="checkbox-label">
                                <input type="checkbox" name="is_active" value="1" <?= old_or('is_active', 1) ? 'checked' : '' ?>>
                                Permission aktif
                            </label>
                        </div>

                        <div class="col-12">
                            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                                <button type="submit" class="btn btn-warning">Simpan Permission</button>
                                <a href="<?= e(base_url('permissions')) ?>" class="btn-outline">Kembali</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
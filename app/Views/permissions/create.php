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
                    </div>

                    <table class="table sortable-table" id="undocumentedRoutesTable">
                        <thead>
                            <tr>
                                <th width="100">Method</th>
                                <th>URI</th>
                                <th width="140">Module</th>
                                <th>Suggested Code</th>
                                <th>Suggested Name</th>
                                <th class="no-sort" width="160">Action</th>
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
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="form-hint" style="margin-top:12px;">
                        Klik <strong>Gunakan Saran</strong> untuk mengisi otomatis form permission di bawah.
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
            <div class="card-header">Tambah Permission</div>
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
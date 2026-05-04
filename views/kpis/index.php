<div class="container mt-4">
    <div class="row align-items-center mb-4">
        <div class="col-lg-8">
            <h1 class="display-6 fw-bold">ตัวชี้วัด (KPI)</h1>
            <p class="text-muted mb-0">จัดการ KPI เพื่อวัดผลความสำเร็จของโครงการ</p>
        </div>
    </div>

    <?php if (!empty($canManage)): ?>
        <div class="card mb-4 bg-panel">
            <div class="card-body">
                <h5 class="card-title mb-3">เพิ่ม KPI ใหม่</h5>
                <form method="POST" action="/kpis" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">รหัสโครงการ</label>
                        <input class="form-control" type="number" name="project_id" value="<?= (int) ($projectId ?? 0) ?>" placeholder="รหัสโครงการ" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ชื่อตัวชี้วัด</label>
                        <input class="form-control" type="text" name="name" placeholder="ชื่อตัวชี้วัด" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ประเภท</label>
                        <select class="form-select" name="type" required>
                            <option value="number">จำนวน</option>
                            <option value="percentage">ร้อยละ</option>
                            <option value="text">ข้อความ</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ค่าเป้าหมาย</label>
                        <input class="form-control" type="number" step="0.01" name="target_value" placeholder="ค่าเป้าหมาย">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">หน่วย</label>
                        <input class="form-control" type="text" name="unit" placeholder="หน่วย">
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">บันทึก KPI</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div class="card bg-panel">
        <div class="card-body">
            <h5 class="card-title mb-3">รายการ KPI</h5>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ชื่อ</th>
                            <th>ประเภท</th>
                            <th>ค่าเป้าหมาย</th>
                            <th>หน่วย</th>
                            <?php if (!empty($canManage)): ?>
                                <th class="text-end">จัดการ</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kpis as $kpi): ?>
                            <tr>
                                <td><?= htmlspecialchars($kpi['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($kpi['type'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= $kpi['target_value'] !== null ? number_format((float) $kpi['target_value'], 2) : '-' ?></td>
                                <td><?= htmlspecialchars((string) ($kpi['unit'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                                <?php if (!empty($canManage)): ?>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#kpi-edit-<?= (int) $kpi['id'] ?>">แก้ไข</button>
                                            <form method="POST" action="/kpis/<?= (int) $kpi['id'] ?>/delete" class="d-inline">
                                                <button class="btn btn-outline-danger" type="submit">ลบ</button>
                                            </form>
                                        </div>
                                        <div class="collapse mt-2" id="kpi-edit-<?= (int) $kpi['id'] ?>">
                                            <div class="card card-body">
                                                <form method="POST" action="/kpis/<?= (int) $kpi['id'] ?>/update" class="row g-2">
                                                    <div class="col-12">
                                                        <label class="form-label">ชื่อตัวชี้วัด</label>
                                                        <input class="form-control form-control-sm" type="text" name="name" value="<?= htmlspecialchars($kpi['name'], ENT_QUOTES, 'UTF-8') ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">ประเภท</label>
                                                        <select class="form-select form-select-sm" name="type" required>
                                                            <option value="number" <?= $kpi['type'] === 'number' ? 'selected' : '' ?>>จำนวน</option>
                                                            <option value="percentage" <?= $kpi['type'] === 'percentage' ? 'selected' : '' ?>>ร้อยละ</option>
                                                            <option value="text" <?= $kpi['type'] === 'text' ? 'selected' : '' ?>>ข้อความ</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">ค่าเป้าหมาย</label>
                                                        <input class="form-control form-control-sm" type="number" step="0.01" name="target_value" value="<?= htmlspecialchars((string) ($kpi['target_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">หน่วย</label>
                                                        <input class="form-control form-control-sm" type="text" name="unit" value="<?= htmlspecialchars((string) ($kpi['unit'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">ค่าเริ่มต้น</label>
                                                        <input class="form-control form-control-sm" type="number" step="0.01" name="baseline_value" value="<?= htmlspecialchars((string) ($kpi['baseline_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">ลำดับ</label>
                                                        <input class="form-control form-control-sm" type="number" name="sort_order" value="<?= (int) ($kpi['sort_order'] ?? 0) ?>">
                                                    </div>
                                                    <div class="col-12 text-end">
                                                        <button class="btn btn-sm btn-primary" type="submit">บันทึกการแก้ไข</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

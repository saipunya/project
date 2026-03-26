<?php
$old = $old ?? [];
$canCreate = $canCreate ?? false;
$canManage = $canManage ?? false;
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><?= $canCreate ? 'สร้างแผน' : 'แผนทั้งหมด' ?></h3>
</div>

<?php if (!empty($createdCode)): ?>
    <div class="alert alert-success">
        สร้างแผนสำเร็จด้วยรหัส <strong><?= htmlspecialchars((string) $createdCode, ENT_QUOTES, 'UTF-8') ?></strong>
    </div>
<?php endif; ?>

<?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars((string) $errorMessage, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<?php if ($canCreate): ?>
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="/plans" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">ปีงบประมาณ</label>
                    <select class="form-select" name="fiscal_year_id" required>
                        <option value="">เลือกปีงบประมาณ</option>
                        <?php foreach ($fiscalYears ?? [] as $fiscalYear): ?>
                            <option value="<?= (int) $fiscalYear['id'] ?>" <?= (int) ($old['fiscal_year_id'] ?? $selectedFiscalYearId ?? 0) === (int) $fiscalYear['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars((string) $fiscalYear['fiscal_year'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">รหัสอัตโนมัติ</label>
                    <input class="form-control" type="text" value="<?= htmlspecialchars((string) ($previewCode ?? 'สร้างอัตโนมัติ'), ENT_QUOTES, 'UTF-8') ?>" readonly>
                    <div class="form-text">ระบบจะกำหนดรหัสตัวอักษรถัดไปให้อัตโนมัติ</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">ชื่อแผน</label>
                    <input class="form-control" type="text" name="name" value="<?= htmlspecialchars((string) ($old['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">หน่วยงานเจ้าของแผน</label>
                    <input class="form-control" type="text" name="owner_department" value="<?= htmlspecialchars((string) ($old['owner_department'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="ใส่หรือเว้นว่างได้">
                </div>

                <div class="col-12">
                    <label class="form-label">รายละเอียด</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="ใส่หรือเว้นว่างได้"><?= htmlspecialchars((string) ($old['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary" type="submit">บันทึกแผน</button>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        หน้านี้แสดงรายการแผนทั้งหมด ผู้ดูแลระบบเท่านั้นที่สามารถสร้างแผนใหม่ได้
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <h5 class="mb-3">รายการแผน</h5>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                <tr>
                    <th>รหัส</th>
                    <th>ปีงบประมาณ</th>
                    <th>ชื่อ</th>
                    <th>หน่วยงานเจ้าของแผน</th>
                    <?php if (!empty($canManage)): ?>
                        <th class="text-end">จัดการ</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php if (($plans ?? []) === []): ?>
                    <tr>
                        <td colspan="<?= !empty($canManage) ? 5 : 4 ?>" class="text-center text-muted">ไม่พบข้อมูลแผน</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($plans as $plan): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($plan['code'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                            <td><?= htmlspecialchars((string) $plan['fiscal_year_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string) ($plan['owner_department'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                            <?php if (!empty($canManage)): ?>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#plan-edit-<?= (int) $plan['id'] ?>">แก้ไข</button>
                                        <form method="POST" action="/plans/<?= (int) $plan['id'] ?>/delete" class="d-inline">
                                            <button class="btn btn-outline-danger" type="submit">ลบ</button>
                                        </form>
                                    </div>
                                    <div class="collapse mt-2" id="plan-edit-<?= (int) $plan['id'] ?>">
                                        <div class="card card-body">
                                            <form method="POST" action="/plans/<?= (int) $plan['id'] ?>/update" class="row g-2">
                                                <div class="col-12">
                                                    <label class="form-label">ชื่อแผน</label>
                                                    <input class="form-control form-control-sm" type="text" name="name" value="<?= htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">หน่วยงานเจ้าของแผน</label>
                                                    <input class="form-control form-control-sm" type="text" name="owner_department" value="<?= htmlspecialchars((string) ($plan['owner_department'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">รายละเอียด</label>
                                                    <textarea class="form-control form-control-sm" name="description" rows="2" placeholder="ใส่หรือเว้นว่างได้"><?= htmlspecialchars((string) ($plan['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
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
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

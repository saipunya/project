<?php
$canManage = $canManage ?? false;
?>

<div class="container mt-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold">จัดการโครงการ</h1>
            <p class="text-muted mb-0">สร้าง แก้ไข และติดตามสถานะโครงการได้จากที่เดียว</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <?php if ($canManage): ?>
                <span class="badge bg-primary">ผู้ดูแลระบบ</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4 bg-panel">
        <div class="card-body">
            <h5 class="card-title mb-3">สร้างโครงการใหม่</h5>
            <form method="POST" action="/projects" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">แผน</label>
                    <select class="form-select" name="plan_id" required>
                        <option value="">เลือกแผน</option>
                        <?php foreach ($plans ?? [] as $plan): ?>
                            <option value="<?= (int) $plan['id'] ?>">
                                <?= htmlspecialchars($plan['fiscal_year_name'], ENT_QUOTES, 'UTF-8') ?> -
                                <?= htmlspecialchars($plan['code'], ENT_QUOTES, 'UTF-8') ?> :
                                <?= htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">ระบบจะสร้างรหัสโครงการอัตโนมัติ</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ชื่อโครงการ</label>
                    <input class="form-control" type="text" name="name" placeholder="ชื่อโครงการ" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">งบประมาณที่ได้รับ</label>
                    <input class="form-control" type="number" step="0.01" name="allocated_budget" placeholder="งบประมาณ" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">รายละเอียด</label>
                    <textarea class="form-control" name="description" placeholder="รายละเอียด"></textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">วันที่เริ่ม</label>
                    <input class="form-control" type="date" name="start_date">
                </div>
                <div class="col-md-3">
                    <label class="form-label">วันที่สิ้นสุด</label>
                    <input class="form-control" type="date" name="end_date">
                </div>
                <div class="col-12 text-end">
                    <button class="btn btn-primary" type="submit">บันทึกโครงการ</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-panel">
        <div class="card-body">
            <h5 class="card-title mb-3">รายการโครงการ</h5>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>รหัส</th>
                            <th>ชื่อ</th>
                            <th>สถานะ</th>
                            <th>งบประมาณ</th>
                            <th class="text-center">ดู</th>
                            <?php if (!empty($canManage)): ?>
                                <th class="text-end">จัดการ</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                            <tr>
                                <td><?= htmlspecialchars($project['code'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($project['project_status'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= number_format((float) $project['allocated_budget'], 2) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-info" onclick="viewProject(<?= (int) $project['id'] ?>)">ดู</button>
                                </td>
                                <?php if (!empty($canManage)): ?>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#project-edit-<?= (int) $project['id'] ?>">แก้ไข</button>
                                            <form method="POST" action="/projects/<?= (int) $project['id'] ?>/delete" class="d-inline">
                                                <button class="btn btn-outline-danger" type="submit">ลบ</button>
                                            </form>
                                        </div>
                                        <div class="collapse mt-2" id="project-edit-<?= (int) $project['id'] ?>">
                                            <div class="card card-body">
                                                <form method="POST" action="/projects/<?= (int) $project['id'] ?>/update" class="row g-2">
                                                    <div class="col-md-6">
                                                        <label class="form-label">แผน</label>
                                                        <select class="form-select form-select-sm" name="plan_id" required>
                                                            <option value="">เลือกแผน</option>
                                                            <?php foreach ($plans ?? [] as $plan): ?>
                                                                <option value="<?= (int) $plan['id'] ?>" <?= (int) $plan['id'] === (int) $project['plan_id'] ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($plan['fiscal_year_name'], ENT_QUOTES, 'UTF-8') ?> -
                                                                    <?= htmlspecialchars($plan['code'], ENT_QUOTES, 'UTF-8') ?> :
                                                                    <?= htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8') ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">ชื่อโครงการ</label>
                                                        <input class="form-control form-control-sm" type="text" name="name" value="<?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">งบประมาณที่ได้รับ</label>
                                                        <input class="form-control form-control-sm" type="number" step="0.01" name="allocated_budget" value="<?= htmlspecialchars((string) $project['allocated_budget'], ENT_QUOTES, 'UTF-8') ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">รายละเอียด</label>
                                                        <input class="form-control form-control-sm" type="text" name="description" value="<?= htmlspecialchars((string) ($project['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">วันที่เริ่ม</label>
                                                        <input class="form-control form-control-sm" type="date" name="start_date" value="<?= htmlspecialchars((string) ($project['start_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">วันที่สิ้นสุด</label>
                                                        <input class="form-control form-control-sm" type="date" name="end_date" value="<?= htmlspecialchars((string) ($project['end_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
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


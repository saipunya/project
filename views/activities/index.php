<?php
$canUpdate = $canUpdate ?? false;
$canDelete = $canDelete ?? false;
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>กิจกรรม</h3>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5>สร้างกิจกรรม</h5>
        <form method="POST" action="/activities" class="row g-3">
            <div class="col-md-2"><input class="form-control" type="number" name="project_id" value="<?= (int) ($projectId ?? 0) ?>" placeholder="รหัสโครงการ" required></div>
            <div class="col-md-4"><input class="form-control" type="text" name="name" placeholder="ชื่อกิจกรรม" required></div>
            <div class="col-md-2">
                <select class="form-select" name="status" required>
                    <option value="NOT_STARTED">ยังไม่เริ่ม</option>
                    <option value="IN_PROGRESS">กำลังดำเนินการ</option>
                    <option value="COMPLETED">เสร็จสิ้น</option>
                </select>
            </div>
            <div class="col-md-2"><input class="form-control" type="date" name="start_date" required></div>
            <div class="col-md-2"><input class="form-control" type="date" name="end_date" required></div>
            <div class="col-12"><textarea class="form-control" name="description" placeholder="คำอธิบาย"></textarea></div>
            <div class="col-12"><button class="btn btn-primary" type="submit">บันทึกกิจกรรม</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>รายการกิจกรรม</h5>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ชื่อ</th>
                    <th>สถานะ</th>
                    <th>เริ่มต้น</th>
                    <th>สิ้นสุด</th>
                    <?php if (!empty($canUpdate) || !empty($canDelete)): ?>
                        <th class="text-end">จัดการ</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td><?= htmlspecialchars($activity['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($activity['status'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($activity['start_date'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($activity['end_date'], ENT_QUOTES, 'UTF-8') ?></td>
                        <?php if (!empty($canUpdate) || !empty($canDelete)): ?>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <?php if (!empty($canUpdate)): ?>
                                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#activity-edit-<?= (int) $activity['id'] ?>">แก้ไข</button>
                                    <?php endif; ?>
                                    <?php if (!empty($canDelete)): ?>
                                        <form method="POST" action="/activities/<?= (int) $activity['id'] ?>/delete" class="d-inline">
                                            <button class="btn btn-outline-danger" type="submit">ลบ</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($canUpdate)): ?>
                                    <div class="collapse mt-2" id="activity-edit-<?= (int) $activity['id'] ?>">
                                        <div class="card card-body">
                                            <form method="POST" action="/activities/<?= (int) $activity['id'] ?>/update" class="row g-2">
                                                <input type="hidden" name="project_id" value="<?= (int) $activity['project_id'] ?>">
                                                <div class="col-12">
                                                    <label class="form-label">ชื่อกิจกรรม</label>
                                                    <input class="form-control form-control-sm" type="text" name="name" value="<?= htmlspecialchars($activity['name'], ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">สถานะ</label>
                                                    <select class="form-select form-select-sm" name="status" required>
                                                        <option value="NOT_STARTED" <?= $activity['status'] === 'NOT_STARTED' ? 'selected' : '' ?>>ยังไม่เริ่ม</option>
                                                        <option value="IN_PROGRESS" <?= $activity['status'] === 'IN_PROGRESS' ? 'selected' : '' ?>>กำลังดำเนินการ</option>
                                                        <option value="COMPLETED" <?= $activity['status'] === 'COMPLETED' ? 'selected' : '' ?>>เสร็จสิ้น</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">ลำดับ</label>
                                                    <input class="form-control form-control-sm" type="number" name="sort_order" value="<?= (int) ($activity['sort_order'] ?? 0) ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">วันที่เริ่ม</label>
                                                    <input class="form-control form-control-sm" type="date" name="start_date" value="<?= htmlspecialchars($activity['start_date'], ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">วันที่สิ้นสุด</label>
                                                    <input class="form-control form-control-sm" type="date" name="end_date" value="<?= htmlspecialchars($activity['end_date'], ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label">คำอธิบาย</label>
                                                    <textarea class="form-control form-control-sm" name="description" rows="2" placeholder="ใส่หรือเว้นว่างได้"><?= htmlspecialchars((string) ($activity['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                                                </div>
                                                <div class="col-12 text-end">
                                                    <button class="btn btn-sm btn-primary" type="submit">บันทึกการแก้ไข</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

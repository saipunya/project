<?php
require_once __DIR__ . '/../layouts/header.php';
$canManage = $canManage ?? false;
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>โครงการ</h3>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5>สร้างโครงการ</h5>
        <form method="POST" action="/projects" class="row g-3">
            <div class="col-md-4">
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
                <div class="form-text">ระบบจะสร้างรหัสโครงการอัตโนมัติ เช่น 2026A001</div>
            </div>
            <div class="col-md-3"><input class="form-control" type="text" name="name" placeholder="ชื่อโครงการ" required></div>
            <div class="col-md-3"><input class="form-control" type="number" step="0.01" name="allocated_budget" placeholder="งบประมาณที่ได้รับ" required></div>
            <div class="col-md-6"><textarea class="form-control" name="description" placeholder="รายละเอียด"></textarea></div>
            <div class="col-md-3"><input class="form-control" type="date" name="start_date"></div>
            <div class="col-md-3"><input class="form-control" type="date" name="end_date"></div>
            <div class="col-12"><button class="btn btn-primary" type="submit">บันทึกโครงการ</button></div>
        </form>
    </div>
</div>

<!-- Project View Modal -->
<div class="modal fade" id="projectViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">รายละเอียดโครงการ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="projectModalContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewProject(projectId) {
    fetch(`/projects/${projectId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            
            const project = data.project;
            const plan = data.plan;
            
            const content = `
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>รหัสโครงการ:</strong></td>
                                <td>${project.code}</td>
                            </tr>
                            <tr>
                                <td><strong>ชื่อโครงการ:</strong></td>
                                <td>${project.name}</td>
                            </tr>
                            <tr>
                                <td><strong>แผนงาน:</strong></td>
                                <td>${plan ? `${plan.fiscal_year_name} - ${plan.code} : ${plan.name}` : '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>สถานะ:</strong></td>
                                <td>
                                    <span class="badge bg-${project.status === 'active' ? 'success' : 'secondary'}">
                                        ${project.status === 'active' ? 'ดำเนินการ' : 'ไม่ดำเนินการ'}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>งบประมาณที่ได้รับ:</strong></td>
                                <td>${parseFloat(project.allocated_budget).toLocaleString('th-TH', {minimumFractionDigits: 2})} บาท</td>
                            </tr>
                            <tr>
                                <td><strong>วันที่เริ่มต้น:</strong></td>
                                <td>${project.start_date ? new Date(project.start_date).toLocaleDateString('th-TH') : '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>วันที่สิ้นสุด:</strong></td>
                                <td>${project.end_date ? new Date(project.end_date).toLocaleDateString('th-TH') : '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>วันที่สร้าง:</strong></td>
                                <td>${new Date(project.created_at).toLocaleString('th-TH')}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                ${project.description ? `
                <div class="mt-3">
                    <h6>รายละเอียดเพิ่มเติม</h6>
                    <p class="text-muted">${project.description.replace(/\n/g, '<br>')}</p>
                </div>
                ` : ''}
            `;
            
            document.getElementById('projectModalContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('projectViewModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาดในการดึงข้อมูล');
        });
}
</script>

<div class="card">
    <div class="card-body">
        <h5>รายการโครงการ (ปีงบประมาณ ID: <?= (int) ($fiscalYearId ?? 0) ?>)</h5>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>รหัส</th>
                    <th>ชื่อ</th>
                    <th>สถานะ</th>
                    <th>งบประมาณที่ได้รับ</th>
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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

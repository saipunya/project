<?php
$isEdit = ($mode ?? 'create') === 'edit';
$report = $reportData['report'] ?? [];
$projectId = (int) ($report['project_id'] ?? ($projectId ?? 0));
$kpis = $kpis ?? [];
$activities = $activities ?? [];
$kpiProgressMap = [];
$activityUpdateMap = [];

foreach (($reportData['kpi_progress'] ?? []) as $row) {
    $kpiProgressMap[(int) $row['kpi_id']] = $row;
}

foreach (($reportData['activity_updates'] ?? []) as $row) {
    $activityUpdateMap[(int) $row['activity_id']] = $row;
}

$actionUrl = $isEdit ? '/reports/' . (int) ($report['id'] ?? 0) . '/update' : '/reports';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><?= $isEdit ? 'แก้ไขรายงานประจำเดือน' : 'ส่งรายงานประจำเดือน' ?></h3>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= htmlspecialchars($actionUrl, ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-4">
                <select class="form-select" name="project_id" id="project_select" required <?= $isEdit ? 'disabled' : '' ?>>
                    <option value="">เลือกโครงการ</option>
                    <?php foreach ($projects ?? [] as $project): ?>
                        <option value="<?= (int) $project['id'] ?>" <?= $projectId === (int) $project['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($project['fiscal_year_name'], ENT_QUOTES, 'UTF-8') ?> - 
                            <?= htmlspecialchars($project['code'], ENT_QUOTES, 'UTF-8') ?> : 
                            <?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($isEdit): ?>
                    <input type="hidden" name="project_id" value="<?= htmlspecialchars((string) $projectId, ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>
            </div>
            <div class="col-md-2"><input class="form-control" type="number" min="1" max="12" name="month" placeholder="เดือน" value="<?= htmlspecialchars((string) ($report['month'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required <?= $isEdit ? 'readonly' : '' ?>></div>
            <div class="col-md-2"><input class="form-control" type="number" name="year" placeholder="ปี" value="<?= htmlspecialchars((string) ($report['year'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required <?= $isEdit ? 'readonly' : '' ?>></div>

            <?php if (!$isEdit && $projectId === 0): ?>
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        โปรดเลือกโครงการเพื่อแสดงตัวชี้วัดและกิจกรรมสำหรับรายงาน
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-12">
                <div class="alert alert-warning mb-0">
                    การรายงานงบประมาณย้ายไปที่เมนู <strong>รายงานงบประมาณ</strong>
                </div>
            </div>

            <div class="col-12">
                <h5 class="mb-2">ความก้าวหน้าของ KPI</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle" id="kpiTable">
                        <thead class="table-light">
                        <tr>
                            <th>KPI</th>
                            <th>ประเภท</th>
                            <th>ค่าเป้าหมาย</th>
                            <th>ค่าความก้าวหน้า</th>
                            <th>อัปเดตข้อความ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($kpis === []): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">ไม่พบ KPI สำหรับโครงการนี้</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($kpis as $kpi): ?>
                                <?php $progress = $kpiProgressMap[(int) $kpi['id']] ?? []; ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($kpi['name'], ENT_QUOTES, 'UTF-8') ?>
                                        <input type="hidden" name="kpi_ids[]" value="<?= (int) $kpi['id'] ?>">
                                    </td>
                                    <td><?= htmlspecialchars($kpi['type'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <?= $kpi['target_value'] !== null ? number_format((float) $kpi['target_value'], 2) : '-' ?>
                                        <?= htmlspecialchars((string) ($kpi['unit'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                    <td>
                                        <input
                                            class="form-control form-control-sm"
                                            type="number"
                                            step="0.01"
                                            name="kpi_incremental_values[]"
                                            value="<?= htmlspecialchars((string) ($progress['incremental_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                            <?= $kpi['type'] === 'text' ? 'readonly' : '' ?>
                                        >
                                    </td>
                                    <td>
                                        <input
                                            class="form-control form-control-sm"
                                            type="text"
                                            name="kpi_text_values[]"
                                            value="<?= htmlspecialchars((string) ($progress['text_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                            <?= $kpi['type'] !== 'text' ? 'placeholder="ใส่หรือเว้นว่างได้"' : 'placeholder="อัปเดตเป็นข้อความ"' ?>
                                        >
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-12">
                <h5 class="mb-2">อัปเดตสถานะกิจกรรม</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle" id="activityTable">
                        <thead class="table-light">
                        <tr>
                            <th>กิจกรรม</th>
                            <th>สถานะ</th>
                            <th>ความคืบหน้า %</th>
                            <th>บันทึกอัปเดต</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($activities === []): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">ไม่พบกิจกรรมสำหรับโครงการนี้</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($activities as $activity): ?>
                                <?php $update = $activityUpdateMap[(int) $activity['id']] ?? []; ?>
                                <?php $selectedStatus = (string) ($update['status'] ?? $activity['status'] ?? 'NOT_STARTED'); ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($activity['name'], ENT_QUOTES, 'UTF-8') ?>
                                        <input type="hidden" name="activity_ids[]" value="<?= (int) $activity['id'] ?>">
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm" name="activity_statuses[]" required>
                                            <option value="NOT_STARTED" <?= $selectedStatus === 'NOT_STARTED' ? 'selected' : '' ?>>ยังไม่เริ่ม</option>
                                            <option value="IN_PROGRESS" <?= $selectedStatus === 'IN_PROGRESS' ? 'selected' : '' ?>>กำลังดำเนินการ</option>
                                            <option value="COMPLETED" <?= $selectedStatus === 'COMPLETED' ? 'selected' : '' ?>>เสร็จสิ้น</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm" type="number" min="0" max="100" step="0.01" name="activity_progress_percents[]" value="<?= htmlspecialchars((string) ($update['progress_percent'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm" type="text" name="activity_update_notes[]" value="<?= htmlspecialchars((string) ($update['update_note'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="ใส่หรือเว้นว่างได้">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-12"><textarea class="form-control" name="notes" placeholder="บันทึกเพิ่มเติม"><?= htmlspecialchars((string) ($report['notes'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></div>
            <div class="col-md-6"><input class="form-control" type="file" name="attachment"></div>
            <div class="col-md-6 d-flex align-items-center">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="lock_after_submit" id="lock_after_submit" <?= !empty($report['is_locked']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="lock_after_submit">ล็อกหลังส่งรายงาน</label>
                </div>
            </div>

            <div class="col-12"><button class="btn btn-success" type="submit"><?= $isEdit ? 'อัปเดตรายงาน' : 'ส่งรายงาน' ?></button></div>
        </form>
    </div>
</div>

<?php if (!$isEdit): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const projectSelect = document.getElementById('project_select');
    const kpiTableBody = document.querySelector('#kpiTable tbody');
    const activityTableBody = document.querySelector('#activityTable tbody');
    
    if (!projectSelect) return;
    
    projectSelect.addEventListener('change', function() {
        const projectId = parseInt(this.value);
        if (!projectId) {
            clearTables();
            return;
        }
        
        fetch(`/reports/load-project-data?project_id=${projectId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }
                updateKpiTable(data.kpis || []);
                updateActivityTable(data.activities || []);
            })
            .catch(error => console.error('Error loading project data:', error));
    });
    
    function updateKpiTable(kpis) {
        if (!kpiTableBody) return;
        
        if (kpis.length === 0) {
            kpiTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">ไม่พบ KPI สำหรับโครงการนี้</td></tr>';
            return;
        }
        
        let html = '';
        kpis.forEach((kpi, index) => {
            html += `
                <tr>
                    <td>
                        ${kpi.name.replace(/</g, '&lt;').replace(/>/g, '&gt;')}
                        <input type="hidden" name="kpi_ids[]" value="${kpi.id}">
                    </td>
                    <td>${kpi.type}</td>
                    <td>
                        ${kpi.target_value !== null ? parseFloat(kpi.target_value).toFixed(2) : '-'}
                        ${kpi.unit || ''}
                    </td>
                    <td>
                        <input
                            class="form-control form-control-sm"
                            type="number"
                            step="0.01"
                            name="kpi_incremental_values[]"
                            ${kpi.type === 'text' ? 'readonly' : ''}
                        >
                    </td>
                    <td>
                        <input
                            class="form-control form-control-sm"
                            type="text"
                            name="kpi_text_values[]"
                            placeholder="${kpi.type !== 'text' ? 'ใส่หรือเว้นว่างได้' : 'อัปเดตเป็นข้อความ'}"
                        >
                    </td>
                </tr>
            `;
        });
        kpiTableBody.innerHTML = html;
    }
    
    function updateActivityTable(activities) {
        if (!activityTableBody) return;
        
        if (activities.length === 0) {
            activityTableBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">ไม่พบกิจกรรมสำหรับโครงการนี้</td></tr>';
            return;
        }
        
        let html = '';
        activities.forEach(activity => {
            html += `
                <tr>
                    <td>
                        ${activity.name.replace(/</g, '&lt;').replace(/>/g, '&gt;')}
                        <input type="hidden" name="activity_ids[]" value="${activity.id}">
                    </td>
                    <td>
                        <select class="form-select form-select-sm" name="activity_statuses[]" required>
                            <option value="NOT_STARTED" ${activity.status === 'NOT_STARTED' ? 'selected' : ''}>ยังไม่เริ่ม</option>
                            <option value="IN_PROGRESS" ${activity.status === 'IN_PROGRESS' ? 'selected' : ''}>กำลังดำเนินการ</option>
                            <option value="COMPLETED" ${activity.status === 'COMPLETED' ? 'selected' : ''}>เสร็จสิ้น</option>
                        </select>
                    </td>
                    <td>
                        <input class="form-control form-control-sm" type="number" min="0" max="100" step="0.01" name="activity_progress_percents[]" value="">
                    </td>
                    <td>
                        <input class="form-control form-control-sm" type="text" name="activity_update_notes[]" placeholder="ใส่หรือเว้นว่างได้">
                    </td>
                </tr>
            `;
        });
        activityTableBody.innerHTML = html;
    }
    
    function clearTables() {
        if (kpiTableBody) {
            kpiTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">เลือกโครงการเพื่อแสดง KPI</td></tr>';
        }
        if (activityTableBody) {
            activityTableBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">เลือกโครงการเพื่อแสดงกิจกรรม</td></tr>';
        }
    }
});
</script>
<?php endif; ?>

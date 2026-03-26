<?php
$report = $reportData['report'] ?? null;
$budget = $reportData['budget_usage'] ?? ['expense_amount' => '', 'expense_note' => ''];
$selectedProjectId = (int) ($projectId ?? ($report['project_id'] ?? 0));
$selectedMonth = (int) ($month ?? ($report['month'] ?? 0));
$selectedYear = (int) ($year ?? ($report['year'] ?? 0));
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>รายงานงบประมาณ</h3>
    </div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/budget-reports" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">โครงการ</label>
                <select class="form-select" name="project_id" required>
                    <option value="">เลือกโครงการ</option>
                    <?php foreach ($projects ?? [] as $project): ?>
                        <option value="<?= (int) $project['id'] ?>" <?= $selectedProjectId === (int) $project['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($project['fiscal_year_name'], ENT_QUOTES, 'UTF-8') ?> -
                            <?= htmlspecialchars($project['code'], ENT_QUOTES, 'UTF-8') ?> :
                            <?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">เดือน</label>
                <input class="form-control" type="number" min="1" max="12" name="month" value="<?= htmlspecialchars((string) $selectedMonth, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">ปี</label>
                <input class="form-control" type="number" name="year" value="<?= htmlspecialchars((string) $selectedYear, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">จำนวนเงินใช้จ่าย</label>
                <input class="form-control" type="number" step="0.01" min="0" name="expense_amount" value="<?= htmlspecialchars((string) ($budget['expense_amount'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div class="col-12">
                <label class="form-label">หมายเหตุการใช้จ่าย</label>
                <textarea class="form-control" name="expense_note" rows="3" placeholder="บันทึกหรือรายละเอียดงบประมาณ"><?= htmlspecialchars((string) ($budget['expense_note'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <div class="col-12">
                <div class="alert alert-info mb-0">
                    ใช้เมนูนี้สำหรับรายงานงบประมาณประจำเดือนเท่านั้น ส่วนรายงาน KPI และกิจกรรมให้ใช้งานที่เมนูรายงานประจำเดือน
                </div>
            </div>

            <div class="col-12">
                <button class="btn btn-success" type="submit">บันทึกรายงานงบประมาณ</button>
            </div>
        </form>
    </div>
</div>

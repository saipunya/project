<?php
$totalProjects = count($summary['projects'] ?? []);
$completedProjects = count(array_filter($summary['projects'] ?? [], fn($p) => $p['status'] === 'เสร็จสิ้น'));
$inProgressProjects = count(array_filter($summary['projects'] ?? [], fn($p) => $p['status'] === 'กำลังดำเนินการ'));
$kpiAchievement = (float) ($summary['kpi_achievement_percent'] ?? 0);

$kpiColor = match(true) {
    $kpiAchievement >= 80 => 'success',
    $kpiAchievement >= 60 => 'primary',
    $kpiAchievement >= 40 => 'warning',
    default => 'danger'
};
?>

<div class="dashboard-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold mb-2">
                <i class="bi bi-graph-up-arrow me-2"></i>แดชบอร์ดผู้บริหาร
            </h1>
            <p class="mb-0 opacity-75">ภาพรวม KPI และความคืบหน้าของโครงการที่สำคัญ</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-start border-4 border-<?= $kpiColor ?>">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-card-icon text-<?= $kpiColor ?>">
                            <i class="bi bi-trophy"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">ความสำเร็จ KPI</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($kpiAchievement, 1) ?>%</h3>
                        <small class="text-<?= $kpiColor ?>">
                            <?= $kpiAchievement >= 80 ? 'ดีมาก' : ($kpiAchievement >= 60 ? 'ดี' : ($kpiAchievement >= 40 ? 'ปานกลาง' : 'ต้องปรับปรุง')) ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-start border-4 border-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-card-icon text-primary">
                            <i class="bi bi-folder"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">โครงการทั้งหมด</h6>
                        <h3 class="fw-bold mb-0"><?= $totalProjects ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-start border-4 border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-card-icon text-info">
                            <i class="bi bi-arrow-right-circle"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">กำลังดำเนินการ</h6>
                        <h3 class="fw-bold mb-0"><?= $inProgressProjects ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-start border-4 border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-card-icon text-success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">เสร็จสิ้น</h6>
                        <h3 class="fw-bold mb-0"><?= $completedProjects ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($kpiAchievement > 0): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-panel">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-bar-chart me-2"></i>ระดับความสำเร็จ KPI
                </h5>
                <div class="progress" style="height: 2rem;">
                    <div class="progress-bar bg-<?= $kpiColor ?>" role="progressbar" style="width: <?= min(100, $kpiAchievement) ?>%" aria-valuenow="<?= $kpiAchievement ?>" aria-valuemin="0" aria-valuemax="100">
                        <?= number_format($kpiAchievement, 1) ?>%
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-2 text-muted small">
                    <span>0%</span>
                    <span>50%</span>
                    <span>100%</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="card bg-panel">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-table me-2"></i>ภาพรวมโครงการ
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">โครงการ</th>
                        <th>สถานะ</th>
                        <th>ใช้งบประมาณ</th>
                        <th style="width: 150px;">ความคืบหน้า</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($summary['projects'] as $project): ?>
                        <?php $budgetPercent = (float) ($project['budget_used_percent'] ?? 0); ?>
                        <tr>
                            <td class="ps-4 fw-medium"><?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php
                                $status = $project['status'];
                                $badgeClass = match($status) {
                                    'เสร็จสิ้น' => 'bg-success',
                                    'กำลังดำเนินการ' => 'bg-primary',
                                    'รอดำเนินการ' => 'bg-warning text-dark',
                                    'ยกเลิก' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span>
                            </td>
                            <td>
                                <span class="fw-semibold"><?= number_format($budgetPercent, 1) ?>%</span>
                            </td>
                            <td>
                                <div class="progress" style="height: 0.5rem;">
                                    <div class="progress-bar <?= $budgetPercent > 90 ? 'bg-danger' : ($budgetPercent > 70 ? 'bg-warning' : 'bg-success') ?>" role="progressbar" style="width: <?= min(100, $budgetPercent) ?>%"></div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($summary['projects'])): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-inbox mb-2 d-block" style="font-size: 2rem;"></i>
                                ยังไม่มีข้อมูลโครงการ
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

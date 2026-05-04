<?php
use App\Services\ProjectService;

require_once __DIR__ . '/../../app/services/ProjectService.php';

$projectService = new ProjectService();
$projectSummaries = $projectService->getProjectSummaries();

$totalProjects = count($projectSummaries);
$totalBudget = array_sum(array_map(fn($p) => $p['allocated_budget'] ?? 0, $projectSummaries));
$completedProjects = count(array_filter($projectSummaries, fn($p) => ($p['status'] ?? '') === 'เสร็จสิ้น'));
$inProgressProjects = count(array_filter($projectSummaries, fn($p) => ($p['status'] ?? '') === 'กำลังดำเนินการ'));
?>

<div class="dashboard-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold mb-2">
                <i class="bi bi-speedometer2 me-2"></i>แดชบอร์ดผู้ดูแลระบบ
            </h1>
            <p class="mb-0 opacity-75">ภาพรวมระบบและเมนูหลักสำหรับการจัดการโครงการ</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="/profile" class="btn btn-light me-2">
                <i class="bi bi-person me-1"></i>โปรไฟล์
            </a>
            <a href="/summary" class="btn btn-outline-light">
                <i class="bi bi-pie-chart me-1"></i>สรุปโครงการ
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
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
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-card-icon text-success">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">งบประมาณรวม</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($totalBudget, 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100">
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
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-card-icon text-warning">
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

<h5 class="fw-bold mb-3">
    <i class="bi bi-lightning me-2"></i>ทางลัด
</h5>
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="/projects" class="text-decoration-none">
            <div class="quick-action-card">
                <div class="quick-action-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-folder-plus"></i>
                </div>
                <h6 class="fw-semibold mb-1">โครงการ</h6>
                <small class="text-muted">จัดการโครงการ</small>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="/kpis" class="text-decoration-none">
            <div class="quick-action-card">
                <div class="quick-action-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-graph-up"></i>
                </div>
                <h6 class="fw-semibold mb-1">KPI</h6>
                <small class="text-muted">ตัวชี้วัด</small>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="/activities" class="text-decoration-none">
            <div class="quick-action-card">
                <div class="quick-action-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-list-check"></i>
                </div>
                <h6 class="fw-semibold mb-1">กิจกรรม</h6>
                <small class="text-muted">ติดตามกิจกรรม</small>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="/reports/create" class="text-decoration-none">
            <div class="quick-action-card">
                <div class="quick-action-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h6 class="fw-semibold mb-1">รายงาน</h6>
                <small class="text-muted">รายงานเดือน</small>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="/budget-reports/create" class="text-decoration-none">
            <div class="quick-action-card">
                <div class="quick-action-icon bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-receipt"></i>
                </div>
                <h6 class="fw-semibold mb-1">งบประมาณ</h6>
                <small class="text-muted">รายงานงบ</small>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="/plans/create" class="text-decoration-none">
            <div class="quick-action-card">
                <div class="quick-action-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="bi bi-kanban"></i>
                </div>
                <h6 class="fw-semibold mb-1">แผน</h6>
                <small class="text-muted">สร้างแผน</small>
            </div>
        </a>
    </div>
</div>

<h5 class="fw-bold mb-3">
    <i class="bi bi-clock-history me-2"></i>โครงการล่าสุด
</h5>
<div class="card bg-panel">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ชื่อโครงการ</th>
                        <th>สถานะ</th>
                        <th>วันที่เริ่ม</th>
                        <th>วันที่สิ้นสุด</th>
                        <th class="text-end pe-4">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($projectSummaries, 0, 10) as $summary): ?>
                        <tr>
                            <td class="ps-4 fw-medium"><?= htmlspecialchars($summary['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php
                                $status = $summary['status'] ?? '';
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
                            <td><?= htmlspecialchars($summary['start_date'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($summary['end_date'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-end pe-4">
                                <a href="/projects/<?= $summary['id'] ?? '' ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($projectSummaries)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
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

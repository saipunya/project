<?php

declare(strict_types=1);

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container mt-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold">รายละเอียดโครงการ</h1>
            <p class="text-muted mb-0">ดูข้อมูลสำคัญของโครงการทั้งหมดในหน้าเดียว</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="/projects" class="btn btn-outline-secondary">กลับไปยังรายการโครงการ</a>
        </div>
    </div>

    <div class="card bg-panel">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><?= htmlspecialchars($project['code'], ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></h5>
        </div>
        <div class="card-body">
            <div class="row gy-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="card-title">ข้อมูลทั่วไป</h6>
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>รหัสโครงการ:</strong></td>
                                    <td><?= htmlspecialchars($project['code'], ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>ชื่อโครงการ:</strong></td>
                                    <td><?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>แผนงาน:</strong></td>
                                    <td>
                                        <?php if ($plan): ?>
                                            <?= htmlspecialchars($plan['fiscal_year_name'], ENT_QUOTES, 'UTF-8') ?> -
                                            <?= htmlspecialchars($plan['code'], ENT_QUOTES, 'UTF-8') ?> :
                                            <?= htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>สถานะ:</strong></td>
                                    <td>
                                        <span class="badge bg-<?= $project['status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= $project['status'] === 'active' ? 'ดำเนินการ' : 'ไม่ดำเนินการ' ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="card-title">ข้อมูลงบประมาณ</h6>
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>งบประมาณที่ได้รับ:</strong></td>
                                    <td><?= number_format((float) $project['allocated_budget'], 2) ?> บาท</td>
                                </tr>
                                <tr>
                                    <td><strong>วันที่เริ่มต้น:</strong></td>
                                    <td><?= $project['start_date'] ? date('d/m/Y', strtotime($project['start_date'])) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>วันที่สิ้นสุด:</strong></td>
                                    <td><?= $project['end_date'] ? date('d/m/Y', strtotime($project['end_date'])) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>วันที่สร้าง:</strong></td>
                                    <td><?= date('d/m/Y H:i', strtotime($project['created_at'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($project['description'])): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title">รายละเอียดเพิ่มเติม</h6>
                        <p class="text-muted mb-0"><?= nl2br(htmlspecialchars($project['description'], ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

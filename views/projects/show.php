<?php

declare(strict_types=1);

require_once __DIR__ . '/../layout/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>รายละเอียดโครงการ</h2>
        <a href="/projects" class="btn btn-outline-secondary">กลับรายการโครงการ</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><?= htmlspecialchars($project['code'], ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
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
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>งบประมาณที่ได้รับ:</strong></td>
                            <td><?= number_format($project['allocated_budget'], 2) ?> บาท</td>
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
            
            <?php if (!empty($project['description'])): ?>
            <div class="mt-3">
                <h6>รายละเอียดเพิ่มเติม</h6>
                <p class="text-muted"><?= nl2br(htmlspecialchars($project['description'], ENT_QUOTES, 'UTF-8')) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

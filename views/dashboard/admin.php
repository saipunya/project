<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>แดชบอร์ดผู้ดูแลระบบ</h3>
</div>

<div class="row g-3 mb-3">
    <?php foreach ($summary['status_breakdown'] as $item): ?>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">สถานะ</h6>
                    <h5><?= htmlspecialchars($item['project_status'], ENT_QUOTES, 'UTF-8') ?></h5>
                    <div class="display-6"><?= (int) $item['total'] ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5>การใช้งบประมาณ</h5>
                <p>งบประมาณทั้งหมด: <strong><?= number_format((float) $summary['budget']['total_allocated'], 2) ?></strong></p>
                <p>ใช้ไปแล้ว: <strong><?= number_format((float) $summary['budget']['total_used'], 2) ?></strong></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5>แนวโน้มรายเดือน</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($summary['monthly_trend'] as $trend): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?= (int) $trend['month'] ?>/<?= (int) $trend['year'] ?></span>
                            <span><?= number_format((float) $trend['monthly_expense'], 2) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

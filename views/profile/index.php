<div class="row align-items-center mb-4">
    <div class="col-lg-8">
        <h1 class="display-6 fw-bold">
            <i class="bi bi-person-circle me-2 text-primary"></i>ข้อมูลส่วนบุคคล
        </h1>
        <p class="text-muted mb-0">ดูข้อมูลผู้ใช้และเมนูที่คุณสามารถเข้าถึงได้</p>
    </div>
    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <a href="/dashboard" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i>กลับแดชบอร์ด
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="bi bi-person-fill text-primary" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-1"><?= htmlspecialchars($user['full_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></h4>
                <p class="text-muted mb-3">@<?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>

                <?php
                $role = $user['role_name'] ?? $user['role'] ?? '';
                $roleBadge = match($role) {
                    'ADMIN' => 'bg-danger',
                    'STAFF' => 'bg-primary',
                    'EXECUTIVE' => 'bg-info',
                    default => 'bg-secondary'
                };
                $isActive = (bool) ($user['is_active'] ?? 0);
                ?>

                <div class="mb-3">
                    <span class="badge <?= $roleBadge ?> rounded-pill px-3 py-2"><?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8') ?></span>
                </div>

                <hr class="my-4">

                <div class="text-start">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">สถานะ</span>
                        <span class="badge <?= $isActive ? 'bg-success' : 'bg-secondary' ?> rounded-pill">
                            <i class="bi bi-<?= $isActive ? 'check-circle' : 'x-circle' ?> me-1"></i>
                            <?= $isActive ? 'ใช้งานได้' : 'ถูกปิดใช้งาน' ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">เข้าสู่ระบบล่าสุด</span>
                        <span class="small fw-medium"><?= htmlspecialchars($user['last_login_at'] ?? 'ไม่พบข้อมูล', ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-grid me-2"></i>เมนูที่คุณสามารถใช้งานได้
                </h5>
            </div>
            <div class="card-body p-4">
                <?php foreach ($menuItems as $index => $group): ?>
                    <div class="mb-4 <?= $index === count($menuItems) - 1 ? 'mb-0' : '' ?>">
                        <h6 class="fw-bold mb-3 text-muted small text-uppercase ls-1">
                            <?= htmlspecialchars($group['group'], ENT_QUOTES, 'UTF-8') ?>
                        </h6>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2">
                            <?php foreach ($group['items'] as $item): ?>
                                <div class="col">
                                    <a href="<?= htmlspecialchars($item['path'], ENT_QUOTES, 'UTF-8') ?>" class="menu-item-link btn btn-light w-100 text-start d-flex align-items-center p-3">
                                        <span class="menu-item-label fw-medium"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                                        <i class="bi bi-chevron-right ms-auto text-muted small"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($menuItems)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox mb-2 d-block" style="font-size: 2rem;"></i>
                        ไม่มีเมนูที่แสดง
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.menu-item-link {
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.menu-item-link:hover {
    background: #f0fdfa;
    border-color: var(--primary);
    color: var(--primary-dark);
    transform: translateX(4px);
}

.menu-item-link:hover i {
    color: var(--primary-dark) !important;
}

.ls-1 {
    letter-spacing: 0.05em;
}
</style>

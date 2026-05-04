<?php

use App\Helpers\Auth;

$user = Auth::user();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$isLandingPage = ($currentPath === '/' || $currentPath === '');
$isAuthPage = in_array($currentPath, ['/login', '/register'], true);
$useLandingLayout = $isLandingPage || $isAuthPage || (($layout ?? null) === 'landing');

function navActive(string $path, string $currentPath): string
{
    return $currentPath === $path || str_starts_with($currentPath, $path . '/') ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'ระบบติดตามโครงการ', ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/public/styles.css" rel="stylesheet">
</head>
<body>

<?php if ($useLandingLayout): ?>

<nav class="navbar navbar-expand-lg navbar-main fixed-top">
    <div class="container">
        <a class="navbar-brand text-white" href="/">
            <i class="bi bi-bar-chart-line me-2"></i>ระบบติดตามโครงการ
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= navActive('/summary', $currentPath) ?>" href="/summary">สรุปภาพรวม</a>
                </li>
                <?php if ($user): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= navActive('/dashboard', $currentPath) ?>" href="/dashboard">แดชบอร์ด</a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="d-flex gap-2 ms-lg-3 mt-3 mt-lg-0">
                <?php if ($user): ?>
                    <a href="/dashboard" class="btn btn-light btn-sm">เข้าสู่ระบบจัดการ</a>
                    <form method="POST" action="/logout" class="mb-0">
                        <button class="btn btn-outline-light btn-sm" type="submit">ออกจากระบบ</button>
                    </form>
                <?php else: ?>
                    <a href="/login" class="btn btn-light btn-sm">เข้าสู่ระบบ</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<?php else: ?>

<nav class="navbar navbar-expand-lg navbar-main">
    <div class="container">
        <a class="navbar-brand text-white" href="<?= $user ? '/dashboard' : '/' ?>">
            <i class="bi bi-bar-chart-line me-2"></i>ระบบติดตามโครงการ
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($user): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= navActive('/dashboard', $currentPath) ?>" href="/dashboard">
                            <i class="bi bi-speedometer2 me-1"></i>แดชบอร์ด
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= navActive('/summary', $currentPath) ?>" href="/summary">
                            <i class="bi bi-pie-chart me-1"></i>สรุปโครงการ
                        </a>
                    </li>

                    <?php if (in_array($user['role'], ['ADMIN', 'STAFF'], true)): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= str_starts_with($currentPath, '/projects') ? 'active' : '' ?>" href="/projects">
                                <i class="bi bi-folder me-1"></i>โครงการ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_starts_with($currentPath, '/kpis') ? 'active' : '' ?>" href="/kpis">
                                <i class="bi bi-graph-up me-1"></i>KPI
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_starts_with($currentPath, '/activities') ? 'active' : '' ?>" href="/activities">
                                <i class="bi bi-list-check me-1"></i>กิจกรรม
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_starts_with($currentPath, '/reports') ? 'active' : '' ?>" href="/reports/create">
                                <i class="bi bi-file-earmark-text me-1"></i>รายงาน
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_starts_with($currentPath, '/budget-reports') ? 'active' : '' ?>" href="/budget-reports/create">
                                <i class="bi bi-cash-stack me-1"></i>งบประมาณ
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($user['role'] === 'ADMIN'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-gear me-1"></i>จัดการระบบ
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/plans/create"><i class="bi bi-kanban me-2"></i>สร้างแผน</a></li>
                                <li><a class="dropdown-item" href="/register"><i class="bi bi-person-plus me-2"></i>ลงทะเบียนผู้ใช้</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/summary">สรุปภาพรวม</a>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if ($user): ?>
                <div class="d-flex align-items-center gap-3">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8') ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted"><small><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></small></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>โปรไฟล์</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="/logout">
                                    <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php if (!$isAuthPage): ?>
<div class="container pb-5 mt-4">
<?php endif; ?>

<?php endif; ?>

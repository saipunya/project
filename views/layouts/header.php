<?php

use App\Helpers\Auth;

$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'ระบบติดตามโครงการ', ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/dashboard">ระบบติดตามโครงการ</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php if ($user): ?>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">แดชบอร์ด</a>
                    </li>

                    <?php if (in_array($user['role'], ['ADMIN', 'STAFF'], true)): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/projects">โครงการ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/kpis">ตัวชี้วัด (KPI)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/activities">กิจกรรม</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/reports/create">รายงานประจำเดือน</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/budget-reports/create">รายงานงบประมาณ</a>
                        </li>
                    <?php endif; ?>

                    <?php if ($user['role'] === 'ADMIN'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/plans/create">สร้างแผน</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">ลงทะเบียนผู้ใช้</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="d-flex align-items-center text-white gap-3">
                    <span><?= htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?>)</span>
                    <form method="POST" action="/logout" class="mb-0">
                        <button class="btn btn-sm btn-outline-light" type="submit">ออกจากระบบ</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</nav>
<div class="container pb-5">

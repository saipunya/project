<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$isLandingPage = ($currentPath === '/' || $currentPath === '');
$isAuthPage = in_array($currentPath, ['/login', '/register'], true);
$useLandingLayout = $isLandingPage || $isAuthPage || (($layout ?? null) === 'landing');
?>

<?php if (!$useLandingLayout && !$isAuthPage): ?>
</div>
<?php endif; ?>

<footer class="footer-custom">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <h5><i class="bi bi-bar-chart-line me-2"></i>ระบบติดตามโครงการ</h5>
                <p class="text-white-50 mb-3">ระบบจัดการและติดตามโครงการอย่างมีประสิทธิภาพ พร้อมรายงานและวิเคราะห์ข้อมูลแบบเรียลไทม์</p>
            </div>
            <div class="col-lg-2 col-md-6 col-6">
                <h5>เมนูหลัก</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/summary">สรุปโครงการ</a></li>
                    <?php if (!empty($user)): ?>
                        <li class="mb-2"><a href="/dashboard">แดชบอร์ด</a></li>
                        <li class="mb-2"><a href="/projects">โครงการ</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 col-6">
                <h5>จัดการ</h5>
                <ul class="list-unstyled">
                    <?php if (!empty($user) && in_array($user['role'], ['ADMIN', 'STAFF'], true)): ?>
                        <li class="mb-2"><a href="/kpis">ตัวชี้วัด KPI</a></li>
                        <li class="mb-2"><a href="/activities">กิจกรรม</a></li>
                        <li class="mb-2"><a href="/reports/create">รายงานประจำเดือน</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6">
                <h5>ติดต่อ</h5>
                <ul class="list-unstyled text-white-50">
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i>admin@project-tracker.go.th</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i>02-xxx-xxxx</li>
                    <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>กรุงเทพมหานคร</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <p class="text-white-50 mb-0">&copy; <?= date('Y') ?> ระบบติดตามโครงการ | พัฒนาด้วย Bootstrap 5.3</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

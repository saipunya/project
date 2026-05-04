<div class="login-wrapper">
    <div class="login-container">
        <div class="login-card">
            <div class="row g-0">
                <div class="col-lg-5 d-none d-lg-flex">
                    <div class="login-left w-100">
                        <div class="mb-4">
                            <i class="bi bi-bar-chart-line" style="font-size: 3rem;"></i>
                        </div>
                        <h2>ระบบติดตามโครงการ</h2>
                        <p class="mb-4">แพลตฟอร์มครบวงจรสำหรับจัดการและติดตามโครงการอย่างเป็นระบบ</p>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill me-2"></i>จัดการโครงการและแผนงาน
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill me-2"></i>ติดตามตัวชี้วัด KPI
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill me-2"></i>รายงานงบประมาณและความคืบหน้า
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill me-2"></i>แดชบอร์ดสำหรับผู้บริหาร
                            </li>
                        </ul>
                        <div class="mt-auto pt-4">
                            <a href="/" class="btn btn-outline-light">
                                <i class="bi bi-arrow-left me-2"></i>กลับหน้าหลัก
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="login-right">
                        <div class="mb-4">
                            <h3 class="fw-bold">เข้าสู่ระบบ</h3>
                            <p class="text-muted">กรุณากรอกข้อมูลเพื่อเข้าสู่ระบบ</p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                <span><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success d-flex align-items-center">
                                <i class="bi bi-check-circle me-2"></i>
                                <span><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                        <?php endif; ?>

                        <form class="login-form" action="/login" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="username" name="username" placeholder="กรอกชื่อผู้ใช้" required autofocus>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">รหัสผ่าน</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-lock text-muted"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="กรอกรหัสผ่าน" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-4">
                                <i class="bi bi-box-arrow-in-right me-2"></i>เข้าสู่ระบบ
                            </button>
                        </form>

                        <div class="text-center">
                            <p class="text-muted mb-0">ยังไม่มีบัญชี? <a href="/register" class="fw-semibold">ติดต่อผู้ดูแลระบบ</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

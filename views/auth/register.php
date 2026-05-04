<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="card bg-panel">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="fw-bold">ลงทะเบียนผู้ใช้ใหม่</h3>
                    <p class="text-muted">กรอกข้อมูลเพื่อสร้างบัญชีผู้ใช้ใหม่</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <span><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/register">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">ชื่อ-นามสกุล</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-person text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="full_name" name="full_name" placeholder="กรอกชื่อ-นามสกุล" required autofocus>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">ชื่อผู้ใช้</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-at text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="username" name="username" placeholder="กรอกชื่อผู้ใช้" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">รหัสผ่าน</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="อย่างน้อย 8 ตัวอักษร" minlength="8" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-lock-fill text-muted"></i>
                            </span>
                            <input type="password" class="form-control border-start-0" id="confirm_password" name="confirm_password" placeholder="กรอกรหัสผ่านอีกครั้ง" minlength="8" required>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 mb-3" type="submit">
                        <i class="bi bi-check-circle me-2"></i>สร้างบัญชี
                    </button>
                </form>

                <div class="text-center">
                    <a href="/login" class="text-muted">
                        <i class="bi bi-arrow-left me-1"></i>กลับสู่หน้าเข้าสู่ระบบ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

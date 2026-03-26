<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">ลงทะเบียน</h4>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <form method="POST" action="/register">
                    <div class="mb-3">
                        <label class="form-label">ชื่อ-นามสกุล</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">รหัสผ่าน</label>
                        <input type="password" name="password" class="form-control" minlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ยืนยันรหัสผ่าน</label>
                        <input type="password" name="confirm_password" class="form-control" minlength="8" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">สร้างบัญชี</button>
                </form>
                <div class="text-center mt-3">
                    <a href="/login">กลับสู่หน้าเข้าสู่ระบบ</a>
                </div>
            </div>
        </div>
    </div>
</div>

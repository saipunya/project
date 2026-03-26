<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">เข้าสู่ระบบ</h4>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <form method="POST" action="/login">
                    <div class="mb-3">
                        <label class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">รหัสผ่าน</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">เข้าสู่ระบบ</button>
                </form>
                <div class="text-center mt-3">
                    <a href="/register">สร้างบัญชีใหม่</a>
                </div>
            </div>
        </div>
    </div>
</div>

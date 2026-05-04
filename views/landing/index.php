<!-- Hero Section -->
<section class="landing-hero">
    <div class="container">
        <div class="row align-items-center hero-content">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <h1>ระบบติดตามและ<br>จัดการโครงการ</h1>
                <p class="mb-4">แพลตฟอร์มครบวงจรสำหรับติดตามความคืบหน้า งบประมาณ และผลลัพธ์ของโครงการอย่างเป็นระบบ พร้อมรายงานเชิงลึกสำหรับผู้บริหาร</p>
                <div class="d-flex flex-wrap gap-3">
                    <?php if ($user): ?>
                        <a href="/dashboard" class="btn btn-light btn-lg">
                            <i class="bi bi-speedometer2 me-2"></i>เข้าสู่แดชบอร์ด
                        </a>
                    <?php else: ?>
                        <a href="/login" class="btn btn-light btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>เข้าสู่ระบบ
                        </a>
                        <a href="/summary" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-pie-chart me-2"></i>ดูสรุปภาพรวม
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="bg-white bg-opacity-10 rounded-4 p-4 backdrop-blur">
                    <div class="row g-3 text-center">
                        <?php if (!empty($summary)): ?>
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-white bg-opacity-10">
                                    <div class="fs-2 fw-bold"><?= $summary['total_projects'] ?? 0 ?></div>
                                    <div class="small opacity-75">โครงการทั้งหมด</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-white bg-opacity-10">
                                    <div class="fs-2 fw-bold"><?= $summary['total_budget'] ?? '0' ?></div>
                                    <div class="small opacity-75">งบประมาณ (บาท)</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-white bg-opacity-10">
                                    <div class="fs-2 fw-bold"><?= $summary['total_kpis'] ?? 0 ?></div>
                                    <div class="small opacity-75">ตัวชี้วัด</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-white bg-opacity-10">
                                    <div class="fs-2 fw-bold"><?= $summary['kpi_achievement'] ?? '0' ?>%</div>
                                    <div class="small opacity-75">ผลสำเร็จ KPI</div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-white bg-opacity-10">
                                    <div class="fs-2 fw-bold">-</div>
                                    <div class="small opacity-75">โครงการทั้งหมด</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-white bg-opacity-10">
                                    <div class="fs-2 fw-bold">-</div>
                                    <div class="small opacity-75">งบประมาณ</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-white bg-opacity-10">
                                    <div class="fs-2 fw-bold">-</div>
                                    <div class="small opacity-75">ตัวชี้วัด</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-white bg-opacity-10">
                                    <div class="fs-2 fw-bold">-%</div>
                                    <div class="small opacity-75">ผลสำเร็จ KPI</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="section-title">คุณสมบัติของระบบ</h2>
            <p class="section-subtitle mx-auto" style="max-width: 600px;">เครื่องมือครบครันสำหรับการจัดการโครงการอย่างมีประสิทธิภาพ</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon feature-icon-primary">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <h5 class="fw-bold mb-3">จัดการโครงการ</h5>
                    <p class="text-muted mb-0">สร้างและจัดการโครงการ พร้อมติดตามสถานะและความคืบหน้าแบบเรียลไทม์</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon feature-icon-success">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h5 class="fw-bold mb-3">ตัวชี้วัด KPI</h5>
                    <p class="text-muted mb-0">กำหนดและติดตามตัวชี้วัดประสิทธิภาพ พร้อมคำนวณผลลัพธ์อัตโนมัติ</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon feature-icon-warning">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h5 class="fw-bold mb-3">ติดตามงบประมาณ</h5>
                    <p class="text-muted mb-0">บันทึกและวิเคราะห์การใช้จ่ายงบประมาณ พร้อมรายงานสรุปที่ชัดเจน</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon feature-icon-info">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                    </div>
                    <h5 class="fw-bold mb-3">รายงานประจำเดือน</h5>
                    <p class="text-muted mb-0">สร้างรายงานความคืบหน้ารายเดือน พร้อมแนบไฟล์เอกสารประกอบ</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon feature-icon-danger">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold mb-3">ระบบสิทธิ์</h5>
                    <p class="text-muted mb-0">จัดการสิทธิ์การเข้าถึงตามบทบาท ผู้ดูแล เจ้าหน้าที่ และผู้บริหาร</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon feature-icon-secondary">
                        <i class="bi bi-phone"></i>
                    </div>
                    <h5 class="fw-bold mb-3">รองรับทุกอุปกรณ์</h5>
                    <p class="text-muted mb-0">ใช้งานได้จากทุกที่ ทุกอุปกรณ์ ไม่ว่าจะเป็นคอมพิวเตอร์ แท็บเล็ต หรือมือถือ</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title">เริ่มต้นใช้งานระบบ</h2>
                <p class="section-subtitle mb-4">เข้าสู่ระบบเพื่อจัดการโครงการหรือดูสรุปภาพรวมแบบสาธารณะ</p>
                <?php if ($user): ?>
                    <a href="/dashboard" class="btn btn-primary btn-lg">
                        <i class="bi bi-speedometer2 me-2"></i>ไปยังแดชบอร์ด
                    </a>
                <?php else: ?>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="/login" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>เข้าสู่ระบบ
                        </a>
                        <a href="/summary" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-eye me-2"></i>ดูสรุปโครงการ
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

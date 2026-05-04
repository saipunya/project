<?php
$totalBudget = $summary['budget']['total_allocated'] ?? 0;
$usedBudget = $summary['budget']['total_used'] ?? 0;
$remainingBudget = max(0, $totalBudget - $usedBudget);
$budgetPercent = $totalBudget > 0 ? min(100, ($usedBudget / $totalBudget) * 100) : 0;

$statusLabels = [];
$statusData = [];
$statusColors = [];
foreach ($summary['status_breakdown'] as $item) {
    $statusLabels[] = $item['project_status'] ?? '';
    $statusData[] = (int) ($item['total'] ?? 0);
    $statusColors[] = match($item['project_status']) {
        'เสร็จสิ้น' => '#198754',
        'กำลังดำเนินการ' => '#0d6efd',
        'รอดำเนินการ' => '#ffc107',
        'ยกเลิก' => '#dc3545',
        default => '#6c757d'
    };
}
?>

<div class="row align-items-center mb-4">
  <div class="col-lg-8">
    <h1 class="display-6 fw-bold">
      <i class="bi bi-pie-chart me-2 text-primary"></i>สรุปโครงการ
    </h1>
    <p class="text-muted mb-0">ภาพรวมสถานะและงบประมาณของโครงการแบบสาธารณะ</p>
  </div>
  <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
    <a href="/" class="btn btn-outline-primary">
      <i class="bi bi-house me-1"></i>กลับหน้าหลัก
    </a>
  </div>
</div>

<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="card h-100 border-start border-4 border-success">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="stat-card-icon text-success">
              <i class="bi bi-wallet2"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="text-muted mb-1">งบประมาณทั้งหมด</h6>
            <h4 class="fw-bold mb-0 text-success"><?= number_format($totalBudget, 2) ?> บาท</h4>
            <small class="text-muted">จัดสรรแล้วทั้งหมด</small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card h-100 border-start border-4 border-danger">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="stat-card-icon text-danger">
              <i class="bi bi-receipt"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="text-muted mb-1">งบประมาณที่ใช้แล้ว</h6>
            <h4 class="fw-bold mb-0 text-danger"><?= number_format($usedBudget, 2) ?> บาท</h4>
            <small class="text-muted">ใช้งานไปแล้ว</small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card h-100 border-start border-4 border-primary">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="stat-card-icon text-primary">
              <i class="bi bi-piggy-bank"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="text-muted mb-1">งบประมาณคงเหลือ</h6>
            <h4 class="fw-bold mb-0 text-primary"><?= number_format($remainingBudget, 2) ?> บาท</h4>
            <small class="text-muted">ยอดคงเหลือ</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4 mb-4">
  <div class="col-lg-6">
    <div class="card bg-panel h-100">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
          <i class="bi bi-bar-chart me-2"></i>เปรียบเทียบงบประมาณ
        </h5>
      </div>
      <div class="card-body">
        <canvas id="budgetChart" height="250"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card bg-panel h-100">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
          <i class="bi bi-pie-chart me-2"></i>สัดส่วนการใช้งบประมาณ
        </h5>
      </div>
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-6">
            <canvas id="budgetDoughnut"></canvas>
          </div>
          <div class="col-md-6">
            <div class="d-flex flex-column gap-3">
              <div class="d-flex align-items-center">
                <span class="badge bg-danger rounded-circle me-2" style="width: 12px; height: 12px;"></span>
                <span class="text-muted">ใช้แล้ว</span>
                <span class="ms-auto fw-bold"><?= number_format($budgetPercent, 1) ?>%</span>
              </div>
              <div class="d-flex align-items-center">
                <span class="badge bg-success rounded-circle me-2" style="width: 12px; height: 12px;"></span>
                <span class="text-muted">คงเหลือ</span>
                <span class="ms-auto fw-bold"><?= number_format(100 - $budgetPercent, 1) ?>%</span>
              </div>
              <hr>
              <div>
                <small class="text-muted d-block">ใช้แล้ว</small>
                <span class="fw-semibold"><?= number_format($usedBudget, 2) ?> บาท</span>
              </div>
              <div>
                <small class="text-muted d-block">คงเหลือ</small>
                <span class="fw-semibold"><?= number_format($remainingBudget, 2) ?> บาท</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if (!empty($summary['status_breakdown'])): ?>
<div class="row g-4 mb-4">
  <div class="col-lg-5">
    <div class="card bg-panel h-100">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
          <i class="bi bi-flag me-2"></i>สถานะโครงการ
        </h5>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4">สถานะ</th>
              <th class="text-end pe-4">จำนวน</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($summary['status_breakdown'] as $item): ?>
            <tr>
              <td class="ps-4 fw-medium"><?= htmlspecialchars($item['project_status'] ?? '', ENT_QUOTES, 'UTF-8') ?>
              </td>
              <td class="text-end pe-4">
                <span
                  class="badge bg-primary rounded-pill"><?= htmlspecialchars((string) ($item['total'] ?? 0), ENT_QUOTES, 'UTF-8') ?></span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-7">
    <div class="card bg-panel h-100">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
          <i class="bi bi-graph-up me-2"></i>กราฟแสดงสถานะโครงการ
        </h5>
      </div>
      <div class="card-body">
        <canvas id="statusChart" height="200"></canvas>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="row mb-4">
  <div class="col-12">
    <div class="card bg-panel">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
          <i class="bi bi-clock-history me-2"></i>โครงการล่าสุด
        </h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">ชื่อโครงการ</th>
                <th>สถานะ</th>
                <th class="text-end pe-4">งบประมาณ</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($summary['recent_projects'] as $project): ?>
              <tr>
                <td class="ps-4 fw-medium"><?= htmlspecialchars($project['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <?php
                                        $status = $project['project_status'] ?? '';
                                        $badgeClass = match($status) {
                                            'เสร็จสิ้น' => 'bg-success',
                                            'กำลังดำเนินการ' => 'bg-primary',
                                            'รอดำเนินการ' => 'bg-warning text-dark',
                                            'ยกเลิก' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                  <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span>
                </td>
                <td class="text-end pe-4 fw-semibold">
                  <?= number_format((float) ($project['allocated_budget'] ?? 0), 2) ?> บาท</td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($summary['recent_projects'])): ?>
              <tr>
                <td colspan="3" class="text-center text-muted py-4">
                  <i class="bi bi-inbox mb-2 d-block" style="font-size: 2rem;"></i>
                  ไม่มีโครงการให้แสดง
                </td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  Chart.defaults.font.family = "'Inter', system-ui, -apple-system, sans-serif";

  const statusLabels = <?= json_encode($statusLabels) ?>;
  const statusData = <?= json_encode($statusData) ?>;
  const statusColors = <?= json_encode($statusColors) ?>;

  const budgetCtx = document.getElementById('budgetChart').getContext('2d');
  new Chart(budgetCtx, {
    type: 'bar',
    data: {
      labels: ['จัดสรร', 'ใช้แล้ว', 'คงเหลือ'],
      datasets: [{
        label: 'งบประมาณ (บาท)',
        data: [<?= $totalBudget ?>, <?= $usedBudget ?>, <?= $remainingBudget ?>],
        backgroundColor: [
          'rgba(13, 110, 253, 0.8)',
          'rgba(220, 53, 69, 0.8)',
          'rgba(25, 135, 84, 0.8)'
        ],
        borderColor: [
          'rgb(13, 110, 253)',
          'rgb(220, 53, 69)',
          'rgb(25, 135, 84)'
        ],
        borderWidth: 2,
        borderRadius: 8,
        maxBarThickness: 80
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return ' ' + Number(context.parsed.y).toLocaleString('th-TH', {
                minimumFractionDigits: 2
              }) + ' บาท';
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return Number(value).toLocaleString('th-TH', {
                minimumFractionDigits: 0
              });
            }
          }
        }
      }
    }
  });

  const doughnutCtx = document.getElementById('budgetDoughnut').getContext('2d');
  new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
      labels: ['ใช้แล้ว', 'คงเหลือ'],
      datasets: [{
        data: [<?= $budgetPercent ?>, <?= 100 - $budgetPercent ?>],
        backgroundColor: ['rgba(220, 53, 69, 0.8)', 'rgba(25, 135, 84, 0.8)'],
        borderColor: ['rgb(220, 53, 69)', 'rgb(25, 135, 84)'],
        borderWidth: 2,
        hoverOffset: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '65%',
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });

  const statusCtx = document.getElementById('statusChart');
  if (statusCtx && statusLabels.length > 0) {
    new Chart(statusCtx.getContext('2d'), {
      type: 'bar',
      data: {
        labels: statusLabels,
        datasets: [{
          label: 'จำนวนโครงการ',
          data: statusData,
          backgroundColor: statusColors.map(c => c + 'cc'),
          borderColor: statusColors,
          borderWidth: 2,
          borderRadius: 8,
          maxBarThickness: 60
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            }
          }
        }
      }
    });
  }
});
</script>

<div class="progress-bar bg-success" role="progressbar" style="width: <?= 100 - $budgetPercent ?>%"></div>
</div>
<div class="d-flex justify-content-between mt-2 text-muted small p-2">
  <span><i class="bi bi-circle-fill text-danger me-1"></i>ใช้งบ <?= number_format($budgetPercent, 1) ?>%</span>
  <span><i class="bi bi-circle-fill text-success me-1"></i>คงเหลือ <?= number_format(100 - $budgetPercent, 1) ?>%</span>
</div>
</div>
</div>
</div>
</div>

<div class="row g-4 mb-4 mx-2">
  <div class="col-lg-5">
    <div class="card bg-panel h-100">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
          <i class="bi bi-flag me-2"></i>สถานะโครงการ
        </h5>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4">สถานะ</th>
              <th class="text-end pe-4">จำนวน</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($summary['status_breakdown'] as $item): ?>
            <tr>
              <td class="ps-4 fw-medium"><?= htmlspecialchars($item['project_status'] ?? '', ENT_QUOTES, 'UTF-8') ?>
              </td>
              <td class="text-end pe-4">
                <span
                  class="badge bg-primary rounded-pill"><?= htmlspecialchars((string) ($item['total'] ?? 0), ENT_QUOTES, 'UTF-8') ?></span>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($summary['status_breakdown'])): ?>
            <tr>
              <td colspan="2" class="text-center text-muted py-4">ไม่มีข้อมูลโครงการ</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-7">
    <div class="card bg-panel h-100">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
          <i class="bi bi-clock-history me-2"></i>โครงการล่าสุด
        </h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">ชื่อโครงการ</th>
                <th>สถานะ</th>
                <th class="text-end pe-4">งบประมาณ</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($summary['recent_projects'] as $project): ?>
              <tr>
                <td class="ps-4 fw-medium"><?= htmlspecialchars($project['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <?php
                                        $status = $project['project_status'] ?? '';
                                        $badgeClass = match($status) {
                                            'เสร็จสิ้น' => 'bg-success',
                                            'กำลังดำเนินการ' => 'bg-primary',
                                            'รอดำเนินการ' => 'bg-warning text-dark',
                                            'ยกเลิก' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                  <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span>
                </td>
                <td class="text-end pe-4 fw-semibold">
                  <?= number_format((float) ($project['allocated_budget'] ?? 0), 2) ?> บาท</td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($summary['recent_projects'])): ?>
              <tr>
                <td colspan="3" class="text-center text-muted py-4">
                  <i class="bi bi-inbox mb-2 d-block" style="font-size: 2rem;"></i>
                  ไม่มีโครงการให้แสดง
                </td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
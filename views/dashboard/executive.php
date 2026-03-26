<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Executive Dashboard</h3>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5>KPI Achievement</h5>
        <div class="display-6"><?= number_format((float) $summary['kpi_achievement_percent'], 2) ?>%</div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>Project Overview</h5>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Project</th>
                    <th>Status</th>
                    <th>Budget Used (%)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($summary['projects'] as $project): ?>
                    <tr>
                        <td><?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($project['status'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= number_format((float) $project['budget_used_percent'], 2) ?>%</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>KPIs</h3>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5>Create KPI</h5>
        <form method="POST" action="/kpis" class="row g-3">
            <div class="col-md-2"><input class="form-control" type="number" name="project_id" value="<?= (int) ($projectId ?? 0) ?>" placeholder="Project ID" required></div>
            <div class="col-md-4"><input class="form-control" type="text" name="name" placeholder="KPI Name" required></div>
            <div class="col-md-2">
                <select class="form-select" name="type" required>
                    <option value="number">number</option>
                    <option value="percentage">percentage</option>
                    <option value="text">text</option>
                </select>
            </div>
            <div class="col-md-2"><input class="form-control" type="number" step="0.01" name="target_value" placeholder="Target"></div>
            <div class="col-md-2"><input class="form-control" type="text" name="unit" placeholder="Unit"></div>
            <div class="col-12"><button class="btn btn-primary" type="submit">Save KPI</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>KPI List</h5>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Target</th>
                    <th>Unit</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($kpis as $kpi): ?>
                    <tr>
                        <td><?= htmlspecialchars($kpi['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($kpi['type'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $kpi['target_value'] !== null ? number_format((float) $kpi['target_value'], 2) : '-' ?></td>
                        <td><?= htmlspecialchars((string) ($kpi['unit'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

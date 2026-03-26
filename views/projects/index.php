<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Projects</h3>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5>Create Project</h5>
        <form method="POST" action="/projects" class="row g-3">
            <div class="col-md-2"><input class="form-control" type="number" name="fiscal_year_id" placeholder="Fiscal Year ID" required></div>
            <div class="col-md-4">
                <select class="form-select" name="plan_id" required>
                    <option value="">Select Plan</option>
                    <?php foreach ($plans ?? [] as $plan): ?>
                        <option value="<?= (int) $plan['id'] ?>">
                            <?= htmlspecialchars($plan['fiscal_year_name'], ENT_QUOTES, 'UTF-8') ?> -
                            <?= htmlspecialchars($plan['code'], ENT_QUOTES, 'UTF-8') ?> :
                            <?= htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Plan codes are generated automatically as A, B, C, ...</div>
            </div>
            <div class="col-md-2"><input class="form-control" type="text" name="code" placeholder="Code" required></div>
            <div class="col-md-3"><input class="form-control" type="text" name="name" placeholder="Project Name" required></div>
            <div class="col-md-3"><input class="form-control" type="number" step="0.01" name="allocated_budget" placeholder="Allocated Budget" required></div>
            <div class="col-md-6"><textarea class="form-control" name="description" placeholder="Description"></textarea></div>
            <div class="col-md-3"><input class="form-control" type="date" name="start_date"></div>
            <div class="col-md-3"><input class="form-control" type="date" name="end_date"></div>
            <div class="col-12"><button class="btn btn-primary" type="submit">Save Project</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>Project List (FY ID: <?= (int) ($fiscalYearId ?? 0) ?>)</h5>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Allocated Budget</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?= htmlspecialchars($project['code'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($project['project_status'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= number_format((float) $project['allocated_budget'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

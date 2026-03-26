<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Activities</h3>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5>Create Activity</h5>
        <form method="POST" action="/activities" class="row g-3">
            <div class="col-md-2"><input class="form-control" type="number" name="project_id" value="<?= (int) ($projectId ?? 0) ?>" placeholder="Project ID" required></div>
            <div class="col-md-4"><input class="form-control" type="text" name="name" placeholder="Activity Name" required></div>
            <div class="col-md-2">
                <select class="form-select" name="status" required>
                    <option value="NOT_STARTED">NOT_STARTED</option>
                    <option value="IN_PROGRESS">IN_PROGRESS</option>
                    <option value="COMPLETED">COMPLETED</option>
                </select>
            </div>
            <div class="col-md-2"><input class="form-control" type="date" name="start_date" required></div>
            <div class="col-md-2"><input class="form-control" type="date" name="end_date" required></div>
            <div class="col-12"><textarea class="form-control" name="description" placeholder="Description"></textarea></div>
            <div class="col-12"><button class="btn btn-primary" type="submit">Save Activity</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>Activity List</h5>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Start</th>
                    <th>End</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td><?= htmlspecialchars($activity['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($activity['status'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($activity['start_date'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($activity['end_date'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

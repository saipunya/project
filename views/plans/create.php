<?php
$old = $old ?? [];
$canCreate = $canCreate ?? false;
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><?= $canCreate ? 'Create Plan' : 'Plans' ?></h3>
</div>

<?php if (!empty($createdCode)): ?>
    <div class="alert alert-success">
        Plan created successfully with code <strong><?= htmlspecialchars((string) $createdCode, ENT_QUOTES, 'UTF-8') ?></strong>.
    </div>
<?php endif; ?>

<?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars((string) $errorMessage, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<?php if ($canCreate): ?>
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="/plans" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Fiscal Year</label>
                    <select class="form-select" name="fiscal_year_id" required>
                        <option value="">Select Fiscal Year</option>
                        <?php foreach ($fiscalYears ?? [] as $fiscalYear): ?>
                            <option value="<?= (int) $fiscalYear['id'] ?>" <?= (int) ($old['fiscal_year_id'] ?? $selectedFiscalYearId ?? 0) === (int) $fiscalYear['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars((string) $fiscalYear['fiscal_year'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Auto Code</label>
                    <input class="form-control" type="text" value="<?= htmlspecialchars((string) ($previewCode ?? 'Auto-generated'), ENT_QUOTES, 'UTF-8') ?>" readonly>
                    <div class="form-text">The next available letter code will be assigned automatically.</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Plan Name</label>
                    <input class="form-control" type="text" name="name" value="<?= htmlspecialchars((string) ($old['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Owner Department</label>
                    <input class="form-control" type="text" name="owner_department" value="<?= htmlspecialchars((string) ($old['owner_department'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Optional">
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Optional"><?= htmlspecialchars((string) ($old['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Save Plan</button>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        You can view existing plans here. Only ADMIN users can create new plans.
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <h5 class="mb-3">Existing Plans</h5>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Fiscal Year</th>
                    <th>Name</th>
                    <th>Owner Department</th>
                </tr>
                </thead>
                <tbody>
                <?php if (($plans ?? []) === []): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">No plans found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($plans as $plan): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($plan['code'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                            <td><?= htmlspecialchars((string) $plan['fiscal_year_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string) ($plan['owner_department'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

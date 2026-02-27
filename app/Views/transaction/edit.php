<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/dashboard"><i class="bi bi-wallet2 me-2"></i>Finance Manager</a>
            <div class="d-flex">
                <a href="/logout" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <div class="mb-3">
                    <a href="/dashboard" class="text-decoration-none text-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Dashboard</a>
                </div>

                <div class="card shadow border-0 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="m-0 fw-bold text-warning text-dark"><i class="bi bi-pencil-square me-2"></i>Edit Transaction</h5>
                    </div>
                    <div class="card-body p-4">

                        <?php if(session()->has('validation')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Failed to save changes.
                                <ul class="mb-0 mt-2">
                                    <?= session('validation')->listErrors() ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="/transaction/update/<?= $transaction['id'] ?>" method="post">
                            <div class="mb-3">
                                <label for="type" class="form-label fw-semibold"><i class="bi bi-tags me-1"></i>Type of Transaction</label>
                                <select name="type" class="form-select" id="type" required>
                                    <option value="income" <?= set_select('type', 'income', $transaction['type'] === 'income') ?>>Income</option>
                                    <option value="expense" <?= set_select('type', 'expense', $transaction['type'] === 'expense') ?>>Expense</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="amount" class="form-label fw-semibold"><i class="bi bi-cash me-1"></i>Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text fw-bold text-secondary">Rp</span>
                                    <input type="text" inputmode="numeric" name="amount" class="form-control" id="amount" value="<?= set_value('amount', $transaction['amount']) ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label fw-semibold"><i class="bi bi-calendar-date me-1"></i>Date</label>
                                <input type="date" name="date" class="form-control" id="date" value="<?= set_value('date', $transaction['date']) ?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold"><i class="bi bi-card-text me-1"></i>Description</label>
                                <textarea name="description" class="form-control" id="description" rows="3" required><?= set_value('description', $transaction['description']) ?></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning py-2 fw-bold text-dark"><i class="bi bi-check2-circle me-2"></i>Update Transaction</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
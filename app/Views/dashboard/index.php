<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .icon-lg { font-size: 2.5rem; opacity: 0.8; }
        .card-hover:hover { transform: translateY(-3px); transition: 0.3s; }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/dashboard"><i class="bi bi-wallet2 me-2"></i>Finance Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <?php if(session()->get('role') === 'admin'): ?>
                        <li class="nav-item">
                            <a href="/admin" class="nav-link text-warning fw-bold"><i class="bi bi-shield-lock me-1"></i>Admin panel</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <span class="navbar-text text-white mx-3">
                            <i class="bi bi-person-circle me-1"></i> Hello, <?= esc($name) ?>!
                        </span>
                    </li>
                    <li class="nav-item">
                        <a href="/logout" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <?php if(session()->getFlashdata('success')):?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif;?>

        <?php if(session()->getFlashdata('error')):?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif;?>

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white shadow-sm card-hover border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Total Revenue</h6>
                            <h3 class="mb-0 fw-bold">Rp <?= number_format($balance, 0, ',', '.') ?></h3>
                        </div>
                        <i class="bi bi-bank icon-lg"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white shadow-sm card-hover border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Income</h6>
                            <h3 class="mb-0 fw-bold">Rp <?= number_format($totalIncome, 0, ',', '.') ?></h3>
                        </div>
                        <i class="bi bi-arrow-down-circle icon-lg"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-danger text-white shadow-sm card-hover border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Expense</h6>
                            <h3 class="mb-0 fw-bold">Rp <?= number_format($totalExpense, 0, ',', '.') ?></h3>
                        </div>
                        <i class="bi bi-arrow-up-circle icon-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow border-0 rounded-3 mb-5">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="m-0 fw-bold text-secondary"><i class="bi bi-clock-history me-2"></i>Transaction History</h5>
                <a href="/transaction/create" class="btn btn-primary btn-sm fw-bold">
                    <i class="bi bi-plus-lg me-1"></i> Add Transaction
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($transactions)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        There's no transaction data yet. Start recording your finances!
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($transactions as $t): ?>
                                    <tr>
                                        <td class="ps-4"><?= date('d M Y', strtotime($t['date'])) ?></td>
                                        <td class="fw-semibold text-secondary"><?= esc($t['description']) ?></td>
                                        <td>
                                            <?php if($t['type'] === 'income'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="bi bi-arrow-down me-1"></i>Income</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1"><i class="bi bi-arrow-up me-1"></i>Expense</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="<?= $t['type'] === 'income' ? 'text-success' : 'text-danger' ?> fw-bold">
                                            <?= $t['type'] === 'income' ? '+' : '-' ?> Rp <?= number_format($t['amount'], 0, ',', '.') ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="/transaction/edit/<?= $t['id'] ?>" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="/transaction/delete/<?= $t['id'] ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Personal Finance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .auth-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { width: 100%; max-width: 400px; }
    </style>
</head>
<body>

    <div class="container auth-wrapper">
        <div class="auth-card">
            
            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary"><i class="bi bi-wallet2 me-2"></i>Finance Manager</h2>
                <p class="text-muted">Login to account</p>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    
                    <?php if(session()->getFlashdata('msg')):?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('msg') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif;?>

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

                    <form action="/login/auth" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control border-start-0" id="email" value="<?= set_value('email') ?>" placeholder="name@email.com" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control border-start-0" id="password" placeholder="Enter password" required>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary py-2 fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>Login</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <span class="text-muted">Don't have the account? </span>
                        <a href="/register" class="text-decoration-none fw-semibold">Register now</a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
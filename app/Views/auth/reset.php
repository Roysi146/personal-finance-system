<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru - Finance Manager</title>
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
                <h2 class="fw-bold text-primary"><i class="bi bi-key me-2"></i>New Password</h2>
                <p class="text-muted">Please create a strong new password</p>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    
                    <?php if(session()->getFlashdata('error')):?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif;?>

                    <form action="/reset/update" method="post">
                        <input type="hidden" name="token" value="<?= $token ?>">

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control border-start-0" id="password" placeholder="At least 6 characters" required>
                            </div>
                        </div>

                        <div class="d-grid mb-2">
                            <button type="submit" class="btn btn-success py-2 fw-bold"><i class="bi bi-check2-circle me-2"></i>Save New Password</button>
                        </div>
                    </form>

                </div>
            </div>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
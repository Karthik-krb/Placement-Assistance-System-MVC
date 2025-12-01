<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - Overview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="/PAS/public/css/company-dashboard.css?v=10" rel="stylesheet" />
    <style>
        .stats-container {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 1.5rem !important;
            margin-top: 2rem !important;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <?php if (!empty($_SESSION['user']['logo'])): ?>
                <img src="<?= htmlspecialchars($_SESSION['user']['logo']) ?>" alt="Company Logo" class="company-logo-sidebar">
            <?php else: ?>
                <div class="company-logo-placeholder-sidebar">
                    <?= strtoupper(substr($_SESSION['user']['name'] ?? 'C', 0, 1)) ?>
                </div>
            <?php endif; ?>
            <h2><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Company') ?></h2>
        </div>
        <ul class="sidebar-menu">
            <li class="active">
                <a href="/PAS/public/company/dashboard">
                    <i class="bi bi-house-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/company/jobposting">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Post Job</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/company/postedjobs">
                    <i class="bi bi-briefcase-fill"></i>
                    <span>Posted Jobs</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/company/applications">
                    <i class="bi bi-people-fill"></i>
                    <span>Applications</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/company/shortlisted">
                    <i class="bi bi-star-fill"></i>
                    <span>Shortlisted</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/company/feedback">
                    <i class="bi bi-chat-square-text-fill"></i>
                    <span>Feedback</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/company/email">
                    <i class="bi bi-envelope-fill"></i>
                    <span>Email</span>
                </a>
            </li>
            <li class="logout-item">
                <a href="/PAS/public/company/logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="main-content">
        <header class="page-header">
            <div class="header-content">
                <h1><i class="bi bi-grid-fill me-3"></i>Dashboard Overview</h1>
                <p class="mb-0">Welcome back! Here's what's happening with your recruitment.</p>
            </div>
        </header>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show modern-alert" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= htmlspecialchars($_SESSION['success_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <section class="stats-container">
            <div class="stat-card stat-card-1">
                <div class="stat-icon">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Total Jobs Posted</p>
                    <h3><?php echo htmlspecialchars($stats['total_jobs']); ?></h3>
                </div>
            </div>

            <div class="stat-card stat-card-2">
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Applications Received</p>
                    <h3><?php echo htmlspecialchars($stats['total_applied']); ?></h3>
                </div>
            </div>

            <div class="stat-card stat-card-3">
                <div class="stat-icon">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Total Candidates</p>
                    <h3><?php echo htmlspecialchars($stats['total_candidates']); ?></h3>
                </div>
            </div>

            <div class="stat-card stat-card-4">
                <div class="stat-icon">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Shortlisted Candidates</p>
                    <h3><?php echo htmlspecialchars($stats['total_shortlisted']); ?></h3>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

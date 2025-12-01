<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Overview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/PAS/public/css/admin-dashboard.css?v=3" rel="stylesheet" />
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-shield-check"></i>
            <h2>Admin Panel</h2>
        </div>
        <ul class="sidebar-menu">
            <li class="active">
                <a href="/PAS/public/admin/dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/admin/candidates">
                    <i class="bi bi-people"></i>
                    <span>Candidates</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/admin/applications">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Applications</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/admin/companies">
                    <i class="bi bi-building"></i>
                    <span>Companies</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/admin/shortlisted">
                    <i class="bi bi-star"></i>
                    <span>Shortlisted</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/admin/feedback">
                    <i class="bi bi-chat-square-text"></i>
                    <span>Feedback</span>
                    <?php if (isset($feedbackCounts['pending']) && $feedbackCounts['pending'] > 0): ?>
                        <span class="badge bg-danger rounded-pill ms-2"><?= $feedbackCounts['pending'] ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="logout">
                <a href="/PAS/public/admin/logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="main-content">
        <header class="page-header">
            <div>
                <h2><i class="bi bi-speedometer2"></i> Dashboard Overview</h2>
                <p class="text-muted">Welcome to Admin Analytics Portal</p>
            </div>
            <div class="user-info">
                <i class="bi bi-person-circle"></i>
                <span><?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Admin'); ?></span>
            </div>
        </header>
        
        <section class="stats-grid">
            <div class="stat-card stat-blue">
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo htmlspecialchars($stats['total_candidates']); ?></h3>
                    <p>Total Candidates</p>
                </div>
            </div>
            
            <div class="stat-card stat-green">
                <div class="stat-icon">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo htmlspecialchars($stats['total_applications']); ?></h3>
                    <p>Applications</p>
                </div>
            </div>
            
            <div class="stat-card stat-orange">
                <div class="stat-icon">
                    <i class="bi bi-building-fill"></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo htmlspecialchars($stats['total_companies']); ?></h3>
                    <p>Companies</p>
                </div>
            </div>
            
            <div class="stat-card stat-purple">
                <div class="stat-icon">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo htmlspecialchars($stats['total_shortlisted']); ?></h3>
                    <p>Shortlisted</p>
                </div>
            </div>
        </section>

        <section class="analytics-section">
            <h3 class="section-title"><i class="bi bi-graph-up"></i> Analytics Report</h3>
            
            <div class="analytics-grid">
                <div class="analytics-card">
                    <div class="card-header">
                        <h4><i class="bi bi-pie-chart"></i> Application Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="status-bars">
                            <?php
                            $total_apps = $analytics['application_status']['shortlisted'] + 
                                         $analytics['application_status']['rejected'] + 
                                         $analytics['application_status']['pending'];
                            $shortlisted_pct = $total_apps > 0 ? ($analytics['application_status']['shortlisted'] / $total_apps * 100) : 0;
                            $rejected_pct = $total_apps > 0 ? ($analytics['application_status']['rejected'] / $total_apps * 100) : 0;
                            $pending_pct = $total_apps > 0 ? ($analytics['application_status']['pending'] / $total_apps * 100) : 0;
                            ?>
                            <div class="status-item">
                                <div class="status-info">
                                    <span class="status-label">Shortlisted</span>
                                    <span class="status-value"><?php echo $analytics['application_status']['shortlisted']; ?></span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar progress-success" style="width: <?php echo $shortlisted_pct; ?>%"></div>
                                </div>
                                <span class="percentage"><?php echo number_format($shortlisted_pct, 1); ?>%</span>
                            </div>
                            
                            <div class="status-item">
                                <div class="status-info">
                                    <span class="status-label">Rejected</span>
                                    <span class="status-value"><?php echo $analytics['application_status']['rejected']; ?></span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar progress-danger" style="width: <?php echo $rejected_pct; ?>%"></div>
                                </div>
                                <span class="percentage"><?php echo number_format($rejected_pct, 1); ?>%</span>
                            </div>
                            
                            <div class="status-item">
                                <div class="status-info">
                                    <span class="status-label">Pending</span>
                                    <span class="status-value"><?php echo $analytics['application_status']['pending']; ?></span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar progress-warning" style="width: <?php echo $pending_pct; ?>%"></div>
                                </div>
                                <span class="percentage"><?php echo number_format($pending_pct, 1); ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="card-header">
                        <h4><i class="bi bi-award"></i> Candidate Metrics</h4>
                    </div>
                    <div class="card-body">
                        <div class="metrics-grid">
                            <div class="metric-item">
                                <div class="metric-icon metric-icon-blue">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="metric-info">
                                    <h5><?php echo $analytics['candidate_metrics']['total']; ?></h5>
                                    <p>Total Registered</p>
                                </div>
                            </div>
                            
                            <div class="metric-item">
                                <div class="metric-icon metric-icon-green">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </div>
                                <div class="metric-info">
                                    <h5><?php echo number_format($analytics['candidate_metrics']['avg_cgpa'], 2); ?></h5>
                                    <p>Average CGPA</p>
                                </div>
                            </div>
                            
                            <div class="metric-item">
                                <div class="metric-icon metric-icon-purple">
                                    <i class="bi bi-trophy"></i>
                                </div>
                                <div class="metric-info">
                                    <h5><?php echo number_format($analytics['candidate_metrics']['max_cgpa'], 2); ?></h5>
                                    <p>Highest CGPA</p>
                                </div>
                            </div>
                            
                            <div class="metric-item">
                                <div class="metric-icon metric-icon-orange">
                                    <i class="bi bi-bar-chart"></i>
                                </div>
                                <div class="metric-info">
                                    <h5><?php echo number_format($analytics['candidate_metrics']['min_cgpa'], 2); ?></h5>
                                    <p>Lowest CGPA</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="card-header">
                        <h4><i class="bi bi-building"></i> Top Companies</h4>
                    </div>
                    <div class="card-body">
                        <div class="company-list">
                            <?php foreach ($analytics['top_companies'] as $index => $company): ?>
                            <div class="company-item">
                                <div class="company-rank">#<?php echo $index + 1; ?></div>
                                <div class="company-details">
                                    <h5><?php echo htmlspecialchars($company['company_name']); ?></h5>
                                    <p><?php echo $company['job_count']; ?> job posting<?php echo $company['job_count'] != 1 ? 's' : ''; ?></p>
                                </div>
                                <div class="company-badge">
                                    <span class="badge bg-primary"><?php echo $company['job_count']; ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="card-header">
                        <h4><i class="bi bi-diagram-3"></i> Department Distribution</h4>
                    </div>
                    <div class="card-body">
                        <div class="department-list">
                            <?php 
                            $total_candidates = array_sum(array_column($analytics['department_distribution'], 'count'));
                            foreach ($analytics['department_distribution'] as $dept): 
                                $dept_pct = ($dept['count'] / $total_candidates) * 100;
                            ?>
                            <div class="department-item">
                                <div class="dept-info">
                                    <span class="dept-name"><?php echo htmlspecialchars($dept['c_department']); ?></span>
                                    <span class="dept-count"><?php echo $dept['count']; ?> students</span>
                                </div>
                                <div class="dept-progress">
                                    <div class="progress-bar-container">
                                        <div class="progress-bar progress-info" style="width: <?php echo $dept_pct; ?>%"></div>
                                    </div>
                                    <span class="percentage"><?php echo number_format($dept_pct, 1); ?>%</span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="analytics-card full-width">
                    <div class="card-header">
                        <h4><i class="bi bi-clock-history"></i> Recent Activities</h4>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            <?php foreach (array_slice($analytics['recent_activities'], 0, 8) as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="bi bi-file-earmark-plus"></i>
                                </div>
                                <div class="activity-details">
                                    <p><strong><?php echo htmlspecialchars($activity['name']); ?></strong> applied for 
                                       <strong><?php echo htmlspecialchars($activity['job_title']); ?></strong> at 
                                       <strong><?php echo htmlspecialchars($activity['company_name']); ?></strong>
                                    </p>
                                    <small class="text-muted">Application ID: #<?php echo $activity['id']; ?></small>
                                </div>
                                <div class="activity-time">
                                    <i class="bi bi-clock"></i>
                                    <span>Recent</span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

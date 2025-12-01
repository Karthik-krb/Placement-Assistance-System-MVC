<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Applications - Company Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="/PAS/public/css/company-dashboard.css?v=10" rel="stylesheet" />
    <link href="/PAS/public/css/applications.css?v=1" rel="stylesheet" />
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
            <li>
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
            <li class="active">
                <a href="/PAS/public/company/applications">
                    <i class="bi bi-file-earmark-text-fill"></i>
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

    <div class="content">
        <div class="page-header">
            <div>
                <h1><i class="bi bi-file-earmark-text-fill"></i> Candidate Applications</h1>
                <p class="text-muted">Review and manage applications for your job postings</p>
            </div>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <?= htmlspecialchars($_SESSION['success_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?= htmlspecialchars($_SESSION['error_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <div class="search-bar">
            <div style="position: relative;">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Search by job title..." />
            </div>
        </div>

        <?php if (empty($jobs)): ?>
            <div class="no-applications-state">
                <i class="bi bi-inbox"></i>
                <h3>No Applications Yet</h3>
                <p>Applications for your posted jobs will appear here.</p>
                <a href="/PAS/public/company/jobposting" class="btn-primary">
                    <i class="bi bi-plus-circle"></i> Post a Job
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($jobs as $job): ?>
                <div class="job-section" data-job-title="<?= strtolower(htmlspecialchars($job['job_title'])) ?>">
                    <div class="job-header-card">
                        <div class="job-title-section">
                            <h2><i class="bi bi-briefcase"></i> <?= htmlspecialchars($job['job_title']) ?></h2>
                            <div class="job-meta">
                                <span class="meta-item">
                                    <i class="bi bi-building"></i>
                                    <?= htmlspecialchars($job['company_name']) ?>
                                </span>
                                <span class="meta-item">
                                    <i class="bi bi-award"></i>
                                    CGPA: <?= htmlspecialchars($job['cgpa_criteria']) ?>
                                </span>
                                <span class="meta-item">
                                    <i class="bi bi-code-slash"></i>
                                    <?= htmlspecialchars($job['required_skills']) ?>
                                </span>
                            </div>
                        </div>
                        <div class="job-actions">
                            <?php if (!$job['is_shortlisted']): ?>
                                <span class="status-badge status-pending">
                                    <i class="bi bi-clock"></i> Pending Shortlist
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-completed">
                                    <i class="bi bi-check-circle-fill"></i> Shortlisted
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (empty($job['applications'])): ?>
                        <div class="no-apps-message">
                            <i class="bi bi-inbox"></i>
                            <p>No applications received for this job yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="applications-table-wrapper">
                            <div class="table-responsive">
                                <table class="applications-table">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-hash"></i> ID</th>
                                            <th><i class="bi bi-person"></i> Candidate Name</th>
                                            <th><i class="bi bi-envelope"></i> Email</th>
                                            <th><i class="bi bi-award"></i> CGPA</th>
                                            <th><i class="bi bi-code-slash"></i> Skills</th>
                                            <th><i class="bi bi-info-circle"></i> Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($job['applications'] as $app): ?>
                                            <tr>
                                                <td><strong>#<?= htmlspecialchars($app['apply_id']) ?></strong></td>
                                                <td>
                                                    <div class="candidate-cell">
                                                        <div class="candidate-avatar">
                                                            <?= strtoupper(substr($app['candidate_name'], 0, 1)) ?>
                                                        </div>
                                                        <span><?= htmlspecialchars($app['candidate_name']) ?></span>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($app['candidate_email']) ?></td>
                                                <td>
                                                    <span class="cgpa-badge <?= $app['c_cgpa'] >= $job['cgpa_criteria'] ? 'cgpa-pass' : 'cgpa-fail' ?>">
                                                        <?= htmlspecialchars($app['c_cgpa']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="skills-cell">
                                                        <?php 
                                                        $skills = array_slice(explode(',', $app['c_skills']), 0, 3);
                                                        foreach ($skills as $skill): 
                                                        ?>
                                                            <span class="skill-badge"><?= htmlspecialchars(trim($skill)) ?></span>
                                                        <?php endforeach; ?>
                                                        <?php if (count(explode(',', $app['c_skills'])) > 3): ?>
                                                            <span class="skill-badge more">+<?= count(explode(',', $app['c_skills'])) - 3 ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if (isset($app['status'])): ?>
                                                        <?php if ($app['status'] === 'Shortlisted'): ?>
                                                            <span class="app-status status-shortlisted">
                                                                <i class="bi bi-star-fill"></i> Shortlisted
                                                            </span>
                                                        <?php elseif ($app['status'] === 'Rejected'): ?>
                                                            <span class="app-status status-rejected">
                                                                <i class="bi bi-x-circle-fill"></i> Rejected
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="app-status status-pending">
                                                                <i class="bi bi-clock"></i> Pending
                                                            </span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="app-status status-pending">
                                                            <i class="bi bi-clock"></i> Pending
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-footer">
                                <?php 
                                $pending_count = count(array_filter($job['applications'], fn($a) => $a['status'] === 'Pending'));
                                $shortlisted_count = count(array_filter($job['applications'], fn($a) => $a['status'] === 'Shortlisted'));
                                $rejected_count = count(array_filter($job['applications'], fn($a) => $a['status'] === 'Rejected'));
                                ?>
                                <span>
                                    <strong><?= count($job['applications']) ?></strong> total application(s) - 
                                    <span class="text-success"><?= $shortlisted_count ?> Shortlisted</span>, 
                                    <span class="text-warning"><?= $pending_count ?> Pending</span>, 
                                    <span class="text-danger"><?= $rejected_count ?> Rejected</span>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const jobSections = document.querySelectorAll('.job-section');
            
            jobSections.forEach(section => {
                const jobTitle = section.getAttribute('data-job-title');
                if (jobTitle.includes(searchValue)) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>

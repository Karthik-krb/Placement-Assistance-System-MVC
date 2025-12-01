<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shortlisted Candidates - Company Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="/PAS/public/css/company-dashboard.css?v=10" rel="stylesheet" />
    <link href="/PAS/public/css/shortlisted.css?v=1" rel="stylesheet" />
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
            <li>
                <a href="/PAS/public/company/applications">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    <span>Applications</span>
                </a>
            </li>
            <li class="active">
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
                <h1><i class="bi bi-star-fill"></i> Shortlisted Candidates</h1>
                <p class="text-muted">Manage your shortlisted candidates for various positions</p>
            </div>
            <div class="header-stats">
                <div class="stat-badge">
                    <i class="bi bi-people-fill"></i>
                    <span><?= count($shortlisted ?? []) ?> Shortlisted</span>
                </div>
            </div>
        </div>

        <div class="search-bar">
            <div style="position: relative;">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Search by candidate name, job title, or email..." />
            </div>
        </div>

        <?php if (!empty($shortlisted)): ?>
            <div class="table-wrapper">
                <div class="table-responsive">
                    <table class="shortlisted-table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash"></i> Apply ID</th>
                                <th><i class="bi bi-briefcase"></i> Job Title</th>
                                <th><i class="bi bi-person"></i> Candidate Name</th>
                                <th><i class="bi bi-envelope"></i> Email</th>
                                <th><i class="bi bi-star"></i> Status</th>
                                <th><i class="bi bi-gear"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody id="shortlistedTableBody">
                            <?php foreach ($shortlisted as $row): ?>
                                <tr data-job="<?= strtolower(htmlspecialchars($row['job_title'])) ?>" 
                                    data-name="<?= strtolower(htmlspecialchars($row['candidate_name'])) ?>" 
                                    data-email="<?= strtolower(htmlspecialchars($row['candidate_email'])) ?>"
                                    data-candidate-id="<?= $row['candidate_id'] ?>"
                                    data-job-id="<?= $row['job_id'] ?>">
                                    <td><strong>#<?= htmlspecialchars($row['apply_id']) ?></strong></td>
                                    <td>
                                        <div class="job-cell">
                                            <i class="bi bi-briefcase-fill"></i>
                                            <span><?= htmlspecialchars($row['job_title']) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="candidate-cell">
                                            <div class="candidate-avatar">
                                                <?= strtoupper(substr($row['candidate_name'], 0, 1)) ?>
                                            </div>
                                            <span><?= htmlspecialchars($row['candidate_name']) ?></span>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($row['candidate_email']) ?></td>
                                    <td>
                                        <span class="status-badge-shortlisted">
                                            <i class="bi bi-star-fill"></i> Shortlisted
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-view" 
                                                    onclick="viewCandidate(<?= $row['apply_id'] ?>)"
                                                    title="View Details">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <span><strong><?= count($shortlisted) ?></strong> candidate(s) shortlisted</span>
                </div>
            </div>
        <?php else: ?>
            <div class="no-candidates">
                <i class="bi bi-star"></i>
                <h3>No Shortlisted Candidates Yet</h3>
                <p>Candidates you shortlist from applications will appear here.</p>
                <a href="/PAS/public/company/applications" class="btn-primary">
                    <i class="bi bi-file-earmark-text"></i> View Applications
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#shortlistedTableBody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                let jobTitle = row.dataset.job;
                let name = row.dataset.name;
                let email = row.dataset.email;

                if (jobTitle.includes(filter) || name.includes(filter) || email.includes(filter)) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            });
        });

        function viewCandidate(applyId) {
            alert('View details for application #' + applyId + '\n\nThis feature will show complete candidate profile and application details.');
        }

        function viewCandidate(applyId) {
            alert('View details for Apply ID: ' + applyId);
        }
    </script>
</body>
</html>

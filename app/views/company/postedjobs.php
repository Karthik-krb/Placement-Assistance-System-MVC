<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posted Jobs - Company Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="/PAS/public/css/company-dashboard.css?v=10" rel="stylesheet" />
    <link href="/PAS/public/css/posted-jobs.css?v=1" rel="stylesheet" />
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
            <li class="active">
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
                <h1><i class="bi bi-briefcase-fill me-3"></i>Posted Jobs</h1>
                <p class="mb-0">Manage and view all your job postings.</p>
            </div>
        </header>

        <div class="search-bar" style="position: relative;">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" placeholder="Search by job title, location, type...">
        </div>

        <div class="jobs-container" id="jobsContainer">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <?php
                    $deadline = $job['application_deadline'] ?? '';
                    $deadline_time = $job['deadline_time'] ?? '23:59:59';
                    $deadline_datetime = $deadline . ' ' . $deadline_time;
                    $is_expired = strtotime($deadline_datetime) < time();
                    
                    $job_type = $job['job_type'] ?? 'Full-Time';
                    $badge_class = 'badge-fulltime';
                    if (stripos($job_type, 'part') !== false) $badge_class = 'badge-parttime';
                    elseif (stripos($job_type, 'intern') !== false) $badge_class = 'badge-internship';
                    elseif (stripos($job_type, 'contract') !== false) $badge_class = 'badge-contract';
                    ?>
                    
                    <div class="job-card" data-job-id="<?= htmlspecialchars($job['job_id']) ?>" 
                         data-title="<?= htmlspecialchars($job['job_title']) ?>" 
                         data-location="<?= htmlspecialchars($job['location'] ?? '') ?>" 
                         data-type="<?= htmlspecialchars($job_type) ?>">
                        
                        <?php if ($deadline): ?>
                            <div class="deadline-badge <?= $is_expired ? 'deadline-expired' : '' ?>">
                                <i class="bi bi-<?= $is_expired ? 'x-circle' : 'clock' ?>"></i>
                                <?= $is_expired ? 'Expired' : 'Deadline: ' . date('M d, Y', strtotime($deadline)) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="job-header">
                            <h3 class="job-title"><?= htmlspecialchars($job['job_title']) ?></h3>
                            <span class="job-type-badge <?= $badge_class ?>">
                                <?= htmlspecialchars($job_type) ?>
                            </span>
                        </div>
                        
                        <div class="job-info">
                            <?php if (!empty($job['location'])): ?>
                                <div class="info-item">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span><?= htmlspecialchars($job['location']) ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($job['num_openings'])): ?>
                                <div class="info-item">
                                    <i class="bi bi-people-fill"></i>
                                    <strong><?= htmlspecialchars($job['num_openings']) ?></strong>
                                    <span>Opening<?= $job['num_openings'] > 1 ? 's' : '' ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="info-item">
                                <i class="bi bi-mortarboard-fill"></i>
                                <span><?= htmlspecialchars($job['degree_required'] ?? 'Any Degree') ?> - <?= htmlspecialchars($job['course_specialization'] ?? 'Any Branch') ?></span>
                            </div>
                            
                            <div class="info-item">
                                <i class="bi bi-graph-up"></i>
                                <strong>CGPA:</strong>
                                <span><?= htmlspecialchars($job['cgpa_criteria']) ?> and above</span>
                            </div>
                            
                            <?php if (!empty($job['supply_backlog_policy'])): ?>
                                <div class="info-item">
                                    <i class="bi bi-clipboard-check"></i>
                                    <strong>Backlogs:</strong>
                                    <span><?= htmlspecialchars($job['supply_backlog_policy']) ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($job['deadline_time'])): ?>
                                <div class="info-item">
                                    <i class="bi bi-alarm-fill"></i>
                                    <strong>Deadline Time:</strong>
                                    <span><?= date('h:i A', strtotime($job['deadline_time'])) ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="info-item">
                                <i class="bi bi-file-text"></i>
                                <span><?= htmlspecialchars(substr($job['job_description'], 0, 120)) ?><?= strlen($job['job_description']) > 120 ? '...' : '' ?></span>
                            </div>
                            
                            <?php if (!empty($job['required_skills'])): ?>
                                <div class="info-item">
                                    <i class="bi bi-code-square"></i>
                                    <div class="skills-tags">
                                        <?php 
                                        $skills = explode(', ', $job['required_skills']);
                                        $display_skills = array_slice($skills, 0, 5);
                                        foreach ($display_skills as $skill): 
                                        ?>
                                            <span class="skill-tag"><?= htmlspecialchars($skill) ?></span>
                                        <?php endforeach; ?>
                                        <?php if (count($skills) > 5): ?>
                                            <span class="skill-tag">+<?= count($skills) - 5 ?> more</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-jobs" style="grid-column: 1/-1;">
                    <i class="bi bi-briefcase"></i>
                    <h3>No Job Postings Yet</h3>
                    <p class="text-muted">You haven't posted any jobs. Click "Post Job" to create your first job posting.</p>
                    <a href="/PAS/public/company/jobposting" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle me-2"></i>Post Your First Job
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <div id="noResult" style="display: none;" class="no-jobs">
            <i class="bi bi-search"></i>
            <h3>No Jobs Found</h3>
            <p class="text-muted">Try adjusting your search terms.</p>
        </div>
    </div>

    <script>
        document.getElementById("searchInput").addEventListener("keyup", function () {
            let filter = this.value.toLowerCase();
            let jobCards = document.querySelectorAll(".job-card");
            let found = false;

            jobCards.forEach(function (card) {
                let title = card.getAttribute('data-title').toLowerCase();
                let location = card.getAttribute('data-location').toLowerCase();
                let type = card.getAttribute('data-type').toLowerCase();

                if (title.includes(filter) || location.includes(filter) || type.includes(filter)) {
                    card.style.display = "";
                    found = true;
                } else {
                    card.style.display = "none";
                }
            });

            document.getElementById("noResult").style.display = found ? "none" : "block";
            document.getElementById("jobsContainer").style.display = found ? "grid" : "none";
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

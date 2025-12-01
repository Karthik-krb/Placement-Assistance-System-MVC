<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Postings - Placement Assistance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="/PAS/public/css/candidate-dashboard.css?v=3" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .page-header {
            background: linear-gradient(135deg, rgb(26, 69, 198) 0%, rgb(41, 98, 255) 100%);
            color: white;
            padding: 1.5rem 0 1.3rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(26, 69, 198, 0.3);
            position: relative;
            overflow: hidden;
            border-radius: 0 0 25px 25px;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .page-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }
        
        .page-header .container {
            position: relative;
            z-index: 1;
        }
        
        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .page-header h1 i {
            margin-right: 0.5rem;
        }
        
        .page-header p {
            font-size: 0.95rem;
            opacity: 0.95;
            font-weight: 400;
            margin-bottom: 0;
        }
        
        .search-filter-bar {
            background: white;
            padding: 1.2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }
        
        .job-card {
            background: white;
            border-radius: 12px;
            padding: 1.2rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            border: 1px solid #e8e8e8;
            position: relative;
            overflow: hidden;
            min-height: auto;
            max-height: none;
        }
        
        .job-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(25, 25, 112, 0.15);
            border-color: #191970;
        }
        
        .job-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #191970 0%, #4169e1 100%);
        }
        
        .company-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 10px;
            border: 2px solid #f0f0f0;
            padding: 6px;
            background: white;
        }
        
        .company-logo-placeholder {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #191970 0%, #4169e1 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .job-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.4rem;
            line-height: 1.3;
        }
        
        .company-name {
            color: #191970;
            font-weight: 700;
            font-size: 1.15rem;
            margin-bottom: 0.3rem;
            line-height: 1.2;
        }
        
        .company-location {
            color: #718096;
            font-size: 0.85rem;
        }
        
        .job-description {
            color: #4a5568;
            line-height: 1.5;
            margin: 0.6rem 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            font-size: 0.9rem;
            max-height: 3em;
        }
        
        .job-meta {
            display: flex;
            gap: 1.2rem;
            margin: 0.6rem 0;
            flex-wrap: wrap;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #4a5568;
            font-size: 0.9rem;
        }
        
        .meta-item i {
            color: #191970;
        }
        
        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin: 0.6rem 0;
            max-height: 60px;
            overflow: hidden;
        }
        
        .skill-badge {
            background: #e6f0ff;
            color: #191970;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #cce0ff;
        }
        
        .cgpa-badge {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        
        .status-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 0.35rem 0.9rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-applied {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-closed {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .status-new {
            background: #d1fae5;
            color: #065f46;
        }
        
        .btn-apply {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            border: none;
            color: white;
            padding: 0.6rem 1.8rem;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .btn-apply:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
            color: white;
        }
        
        .btn-withdraw {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            color: white;
            padding: 0.6rem 1.8rem;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .btn-withdraw:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
            color: white;
        }
        
        .btn-view-details {
            background: white;
            border: 2px solid #191970;
            color: #191970;
            padding: 0.6rem 1.8rem;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .btn-view-details:hover {
            background: #191970;
            color: white;
        }
        
        .job-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .application-count {
            color: #718096;
            font-size: 0.85rem;
        }
        
        .no-jobs {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        
        .no-jobs i {
            font-size: 3.5rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }
        
        .search-input {
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            padding: 0.6rem 1.2rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .search-input:focus {
            border-color: #191970;
            box-shadow: 0 0 0 3px rgba(25, 25, 112, 0.1);
        }
        
        .filter-select {
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
        }
        
        .filter-select:focus {
            border-color: #191970;
            box-shadow: 0 0 0 3px rgba(25, 25, 112, 0.1);
        }
        
        @media (max-width: 768px) {
            .job-card {
                padding: 1rem;
            }
            
            .company-logo, .company-logo-placeholder {
                width: 55px;
                height: 55px;
            }
            
            .job-title {
                font-size: 1.1rem;
            }
            
            .job-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#">Placement Assistance System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-md-auto mx-5 px-4">
                <a class="nav-link" href="/PAS/public/candidate/dashboard">Home</a>
                <a class="nav-link active" href="/PAS/public/candidate/job-postings">Job Postings</a>
                <a class="nav-link" href="/PAS/public/candidate/applications">Check Application</a>
                <a class="nav-link" href="/PAS/public/candidate/feedback">Feedback</a>
                <a class="nav-link" href="/PAS/public/candidate/logout">Log Out</a>
            </div>
        </div>
    </div>
</nav>

<div class="page-header">
    <div class="container">
        <h1>
            <i class="bi bi-briefcase-fill"></i> Job Opportunities
        </h1>
        <p>Discover and apply for your dream career</p>
    </div>
</div>

<main class="container my-4">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="search-filter-bar">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 25px 0 0 25px;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 search-input" id="searchInput" 
                           placeholder="Search by job title or company..." style="border-radius: 0 25px 25px 0;">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="cgpaFilter">
                    <option value="">All CGPA Requirements</option>
                    <option value="6">6.0 and above</option>
                    <option value="7">7.0 and above</option>
                    <option value="8">8.0 and above</option>
                    <option value="9">9.0 and above</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="statusFilter">
                    <option value="">All Jobs</option>
                    <option value="available">Available</option>
                    <option value="applied">Applied</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
        </div>
        <div class="mt-3">
            <span class="text-muted">
                <i class="bi bi-info-circle"></i> 
                Showing <strong id="jobCount"><?= count($jobs) ?></strong> job opportunities
            </span>
        </div>
    </div>
    
    <?php if (empty($jobs)): ?>
        <div class="no-jobs">
            <i class="bi bi-briefcase"></i>
            <h4 class="text-muted">No Job Postings Available</h4>
            <p class="text-muted">Check back later for new opportunities!</p>
        </div>
    <?php else: ?>
        <div id="jobsContainer">
            <?php foreach ($jobs as $job): ?>
                <?php
                $has_applied = !empty($job['user_applied']);
                $is_shortlisted = !empty($job['is_shortlisted']);
                $job_closed = (int)$job['job_closed'] === 1;
                $skills = explode(',', $job['required_skills']);
                ?>
                <div class="job-card" 
                     data-title="<?= strtolower(htmlspecialchars($job['job_title'])) ?>"
                     data-company="<?= strtolower(htmlspecialchars($job['company_name'])) ?>"
                     data-cgpa="<?= htmlspecialchars($job['cgpa_criteria']) ?>"
                     data-status="<?= $job_closed ? 'closed' : ($has_applied ? 'applied' : 'available') ?>">
                    
                    <?php if ($has_applied): ?>
                        <span class="status-badge status-applied">
                            <i class="bi bi-check-circle-fill"></i> Applied
                        </span>
                    <?php elseif ($job_closed): ?>
                        <span class="status-badge status-closed">
                            <i class="bi bi-x-circle-fill"></i> Positions Full
                        </span>
                    <?php else: ?>
                        <span class="status-badge status-new">
                            <i class="bi bi-star-fill"></i> Open
                        </span>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-auto">
                            <?php if (!empty($job['company_logo'])): ?>
                                <img src="<?= htmlspecialchars($job['company_logo']) ?>" 
                                     alt="<?= htmlspecialchars($job['company_name']) ?>" 
                                     class="company-logo">
                            <?php else: ?>
                                <div class="company-logo-placeholder">
                                    <?= strtoupper(substr($job['company_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col">
                            <div class="company-name">
                                <?= htmlspecialchars($job['company_name']) ?>
                            </div>
                            <?php if (!empty($job['company_address'])): ?>
                                <div class="company-location">
                                    <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($job['company_address']) ?>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="job-title mt-2">
                                <?= htmlspecialchars($job['job_title']) ?>
                            </h3>
                            
                            <?php if (!empty($job['job_description'])): ?>
                                <p class="job-description">
                                    <?= htmlspecialchars($job['job_description']) ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="job-meta">
                                <div class="meta-item">
                                    <i class="bi bi-trophy-fill"></i>
                                    <span class="cgpa-badge">
                                        CGPA: <?= htmlspecialchars($job['cgpa_criteria']) ?>+
                                    </span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-briefcase-fill"></i>
                                    <strong><?= htmlspecialchars($job['num_openings']) ?></strong> 
                                    <?= $job['num_openings'] == 1 ? 'Vacancy' : 'Vacancies' ?>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-people-fill"></i>
                                    <strong><?= htmlspecialchars($job['total_applications']) ?></strong> 
                                    <?= $job['total_applications'] == 1 ? 'Application' : 'Applications' ?>
                                </div>
                            </div>
                            
                            <?php 
                            $vacancy_percentage = min(100, ($job['total_applications'] / $job['num_openings']) * 100);
                            $remaining_vacancies = max(0, $job['num_openings'] - $job['total_applications']);
                            $shortlisting_started = $job['total_shortlisted'] > 0;
                            ?>
                            <div class="mt-2 mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">
                                        <i class="bi bi-clipboard-check"></i> Vacancy Status
                                    </small>
                                    <small class="fw-bold <?= $shortlisting_started ? 'text-danger' : ($remaining_vacancies > 0 ? 'text-success' : 'text-danger') ?>">
                                        <?php if ($shortlisting_started): ?>
                                            <i class="bi bi-lock-fill"></i> Shortlisting in progress
                                        <?php elseif ($remaining_vacancies > 0): ?>
                                            <?= $remaining_vacancies ?> position<?= $remaining_vacancies != 1 ? 's' : '' ?> remaining
                                        <?php else: ?>
                                            All positions filled
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <div class="progress" style="height: 8px; border-radius: 10px;">
                                    <div class="progress-bar <?= ($shortlisting_started || $vacancy_percentage >= 100) ? 'bg-danger' : ($vacancy_percentage >= 75 ? 'bg-warning' : 'bg-success') ?>" 
                                         role="progressbar" 
                                         style="width: <?= $shortlisting_started ? 100 : $vacancy_percentage ?>%;" 
                                         aria-valuenow="<?= $shortlisting_started ? 100 : $vacancy_percentage ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="skills-container">
                                <?php foreach ($skills as $skill): ?>
                                    <span class="skill-badge">
                                        <?= htmlspecialchars(trim($skill)) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="job-footer">
                                <div class="application-count">
                                    <i class="bi bi-clock-fill"></i> Posted recently
                                </div>
                                <div>
                                    <?php if ($job_closed): ?>
                                        <button class="btn btn-secondary" disabled style="border-radius: 25px; padding: 0.7rem 2rem;">
                                            <i class="bi bi-lock-fill"></i> All Positions Filled
                                        </button>
                                    <?php elseif ($has_applied && !$is_shortlisted): ?>
                                        <form action="/PAS/public/candidate/job-postings" method="POST" style="display:inline;">
                                            <input type="hidden" name="withdraw_job_id" value="<?= $job['job_id'] ?>">
                                            <button type="submit" class="btn btn-withdraw" 
                                                    onclick="return confirm('Are you sure you want to withdraw this application?')">
                                                <i class="bi bi-x-circle-fill"></i> Withdraw Application
                                            </button>
                                        </form>
                                    <?php elseif ($has_applied && $is_shortlisted): ?>
                                        <button class="btn btn-secondary" disabled style="border-radius: 25px; padding: 0.7rem 2rem;">
                                            <i class="bi bi-check-circle-fill"></i> Application Submitted
                                        </button>
                                    <?php else: ?>
                                        <form action="/PAS/public/candidate/job-postings" method="POST" style="display:inline;">
                                            <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">
                                            <button type="submit" class="btn btn-apply">
                                                <i class="bi bi-send-fill"></i> Apply Now
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const cgpaFilter = document.getElementById('cgpaFilter');
        const statusFilter = document.getElementById('statusFilter');
        const jobCards = document.querySelectorAll('.job-card');
        const jobCount = document.getElementById('jobCount');
        
        function filterJobs() {
            const searchTerm = searchInput.value.toLowerCase();
            const cgpaValue = cgpaFilter.value;
            const statusValue = statusFilter.value;
            let visibleCount = 0;
            
            jobCards.forEach(card => {
                const title = card.dataset.title;
                const company = card.dataset.company;
                const cgpa = parseFloat(card.dataset.cgpa);
                const status = card.dataset.status;
                
                let showCard = true;
                
                if (searchTerm && !title.includes(searchTerm) && !company.includes(searchTerm)) {
                    showCard = false;
                }
                
                if (cgpaValue && cgpa < parseFloat(cgpaValue)) {
                    showCard = false;
                }
                
                if (statusValue && status !== statusValue) {
                    showCard = false;
                }
                
                if (showCard) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            jobCount.textContent = visibleCount;
        }
        
        searchInput.addEventListener('input', filterJobs);
        cgpaFilter.addEventListener('change', filterJobs);
        statusFilter.addEventListener('change', filterJobs);
    });
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Status - Placement Assistance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="/PAS/public/css/candidate-dashboard.css?v=4" rel="stylesheet">
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
        
        .stats-row {
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border-left: 4px solid;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        
        .stat-card.total {
            border-left-color: #191970;
        }
        
        .stat-card.pending {
            border-left-color: #f59e0b;
        }
        
        .stat-card.shortlisted {
            border-left-color: #10b981;
        }
        
        .stat-card.rejected {
            border-left-color: #ef4444;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .stat-card.total .stat-icon {
            background: linear-gradient(135deg, #191970 0%, #4169e1 100%);
        }
        
        .stat-card.pending .stat-icon {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .stat-card.shortlisted .stat-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .stat-card.rejected .stat-icon {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
        }
        
        .stat-label {
            color: #718096;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .filter-bar {
            background: white;
            padding: 1.2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            margin-bottom: 1.5rem;
        }
        
        .application-card {
            background: white;
            border-radius: 12px;
            padding: 1.2rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border: 1px solid #e8e8e8;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .application-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(25, 25, 112, 0.15);
        }
        
        .application-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            border-radius: 12px 0 0 12px;
        }
        
        .application-card.status-pending::before {
            background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
        }
        
        .application-card.status-shortlisted::before {
            background: linear-gradient(180deg, #10b981 0%, #059669 100%);
        }
        
        .application-card.status-rejected::before {
            background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%);
        }
        
        .job-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.3rem;
        }
        
        .company-name {
            color: #191970;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.8rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .info-item i {
            color: #191970;
            margin-top: 0.2rem;
        }
        
        .info-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 0.85rem;
        }
        
        .info-value {
            color: #2d3748;
            font-size: 0.9rem;
        }
        
        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-top: 0.5rem;
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
        
        .status-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 0.5rem 1.2rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .status-badge.pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        
        .status-badge.shortlisted {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #34d399;
        }
        
        .status-badge.rejected {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #f87171;
        }
        
        .no-applications {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        
        .no-applications i {
            font-size: 3.5rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }
        
        .filter-btn {
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            padding: 0.5rem 1.2rem;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: white;
            color: #4a5568;
        }
        
        .filter-btn.active {
            background: #191970;
            color: white;
            border-color: #191970;
        }
        
        .filter-btn:hover {
            border-color: #191970;
            color: #191970;
        }
        
        .filter-btn.active:hover {
            background: #0f0f4d;
            color: white;
        }
        
        @media (max-width: 768px) {
            .stats-row {
                margin-bottom: 1rem;
            }
            
            .application-card {
                padding: 1rem;
            }
            
            .status-badge {
                position: relative;
                top: auto;
                right: auto;
                margin-bottom: 0.5rem;
                display: inline-flex;
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-md-auto mx-5 px-4">
                <a class="nav-link" href="/PAS/public/candidate/dashboard">Home</a>
                <a class="nav-link" href="/PAS/public/candidate/job-postings">Job Postings</a>
                <a class="nav-link active" aria-current="page" href="/PAS/public/candidate/applications">Check Application</a>
                <a class="nav-link" href="/PAS/public/candidate/feedback">Feedback</a>
                <a class="nav-link" href="/PAS/public/candidate/logout">Log Out</a>
            </div>
        </div>
    </div>
</nav>

<div class="page-header">
    <div class="container">
        <h1>
            <i class="bi bi-file-text-fill"></i> Application Status
        </h1>
        <p>Monitor your job applications and their progress</p>
    </div>
</div>

<main class="container my-4">
    <?php if (!empty($applications)): ?>
        <div class="row stats-row g-3">
            <?php
            $total = count($applications);
            $pending = count(array_filter($applications, fn($a) => $a['application_status'] === 'Pending'));
            $shortlisted = count(array_filter($applications, fn($a) => $a['application_status'] === 'Shortlisted'));
            $rejected = count(array_filter($applications, fn($a) => $a['application_status'] === 'Rejected'));
            ?>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card total">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                        <div class="ms-3">
                            <div class="stat-number"><?= $total ?></div>
                            <div class="stat-label">Total Applied</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card pending">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div class="ms-3">
                            <div class="stat-number"><?= $pending ?></div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card shortlisted">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="ms-3">
                            <div class="stat-number"><?= $shortlisted ?></div>
                            <div class="stat-label">Shortlisted</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card rejected">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div class="ms-3">
                            <div class="stat-number"><?= $rejected ?></div>
                            <div class="stat-label">Rejected</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="filter-bar">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="text-muted me-2"><i class="bi bi-funnel-fill"></i> Filter by:</span>
                <button class="filter-btn active" data-filter="all">
                    All Applications (<?= $total ?>)
                </button>
                <button class="filter-btn" data-filter="Pending">
                    Pending (<?= $pending ?>)
                </button>
                <button class="filter-btn" data-filter="Shortlisted">
                    Shortlisted (<?= $shortlisted ?>)
                </button>
                <button class="filter-btn" data-filter="Rejected">
                    Rejected (<?= $rejected ?>)
                </button>
            </div>
        </div>
        
        <div id="applicationsContainer">
            <?php foreach ($applications as $app): ?>
                <?php
                $status = $app['application_status'];
                $status_class = strtolower($status);
                $skills = explode(',', $app['required_skills']);
                ?>
                <div class="application-card status-<?= $status_class ?>" data-status="<?= $status ?>">
                    <span class="status-badge <?= $status_class ?>">
                        <?php if ($status === 'Pending'): ?>
                            <i class="bi bi-clock-fill"></i>
                        <?php elseif ($status === 'Shortlisted'): ?>
                            <i class="bi bi-check-circle-fill"></i>
                        <?php else: ?>
                            <i class="bi bi-x-circle-fill"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($status) ?>
                    </span>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="job-title">
                                <?= htmlspecialchars($app['job_title']) ?>
                            </h3>
                            <div class="company-name">
                                <i class="bi bi-building"></i> <?= htmlspecialchars($app['company_name']) ?>
                            </div>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <i class="bi bi-trophy-fill"></i>
                                    <div>
                                        <div class="info-label">CGPA Required</div>
                                        <div class="info-value">
                                            <strong><?= htmlspecialchars($app['cgpa_criteria']) ?>+</strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <i class="bi bi-gear-fill"></i>
                                    <div>
                                        <div class="info-label">Required Skills</div>
                                        <div class="skills-container">
                                            <?php foreach ($skills as $skill): ?>
                                                <span class="skill-badge">
                                                    <?= htmlspecialchars(trim($skill)) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3 pt-3 border-top">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle-fill"></i>
                                    <?php if ($status === 'Pending'): ?>
                                        Your application is under review. Please wait for the company's response.
                                    <?php elseif ($status === 'Shortlisted'): ?>
                                        Congratulations! You have been shortlisted. The company will contact you soon.
                                    <?php else: ?>
                                        Unfortunately, your application was not selected for this position.
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-applications">
            <i class="bi bi-inbox"></i>
            <h4 class="text-muted mb-3">No Applications Found</h4>
            <p class="text-muted mb-4">You haven't applied for any jobs yet.</p>
            <a href="/PAS/public/candidate/job-postings" class="btn btn-primary" style="background: linear-gradient(135deg, #191970 0%, #4169e1 100%); border: none; border-radius: 20px; padding: 0.7rem 2rem;">
                <i class="bi bi-search"></i> Browse Job Postings
            </a>
        </div>
    <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const applicationCards = document.querySelectorAll('.application-card');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                
                applicationCards.forEach(card => {
                    if (filter === 'all' || card.dataset.status === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Management - Company Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="/PAS/public/css/company-dashboard.css?v=11" rel="stylesheet" />
    <link href="/PAS/public/css/applications.css?v=1" rel="stylesheet" />
    <style>
        .btn-email {
            background: linear-gradient(135deg, rgb(26, 69, 198), rgb(20, 55, 160));
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(26, 69, 198, 0.3);
        }
        
        .btn-email:hover {
            background: linear-gradient(135deg, rgb(20, 55, 160), rgb(16, 44, 128));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 69, 198, 0.4);
            color: white;
        }
        
        .btn-email:active {
            transform: translateY(0);
        }
        
        .btn-email:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .job-header-card {
            cursor: pointer;
        }
        
        .job-header-card:hover {
            background: linear-gradient(135deg, #f8f9ff, #ffffff);
        }
        
        .candidates-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
            border: 2px solid #e9ecef;
        }
        
        .candidates-table {
            width: 100%;
            margin-bottom: 0;
        }
        
        .candidates-table thead {
            background: linear-gradient(135deg, rgb(26, 69, 198), rgb(20, 55, 160));
            color: white;
        }
        
        .candidates-table th {
            padding: 14px;
            font-weight: 600;
            border: none;
        }
        
        .candidates-table thead tr:first-child th:first-child {
            border-top-left-radius: 10px;
        }
        
        .candidates-table thead tr:first-child th:last-child {
            border-top-right-radius: 10px;
        }
        
        .candidates-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .candidates-table tbody tr:hover {
            background-color: #f1f8ff;
            transform: scale(1.01);
        }
        
        .candidates-table td {
            padding: 14px;
            vertical-align: middle;
        }
        
        .candidate-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgb(26, 69, 198), rgb(20, 55, 160));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            margin-right: 12px;
        }
        
        .candidate-cell {
            display: flex;
            align-items: center;
        }
        
        .email-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .email-badge.sent {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }
        
        .email-badge.pending {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
        }
        
        .summary-stats {
            margin-top: 1.5rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
        }
        
        .stat-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .no-jobs-found {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        
        .no-jobs-found i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
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
            <li class="active">
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
            <h1><i class="bi bi-envelope-fill me-2"></i>Email Management</h1>
        </header>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-envelope-check fs-1 text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Total Sent</h6>
                                <h3 class="mb-0 text-success"><?= count($emailHistory ?? []) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-star-fill fs-1 text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Shortlisted</h6>
                                <h3 class="mb-0 text-warning"><?= $totalShortlisted ?? 0 ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-briefcase-fill fs-1 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Active Jobs</h6>
                                <h3 class="mb-0 text-primary"><?= count($jobsWithShortlisted ?? []) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-clock-history fs-1 text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Pending</h6>
                                <h3 class="mb-0 text-info <?= (($totalShortlisted ?? 0) - count($emailHistory ?? [])) > 0 ? 'pulse' : '' ?>"><?= ($totalShortlisted ?? 0) - count($emailHistory ?? []) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($jobsWithShortlisted)): ?>
            <div class="search-bar">
                <div style="position: relative;">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="jobSearchInput" placeholder="Search jobs by title or location..." />
                </div>
            </div>
            
            <div id="noJobsFound" class="no-jobs-found" style="display: none;">
                <i class="bi bi-search"></i>
                <h5>No jobs found</h5>
                <p>Try adjusting your search terms</p>
            </div>
            
            <?php foreach ($jobsWithShortlisted as $job): ?>
                <div class="job-section" data-job-title="<?= strtolower(htmlspecialchars($job['job_title'])) ?>" data-location="<?= strtolower(htmlspecialchars($job['location'])) ?>">
                    <div class="job-header-card">
                        <div class="job-title-section">
                            <h2><i class="bi bi-briefcase"></i> <?= htmlspecialchars($job['job_title']) ?></h2>
                            <div class="job-meta">
                                <span class="meta-item">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <?= htmlspecialchars($job['location']) ?>
                                </span>
                                <span class="meta-item">
                                    <i class="bi bi-star-fill"></i>
                                    <?= $job['shortlisted_count'] ?> Shortlisted
                                </span>
                                <span class="meta-item">
                                    <i class="bi bi-envelope-check"></i>
                                    <?= $job['emails_sent'] ?> Emails Sent
                                </span>
                                <span class="meta-item">
                                    <i class="bi bi-clock"></i>
                                    <?= $job['shortlisted_count'] - $job['emails_sent'] ?> Pending
                                </span>
                            </div>
                        </div>
                        <div class="job-actions">
                            <?php if (($job['shortlisted_count'] - $job['emails_sent']) > 0): ?>
                                <button class="btn-email send-all-btn" data-job-id="<?= $job['job_id'] ?>">
                                    <i class="bi bi-send-fill"></i> Send to All Pending
                                </button>
                            <?php else: ?>
                                <span class="status-badge status-completed">
                                    <i class="bi bi-check-circle-fill"></i> All Emails Sent
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="candidates-container" id="candidates-<?= $job['job_id'] ?>" style="display: none;">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-applications-state">
                <i class="bi bi-inbox"></i>
                <h3>No Jobs with Shortlisted Candidates</h3>
                <p>Jobs with shortlisted candidates will appear here for email notifications.</p>
                <a href="/PAS/public/company/applications" class="btn-primary">
                    <i class="bi bi-people-fill"></i> View Applications
                </a>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Email History</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($emailHistory)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Job Role</th>
                                    <th>Candidate</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($emailHistory as $email): ?>
                                    <tr>
                                        <td><?= date('M d, Y h:i A', strtotime($email['sent_at'])) ?></td>
                                        <td><?= htmlspecialchars($email['job_title']) ?></td>
                                        <td><?= htmlspecialchars($email['candidate_name']) ?></td>
                                        <td><?= htmlspecialchars($email['candidate_email']) ?></td>
                                        <td>
                                            <?php if ($email['email_status'] == 'sent'): ?>
                                                <span class="badge bg-success">Sent</span>
                                            <?php elseif ($email['email_status'] == 'failed'): ?>
                                                <span class="badge bg-danger">Failed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">No emails sent yet</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="notificationContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('jobSearchInput')?.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const jobSections = document.querySelectorAll('.job-section');
            let visibleCount = 0;
            
            jobSections.forEach(section => {
                const title = section.dataset.jobTitle || '';
                const location = section.dataset.location || '';
                
                if (title.includes(searchTerm) || location.includes(searchTerm)) {
                    section.style.display = '';
                    visibleCount++;
                } else {
                    section.style.display = 'none';
                }
            });
            
            const noResults = document.getElementById('noJobsFound');
            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
        
        document.querySelectorAll('.job-header-card').forEach(card => {
            card.addEventListener('click', async function(e) {
                if (e.target.closest('.send-all-btn')) return;
                
                const jobSection = this.closest('.job-section');
                const candidatesContainer = jobSection.querySelector('.candidates-container');
                const jobId = candidatesContainer.id.replace('candidates-', '');
                
                if (candidatesContainer.style.display === 'none' || !candidatesContainer.style.display) {
                    document.querySelectorAll('.candidates-container').forEach(container => {
                        if (container !== candidatesContainer) {
                            container.style.display = 'none';
                        }
                    });
                    
                    candidatesContainer.style.display = 'block';
                    
                    if (candidatesContainer.querySelector('.spinner-border')) {
                        await loadCandidates(jobId, candidatesContainer);
                    }
                } else {
                    candidatesContainer.style.display = 'none';
                }
            });
        });

        async function loadCandidates(jobId, container) {
            try {
                const response = await fetch(`/PAS/public/company/email?action=get_candidates&job_id=${jobId}`);
                const data = await response.json();

                if (data.success && data.candidates.length > 0) {
                    let html = '<div class="table-responsive"><table class="candidates-table">';
                    html += '<thead>';
                    html += '<tr>';
                    html += '<th><i class="bi bi-hash"></i> #</th>';
                    html += '<th><i class="bi bi-person"></i> Name</th>';
                    html += '<th><i class="bi bi-envelope"></i> Email</th>';
                    html += '<th><i class="bi bi-telephone"></i> Phone</th>';
                    html += '<th style="text-align: center;"><i class="bi bi-info-circle"></i> Email Status</th>';
                    html += '</tr></thead><tbody>';
                    
                    data.candidates.forEach((candidate, index) => {
                        const emailSent = candidate.email_sent == 1;
                        html += `<tr style="animation: fadeIn 0.3s ease ${index * 0.05}s both;">
                            <td><strong>#${index + 1}</strong></td>
                            <td>
                                <div class="candidate-cell">
                                    <div class="candidate-avatar">
                                        ${escapeHtml(candidate.name).charAt(0).toUpperCase()}
                                    </div>
                                    <span>${escapeHtml(candidate.name)}</span>
                                </div>
                            </td>
                            <td>${escapeHtml(candidate.email)}</td>
                            <td>${escapeHtml(candidate.phone || 'N/A')}</td>
                            <td style="text-align: center;">
                                ${emailSent 
                                    ? '<span class="email-badge sent"><i class="bi bi-check-circle-fill"></i> Sent</span>' 
                                    : '<span class="email-badge pending"><i class="bi bi-clock-fill"></i> Pending</span>'}
                            </td>
                        </tr>`;
                    });
                    
                    html += '</tbody></table></div>';
                    
                    const sentCount = data.candidates.filter(c => c.email_sent == 1).length;
                    const pendingCount = data.candidates.length - sentCount;
                    html += `<div class="summary-stats">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-item">
                                    <i class="bi bi-people-fill fs-3 text-primary mb-2"></i>
                                    <h6 class="text-muted mb-1">Total Candidates</h6>
                                    <h3 class="mb-0">${data.candidates.length}</h3>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <i class="bi bi-envelope-check-fill fs-3 text-success mb-2"></i>
                                    <h6 class="text-muted mb-1">Emails Sent</h6>
                                    <h3 class="mb-0 text-success">${sentCount}</h3>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <i class="bi bi-clock-fill fs-3 text-warning mb-2"></i>
                                    <h6 class="text-muted mb-1">Pending</h6>
                                    <h3 class="mb-0 text-warning">${pendingCount}</h3>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="no-apps-message"><i class="bi bi-inbox"></i><p>No candidates found</p></div>';
                }
            } catch (error) {
                container.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>Error loading candidates</div>';
                console.error('Error:', error);
            }
        }

        document.querySelectorAll('.send-all-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.stopPropagation(); // Prevent card collapse
                
                const jobId = this.dataset.jobId;
                
                if (!confirm('Send emails to all pending candidates for this job?')) return;
                
                const originalHtml = this.innerHTML;
                this.disabled = true;
                this.innerHTML = '<i class="bi bi-hourglass-split spinner-border spinner-border-sm me-2"></i>Sending...';
                
                try {
                    const response = await fetch(`/PAS/public/company/email?action=get_candidates&job_id=${jobId}`);
                    const data = await response.json();
                    
                    if (data.success) {
                        const pendingCandidates = data.candidates.filter(c => c.email_sent != 1);
                        
                        if (pendingCandidates.length === 0) {
                            showNotification('info', 'All candidates have already received emails');
                            this.disabled = false;
                            this.innerHTML = originalHtml;
                            return;
                        }
                        
                        const candidateIds = pendingCandidates.map(c => c.candidate_id).join(',');
                        
                        const sendResponse = await fetch('/PAS/public/company/email', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                            body: `action=send_bulk&job_id=${jobId}&candidate_ids=${candidateIds}`
                        });
                        
                        const sendData = await sendResponse.json();
                        
                        if (sendData.success) {
                            showNotification('success', `Successfully sent ${sendData.sent} emails!${sendData.failed > 0 ? ' ' + sendData.failed + ' failed.' : ''}`);
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showNotification('error', sendData.message || 'Failed to send emails');
                            this.disabled = false;
                            this.innerHTML = originalHtml;
                        }
                    }
                } catch (error) {
                    showNotification('error', 'Error: ' + error.message);
                    this.disabled = false;
                    this.innerHTML = originalHtml;
                }
            });
        });

        function showNotification(type, message) {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            container.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>

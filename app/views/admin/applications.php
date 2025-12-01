<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/PAS/public/css/admin-dashboard.css?v=3" rel="stylesheet" />
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-shield-check"></i>
            <h2>Admin Panel</h2>
        </div>
        <ul class="sidebar-menu">
            <li>
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
            <li class="active">
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
                <h2><i class="bi bi-file-earmark-text"></i> Applications Management</h2>
                <p class="text-muted">View all candidate job applications</p>
            </div>
            <div class="user-info">
                <i class="bi bi-person-circle"></i>
                <span><?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Admin'); ?></span>
            </div>
        </header>
        
        <section class="content-section">
            <div class="section-header">
                <h3><i class="bi bi-list-check"></i> Application List</h3>
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by Candidate ID, Name, Job Title, or Company...">
                </div>
            </div>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Candidate ID</th>
                            <th>Candidate Name</th>
                            <th>Email</th>
                            <th>Job Title</th>
                            <th>Company Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="applicationTable">
                        <?php if (!empty($applications)): ?>
                            <?php foreach ($applications as $row): ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= htmlspecialchars($row['candidate_id']) ?></span></td>
                                    <td><strong><?= htmlspecialchars($row['candidate_name']) ?></strong></td>
                                    <td><?= htmlspecialchars($row['candidate_email']) ?></td>
                                    <td>
                                        <div class="job-title-cell">
                                            <i class="bi bi-briefcase"></i>
                                            <?= htmlspecialchars($row['job_title']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="company-cell">
                                            <i class="bi bi-building"></i>
                                            <?= htmlspecialchars($row['company_name']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php 
                                        $status = strtolower($row['status'] ?? 'applied');
                                        $statusClass = $status === 'shortlisted' ? 'status-shortlisted' : 'status-applied';
                                        $statusIcon = $status === 'shortlisted' ? 'bi-star-fill' : 'bi-clock-history';
                                        $statusText = ucfirst($status);
                                        ?>
                                        <span class="status-badge <?= $statusClass ?>">
                                            <i class="bi <?= $statusIcon ?>"></i> <?= $statusText ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="no-data">
                                    <i class="bi bi-inbox"></i>
                                    <p>No applications found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('applicationTable');
        const originalRows = [...tableBody.querySelectorAll('tr')];

        searchInput.addEventListener('keyup', function () {
            const filter = this.value.toUpperCase();
            let visibleCount = 0;

            if (filter === '') {
                tableBody.innerHTML = '';
                originalRows.forEach(row => {
                    row.style.display = '';
                    tableBody.appendChild(row);
                });
            } else {
                tableBody.innerHTML = '';
                originalRows.forEach(row => {
                    if (row.cells.length > 1) {
                        const candidateId = row.cells[0].textContent.toUpperCase();
                        const candidateName = row.cells[1].textContent.toUpperCase();
                        const jobTitle = row.cells[3].textContent.toUpperCase();
                        const companyName = row.cells[4].textContent.toUpperCase();

                        if (candidateId.includes(filter) || candidateName.includes(filter) || 
                            jobTitle.includes(filter) || companyName.includes(filter)) {
                            tableBody.appendChild(row);
                            visibleCount++;
                        }
                    }
                });

                if (visibleCount === 0) {
                    tableBody.innerHTML = `<tr><td colspan="6" class="no-data"><i class="bi bi-search"></i><p>No matching applications found</p></td></tr>`;
                }
            }
        });
    </script>
</body>
</html>

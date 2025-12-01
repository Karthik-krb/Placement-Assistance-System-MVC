<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Companies</title>
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
            <li>
                <a href="/PAS/public/admin/applications">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Applications</span>
                </a>
            </li>
            <li class="active">
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
                <h2><i class="bi bi-building"></i> Companies Management</h2>
                <p class="text-muted">View all registered companies</p>
            </div>
            <div class="user-info">
                <i class="bi bi-person-circle"></i>
                <span><?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Admin'); ?></span>
            </div>
        </header>
        
        <section class="content-section">
            <div class="section-header">
                <h3><i class="bi bi-list-ul"></i> Company List</h3>
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by ID, Name, or Location...">
                </div>
            </div>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Company ID</th>
                            <th>Company Name</th>
                            <th>Location</th>
                            <th>Contact Number</th>
                            <th>Total Jobs</th>
                        </tr>
                    </thead>
                    <tbody id="companyTable">
                        <?php if (!empty($companies)): ?>
                            <?php foreach ($companies as $row): ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= htmlspecialchars($row['company_id']) ?></span></td>
                                    <td>
                                        <div class="company-name-cell">
                                            <i class="bi bi-building-fill"></i>
                                            <strong><?= htmlspecialchars($row['company_name']) ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="location-cell">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <?= htmlspecialchars($row['company_address']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact-cell">
                                            <i class="bi bi-telephone-fill"></i>
                                            <?= htmlspecialchars($row['company_contactno']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="job-count-badge">
                                            <i class="bi bi-briefcase-fill"></i>
                                            <span><?= htmlspecialchars($row['total_jobs']) ?> Job<?= $row['total_jobs'] != 1 ? 's' : '' ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-data">
                                    <i class="bi bi-inbox"></i>
                                    <p>No companies found</p>
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
        const tableBody = document.getElementById('companyTable');
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
                        const companyId = row.cells[0].textContent.toUpperCase();
                        const companyName = row.cells[1].textContent.toUpperCase();
                        const location = row.cells[2].textContent.toUpperCase();

                        if (companyId.includes(filter) || companyName.includes(filter) || location.includes(filter)) {
                            tableBody.appendChild(row);
                            visibleCount++;
                        }
                    }
                });

                if (visibleCount === 0) {
                    tableBody.innerHTML = `<tr><td colspan="5" class="no-data"><i class="bi bi-search"></i><p>No matching companies found</p></td></tr>`;
                }
            }
        });
    </script>
</body>
</html>

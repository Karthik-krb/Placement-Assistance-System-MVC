<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Candidates</title>
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
            <li class="active">
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
                <h2><i class="bi bi-people"></i> Candidates Management</h2>
                <p class="text-muted">View and manage all registered candidates</p>
            </div>
            <div class="user-info">
                <i class="bi bi-person-circle"></i>
                <span><?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Admin'); ?></span>
            </div>
        </header>
        
        <section class="content-section">
            <div class="section-header">
                <h3><i class="bi bi-table"></i> Candidate List</h3>
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by ID, Name, Email, or Department...">
                </div>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Degree</th>
                            <th>CGPA</th>
                            <th>Backlogs</th>
                            <th>Skills</th>
                        </tr>
                    </thead>
                    <tbody id="candidateTable">
                        <?php if (!empty($candidates)): ?>
                            <?php foreach ($candidates as $row): ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= htmlspecialchars($row['candidate_id']) ?></span></td>
                                    <td><strong><?= htmlspecialchars($row['c_name']) ?></strong></td>
                                    <td><?= htmlspecialchars($row['c_email']) ?></td>
                                    <td><?= htmlspecialchars($row['c_phone'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['c_department'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['c_degree'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="cgpa-badge <?= $row['c_cgpa'] >= 8 ? 'cgpa-high' : ($row['c_cgpa'] >= 6 ? 'cgpa-medium' : 'cgpa-low') ?>">
                                            <?= htmlspecialchars($row['c_cgpa']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $backlogs = strtolower($row['c_supply_no'] ?? '0');
                                        $hasBacklog = !in_array($backlogs, ['no', '0', 'no backlogs', 'not applicable', 'n/a', '']);
                                        ?>
                                        <span class="backlog-badge <?= $hasBacklog ? 'has-backlog' : 'no-backlog' ?>">
                                            <?= $backlogs === '0' || $backlogs === '' ? 'No' : htmlspecialchars($row['c_supply_no']) ?>
                                        </span>
                                    </td>
                                    <td><span class="skills-text"><?= htmlspecialchars($row['c_skills']) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="no-data">
                                    <i class="bi bi-inbox"></i>
                                    <p>No candidates found</p>
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
        const tableBody = document.getElementById('candidateTable');
        const originalRows = [...tableBody.querySelectorAll('tr')]; 

        searchInput.addEventListener('keyup', function () {
            let filter = this.value.toUpperCase();
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
                        const id = row.cells[0].textContent.toUpperCase();
                        const name = row.cells[1].textContent.toUpperCase();
                        const email = row.cells[2].textContent.toUpperCase();
                        const department = row.cells[4].textContent.toUpperCase();

                        if (id.includes(filter) || name.includes(filter) || email.includes(filter) || department.includes(filter)) {
                            tableBody.appendChild(row);
                            visibleCount++;
                        }
                    }
                });

                if (visibleCount === 0) {
                    tableBody.innerHTML = `<tr><td colspan="9" class="no-data"><i class="bi bi-search"></i><p>No matching candidates found</p></td></tr>`;
                }
            }
        });
    </script>
</body>
</html>

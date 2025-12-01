<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Shortlisted Candidates</title>
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
            <li>
                <a href="/PAS/public/admin/companies">
                    <i class="bi bi-building"></i>
                    <span>Companies</span>
                </a>
            </li>
            <li class="active">
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
                <h2><i class="bi bi-star-fill"></i> Shortlisted Candidates</h2>
                <p class="text-muted">View all shortlisted candidates with profile and resume access</p>
            </div>
            <div class="user-info">
                <i class="bi bi-person-circle"></i>
                <span><?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Admin'); ?></span>
            </div>
        </header>
        
        <section class="content-section">
            <div class="section-header">
                <h3><i class="bi bi-check2-circle"></i> Shortlisted List</h3>
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by Candidate Name, Job Title, or Company...">
                </div>
            </div>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Candidate Name</th>
                            <th>Email</th>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="shortlistTable">
                        <?php if (!empty($shortlisted)): ?>
                            <?php foreach ($shortlisted as $row): ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= htmlspecialchars($row['shortlist_id']) ?></span></td>
                                    <td>
                                        <div class="candidate-info">
                                            <i class="bi bi-person-circle"></i>
                                            <strong><?= htmlspecialchars($row['candidate_name']) ?></strong>
                                        </div>
                                    </td>
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
                                        <div class="action-buttons">
                                            <button 
                                               class="btn-action btn-view" 
                                               title="View Candidate Profile"
                                               data-name="<?= htmlspecialchars($row['candidate_name']) ?>"
                                               data-email="<?= htmlspecialchars($row['candidate_email']) ?>"
                                               data-phone="<?= htmlspecialchars($row['c_phone'] ?? 'N/A') ?>"
                                               data-age="<?= htmlspecialchars($row['c_age'] ?? 'N/A') ?>"
                                               data-sex="<?= htmlspecialchars($row['c_sex'] ?? 'N/A') ?>"
                                               data-department="<?= htmlspecialchars($row['c_department'] ?? 'N/A') ?>"
                                               data-degree="<?= htmlspecialchars($row['c_degree'] ?? 'N/A') ?>"
                                               data-cgpa="<?= htmlspecialchars($row['c_cgpa'] ?? 'N/A') ?>"
                                               data-backlogs="<?= htmlspecialchars($row['c_supply_no'] ?? '0') ?>"
                                               data-skills="<?= htmlspecialchars($row['c_skills'] ?? 'N/A') ?>"
                                               data-start-year="<?= htmlspecialchars($row['c_course_start_year'] ?? 'N/A') ?>"
                                               data-end-year="<?= htmlspecialchars($row['c_course_end_year'] ?? 'N/A') ?>"
                                               data-semester="<?= htmlspecialchars($row['c_current_semester'] ?? 'N/A') ?>"
                                               data-job="<?= htmlspecialchars($row['job_title']) ?>"
                                               data-company="<?= htmlspecialchars($row['company_name']) ?>"
                                               onclick="viewCandidateDetails(this)">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                            <?php if (!empty($row['c_resume'])): ?>
                                                <button 
                                                   class="btn-action btn-resume" 
                                                   title="View Resume"
                                                   onclick="viewResume('<?= htmlspecialchars($row['c_resume']) ?>', '<?= htmlspecialchars($row['candidate_name']) ?>')">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </button>
                                            <?php else: ?>
                                                <span class="btn-action btn-disabled" title="No Resume Available">
                                                    <i class="bi bi-file-earmark-x"></i>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="no-data">
                                    <i class="bi bi-inbox"></i>
                                    <p>No shortlisted candidates found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div id="candidateModal" class="modal" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3><i class="bi bi-person-badge"></i> Candidate Profile</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="profile-section">
                    <h4 class="section-title"><i class="bi bi-person-circle"></i> Personal Information</h4>
                    <div class="profile-grid">
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-person"></i> Full Name</span>
                            <span class="item-value" id="modalName"></span>
                        </div>
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-envelope"></i> Email</span>
                            <span class="item-value" id="modalEmail"></span>
                        </div>
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-telephone"></i> Phone</span>
                            <span class="item-value" id="modalPhone"></span>
                        </div>
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-calendar"></i> Age</span>
                            <span class="item-value" id="modalAge"></span>
                        </div>
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-gender-ambiguous"></i> Gender</span>
                            <span class="item-value" id="modalSex"></span>
                        </div>
                    </div>
                </div>

                <div class="profile-section">
                    <h4 class="section-title"><i class="bi bi-mortarboard"></i> Academic Information</h4>
                    <div class="profile-grid">
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-building"></i> Department</span>
                            <span class="item-value" id="modalDepartment"></span>
                        </div>
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-award"></i> Degree</span>
                            <span class="item-value" id="modalDegree"></span>
                        </div>
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-graph-up"></i> CGPA</span>
                            <span class="item-value cgpa-highlight" id="modalCgpa"></span>
                        </div>
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-exclamation-triangle"></i> Backlogs</span>
                            <span class="item-value" id="modalBacklogs"></span>
                        </div>
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-calendar-range"></i> Course Period</span>
                            <span class="item-value" id="modalCoursePeriod"></span>
                        </div>
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-journal-text"></i> Current Semester</span>
                            <span class="item-value" id="modalSemester"></span>
                        </div>
                    </div>
                </div>

                <div class="profile-section">
                    <h4 class="section-title"><i class="bi bi-tools"></i> Skills & Expertise</h4>
                    <div class="skills-container" id="modalSkills"></div>
                </div>

                <div class="profile-section">
                    <h4 class="section-title"><i class="bi bi-briefcase"></i> Applied Position</h4>
                    <div class="profile-grid">
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-briefcase-fill"></i> Job Title</span>
                            <span class="item-value" id="modalJob"></span>
                        </div>
                        <div class="profile-item">
                            <span class="item-label"><i class="bi bi-building-fill"></i> Company</span>
                            <span class="item-value" id="modalCompany"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="resumeModal" class="modal" onclick="closeResumeModal(event)">
        <div class="resume-modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3><i class="bi bi-file-earmark-pdf-fill"></i> Resume - <span id="resumeCandidateName"></span></h3>
                <button class="modal-close" onclick="closeResumeModal()">&times;</button>
            </div>
            <div class="resume-viewer">
                <iframe id="resumeFrame" src="" frameborder="0"></iframe>
            </div>
        </div>
    </div>

    <style>
        .modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); justify-content: center; align-items: center; overflow-y: auto; padding: 20px; }
        .modal.active { display: flex; }
        
        .modal-content { background: white; border-radius: 20px; width: 100%; max-width: 800px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideDown 0.4s ease; margin: auto; max-height: 90vh; overflow-y: auto; }
        @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 25px 30px; border-bottom: 2px solid var(--border); background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; border-radius: 20px 20px 0 0; }
        .modal-header h3 { margin: 0; font-size: 24px; display: flex; align-items: center; gap: 12px; }
        .modal-header h3 i { font-size: 28px; }
        .modal-close { background: rgba(255,255,255,0.2); border: none; font-size: 32px; color: white; cursor: pointer; transition: all 0.3s; padding: 0; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; }
        .modal-close:hover { background: rgba(255,255,255,0.3); transform: rotate(90deg); }
        
        .modal-body { padding: 30px; }
        
        .profile-section { margin-bottom: 30px; }
        .profile-section:last-child { margin-bottom: 0; }
        .section-title { font-size: 18px; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid var(--border); }
        .section-title i { font-size: 22px; }
        
        .profile-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .profile-item { background: var(--light-bg); padding: 16px; border-radius: 12px; border-left: 4px solid var(--primary); transition: all 0.3s; }
        .profile-item:hover { background: #e8f0fe; transform: translateX(5px); }
        .item-label { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: var(--text-gray); margin-bottom: 8px; }
        .item-label i { color: var(--primary); font-size: 16px; }
        .item-value { display: block; font-size: 15px; font-weight: 600; color: var(--text); word-break: break-word; }
        .cgpa-highlight { color: var(--success); font-size: 18px; }
        
        .skills-container { display: flex; flex-wrap: wrap; gap: 10px; }
        .skill-tag { display: inline-flex; align-items: center; padding: 8px 16px; background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; border-radius: 20px; font-size: 13px; font-weight: 600; box-shadow: 0 2px 8px rgba(26,69,198,0.3); }
        
        @media (max-width: 768px) {
            .profile-grid { grid-template-columns: 1fr; }
            .modal-content { max-width: 100%; margin: 0; border-radius: 16px; }
            .modal-header { padding: 20px; }
            .modal-body { padding: 20px; }
        }
    </style>

    <script>
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('shortlistTable');
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
                        const candidateName = row.cells[1].textContent.toUpperCase();
                        const jobTitle = row.cells[3].textContent.toUpperCase();
                        const company = row.cells[4].textContent.toUpperCase();

                        if (candidateName.includes(filter) || jobTitle.includes(filter) || company.includes(filter)) {
                            tableBody.appendChild(row);
                            visibleCount++;
                        }
                    }
                });

                if (visibleCount === 0) {
                    tableBody.innerHTML = `<tr><td colspan="6" class="no-data"><i class="bi bi-search"></i><p>No matching candidates found</p></td></tr>`;
                }
            }
        });

        function viewCandidateDetails(button) {
            const data = {
                name: button.dataset.name,
                email: button.dataset.email,
                phone: button.dataset.phone,
                age: button.dataset.age,
                sex: button.dataset.sex,
                department: button.dataset.department,
                degree: button.dataset.degree,
                cgpa: button.dataset.cgpa,
                backlogs: button.dataset.backlogs,
                skills: button.dataset.skills,
                start_year: button.dataset.startYear,
                end_year: button.dataset.endYear,
                semester: button.dataset.semester,
                job: button.dataset.job,
                company: button.dataset.company
            };
            
            document.getElementById('modalName').textContent = data.name;
            document.getElementById('modalEmail').textContent = data.email;
            document.getElementById('modalPhone').textContent = data.phone;
            document.getElementById('modalAge').textContent = data.age + ' years';
            document.getElementById('modalSex').textContent = data.sex;
            
            document.getElementById('modalDepartment').textContent = data.department;
            document.getElementById('modalDegree').textContent = data.degree;
            document.getElementById('modalCgpa').textContent = data.cgpa;
            
            const backlogsEl = document.getElementById('modalBacklogs');
            const backlogValue = data.backlogs.toLowerCase();
            const hasBacklog = !['no', '0', 'no backlogs', 'not applicable', 'n/a'].includes(backlogValue);
            backlogsEl.textContent = backlogValue === '0' ? 'No Backlogs' : data.backlogs;
            backlogsEl.style.color = hasBacklog ? 'var(--danger)' : 'var(--success)';
            
            document.getElementById('modalCoursePeriod').textContent = data.start_year + ' - ' + data.end_year;
            document.getElementById('modalSemester').textContent = 'Semester ' + data.semester;
            
            const skillsContainer = document.getElementById('modalSkills');
            skillsContainer.innerHTML = '';
            const skills = data.skills.split(',').map(s => s.trim()).filter(s => s && s !== 'N/A');
            if (skills.length > 0) {
                skills.forEach(skill => {
                    const tag = document.createElement('span');
                    tag.className = 'skill-tag';
                    tag.textContent = skill;
                    skillsContainer.appendChild(tag);
                });
            } else {
                skillsContainer.innerHTML = '<span class="item-value">No skills listed</span>';
            }
            
            document.getElementById('modalJob').textContent = data.job;
            document.getElementById('modalCompany').textContent = data.company;
            
            document.getElementById('candidateModal').classList.add('active');
        }

        function viewResume(path, name) {
            console.log('Resume path:', path); // Debug log
            console.log('Full URL:', window.location.origin + path); // Debug log
            document.getElementById('resumeCandidateName').textContent = name;
            const iframe = document.getElementById('resumeFrame');
            iframe.src = path + '#toolbar=0';
            document.getElementById('resumeModal').classList.add('active');
            
            iframe.onerror = function() {
                console.error('Error loading PDF:', path);
            };
            iframe.onload = function() {
                console.log('PDF loaded successfully');
            };
        }

        function closeModal(event) {
            if (!event || event.target.id === 'candidateModal') {
                document.getElementById('candidateModal').classList.remove('active');
            }
        }

        function closeResumeModal(event) {
            if (!event || event.target.id === 'resumeModal') {
                document.getElementById('resumeModal').classList.remove('active');
                setTimeout(() => {
                    document.getElementById('resumeFrame').src = '';
                }, 300);
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeResumeModal();
            }
        });
    </script>
</body>
</html>

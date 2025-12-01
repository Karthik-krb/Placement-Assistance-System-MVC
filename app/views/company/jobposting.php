<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post New Job - Company Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="/PAS/public/css/company-dashboard.css?v=10" rel="stylesheet" />
    <link href="/PAS/public/css/job-posting.css?v=5" rel="stylesheet" />
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
            <li class="active">
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
                <h1><i class="bi bi-plus-circle-fill me-3"></i>Post New Job</h1>
                <p class="mb-0">Create a new job posting to attract talented candidates.</p>
            </div>
        </header>

        <?php if (!empty($errors ?? [])): ?>
            <div class="alert alert-danger alert-dismissible fade show modern-alert" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="job-posting-container">
            <form method="POST" action="/PAS/public/company/jobposting" class="job-posting-form">
                
                <div class="form-section">
                    <h3 class="section-title"><i class="bi bi-info-circle-fill"></i> Basic Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="job_title">Job Title <span class="required">*</span></label>
                            <input type="text" name="job_title" id="job_title" placeholder="e.g., Software Engineer" 
                                   value="<?= htmlspecialchars($old['job_title'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="job_type">Job Type <span class="required">*</span></label>
                            <select name="job_type" id="job_type" required>
                                <option value="">Select Job Type</option>
                                <option value="Full-Time" <?= ($old['job_type'] ?? '') == 'Full-Time' ? 'selected' : '' ?>>Full-Time</option>
                                <option value="Part-Time" <?= ($old['job_type'] ?? '') == 'Part-Time' ? 'selected' : '' ?>>Part-Time</option>
                                <option value="Internship" <?= ($old['job_type'] ?? '') == 'Internship' ? 'selected' : '' ?>>Internship</option>
                                <option value="Contract" <?= ($old['job_type'] ?? '') == 'Contract' ? 'selected' : '' ?>>Contract</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="location">Job Location <span class="required">*</span></label>
                            <input type="text" name="location" id="location" placeholder="e.g., Bangalore, Karnataka" 
                                   value="<?= htmlspecialchars($old['location'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="num_openings">Number of Openings <span class="required">*</span></label>
                            <input type="number" name="num_openings" id="num_openings" placeholder="e.g., 5" 
                                   min="1" value="<?= htmlspecialchars($old['num_openings'] ?? '1') ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="application_deadline">Application Deadline Date <span class="required">*</span></label>
                            <input type="date" name="application_deadline" id="application_deadline" 
                                   value="<?= htmlspecialchars($old['application_deadline'] ?? '') ?>" 
                                   min="<?= date('Y-m-d') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="deadline_time">Deadline Time <span class="required">*</span></label>
                            <input type="time" name="deadline_time" id="deadline_time" 
                                   value="<?= htmlspecialchars($old['deadline_time'] ?? '23:59') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title"><i class="bi bi-mortarboard-fill"></i> Education & Requirements</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="degree_required">Degree Required <span class="required">*</span></label>
                            <select name="degree_required" id="degree_required" required>
                                <option value="">Select Degree</option>
                                <option value="B.Tech" <?= ($old['degree_required'] ?? '') == 'B.Tech' ? 'selected' : '' ?>>B.Tech (Bachelor of Technology)</option>
                                <option value="B.E." <?= ($old['degree_required'] ?? '') == 'B.E.' ? 'selected' : '' ?>>B.E. (Bachelor of Engineering)</option>
                                <option value="B.Tech/B.E." <?= ($old['degree_required'] ?? '') == 'B.Tech/B.E.' ? 'selected' : '' ?>>B.Tech/B.E. (Both Accepted)</option>
                                <option value="MCA" <?= ($old['degree_required'] ?? '') == 'MCA' ? 'selected' : '' ?>>MCA (Master of Computer Applications)</option>
                                <option value="M.Tech" <?= ($old['degree_required'] ?? '') == 'M.Tech' ? 'selected' : '' ?>>M.Tech (Master of Technology)</option>
                                <option value="M.E." <?= ($old['degree_required'] ?? '') == 'M.E.' ? 'selected' : '' ?>>M.E. (Master of Engineering)</option>
                                <option value="M.Tech/M.E." <?= ($old['degree_required'] ?? '') == 'M.Tech/M.E.' ? 'selected' : '' ?>>M.Tech/M.E. (Both Accepted)</option>
                                <option value="BCA" <?= ($old['degree_required'] ?? '') == 'BCA' ? 'selected' : '' ?>>BCA (Bachelor of Computer Applications)</option>
                                <option value="B.Sc" <?= ($old['degree_required'] ?? '') == 'B.Sc' ? 'selected' : '' ?>>B.Sc (Bachelor of Science)</option>
                                <option value="M.Sc" <?= ($old['degree_required'] ?? '') == 'M.Sc' ? 'selected' : '' ?>>M.Sc (Master of Science)</option>
                                <option value="MBA" <?= ($old['degree_required'] ?? '') == 'MBA' ? 'selected' : '' ?>>MBA (Master of Business Administration)</option>
                                <option value="Any Degree" <?= ($old['degree_required'] ?? '') == 'Any Degree' ? 'selected' : '' ?>>Any Degree (All Accepted)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="course_specialization">Course/Specialization <span class="required">*</span></label>
                            <select name="course_specialization" id="course_specialization" required>
                                <option value="">Select Branch/Department</option>
                                <option value="Computer Science and Engineering" <?= ($old['course_specialization'] ?? '') == 'Computer Science and Engineering' ? 'selected' : '' ?>>Computer Science and Engineering</option>
                                <option value="Information Technology" <?= ($old['course_specialization'] ?? '') == 'Information Technology' ? 'selected' : '' ?>>Information Technology</option>
                                <option value="Electronics and Communication Engineering" <?= ($old['course_specialization'] ?? '') == 'Electronics and Communication Engineering' ? 'selected' : '' ?>>Electronics and Communication Engineering</option>
                                <option value="Electrical and Electronics Engineering" <?= ($old['course_specialization'] ?? '') == 'Electrical and Electronics Engineering' ? 'selected' : '' ?>>Electrical and Electronics Engineering</option>
                                <option value="Mechanical Engineering" <?= ($old['course_specialization'] ?? '') == 'Mechanical Engineering' ? 'selected' : '' ?>>Mechanical Engineering</option>
                                <option value="Civil Engineering" <?= ($old['course_specialization'] ?? '') == 'Civil Engineering' ? 'selected' : '' ?>>Civil Engineering</option>
                                <option value="Master of Computer Applications (MCA)" <?= ($old['course_specialization'] ?? '') == 'Master of Computer Applications (MCA)' ? 'selected' : '' ?>>Master of Computer Applications (MCA)</option>
                                <option value="Artificial Intelligence and Data Science" <?= ($old['course_specialization'] ?? '') == 'Artificial Intelligence and Data Science' ? 'selected' : '' ?>>Artificial Intelligence and Data Science</option>
                                <option value="Cyber Security" <?= ($old['course_specialization'] ?? '') == 'Cyber Security' ? 'selected' : '' ?>>Cyber Security</option>
                                <option value="Any Branch" <?= ($old['course_specialization'] ?? '') == 'Any Branch' ? 'selected' : '' ?>>Any Branch (All Departments)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cgpa_criteria">Minimum CGPA Required <span class="required">*</span></label>
                            <input type="number" name="cgpa_criteria" id="cgpa_criteria" placeholder="e.g., 7.5" 
                                   step="0.01" min="0" max="10" value="<?= htmlspecialchars($old['cgpa_criteria'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="supply_backlog_policy">Supply/Backlog Policy <span class="required">*</span></label>
                            <select name="supply_backlog_policy" id="supply_backlog_policy" required>
                                <option value="">Select Policy</option>
                                <option value="No Backlogs" <?= ($old['supply_backlog_policy'] ?? '') == 'No Backlogs' ? 'selected' : '' ?>>No Backlogs Allowed</option>
                                <option value="Up to 2" <?= ($old['supply_backlog_policy'] ?? '') == 'Up to 2' ? 'selected' : '' ?>>Up to 2 Backlogs</option>
                                <option value="Up to 5" <?= ($old['supply_backlog_policy'] ?? '') == 'Up to 5' ? 'selected' : '' ?>>Up to 5 Backlogs</option>
                                <option value="Any" <?= ($old['supply_backlog_policy'] ?? '') == 'Any' ? 'selected' : '' ?>>Any (No Restriction)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title"><i class="bi bi-file-text-fill"></i> Job Description & Skills</h3>
                    
                    <div class="form-group">
                        <label for="job_description">Job Description <span class="required">*</span></label>
                        <textarea name="job_description" id="job_description" rows="6" 
                                  placeholder="Describe the role, responsibilities, and requirements..." required><?= htmlspecialchars($old['job_description'] ?? '') ?></textarea>
                        <small class="form-text">Provide detailed information about the role and what you're looking for.</small>
                    </div>
                    <div class="form-group">
                        <label>Required Skills <span class="required">*</span></label>
                        <div class="skills-container">
                            <?php 
                            $skills = [
                                'Python', 'Java', 'C++', 'JavaScript', 'SQL', 
                                'Flutter', 'Full Stack Developer', 'Backend Developer', 
                                'Bug Bounty', 'Game Developer', 'React', 'Node.js',
                                'Data Structures', 'Machine Learning', 'Cloud Computing'
                            ];
                            $selected_skills = $old['skills'] ?? [];
                            foreach ($skills as $skill): 
                                $skill_id = strtolower(str_replace([' ', '.'], '', $skill));
                                $checked = in_array($skill, $selected_skills) ? 'checked' : '';
                            ?>
                                <div class="skill-option">
                                    <input type="checkbox" id="<?= $skill_id ?>" name="skills[]" 
                                           value="<?= htmlspecialchars($skill) ?>" <?= $checked ?>>
                                    <label for="<?= $skill_id ?>"><?= htmlspecialchars($skill) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <small class="form-text">Select at least one skill required for this position.</small>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-circle-fill me-2"></i>Post Job
                    </button>
                    <a href="/PAS/public/company/postedjobs" class="btn-cancel">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
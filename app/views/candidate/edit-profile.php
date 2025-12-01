<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile - Candidate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="/PAS/public/css/candidate-dashboard.css?v=2" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
      min-height: 100vh;
    }
    
    .edit-form-container {
      max-width: 1100px;
      margin: 2rem auto;
      padding: 0 1rem;
    }
    
    .page-header {
      background: linear-gradient(135deg, rgb(26, 69, 198) 0%, rgb(41, 98, 255) 100%);
      color: white;
      padding: 2rem;
      border-radius: 25px;
      text-align: center;
      box-shadow: 0 8px 20px rgba(26, 69, 198, 0.3);
      margin-bottom: 2rem;
      position: relative;
      overflow: hidden;
    }
    
    .page-header::before {
      content: '';
      position: absolute;
      top: -50px;
      right: -50px;
      width: 200px;
      height: 200px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
    }
    
    .page-header::after {
      content: '';
      position: absolute;
      bottom: -30px;
      left: -30px;
      width: 150px;
      height: 150px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 50%;
    }
    
    .page-header h2 {
      position: relative;
      z-index: 1;
      margin: 0;
      font-weight: 700;
    }
    
    .page-header p {
      position: relative;
      z-index: 1;
      margin: 0.5rem 0 0 0;
      opacity: 0.95;
    }
    
    .form-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
      margin-bottom: 1.5rem;
      overflow: hidden;
      transition: all 0.3s ease;
    }
    
    .form-card:hover {
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
      transform: translateY(-2px);
    }
    
    .section-header {
      background: linear-gradient(135deg, rgb(26, 69, 198) 0%, rgb(41, 98, 255) 100%);
      color: white;
      padding: 1.3rem 2rem;
      border: none;
      position: relative;
    }
    
    .section-header::after {
      content: '';
      position: absolute;
      top: 50%;
      right: 2rem;
      transform: translateY(-50%);
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 50%;
    }
    
    .section-header h5 {
      margin: 0;
      font-weight: 600;
      position: relative;
      z-index: 1;
    }
    
    .section-body {
      background: white;
      padding: 2rem;
      border: none;
    }
    
    .current-photo {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
      border: 5px solid rgb(26, 69, 198);
      box-shadow: 0 4px 12px rgba(26, 69, 198, 0.2);
    }
    
    .photo-placeholder {
      width: 150px;
      height: 150px;
      background: linear-gradient(135deg, rgb(26, 69, 198) 0%, rgb(41, 98, 255) 100%);
      color: white;
      font-size: 3.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      border: 5px solid rgb(26, 69, 198);
      box-shadow: 0 4px 12px rgba(26, 69, 198, 0.2);
      font-weight: 600;
    }
    
    .form-label {
      color: #2d3748;
      font-size: 0.95rem;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: rgb(26, 69, 198);
      box-shadow: 0 0 0 0.2rem rgba(26, 69, 198, 0.15);
    }
    
    .btn-primary {
      background: linear-gradient(135deg, rgb(26, 69, 198) 0%, rgb(41, 98, 255) 100%);
      border: none;
      padding: 0.8rem 3rem;
      border-radius: 12px;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(26, 69, 198, 0.3);
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(26, 69, 198, 0.4);
    }
    
    .btn-secondary {
      background: #6c757d;
      border: none;
      padding: 0.8rem 3rem;
      border-radius: 12px;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
      transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(108, 117, 125, 0.4);
      background: #5a6268;
    }
    
    .form-check-input:checked {
      background-color: rgb(26, 69, 198);
      border-color: rgb(26, 69, 198);
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="#">Placement Assistance System</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-md-auto mx-5 px-4">
          <a class="nav-link" href="/PAS/public/candidate/dashboard">Home</a>
          <a class="nav-link" href="/PAS/public/candidate/job-postings">Job Postings</a>
          <a class="nav-link" href="/PAS/public/candidate/applications">Check Application</a>
          <a class="nav-link" href="/PAS/public/candidate/feedback">Feedback</a>
          <a class="nav-link" href="/PAS/public/candidate/logout">Log Out</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="edit-form-container">
    <div class="page-header">
      <h2><i class="bi bi-pencil-square"></i> Edit Your Profile</h2>
      <p>Update your information to help companies find you</p>
    </div>

    <?php if (!empty($errors ?? [])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="bi bi-exclamation-triangle-fill"></i> Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (!empty($success ?? '')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <form action="/PAS/public/candidate/edit-profile" method="POST" enctype="multipart/form-data">
      
      <div class="form-card">
        <div class="section-header">
          <h5><i class="bi bi-camera-fill me-2"></i>Profile Photo</h5>
        </div>
        <div class="section-body">
          <div class="row align-items-center">
            <div class="col-md-3 text-center">
              <?php if (!empty($candidate['photo'])): ?>
                <img src="<?= htmlspecialchars($candidate['photo']) ?>" alt="Current Photo" class="current-photo">
              <?php else: ?>
                <div class="photo-placeholder mx-auto">
                  <?= strtoupper(substr($candidate['name'], 0, 1)) ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="col-md-9">
              <label for="photo" class="form-label fw-bold">Upload New Photo</label>
              <input type="file" class="form-control" id="photo" name="c_photo" accept="image/jpeg,image/png,image/jpg">
              <div class="form-text">JPG, PNG or JPEG. Max 2MB. Leave empty to keep current photo.</div>
            </div>
          </div>
        </div>
      </div>

      <div class="form-card">
        <div class="section-header">
          <h5><i class="bi bi-person-fill me-2"></i>Personal Information</h5>
        </div>
        <div class="section-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="name" class="form-label fw-bold">Full Name *</label>
              <input type="text" class="form-control" id="name" name="c_name" 
                     value="<?= htmlspecialchars($candidate['name']) ?>" required>
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label fw-bold">Email Address</label>
              <input type="email" class="form-control" id="email" 
                     value="<?= htmlspecialchars($candidate['email']) ?>" disabled>
              <div class="form-text">Email cannot be changed</div>
            </div>
            <div class="col-md-6">
              <label for="phone" class="form-label fw-bold">Phone Number</label>
              <input type="tel" class="form-control" id="phone" name="c_phone" 
                     value="<?= htmlspecialchars($candidate['phone'] ?? '') ?>" 
                     pattern="[0-9]{10}" placeholder="10 digit number">
            </div>
            <div class="col-md-3">
              <label for="age" class="form-label fw-bold">Age</label>
              <input type="number" class="form-control" id="age" name="c_age" 
                     value="<?= htmlspecialchars($candidate['age'] ?? '') ?>" 
                     min="17" max="100">
            </div>
            <div class="col-md-3">
              <label for="sex" class="form-label fw-bold">Gender</label>
              <select class="form-select" id="sex" name="c_sex">
                <option value="">Select</option>
                <option value="Male" <?= ($candidate['sex'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= ($candidate['sex'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= ($candidate['sex'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
              </select>
            </div>
            <div class="col-12">
              <label for="address" class="form-label fw-bold">Address</label>
              <textarea class="form-control" id="address" name="c_address" rows="2"><?= htmlspecialchars($candidate['address'] ?? '') ?></textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="form-card">
        <div class="section-header">
          <h5><i class="bi bi-mortarboard-fill me-2"></i>Academic Information</h5>
        </div>
        <div class="section-body">
          <div class="row g-3">
          <div class="col-md-6">
            <label for="university" class="form-label fw-bold">University Name</label>
            <select class="form-select" id="university" name="c_university">
              <option value="">Select University</option>
              <option value="CUSAT (Cochin University of Science and Technology)" <?= ($candidate['university'] ?? '') === 'CUSAT (Cochin University of Science and Technology)' ? 'selected' : '' ?>>CUSAT (Cochin University of Science and Technology)</option>
              <option value="APJ Abdul Kalam Technological University (KTU)" <?= ($candidate['university'] ?? '') === 'APJ Abdul Kalam Technological University (KTU)' ? 'selected' : '' ?>>APJ Abdul Kalam Technological University (KTU)</option>
              <option value="Kerala University" <?= ($candidate['university'] ?? '') === 'Kerala University' ? 'selected' : '' ?>>Kerala University</option>
              <option value="Calicut University" <?= ($candidate['university'] ?? '') === 'Calicut University' ? 'selected' : '' ?>>Calicut University</option>
              <option value="Kannur University" <?= ($candidate['university'] ?? '') === 'Kannur University' ? 'selected' : '' ?>>Kannur University</option>
              <option value="Mahatma Gandhi University" <?= ($candidate['university'] ?? '') === 'Mahatma Gandhi University' ? 'selected' : '' ?>>Mahatma Gandhi University</option>
              <option value="National Institute of Technology (NIT)" <?= ($candidate['university'] ?? '') === 'National Institute of Technology (NIT)' ? 'selected' : '' ?>>National Institute of Technology (NIT)</option>
              <option value="Indian Institute of Technology (IIT)" <?= ($candidate['university'] ?? '') === 'Indian Institute of Technology (IIT)' ? 'selected' : '' ?>>Indian Institute of Technology (IIT)</option>
              <option value="Amrita Vishwa Vidyapeetham" <?= ($candidate['university'] ?? '') === 'Amrita Vishwa Vidyapeetham' ? 'selected' : '' ?>>Amrita Vishwa Vidyapeetham</option>
              <option value="Other">Other (Type Manually)</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="college" class="form-label fw-bold">College Name</label>
            <input type="text" class="form-control" id="college" name="c_college" 
                   list="collegeList"
                   value="<?= htmlspecialchars($candidate['college'] ?? '') ?>"
                   placeholder="Start typing or select from list">
            <datalist id="collegeList">
              <option value="School of Engineering, CUSAT">
              <option value="College of Engineering Kuttanad, CUSAT">
              <option value="School of Management Studies, CUSAT">
              <option value="Cochin College of Engineering and Technology">
              <option value="Rajagiri School of Engineering & Technology">
              <option value="Toc H Institute of Science and Technology">
              <option value="Mar Athanasius College of Engineering">
              <option value="Amal Jyothi College of Engineering">
              
              <option value="Government Engineering College, Thrissur">
              <option value="Government Engineering College, Idukki">
              <option value="Government Engineering College, Wayanad">
              <option value="Government Engineering College, Palakkad">
              <option value="Government Engineering College, Kozhikode">
              <option value="Model Engineering College">
              <option value="TKM College of Engineering">
              <option value="NSS College of Engineering">
              <option value="Govt. College of Engineering, Kannur">
              
              <option value="College of Engineering Trivandrum (CET)">
              <option value="University College of Engineering, Kariavattom">
              <option value="Government Engineering College, Barton Hill">
              <option value="LBS Institute of Technology for Women">
              <option value="Sree Chitra Thirunal College of Engineering">
              
              <option value="National Institute of Technology, Calicut (NIT-C)">
              <option value="Indian Institute of Technology, Palakkad (IIT-P)">
              <option value="Indian Institute of Space Science and Technology (IIST)">
              
              <option value="Amrita School of Engineering, Amritapuri">
              <option value="Amrita School of Engineering, Coimbatore">
              <option value="College of Engineering, Vadakara">
              <option value="Marian Engineering College">
              <option value="SCMS School of Engineering and Technology">
              <option value="Albertian Institute of Science and Technology">
            </datalist>
            <div class="form-text">Type your college name or select from suggestions</div>
          </div>
          <div class="col-md-6">
            <label for="degree" class="form-label fw-bold">Degree *</label>
            <select class="form-select" id="degree" name="c_degree" required>
              <option value="">Select Degree</option>
              <option value="B.Tech" <?= ($candidate['degree'] ?? '') === 'B.Tech' ? 'selected' : '' ?>>B.Tech (Bachelor of Technology)</option>
              <option value="B.E." <?= ($candidate['degree'] ?? '') === 'B.E.' ? 'selected' : '' ?>>B.E. (Bachelor of Engineering)</option>
              <option value="MCA" <?= ($candidate['degree'] ?? '') === 'MCA' ? 'selected' : '' ?>>MCA (Master of Computer Applications)</option>
              <option value="M.Tech" <?= ($candidate['degree'] ?? '') === 'M.Tech' ? 'selected' : '' ?>>M.Tech (Master of Technology)</option>
              <option value="M.E." <?= ($candidate['degree'] ?? '') === 'M.E.' ? 'selected' : '' ?>>M.E. (Master of Engineering)</option>
              <option value="BCA" <?= ($candidate['degree'] ?? '') === 'BCA' ? 'selected' : '' ?>>BCA (Bachelor of Computer Applications)</option>
              <option value="B.Sc" <?= ($candidate['degree'] ?? '') === 'B.Sc' ? 'selected' : '' ?>>B.Sc (Bachelor of Science)</option>
              <option value="M.Sc" <?= ($candidate['degree'] ?? '') === 'M.Sc' ? 'selected' : '' ?>>M.Sc (Master of Science)</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="department" class="form-label fw-bold">Department/Branch</label>
            <select class="form-select" id="department" name="c_department">
              <option value="">Select Department</option>
              <option value="Computer Science and Engineering" <?= ($candidate['department'] ?? '') === 'Computer Science and Engineering' ? 'selected' : '' ?>>Computer Science and Engineering</option>
              <option value="Information Technology" <?= ($candidate['department'] ?? '') === 'Information Technology' ? 'selected' : '' ?>>Information Technology</option>
              <option value="Electronics and Communication Engineering" <?= ($candidate['department'] ?? '') === 'Electronics and Communication Engineering' ? 'selected' : '' ?>>Electronics and Communication Engineering</option>
              <option value="Electrical and Electronics Engineering" <?= ($candidate['department'] ?? '') === 'Electrical and Electronics Engineering' ? 'selected' : '' ?>>Electrical and Electronics Engineering</option>
              <option value="Mechanical Engineering" <?= ($candidate['department'] ?? '') === 'Mechanical Engineering' ? 'selected' : '' ?>>Mechanical Engineering</option>
              <option value="Civil Engineering" <?= ($candidate['department'] ?? '') === 'Civil Engineering' ? 'selected' : '' ?>>Civil Engineering</option>
              <option value="Master of Computer Applications (MCA)" <?= ($candidate['department'] ?? '') === 'Master of Computer Applications (MCA)' ? 'selected' : '' ?>>Master of Computer Applications (MCA)</option>
              <option value="Artificial Intelligence and Data Science" <?= ($candidate['department'] ?? '') === 'Artificial Intelligence and Data Science' ? 'selected' : '' ?>>Artificial Intelligence and Data Science</option>
              <option value="Cyber Security" <?= ($candidate['department'] ?? '') === 'Cyber Security' ? 'selected' : '' ?>>Cyber Security</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="cgpa" class="form-label fw-bold">CGPA *</label>
            <input type="number" class="form-control" id="cgpa" name="c_cgpa" 
                   value="<?= htmlspecialchars($candidate['cgpa']) ?>" 
                   step="0.01" min="0" max="10" required>
          </div>
          <div class="col-md-4">
            <label for="start_year" class="form-label fw-bold">Course Start Year</label>
            <input type="number" class="form-control" id="start_year" name="c_course_start_year" 
                   value="<?= htmlspecialchars($candidate['course_start_year'] ?? '') ?>" 
                   min="2000" max="2030">
          </div>
          <div class="col-md-4">
            <label for="end_year" class="form-label fw-bold">Course End Year</label>
            <input type="number" class="form-control" id="end_year" name="c_course_end_year" 
                   value="<?= htmlspecialchars($candidate['course_end_year'] ?? '') ?>" 
                   min="2000" max="2035">
          </div>
          <div class="col-md-4">
            <label for="semester" class="form-label fw-bold">Current Semester</label>
            <select class="form-select" id="semester" name="c_current_semester">
              <option value="">Select</option>
              <?php for ($i = 1; $i <= 8; $i++): ?>
                <option value="<?= $i ?>" <?= ($candidate['current_semester'] ?? '') == $i ? 'selected' : '' ?>>
                  Semester <?= $i ?>
                </option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label for="supply_no" class="form-label fw-bold">Number of Supplies</label>
            <input type="number" class="form-control" id="supply_no" name="c_supply_no" 
                   value="<?= htmlspecialchars($candidate['supply_no'] ?? '0') ?>" 
                   min="0" max="20" placeholder="0 if none">
            <div class="form-text">Enter 0 if no backlogs</div>
          </div>
        </div>
      </div>
      </div>

      <div class="form-card">
        <div class="section-header">
          <h5><i class="bi bi-gear-fill me-2"></i>Skills & Professional Links</h5>
        </div>
        <div class="section-body">
          <div class="mb-4">
          <label class="form-label fw-bold">Skills (Select at least one) *</label>
          <div class="row g-2">
            <?php 
              $skills = [
                'Python', 'Java', 'C++', 'JavaScript', 'SQL', 
                'Flutter', 'Full Stack Developer', 'Backend Developer', 
                'Bug Bounty', 'Game Developer'
              ];
              $selected_skills = explode(', ', $candidate['skills']);
              foreach ($skills as $skill): 
            ?>
            <div class="col-md-6">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" 
                       id="skill_<?= strtolower(str_replace(' ', '_', $skill)) ?>" 
                       name="c_skills[]" value="<?= htmlspecialchars($skill) ?>"
                       <?= in_array($skill, $selected_skills) ? 'checked' : '' ?>>
                <label class="form-check-label" for="skill_<?= strtolower(str_replace(' ', '_', $skill)) ?>">
                  <?= htmlspecialchars($skill) ?>
                </label>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label for="linkedin" class="form-label fw-bold">
              <i class="bi bi-linkedin text-primary"></i> LinkedIn Profile
            </label>
            <input type="url" class="form-control" id="linkedin" name="c_linkedin" 
                   value="<?= htmlspecialchars($candidate['linkedin'] ?? '') ?>" 
                   placeholder="https://linkedin.com/in/yourprofile">
          </div>
          <div class="col-md-6">
            <label for="github" class="form-label fw-bold">
              <i class="bi bi-github"></i> GitHub Profile
            </label>
            <input type="url" class="form-control" id="github" name="c_github" 
                   value="<?= htmlspecialchars($candidate['github'] ?? '') ?>" 
                   placeholder="https://github.com/yourusername">
          </div>
        </div>
        </div>
      </div>

      <div class="text-center my-4 pb-4">
        <button type="submit" class="btn btn-primary btn-lg px-5">
          <i class="bi bi-check-circle-fill me-2"></i>Save Changes
        </button>
        <a href="/PAS/public/candidate/dashboard" class="btn btn-secondary btn-lg px-5 ms-3">
          <i class="bi bi-x-circle-fill me-2"></i>Cancel
        </a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/PAS/public/css/register.css?v=3">
</head>
<body>
<div class="container">
    <h2>Candidate Registration</h2>
    
    <?php if (!empty($errors ?? [])): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form action="/PAS/public/auth/candidate/register" method="POST" enctype="multipart/form-data">
        <div class="section-title">Personal Information</div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="naam">Full Name *</label>
                <input type="text" id="naam" name="c_name" placeholder="Enter your full name" 
                       value="<?= htmlspecialchars($old['c_name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="mail">Email Address *</label>
                <input type="email" id="mail" name="c_email" placeholder="Enter your email" 
                       value="<?= htmlspecialchars($old['c_email'] ?? '') ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="pass">Password *</label>
                <input type="password" id="pass" name="c_password" placeholder="Min 6 characters" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="c_phone" placeholder="Enter phone number" 
                       value="<?= htmlspecialchars($old['c_phone'] ?? '') ?>" pattern="[0-9]{10}">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="c_age" placeholder="Enter your age" 
                       min="17" max="100" value="<?= htmlspecialchars($old['c_age'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="sex">Gender</label>
                <select id="sex" name="c_sex">
                    <option value="">Select Gender</option>
                    <option value="Male" <?= ($old['c_sex'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= ($old['c_sex'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= ($old['c_sex'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="c_address" placeholder="Enter your address" rows="2"><?= htmlspecialchars($old['c_address'] ?? '') ?></textarea>
        </div>
        
        <div class="section-title">Academic Information</div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="university">University Name</label>
                <select class="form-select" id="university" name="c_university">
                    <option value="">Select University</option>
                    <option value="CUSAT (Cochin University of Science and Technology)" <?= ($old['c_university'] ?? '') === 'CUSAT (Cochin University of Science and Technology)' ? 'selected' : '' ?>>CUSAT (Cochin University of Science and Technology)</option>
                    <option value="APJ Abdul Kalam Technological University (KTU)" <?= ($old['c_university'] ?? '') === 'APJ Abdul Kalam Technological University (KTU)' ? 'selected' : '' ?>>APJ Abdul Kalam Technological University (KTU)</option>
                    <option value="Kerala University" <?= ($old['c_university'] ?? '') === 'Kerala University' ? 'selected' : '' ?>>Kerala University</option>
                    <option value="Calicut University" <?= ($old['c_university'] ?? '') === 'Calicut University' ? 'selected' : '' ?>>Calicut University</option>
                    <option value="Kannur University" <?= ($old['c_university'] ?? '') === 'Kannur University' ? 'selected' : '' ?>>Kannur University</option>
                    <option value="Mahatma Gandhi University" <?= ($old['c_university'] ?? '') === 'Mahatma Gandhi University' ? 'selected' : '' ?>>Mahatma Gandhi University</option>
                    <option value="National Institute of Technology (NIT)" <?= ($old['c_university'] ?? '') === 'National Institute of Technology (NIT)' ? 'selected' : '' ?>>National Institute of Technology (NIT)</option>
                    <option value="Indian Institute of Technology (IIT)" <?= ($old['c_university'] ?? '') === 'Indian Institute of Technology (IIT)' ? 'selected' : '' ?>>Indian Institute of Technology (IIT)</option>
                    <option value="Amrita Vishwa Vidyapeetham" <?= ($old['c_university'] ?? '') === 'Amrita Vishwa Vidyapeetham' ? 'selected' : '' ?>>Amrita Vishwa Vidyapeetham</option>
                    <option value="Other">Other (Type Manually)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="college">College Name</label>
                <input type="text" id="college" name="c_college" 
                       list="collegeList"
                       placeholder="Start typing or select from list" 
                       value="<?= htmlspecialchars($old['c_college'] ?? '') ?>">
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
                <small class="form-text text-muted">Type your college name or select from suggestions. You can enter manually if not in list.</small>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="department">Department/Branch</label>
                <select class="form-select" id="department" name="c_department">
                    <option value="">Select Department</option>
                    <option value="Computer Science and Engineering" <?= ($old['c_department'] ?? '') === 'Computer Science and Engineering' ? 'selected' : '' ?>>Computer Science and Engineering</option>
                    <option value="Information Technology" <?= ($old['c_department'] ?? '') === 'Information Technology' ? 'selected' : '' ?>>Information Technology</option>
                    <option value="Electronics and Communication Engineering" <?= ($old['c_department'] ?? '') === 'Electronics and Communication Engineering' ? 'selected' : '' ?>>Electronics and Communication Engineering</option>
                    <option value="Electrical and Electronics Engineering" <?= ($old['c_department'] ?? '') === 'Electrical and Electronics Engineering' ? 'selected' : '' ?>>Electrical and Electronics Engineering</option>
                    <option value="Mechanical Engineering" <?= ($old['c_department'] ?? '') === 'Mechanical Engineering' ? 'selected' : '' ?>>Mechanical Engineering</option>
                    <option value="Civil Engineering" <?= ($old['c_department'] ?? '') === 'Civil Engineering' ? 'selected' : '' ?>>Civil Engineering</option>
                    <option value="Master of Computer Applications (MCA)" <?= ($old['c_department'] ?? '') === 'Master of Computer Applications (MCA)' ? 'selected' : '' ?>>Master of Computer Applications (MCA)</option>
                    <option value="Artificial Intelligence and Data Science" <?= ($old['c_department'] ?? '') === 'Artificial Intelligence and Data Science' ? 'selected' : '' ?>>Artificial Intelligence and Data Science</option>
                    <option value="Cyber Security" <?= ($old['c_department'] ?? '') === 'Cyber Security' ? 'selected' : '' ?>>Cyber Security</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="cgpa">CGPA *</label>
                <input type="number" id="cgpa" name="c_cgpa" step="0.01" min="0" max="10" 
                       placeholder="Enter your CGPA" value="<?= htmlspecialchars($old['c_cgpa'] ?? '') ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="start_year">Course Start Year</label>
                <input type="number" id="start_year" name="c_course_start_year" placeholder="e.g., 2021" 
                       min="2000" max="2030" value="<?= htmlspecialchars($old['c_course_start_year'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="end_year">Course End Year</label>
                <input type="number" id="end_year" name="c_course_end_year" placeholder="e.g., 2025" 
                       min="2000" max="2035" value="<?= htmlspecialchars($old['c_course_end_year'] ?? '') ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="semester">Current Semester</label>
            <select class="form-select" id="semester" name="c_current_semester">
                <option value="">Select Semester</option>
                <?php for ($i = 1; $i <= 8; $i++): ?>
                    <option value="<?= $i ?>" <?= ($old['c_current_semester'] ?? '') == $i ? 'selected' : '' ?>>Semester <?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="supply_no">Number of Supplies/Backlogs</label>
            <input type="number" id="supply_no" name="c_supply_no" 
                   placeholder="Enter number of supplies (0 if none)" 
                   min="0" max="20" 
                   value="<?= htmlspecialchars($old['c_supply_no'] ?? '0') ?>">
            <small class="form-text text-muted">Enter 0 if you have no pending supplies/backlogs</small>
        </div>
        
        <div class="section-title">Skills & Profile Links</div>
        
        <div class="form-group">
            <label>Skills (Select at least one) *</label>
            <div class="skills-container">
                <?php 
                $skills = [
                    'Python', 'Java', 'C++', 'JavaScript', 'SQL', 
                    'Flutter', 'Full Stack Developer', 'Backend Developer', 
                    'Bug Bounty', 'Game Developer'
                ];
                $selected_skills = $old['c_skills'] ?? [];
                foreach ($skills as $skill): 
                ?>
                <div class="skill-option">
                    <input type="checkbox" id="skill_<?= strtolower(str_replace(' ', '_', $skill)) ?>" 
                           name="c_skills[]" value="<?= htmlspecialchars($skill) ?>"
                           <?= in_array($skill, $selected_skills) ? 'checked' : '' ?>>
                    <label for="skill_<?= strtolower(str_replace(' ', '_', $skill)) ?>"><?= htmlspecialchars($skill) ?></label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="linkedin">LinkedIn Profile</label>
                <input type="url" id="linkedin" name="c_linkedin" placeholder="https://linkedin.com/in/yourprofile" 
                       value="<?= htmlspecialchars($old['c_linkedin'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="github">GitHub Profile</label>
                <input type="url" id="github" name="c_github" placeholder="https://github.com/yourusername" 
                       value="<?= htmlspecialchars($old['c_github'] ?? '') ?>">
            </div>
        </div>
        
        <div class="section-title">Upload Documents</div>
        
        <div class="form-group">
            <label>Profile Photo (JPG, PNG - Max 2MB)</label>
            <div class="file-input-wrapper">
                <label class="file-input-label">
                    <span class="file-input-text" id="photo-name">Choose a photo...</span>
                    <i class="fas fa-camera file-input-icon"></i>
                    <input type="file" id="photo" name="c_photo" accept="image/jpeg,image/png,image/jpg" 
                           onchange="updateFileName(this, 'photo-name')">
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label>Upload Resume (PDF only) *</label>
            <div class="file-input-wrapper">
                <label class="file-input-label">
                    <span class="file-input-text" id="resume-name">Choose a file...</span>
                    <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                    <input type="file" id="resume" name="c_resume" accept="application/pdf" 
                           required onchange="updateFileName(this, 'resume-name')">
                </label>
            </div>
        </div>
        
        <input type="submit" value="Register Now">
    </form>
    
    <p class="login-link">Already have an account? <a href="/PAS/public/auth/candidate/login">Login here</a></p>
</div>

<script>
function updateFileName(input, targetId) {
    const fileName = input.files[0]?.name || 'Choose a file...';
    document.getElementById(targetId).textContent = fileName;
}
</script>
</body>
</html>
        <div class="login-link">
            <p>Already have an account? <a href="/PAS/public/auth/candidate/login">Login here</a></p>
        </div>
    </form>
</div>

<script>
    function updateFileName(input) {
        const fileNameElement = document.getElementById('file-name');
        if (input.files.length > 0) {
            fileNameElement.textContent = input.files[0].name;
            fileNameElement.style.color = '#2c3e50';
        } else {
            fileNameElement.textContent = 'Choose a file...';
            fileNameElement.style.color = '#666';
        }
    }
</script>
</body>
</html>

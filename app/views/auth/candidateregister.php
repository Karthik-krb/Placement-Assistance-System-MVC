<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/PAS/public/css/register.css?v=1">
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
        <div class="form-group">
            <label for="naam">Full Name</label>
            <input type="text" id="naam" name="c_name" placeholder="Enter your full name" 
                   value="<?= htmlspecialchars($old['c_name'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label for="mail">Email Address</label>
            <input type="email" id="mail" name="c_email" placeholder="Enter your email" 
                   value="<?= htmlspecialchars($old['c_email'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label for="pass">Password</label>
            <input type="password" id="pass" name="c_password" placeholder="Create a password (min 6 characters)" required>
        </div>
        
        <div class="form-group">
            <label for="cgpa">CGPA</label>
            <input type="number" id="cgpa" name="c_cgpa" step="0.01" min="0" max="10" placeholder="Enter your CGPA" 
                   value="<?= htmlspecialchars($old['c_cgpa'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Skills (Select at least one)</label>
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
        
        <div class="form-group">
            <label>Upload Resume (PDF only)</label>
            <div class="file-input-wrapper">
                <label class="file-input-label">
                    <span class="file-input-text" id="file-name">Choose a file...</span>
                    <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                    <input type="file" id="resume" name="c_resume" accept="application/pdf" required onchange="updateFileName(this)">
                </label>
            </div>
        </div>
        
        <input type="submit" value="Register Now">
        
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

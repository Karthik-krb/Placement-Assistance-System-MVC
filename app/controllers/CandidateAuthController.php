<?php
class CandidateAuthController {
    private $pdo;
    private $config;

    public function __construct($config) {
        $this->config = $config;
        $this->pdo = Database::getInstance($config)->getConnection();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function showLogin($errors = [], $old = []) {
        $data = ['errors' => $errors, 'old' => $old];
        require __DIR__ . '/../views/auth/candidatelogin.php';
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $errors = [];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email.";
        if (strlen($password) < 3) $errors[] = "Enter a password.";

        if ($errors) return $this->showLogin($errors, ['email' => $email]);

        $candidateModel = new Candidate($this->pdo);
        $user = $candidateModel->authenticate($email, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            $_SESSION['role'] = 'candidate';
            header('Location: /PAS/public/candidate/dashboard');
            exit;
        }
        $errors[] = "Invalid Email or password.";
        return $this->showLogin($errors, ['email' => $email]);
    }

    public function showRegister($errors = [], $old = []) {
        $data = ['errors' => $errors, 'old' => $old];
        require __DIR__ . '/../views/auth/candidateregister.php';
    }

    public function register() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return $this->showRegister();
        }

        $c_name = trim($_POST['c_name'] ?? '');
        $c_email = trim($_POST['c_email'] ?? '');
        $c_password = $_POST['c_password'] ?? '';
        $c_cgpa = $_POST['c_cgpa'] ?? '';
        $c_skills = isset($_POST['c_skills']) ? implode(", ", $_POST['c_skills']) : '';

        $c_phone = trim($_POST['c_phone'] ?? '');
        $c_age = !empty($_POST['c_age']) ? (int)$_POST['c_age'] : null;
        $c_sex = !empty($_POST['c_sex']) ? $_POST['c_sex'] : null;
        $c_address = trim($_POST['c_address'] ?? '');

        $c_college = trim($_POST['c_college'] ?? '');
        $c_university = trim($_POST['c_university'] ?? '');
        $c_department = trim($_POST['c_department'] ?? '');
        $c_course_start_year = !empty($_POST['c_course_start_year']) ? (int)$_POST['c_course_start_year'] : null;
        $c_course_end_year = !empty($_POST['c_course_end_year']) ? (int)$_POST['c_course_end_year'] : null;
        $c_current_semester = !empty($_POST['c_current_semester']) ? (int)$_POST['c_current_semester'] : null;
        $c_supply_no = !empty($_POST['c_supply_no']) ? (int)$_POST['c_supply_no'] : 0;

        $c_linkedin = trim($_POST['c_linkedin'] ?? '');
        $c_github = trim($_POST['c_github'] ?? '');

        $errors = [];
        $old = [
            'c_name' => $c_name,
            'c_email' => $c_email,
            'c_cgpa' => $c_cgpa,
            'c_skills' => $_POST['c_skills'] ?? [],
            'c_phone' => $c_phone,
            'c_age' => $c_age,
            'c_sex' => $c_sex,
            'c_address' => $c_address,
            'c_college' => $c_college,
            'c_university' => $c_university,
            'c_department' => $c_department,
            'c_course_start_year' => $c_course_start_year,
            'c_course_end_year' => $c_course_end_year,
            'c_current_semester' => $c_current_semester,
            'c_supply_no' => $c_supply_no,
            'c_linkedin' => $c_linkedin,
            'c_github' => $c_github
        ];

        if (empty($c_name)) $errors[] = "Name is required.";
        if (!filter_var($c_email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
        if (strlen($c_password) < 6) $errors[] = "Password must be at least 6 characters.";
        if (empty($c_cgpa) || $c_cgpa < 0 || $c_cgpa > 10) $errors[] = "Valid CGPA (0-10) is required.";
        if (empty($c_skills)) $errors[] = "Please select at least one skill.";

        if (!empty($c_phone) && !preg_match('/^[0-9]{10}$/', $c_phone)) {
            $errors[] = "Phone number must be exactly 10 digits.";
        }
        if ($c_age !== null && ($c_age < 17 || $c_age > 100)) {
            $errors[] = "Age must be between 17 and 100.";
        }
        if (!empty($c_linkedin) && !filter_var($c_linkedin, FILTER_VALIDATE_URL)) {
            $errors[] = "LinkedIn profile must be a valid URL.";
        }
        if (!empty($c_github) && !filter_var($c_github, FILTER_VALIDATE_URL)) {
            $errors[] = "GitHub profile must be a valid URL.";
        }
        if ($c_course_start_year !== null && $c_course_end_year !== null) {
            if ($c_course_start_year >= $c_course_end_year) {
                $errors[] = "Course end year must be after start year.";
            }
        }
        if ($c_supply_no < 0 || $c_supply_no > 20) {
            $errors[] = "Number of supplies must be between 0 and 20.";
        }

        if (!isset($_FILES['c_resume']) || $_FILES['c_resume']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Resume file is required.";
        } else {
            $file_type = strtolower(pathinfo($_FILES["c_resume"]["name"], PATHINFO_EXTENSION));
            if ($file_type !== "pdf") {
                $errors[] = "Only PDF files are allowed for resume.";
            }
        }

        $photo_uploaded = false;
        if (isset($_FILES['c_photo']) && $_FILES['c_photo']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['jpg', 'jpeg', 'png'];
            $photo_type = strtolower(pathinfo($_FILES["c_photo"]["name"], PATHINFO_EXTENSION));
            
            if (!in_array($photo_type, $allowed_types)) {
                $errors[] = "Only JPG, JPEG, and PNG files are allowed for photo.";
            }
            
            if ($_FILES['c_photo']['size'] > 2 * 1024 * 1024) {
                $errors[] = "Photo size must not exceed 2MB.";
            } else {
                $photo_uploaded = true;
            }
        }

        if ($errors) {
            return $this->showRegister($errors, $old);
        }

        $hashed_password = password_hash($c_password, PASSWORD_DEFAULT);

        $upload_dir = '/Applications/XAMPP/xamppfiles/htdocs/PAS/public/uploads/resumes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $resume_name = uniqid() . '_' . basename($_FILES["c_resume"]["name"]);
        $resume_target = $upload_dir . $resume_name;

        if (!move_uploaded_file($_FILES["c_resume"]["tmp_name"], $resume_target)) {
            $errors[] = "Resume upload failed. Please check folder permissions.";
            return $this->showRegister($errors, $old);
        }

        $resume_path = "/PAS/public/uploads/resumes/" . $resume_name;

        $photo_path = null;
        if ($photo_uploaded) {
            $photo_dir = '/Applications/XAMPP/xamppfiles/htdocs/PAS/public/uploads/photos/';
            if (!is_dir($photo_dir)) {
                mkdir($photo_dir, 0777, true);
            }

            $photo_name = uniqid() . '_' . basename($_FILES["c_photo"]["name"]);
            $photo_target = $photo_dir . $photo_name;

            if (move_uploaded_file($_FILES["c_photo"]["tmp_name"], $photo_target)) {
                $photo_path = "/PAS/public/uploads/photos/" . $photo_name;
            }
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO candidate (
                    c_name, c_email, c_password, c_cgpa, c_skills, c_resume,
                    c_photo, c_phone, c_age, c_sex, c_address,
                    c_college, c_university, c_department, 
                    c_course_start_year, c_course_end_year, c_current_semester, c_supply_no,
                    c_linkedin, c_github
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $c_name, $c_email, $hashed_password, $c_cgpa, $c_skills, $resume_path,
                $photo_path, $c_phone, $c_age, $c_sex, $c_address,
                $c_college, $c_university, $c_department,
                $c_course_start_year, $c_course_end_year, $c_current_semester, $c_supply_no,
                $c_linkedin, $c_github
            ]);

            $_SESSION['success_message'] = "Registration successful! Please login.";
            header('Location: /PAS/public/auth/candidate/login');
            exit;

        } catch (PDOException $e) {
            if (file_exists($resume_target)) unlink($resume_target);
            if ($photo_path && file_exists($photo_target)) unlink($photo_target);

            if ($e->getCode() == 23000) {
                $errors[] = "Email already exists. Please use a different email.";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
            return $this->showRegister($errors, $old);
        }
    }
}
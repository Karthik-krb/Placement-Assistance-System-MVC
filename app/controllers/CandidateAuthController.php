<?php
// app/Controllers/CandidateAuthController.php
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
        $errors[] = "Invalid credentials.";
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

        // Get form data
        $c_name = trim($_POST['c_name'] ?? '');
        $c_email = trim($_POST['c_email'] ?? '');
        $c_password = $_POST['c_password'] ?? '';
        $c_cgpa = $_POST['c_cgpa'] ?? '';
        $c_skills = isset($_POST['c_skills']) ? implode(", ", $_POST['c_skills']) : '';

        // Validation
        $errors = [];
        $old = [
            'c_name' => $c_name,
            'c_email' => $c_email,
            'c_cgpa' => $c_cgpa,
            'c_skills' => $_POST['c_skills'] ?? []
        ];

        if (empty($c_name)) $errors[] = "Name is required.";
        if (!filter_var($c_email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
        if (strlen($c_password) < 6) $errors[] = "Password must be at least 6 characters.";
        if (empty($c_cgpa) || $c_cgpa < 0 || $c_cgpa > 10) $errors[] = "Valid CGPA (0-10) is required.";
        if (empty($c_skills)) $errors[] = "Please select at least one skill.";

        // File validation
        if (!isset($_FILES['c_resume']) || $_FILES['c_resume']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Resume file is required.";
        } else {
            $file_type = strtolower(pathinfo($_FILES["c_resume"]["name"], PATHINFO_EXTENSION));
            if ($file_type !== "pdf") {
                $errors[] = "Only PDF files are allowed for resume.";
            }
        }

        if ($errors) {
            return $this->showRegister($errors, $old);
        }

        // Hash password
        $hashed_password = password_hash($c_password, PASSWORD_DEFAULT);

        // Handle file upload
        $upload_dir = __DIR__ . '/../../public/uploads/resumes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = uniqid() . '_' . basename($_FILES["c_resume"]["name"]);
        $target_file = $upload_dir . $file_name;

        if (!move_uploaded_file($_FILES["c_resume"]["tmp_name"], $target_file)) {
            $errors[] = "File upload failed. Please check folder permissions.";
            return $this->showRegister($errors, $old);
        }

        // Save to database
        $resume_path = "/PAS/public/uploads/resumes/" . $file_name;

        try {
            $stmt = $this->pdo->prepare("INSERT INTO candidate (name, email, password, cgpa, skills, resume) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$c_name, $c_email, $hashed_password, $c_cgpa, $c_skills, $resume_path]);

            // Success - redirect to login
            $_SESSION['success_message'] = "Registration successful! Please login.";
            header('Location: /PAS/public/auth/candidate/login');
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                $errors[] = "Email already exists. Please use a different email.";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
            // Delete uploaded file if DB insert fails
            if (file_exists($target_file)) {
                unlink($target_file);
            }
            return $this->showRegister($errors, $old);
        }
    }
}
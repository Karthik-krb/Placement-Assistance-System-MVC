<?php
class CompanyAuthController {
    private $pdo;

    public function __construct($config) {
        $this->pdo = Database::getInstance($config)->getConnection();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function showLogin($errors = [], $old = []) {
        $data = ['errors' => $errors, 'old' => $old];
        require __DIR__ . '/../views/auth/company.php';
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $errors = [];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email.";
        if (strlen($password) < 3) $errors[] = "Enter a password.";

        if ($errors) return $this->showLogin($errors, ['email' => $email]);

        $companyModel = new Company($this->pdo);
        $user = $companyModel->authenticate($email, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            $_SESSION['role'] = 'company';
            header('Location: /PAS/public/company/dashboard');
            exit;
        }
        $errors[] = "Invalid credentials.";
        return $this->showLogin($errors, ['email' => $email]);
    }

    public function showRegister($errors = [], $old = []) {
        $data = ['errors' => $errors, 'old' => $old];
        require __DIR__ . '/../views/auth/companyregister.php';
    }

    public function register() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return $this->showRegister();
        }

        $company_name = trim($_POST['company_name'] ?? '');
        $company_password = $_POST['company_password'] ?? '';
        $company_email = trim($_POST['company_email'] ?? '');
        $company_address = trim($_POST['address'] ?? '');
        $company_contactno = trim($_POST['contact_no'] ?? '');

        $errors = [];
        $old = [
            'company_name' => $company_name,
            'company_email' => $company_email,
            'address' => $company_address,
            'contact_no' => $company_contactno
        ];

        if (empty($company_name)) $errors[] = "Company name is required.";
        if (empty($company_email) || !filter_var($company_email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid company email is required.";
        }
        if (strlen($company_password) < 6) $errors[] = "Password must be at least 6 characters.";
        if (empty($company_address)) $errors[] = "Address is required.";
        if (empty($company_contactno)) $errors[] = "Contact number is required.";

        if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Company logo is required.";
        } else {
            $check = getimagesize($_FILES["logo"]["tmp_name"]);
            if ($check === false) {
                $errors[] = "File is not an image.";
            } else {
                $imageFileType = strtolower(pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION));
                $allowed_types = array("jpg", "jpeg", "png", "gif");
                if (!in_array($imageFileType, $allowed_types)) {
                    $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
                }
            }
        }

        if ($errors) {
            return $this->showRegister($errors, $old);
        }

        $hashed_password = password_hash($company_password, PASSWORD_DEFAULT);

        $upload_dir = '/Applications/XAMPP/xamppfiles/htdocs/PAS/public/uploads/logos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = uniqid() . '_' . basename($_FILES["logo"]["name"]);
        $target_file = $upload_dir . $file_name;

        if (!move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
            $errors[] = "File upload failed. Please check folder permissions.";
            return $this->showRegister($errors, $old);
        }

        $logo_path = "/PAS/public/uploads/logos/" . $file_name;

        try {
            $stmt = $this->pdo->prepare("INSERT INTO company (company_name, company_email, company_password, company_address, company_contactno, company_logo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$company_name, $company_email, $hashed_password, $company_address, $company_contactno, $logo_path]);

            $_SESSION['success_message'] = "Registration successful! Please login.";
            header('Location: /PAS/public/auth/company/login');
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "Company email already exists. Please use a different email.";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
            if (file_exists($target_file)) {
                unlink($target_file);
            }
            return $this->showRegister($errors, $old);
        }
    }
}
<?php
// app/Controllers/CompanyAuthController.php
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
            header('Location: /company/dashboard.php'); // change as needed
            exit;
        }
        $errors[] = "Invalid credentials.";
        return $this->showLogin($errors, ['email' => $email]);
    }
}
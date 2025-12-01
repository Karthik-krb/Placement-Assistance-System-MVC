<?php

class AdminAuthController {
    private $pdo;

    public function __construct($config) {
        $this->pdo = Database::getInstance($config)->getConnection();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function showLogin($errors = [], $old = []) {
        $data = ['errors' => $errors, 'old' => $old];
        require __DIR__ . '/../views/auth/adminlogin.php';
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        
        $errors = [];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email.";
        if (strlen($password) < 3) $errors[] = "Enter a password.";

        if ($errors) {
            return $this->showLogin($errors, ['email' => $email]);
        }

        $adminModel = new Admin($this->pdo);
        $user = $adminModel->authenticate($email, $password);
        if ($user) {
            
            $_SESSION['user'] = $user;
            $_SESSION['role'] = 'admin';
            header('Location: /PAS/public/admin/dashboard');
            exit;
        }
        $errors[] = "Invalid credentials.";
        return $this->showLogin($errors, ['email' => $email]);
    }
}
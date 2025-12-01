<?php
class Admin {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function authenticate(string $email, string $password) {
        $sql = "SELECT admin_id, admin_name, admin_email, admin_password FROM admin WHERE admin_email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return false;

        if (isset($row['admin_password']) && password_verify($password, $row['admin_password'])) {
            return [
                'id' => $row['admin_id'],
                'name' => $row['admin_name'],
                'email' => $row['admin_email']
            ];
        }
        return false;
    }
}
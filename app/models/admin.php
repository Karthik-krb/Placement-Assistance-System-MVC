<?php
// app/Models/Admin.php
class Admin {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Authenticate admin by email and password.
     * Returns admin row (assoc) on success, false on failure.
     */
    public function authenticate(string $email, string $password) {
        $sql = "SELECT id, name, email, password FROM admin WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return false;

        if (isset($row['password']) && password_verify($password, $row['password'])) {
            // remove password before returning
            unset($row['password']);
            return $row;
        }
        return false;
    }
}
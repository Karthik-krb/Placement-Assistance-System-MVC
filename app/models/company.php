<?php
// app/Models/Company.php
class Company {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function authenticate(string $email, string $password) {
        $sql = "SELECT id, name, email, password FROM company WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return false;

        if (isset($row['password']) && password_verify($password, $row['password'])) {
            unset($row['password']);
            return $row;
        }
        return false;
    }
}
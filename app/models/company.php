<?php
class Company {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function authenticate(string $email, string $password) {
        $sql = "SELECT company_id as id, company_name as name, company_email as email, company_password as password, CAST(company_logo AS CHAR) as logo FROM company WHERE company_email = :email LIMIT 1";
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
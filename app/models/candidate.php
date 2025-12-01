<?php
class Candidate {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function authenticate(string $email, string $password) {
        $sql = "SELECT candidate_id as id, c_name as name, c_email as email, c_password as password FROM candidate WHERE c_email = :email LIMIT 1";
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

    public function getProfileById(int $candidate_id) {
        $sql = "SELECT candidate_id as id, c_name as name, c_email as email, c_skills as skills, c_cgpa as cgpa, c_resume as resume FROM candidate WHERE candidate_id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $candidate_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllJobPostings(int $candidate_id) {
        $sql = "SELECT j.job_id, 
                       j.job_title, 
                       j.cgpa_criteria, 
                       j.required_skills, 
                       c.company_id, 
                       c.company_name,
                       a.apply_id AS user_applied,
                       EXISTS (
                           SELECT 1 
                           FROM apply ap 
                           JOIN shortlist sh ON sh.apply_id = ap.apply_id 
                           WHERE ap.job_id = j.job_id
                       ) AS job_closed
                FROM job j 
                JOIN company c ON j.company_id = c.company_id 
                LEFT JOIN apply a ON a.job_id = j.job_id AND a.candidate_id = :candidate_id
                ORDER BY j.job_id DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':candidate_id' => $candidate_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function applyForJob(int $candidate_id, int $job_id) {
        
        $check_sql = "SELECT * FROM apply WHERE candidate_id = :candidate_id AND job_id = :job_id";
        $stmt = $this->pdo->prepare($check_sql);
        $stmt->execute([':candidate_id' => $candidate_id, ':job_id' => $job_id]);
        
        if ($stmt->fetch()) {
            return false; // Already applied
        }

        
        $company_sql = "SELECT company_id FROM job WHERE job_id = :job_id";
        $stmt = $this->pdo->prepare($company_sql);
        $stmt->execute([':job_id' => $job_id]);
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$company) {
            return false; // Job not found
        }

        
        $insert_sql = "INSERT INTO apply (candidate_id, company_id, job_id) VALUES (:candidate_id, :company_id, :job_id)";
        $stmt = $this->pdo->prepare($insert_sql);
        return $stmt->execute([
            ':candidate_id' => $candidate_id,
            ':company_id' => $company['company_id'],
            ':job_id' => $job_id
        ]);
    }

    public function withdrawApplication(int $candidate_id, int $job_id) {
        $sql = "DELETE FROM apply WHERE candidate_id = :candidate_id AND job_id = :job_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':candidate_id' => $candidate_id,
            ':job_id' => $job_id
        ]);
    }
}
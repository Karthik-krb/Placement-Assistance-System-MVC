<?php
class CandidateModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getDashboardStats($candidate_id) {
        $stats = [];
        
        $sql = "SELECT COUNT(*) AS total FROM job";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $stats['total_jobs'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $sql = "SELECT COUNT(*) AS total FROM apply WHERE candidate_id = :candidate_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':candidate_id' => $candidate_id]);
        $stats['total_applied'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $sql = "SELECT COUNT(*) AS total 
                FROM apply a 
                JOIN shortlist s ON s.apply_id = a.apply_id 
                WHERE a.candidate_id = :candidate_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':candidate_id' => $candidate_id]);
        $stats['total_shortlisted'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $sql = "SELECT COUNT(*) AS total FROM company";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $stats['total_companies'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $stats;
    }

    public function getProfileById(int $candidate_id) {
        $sql = "SELECT candidate_id as id, c_name as name, c_email as email, 
                       c_skills as skills, c_cgpa as cgpa, c_resume as resume,
                       c_photo as photo, c_phone as phone, c_age as age, c_sex as sex,
                       c_address as address, c_college as college, c_university as university,
                       c_department as department, c_course_start_year as course_start_year,
                       c_course_end_year as course_end_year, c_current_semester as current_semester,
                       c_supply_no as supply_no, c_linkedin as linkedin, c_github as github
                FROM candidate WHERE candidate_id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $candidate_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($candidate_id, $data) {
        $sql = "UPDATE candidate SET 
                c_name = :name, c_phone = :phone, c_age = :age, c_sex = :sex,
                c_address = :address, c_college = :college, c_university = :university,
                c_degree = :degree, c_department = :department, c_cgpa = :cgpa, c_skills = :skills,
                c_course_start_year = :start_year, c_course_end_year = :end_year,
                c_current_semester = :semester, c_supply_no = :supply_no,
                c_linkedin = :linkedin, c_github = :github, c_photo = :photo
                WHERE candidate_id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':phone' => $data['phone'],
            ':age' => $data['age'],
            ':sex' => $data['sex'],
            ':address' => $data['address'],
            ':college' => $data['college'],
            ':university' => $data['university'],
            ':degree' => $data['degree'],
            ':department' => $data['department'],
            ':cgpa' => $data['cgpa'],
            ':skills' => $data['skills'],
            ':start_year' => $data['start_year'],
            ':end_year' => $data['end_year'],
            ':semester' => $data['semester'],
            ':supply_no' => $data['supply_no'],
            ':linkedin' => $data['linkedin'],
            ':github' => $data['github'],
            ':photo' => $data['photo'],
            ':id' => $candidate_id
        ]);
    }

    public function getAllJobPostings(int $candidate_id) {
        $sql = "SELECT j.job_id, 
                       j.job_title,
                       j.job_description,
                       j.cgpa_criteria, 
                       j.required_skills,
                       j.num_openings,
                       c.company_id, 
                       c.company_name,
                       c.company_logo,
                       c.company_address,
                       a.apply_id AS user_applied,
                       s.shortlist_id AS is_shortlisted,
                       (SELECT COUNT(*) FROM apply ap WHERE ap.job_id = j.job_id) AS total_applications,
                       (SELECT COUNT(*) FROM shortlist sh 
                        JOIN apply ap ON sh.apply_id = ap.apply_id 
                        WHERE ap.job_id = j.job_id) AS total_shortlisted,
                       CASE 
                           WHEN (SELECT COUNT(*) FROM shortlist sh 
                                 JOIN apply ap ON sh.apply_id = ap.apply_id 
                                 WHERE ap.job_id = j.job_id) > 0 THEN 1
                           WHEN (SELECT COUNT(*) FROM apply ap WHERE ap.job_id = j.job_id) >= j.num_openings THEN 1
                           ELSE 0
                       END AS job_closed
                FROM job j 
                JOIN company c ON j.company_id = c.company_id 
                LEFT JOIN apply a ON a.job_id = j.job_id AND a.candidate_id = :candidate_id
                LEFT JOIN shortlist s ON s.apply_id = a.apply_id
                ORDER BY j.job_id DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':candidate_id' => $candidate_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function applyForJob(int $candidate_id, int $job_id) {
        $check_sql = "SELECT a.apply_id, s.shortlist_id 
                      FROM apply a 
                      LEFT JOIN shortlist s ON a.apply_id = s.apply_id
                      WHERE a.candidate_id = :candidate_id AND a.job_id = :job_id";
        $stmt = $this->pdo->prepare($check_sql);
        $stmt->execute([':candidate_id' => $candidate_id, ':job_id' => $job_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            return false;
        }

        $vacancy_sql = "SELECT j.num_openings,
                        (SELECT COUNT(*) FROM apply ap WHERE ap.job_id = j.job_id) AS total_applications,
                        (SELECT COUNT(*) FROM shortlist sh 
                         JOIN apply ap ON sh.apply_id = ap.apply_id 
                         WHERE ap.job_id = j.job_id) AS total_shortlisted
                        FROM job j WHERE j.job_id = :job_id";
        $stmt = $this->pdo->prepare($vacancy_sql);
        $stmt->execute([':job_id' => $job_id]);
        $job_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($job_data && ($job_data['total_shortlisted'] > 0 || $job_data['total_applications'] >= $job_data['num_openings'])) {
            return false;
        }

        $company_sql = "SELECT company_id FROM job WHERE job_id = :job_id";
        $stmt = $this->pdo->prepare($company_sql);
        $stmt->execute([':job_id' => $job_id]);
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$company) {
            return false;
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
        $check_sql = "SELECT s.shortlist_id 
                      FROM apply a 
                      LEFT JOIN shortlist s ON a.apply_id = s.apply_id
                      WHERE a.candidate_id = :candidate_id AND a.job_id = :job_id";
        $stmt = $this->pdo->prepare($check_sql);
        $stmt->execute([':candidate_id' => $candidate_id, ':job_id' => $job_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $result['shortlist_id']) {
            return false;
        }
        
        $sql = "DELETE FROM apply WHERE candidate_id = :candidate_id AND job_id = :job_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':candidate_id' => $candidate_id,
            ':job_id' => $job_id
        ]);
    }

    public function getApplications(int $candidate_id) {
        $sql = "SELECT a.apply_id, 
                       j.job_title, 
                       j.cgpa_criteria,
                       j.required_skills,
                       c.company_name,
                       c.company_address,
                       IFNULL(s.status, 'Pending') AS application_status
                FROM apply a 
                JOIN job j ON a.job_id = j.job_id 
                JOIN company c ON j.company_id = c.company_id 
                LEFT JOIN shortlist s ON s.apply_id = a.apply_id
                WHERE a.candidate_id = :candidate_id
                ORDER BY a.apply_id DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':candidate_id' => $candidate_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

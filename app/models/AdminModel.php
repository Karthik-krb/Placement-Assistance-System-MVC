<?php

class AdminModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getDashboardStats() {
        $stats = [];
        
        $sql = "SELECT COUNT(*) AS total FROM candidate";
        $stmt = $this->pdo->query($sql);
        $stats['total_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $sql = "SELECT COUNT(*) AS total FROM apply";
        $stmt = $this->pdo->query($sql);
        $stats['total_applications'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $sql = "SELECT COUNT(*) AS total FROM company";
        $stmt = $this->pdo->query($sql);
        $stats['total_companies'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $sql = "SELECT COUNT(*) AS total FROM shortlist";
        $stmt = $this->pdo->query($sql);
        $stats['total_shortlisted'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $stats;
    }

    public function getAllCandidates() {
        $sql = "SELECT candidate_id, c_name, c_email, c_phone, c_department, 
                       c_degree, c_cgpa, c_supply_no, c_skills 
                FROM candidate 
                ORDER BY candidate_id DESC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllApplications() {
        $sql = "SELECT c.candidate_id, c.c_name AS candidate_name, c.c_email AS candidate_email, 
                       j.job_title, comp.company_name, a.job_id, a.apply_id,
                       CASE 
                           WHEN s.apply_id IS NOT NULL THEN 'shortlisted'
                           ELSE 'applied'
                       END AS status
                FROM apply a
                JOIN candidate c ON a.candidate_id = c.candidate_id
                JOIN job j ON a.job_id = j.job_id
                JOIN company comp ON j.company_id = comp.company_id
                LEFT JOIN shortlist s ON a.apply_id = s.apply_id
                ORDER BY c.candidate_id, j.job_title";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCompanies() {
        $sql = "SELECT c.company_id, c.company_name, c.company_address, c.company_contactno,
                       COUNT(j.job_id) as total_jobs
                FROM company c
                LEFT JOIN job j ON c.company_id = j.company_id
                GROUP BY c.company_id, c.company_name, c.company_address, c.company_contactno
                ORDER BY c.company_id DESC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllShortlisted() {
        $sql = "SELECT s.shortlist_id, j.job_title, c.candidate_id, c.c_name AS candidate_name, 
                       c.c_email AS candidate_email, c.c_phone, c.c_age, c.c_sex, c.c_department,
                       c.c_degree, c.c_cgpa, c.c_supply_no, c.c_skills, c.c_course_start_year,
                       c.c_course_end_year, c.c_current_semester, c.c_resume, cmp.company_name, 
                       s.shortlist_type, s.status
                FROM shortlist s
                JOIN apply a ON s.apply_id = a.apply_id
                JOIN candidate c ON a.candidate_id = c.candidate_id
                JOIN job j ON a.job_id = j.job_id
                JOIN company cmp ON j.company_id = cmp.company_id
                WHERE s.status = 'Shortlisted'
                ORDER BY s.shortlist_id DESC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnalyticsData() {
        $analytics = [];
        
        
        $sql = "SELECT 'application' as activity_type, c.c_name as name, j.job_title, 
                       comp.company_name, a.apply_id as id, NOW() as activity_date
                FROM apply a
                JOIN candidate c ON a.candidate_id = c.candidate_id
                JOIN job j ON a.job_id = j.job_id
                JOIN company comp ON j.company_id = comp.company_id
                ORDER BY a.apply_id DESC LIMIT 10";
        $stmt = $this->pdo->query($sql);
        $analytics['recent_activities'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
        $sql = "SELECT comp.company_name, COUNT(j.job_id) as job_count
                FROM company comp
                LEFT JOIN job j ON comp.company_id = j.company_id
                GROUP BY comp.company_id, comp.company_name
                ORDER BY job_count DESC
                LIMIT 5";
        $stmt = $this->pdo->query($sql);
        $analytics['top_companies'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
        $sql = "SELECT 
                    SUM(CASE WHEN s.status = 'Shortlisted' THEN 1 ELSE 0 END) as shortlisted,
                    SUM(CASE WHEN s.status = 'Rejected' THEN 1 ELSE 0 END) as rejected,
                    SUM(CASE WHEN s.status = 'Pending' OR s.status IS NULL THEN 1 ELSE 0 END) as pending
                FROM apply a
                LEFT JOIN shortlist s ON a.apply_id = s.apply_id";
        $stmt = $this->pdo->query($sql);
        $analytics['application_status'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        
        $sql = "SELECT 
                    COUNT(*) as total,
                    AVG(c_cgpa) as avg_cgpa,
                    MAX(c_cgpa) as max_cgpa,
                    MIN(c_cgpa) as min_cgpa
                FROM candidate";
        $stmt = $this->pdo->query($sql);
        $analytics['candidate_metrics'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        
        $sql = "SELECT required_skills, COUNT(*) as skill_count
                FROM job
                WHERE required_skills IS NOT NULL AND required_skills != ''
                GROUP BY required_skills
                ORDER BY skill_count DESC
                LIMIT 5";
        $stmt = $this->pdo->query($sql);
        $analytics['top_skills'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
        $sql = "SELECT c_department, COUNT(*) as count
                FROM candidate
                WHERE c_department IS NOT NULL
                GROUP BY c_department
                ORDER BY count DESC
                LIMIT 5";
        $stmt = $this->pdo->query($sql);
        $analytics['department_distribution'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
        $sql = "SELECT 
                    SUM(CASE WHEN shortlist_type = 'auto' THEN 1 ELSE 0 END) as auto_count,
                    SUM(CASE WHEN shortlist_type = 'manual' THEN 1 ELSE 0 END) as manual_count
                FROM shortlist
                WHERE status = 'Shortlisted'";
        $stmt = $this->pdo->query($sql);
        $analytics['shortlist_types'] = $stmt->fetch(PDO::FETCH_ASSOC);

        return $analytics;
    }
}

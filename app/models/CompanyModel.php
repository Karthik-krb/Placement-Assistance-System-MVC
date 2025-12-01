<?php
class CompanyModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getDashboardStats($company_id) {
        $stats = [];
        
        $sql = "SELECT COUNT(*) AS total FROM job WHERE company_id = :company_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':company_id' => $company_id]);
        $stats['total_jobs'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $sql = "SELECT COUNT(DISTINCT a.candidate_id) AS total 
                FROM apply a 
                JOIN job j ON a.job_id = j.job_id 
                WHERE j.company_id = :company_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':company_id' => $company_id]);
        $stats['total_applied'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $sql = "SELECT COUNT(*) AS total FROM candidate";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $stats['total_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $sql = "SELECT COUNT(*) AS total 
                FROM shortlist s
                JOIN apply a ON s.apply_id = a.apply_id
                JOIN job j ON a.job_id = j.job_id
                WHERE s.status = 'Shortlisted' AND j.company_id = :company_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':company_id' => $company_id]);
        $stats['total_shortlisted'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $stats;
    }

    public function createJob($company_id, $job_title, $job_type, $location, $num_openings, $application_deadline, $deadline_time, $job_description, $cgpa_criteria, $degree_required, $course_specialization, $supply_backlog_policy, $required_skills) {
        $sql = "INSERT INTO job (job_title, job_type, location, num_openings, application_deadline, deadline_time, job_description, cgpa_criteria, degree_required, course_specialization, supply_backlog_policy, required_skills, company_id) 
                VALUES (:job_title, :job_type, :location, :num_openings, :application_deadline, :deadline_time, :job_description, :cgpa_criteria, :degree_required, :course_specialization, :supply_backlog_policy, :required_skills, :company_id)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':job_title' => $job_title,
            ':job_type' => $job_type,
            ':location' => $location,
            ':num_openings' => $num_openings,
            ':application_deadline' => $application_deadline,
            ':deadline_time' => $deadline_time,
            ':job_description' => $job_description,
            ':cgpa_criteria' => $cgpa_criteria,
            ':degree_required' => $degree_required,
            ':course_specialization' => $course_specialization,
            ':supply_backlog_policy' => $supply_backlog_policy,
            ':required_skills' => $required_skills,
            ':company_id' => $company_id
        ]);
        
        return $this->pdo->lastInsertId();
    }

    public function getPostedJobs($company_id) {
        $sql = "SELECT job_id, job_title, job_type, location, num_openings, application_deadline, 
                deadline_time, job_description, cgpa_criteria, degree_required, course_specialization, 
                supply_backlog_policy, required_skills 
                FROM job 
                WHERE company_id = :company_id 
                ORDER BY application_deadline DESC, job_title";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':company_id' => $company_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJobsWithApplications($company_id) {
        $sql = "SELECT DISTINCT j.job_id, j.job_title, j.cgpa_criteria, j.required_skills, c.company_name 
                FROM job j
                JOIN company c ON j.company_id = c.company_id  
                JOIN apply a ON a.job_id = j.job_id
                WHERE j.company_id = :company_id
                ORDER BY j.job_id DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':company_id' => $company_id]);
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($jobs as &$job) {
            $job['applications'] = $this->getApplicationsForJob($job['job_id'], $company_id);
            $job['is_shortlisted'] = $this->isJobShortlisted($job['job_id']);
        }
        
        return $jobs;
    }

    public function getApplicationsForJob($job_id, $company_id) {
        $sql = "SELECT a.apply_id, c.c_name AS candidate_name, c.c_email AS candidate_email, 
                c.c_cgpa, c.c_skills, 
                IFNULL(s.status, 'Pending') AS status, 
                s.shortlist_id
                FROM apply a
                JOIN candidate c ON a.candidate_id = c.candidate_id
                JOIN job j ON a.job_id = j.job_id
                LEFT JOIN shortlist s ON s.apply_id = a.apply_id
                WHERE a.job_id = :job_id 
                AND j.company_id = :company_id
                ORDER BY 
                    CASE 
                        WHEN s.status = 'Shortlisted' THEN 1
                        WHEN s.status = 'Pending' OR s.status IS NULL THEN 2
                        WHEN s.status = 'Rejected' THEN 3
                    END,
                    a.apply_id DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':job_id' => $job_id, ':company_id' => $company_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isJobShortlisted($job_id) {
        $sql = "SELECT COUNT(*) as count FROM shortlist s 
                JOIN apply a ON s.apply_id = a.apply_id 
                WHERE a.job_id = :job_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':job_id' => $job_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function getApplicationsForShortlisting($job_id, $company_id) {
        $sql = "SELECT a.apply_id, c.c_cgpa, c.c_skills, j.cgpa_criteria, j.required_skills
                FROM apply a
                JOIN candidate c ON a.candidate_id = c.candidate_id
                JOIN job j ON a.job_id = j.job_id
                WHERE a.job_id = :job_id 
                AND j.company_id = :company_id 
                AND a.apply_id NOT IN (SELECT apply_id FROM shortlist)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':job_id' => $job_id, ':company_id' => $company_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertShortlist($shortlist_data) {
        $sql = "INSERT INTO shortlist (apply_id, status) VALUES (:apply_id, :status)";
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($shortlist_data as $data) {
            $stmt->execute([
                ':apply_id' => $data['apply_id'],
                ':status' => $data['status']
            ]);
        }
    }

    public function getShortlistedCandidates($company_id) {
        $sql = "SELECT s.apply_id, a.job_id, a.candidate_id, 
                       j.job_title, c.c_name AS candidate_name, c.c_email AS candidate_email,
                       CASE WHEN e.email_status = 'sent' THEN 1 ELSE 0 END as email_sent,
                       e.sent_at as email_sent_at
                FROM shortlist s
                JOIN apply a ON s.apply_id = a.apply_id
                JOIN candidate c ON a.candidate_id = c.candidate_id
                JOIN job j ON a.job_id = j.job_id
                LEFT JOIN email e ON (e.candidate_id = c.candidate_id AND e.job_id = j.job_id AND e.company_id = :company_id AND e.email_status = 'sent')
                WHERE s.status = 'Shortlisted' AND j.company_id = :company_id
                ORDER BY j.job_title, c.c_name";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':company_id' => $company_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function autoShortlistForJob($job_id, $company_id) {
        $sql = "SELECT * FROM job 
                WHERE job_id = :job_id 
                AND company_id = :company_id 
                AND auto_shortlisted = FALSE 
                AND CONCAT(application_deadline, ' ', IFNULL(deadline_time, '23:59:59')) < NOW()";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':job_id' => $job_id, ':company_id' => $company_id]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$job) {
            return ['success' => false, 'message' => 'Job not eligible for auto-shortlisting'];
        }

        $sql = "SELECT a.apply_id, a.candidate_id, 
                       c.c_cgpa, c.c_skills, c.c_supply_no, c.c_department, c.c_degree
                FROM apply a
                JOIN candidate c ON a.candidate_id = c.candidate_id
                WHERE a.job_id = :job_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':job_id' => $job_id]);
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $shortlisted_count = 0;
        $rejected_count = 0;

        foreach ($applications as $app) {
            $check_sql = "SELECT shortlist_id FROM shortlist WHERE apply_id = :apply_id";
            $check_stmt = $this->pdo->prepare($check_sql);
            $check_stmt->execute([':apply_id' => $app['apply_id']]);
            
            if ($check_stmt->fetch()) {
                continue;
            }

            $is_qualified = $this->evaluateCandidate($app, $job);

            if ($is_qualified) {
                $insert_sql = "INSERT INTO shortlist (apply_id, status, shortlist_type) 
                               VALUES (:apply_id, 'Shortlisted', 'auto')";
                $insert_stmt = $this->pdo->prepare($insert_sql);
                $insert_stmt->execute([':apply_id' => $app['apply_id']]);
                $shortlisted_count++;
            } else {
                $insert_sql = "INSERT INTO shortlist (apply_id, status, shortlist_type) 
                               VALUES (:apply_id, 'Rejected', 'auto')";
                $insert_stmt = $this->pdo->prepare($insert_sql);
                $insert_stmt->execute([':apply_id' => $app['apply_id']]);
                $rejected_count++;
            }
        }

        $update_sql = "UPDATE job SET auto_shortlisted = TRUE, shortlist_date = NOW() 
                       WHERE job_id = :job_id";
        $update_stmt = $this->pdo->prepare($update_sql);
        $update_stmt->execute([':job_id' => $job_id]);

        return [
            'success' => true,
            'shortlisted' => $shortlisted_count,
            'rejected' => $rejected_count,
            'message' => "Auto-shortlisting completed: {$shortlisted_count} shortlisted, {$rejected_count} rejected"
        ];
    }

    private function evaluateCandidate($candidate, $job) {
        if ($candidate['c_cgpa'] < $job['cgpa_criteria']) {
            return false;
        }

        $required_skills = array_map('trim', array_map('strtolower', explode(',', $job['required_skills'])));
        $candidate_skills = array_map('trim', array_map('strtolower', explode(',', $candidate['c_skills'])));

        foreach ($required_skills as $skill) {
            if (!in_array($skill, $candidate_skills)) {
                return false;
            }
        }

        if (!empty($job['supply_backlog_policy'])) {
            $policy = strtolower(trim($job['supply_backlog_policy']));
            
            if (in_array($policy, ['no supply', 'not allowed', 'no backlogs', 'no', 'not applicable'])) {
                if ($candidate['c_supply_no'] > 0) {
                    return false;
                }
            }
        }

        if (!empty($job['degree_required'])) {
            $required_degree = strtolower(trim($job['degree_required']));
            $candidate_degree = strtolower(trim($candidate['c_degree'] ?? ''));
            
            $any_degree_variations = ['any degree', 'any', 'all', 'all degrees', 'any graduate'];
            
            if (!in_array($required_degree, $any_degree_variations)) {
                $accepted_degrees = preg_split('/[\/,]/', $required_degree);
                $accepted_degrees = array_map('trim', $accepted_degrees);
                
                $degree_match = false;
                foreach ($accepted_degrees as $accepted) {
                    if ($accepted === $candidate_degree) {
                        $degree_match = true;
                        break;
                    }
                }
                
                if (!$degree_match) {
                    return false;
                }
            }
        }

        if (!empty($job['course_specialization'])) {
            $required_course = strtolower(trim($job['course_specialization']));
            
            $any_branch_variations = ['any branch', 'any', 'all', 'all branches', 'any department'];
            
            if (!in_array($required_course, $any_branch_variations)) {
                $candidate_dept = strtolower(trim($candidate['c_department'] ?? ''));
                
                if ($required_course !== $candidate_dept) {
                    return false;
                }
            }
        }

        return true;
    }

    public function autoShortlistAllEligibleJobs($company_id) {
        $sql = "SELECT job_id FROM job 
                WHERE company_id = :company_id 
                AND auto_shortlisted = FALSE 
                AND CONCAT(application_deadline, ' ', IFNULL(deadline_time, '23:59:59')) < NOW()";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':company_id' => $company_id]);
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $results = [];
        foreach ($jobs as $job) {
            $result = $this->autoShortlistForJob($job['job_id'], $company_id);
            $results[] = $result;
        }

        return $results;
    }
    
    public function getJobsWithShortlisted($company_id) {
        try {
            $sql = "SELECT DISTINCT j.job_id, j.job_title, j.location, 
                           COUNT(DISTINCT a.candidate_id) as shortlisted_count,
                           COUNT(DISTINCT e.email_id) as emails_sent
                    FROM job j
                    JOIN apply a ON j.job_id = a.job_id
                    JOIN shortlist s ON a.apply_id = s.apply_id
                    LEFT JOIN email e ON (e.job_id = j.job_id AND e.email_status = 'sent')
                    WHERE j.company_id = :company_id AND s.status = 'Shortlisted'
                    GROUP BY j.job_id, j.job_title, j.location
                    ORDER BY j.job_id DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':company_id' => $company_id]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error fetching jobs with shortlisted: " . $e->getMessage());
            return [];
        }
    }
}

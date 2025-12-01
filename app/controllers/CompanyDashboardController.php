<?php
class CompanyDashboardController {
    private $pdo;
    private $config;
    private $companyModel;

    public function __construct($config) {
        $this->config = $config;
        $this->pdo = Database::getInstance($config)->getConnection();
        $this->companyModel = new CompanyModel($this->pdo);
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'company') {
            header('Location: /PAS/public/auth/company/login');
            exit;
        }
    }

    public function index() {
        $company_id = $_SESSION['user']['id'];
        
        $stats = $this->companyModel->getDashboardStats($company_id);
        
        $data = [
            'company_id' => $company_id,
            'stats' => $stats
        ];
        require __DIR__ . '/../views/company/dashboard.php';
    }

    public function showJobPostingForm($errors = [], $old = []) {
        $data = ['errors' => $errors, 'old' => $old];
        require __DIR__ . '/../views/company/jobposting.php';
    }

    public function createJobPosting() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return $this->showJobPostingForm();
        }

        $company_id = $_SESSION['user']['id'];

        $job_title = trim($_POST['job_title'] ?? '');
        $job_type = trim($_POST['job_type'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $num_openings = intval($_POST['num_openings'] ?? 1);
        $application_deadline = $_POST['application_deadline'] ?? '';
        $deadline_time = $_POST['deadline_time'] ?? '';
        $cgpa_criteria = $_POST['cgpa_criteria'] ?? '';
        $degree_required = trim($_POST['degree_required'] ?? '');
        $course_specialization = trim($_POST['course_specialization'] ?? '');
        $supply_backlog_policy = trim($_POST['supply_backlog_policy'] ?? '');
        $job_description = trim($_POST['job_description'] ?? '');
        $skills = isset($_POST['skills']) ? implode(", ", $_POST['skills']) : '';

        $errors = [];
        $old = [
            'job_title' => $job_title,
            'job_type' => $job_type,
            'location' => $location,
            'num_openings' => $num_openings,
            'application_deadline' => $application_deadline,
            'deadline_time' => $deadline_time,
            'cgpa_criteria' => $cgpa_criteria,
            'degree_required' => $degree_required,
            'course_specialization' => $course_specialization,
            'supply_backlog_policy' => $supply_backlog_policy,
            'job_description' => $job_description,
            'skills' => $_POST['skills'] ?? []
        ];

        if (empty($job_title)) $errors[] = "Job title is required.";
        if (empty($job_type)) $errors[] = "Job type is required.";
        if (empty($location)) $errors[] = "Job location is required.";
        if ($num_openings < 1) $errors[] = "Number of openings must be at least 1.";
        if (empty($application_deadline)) $errors[] = "Application deadline is required.";
        if (empty($deadline_time)) $errors[] = "Deadline time is required.";
        if (empty($degree_required)) $errors[] = "Degree requirement is required.";
        if (empty($course_specialization)) $errors[] = "Course/Specialization is required.";
        if (empty($supply_backlog_policy)) $errors[] = "Supply/Backlog policy is required.";
        if (empty($cgpa_criteria) || $cgpa_criteria < 0 || $cgpa_criteria > 10) {
            $errors[] = "Valid CGPA criteria (0-10) is required.";
        }
        if (empty($job_description)) $errors[] = "Job description is required.";
        if (empty($skills)) $errors[] = "Please select at least one required skill.";

        if ($errors) {
            return $this->showJobPostingForm($errors, $old);
        }

        try {
            $this->companyModel->createJob(
                $company_id, 
                $job_title, 
                $job_type,
                $location,
                $num_openings,
                $application_deadline,
                $deadline_time,
                $job_description, 
                $cgpa_criteria,
                $degree_required,
                $course_specialization,
                $supply_backlog_policy,
                $skills
            );

            $_SESSION['success_message'] = "Job posted successfully!";
            header('Location: /PAS/public/company/dashboard');
            exit;

        } catch (PDOException $e) {
            $errors[] = "Failed to post job. Please try again.";
            return $this->showJobPostingForm($errors, $old);
        }
    }

    public function showPostedJobs() {
        $company_id = $_SESSION['user']['id'];
        
        $jobs = $this->companyModel->getPostedJobs($company_id);
        
        $data = ['jobs' => $jobs];
        require __DIR__ . '/../views/company/postedjobs.php';
    }

    public function showApplications() {
        $company_id = $_SESSION['user']['id'];
        
        $auto_shortlist_results = $this->companyModel->autoShortlistAllEligibleJobs($company_id);
        
        if (!empty($auto_shortlist_results)) {
            $total_shortlisted = 0;
            $total_rejected = 0;
            foreach ($auto_shortlist_results as $result) {
                if ($result['success']) {
                    $total_shortlisted += $result['shortlisted'];
                    $total_rejected += $result['rejected'];
                }
            }
            
            if ($total_shortlisted > 0 || $total_rejected > 0) {
                $_SESSION['success_message'] = "Auto-evaluation completed! {$total_shortlisted} candidates shortlisted, {$total_rejected} candidates rejected.";
            }
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
            $this->filterAndShortlist($_POST['job_id'], $company_id);
            header("Location: /PAS/public/company/applications");
            exit;
        }
        
        $jobs = $this->companyModel->getJobsWithApplications($company_id);
        
        $data = [
            'company_id' => $company_id,
            'jobs' => $jobs
        ];
        require __DIR__ . '/../views/company/applications.php';
    }

    private function filterAndShortlist($job_id, $company_id) {
        $applications = $this->companyModel->getApplicationsForShortlisting($job_id, $company_id);
        
        if (empty($applications)) {
            $_SESSION['error_message'] = 'No applications found for this job or already shortlisted.';
            return;
        }
        
        $shortlist_data = [];
        
        foreach ($applications as $app) {
            $apply_id = $app['apply_id'];
            $candidate_cgpa = $app['c_cgpa'];
            $cgpa_criteria = $app['cgpa_criteria'];
            $candidate_skills = array_map('trim', explode(",", $app['c_skills']));
            $required_skills = array_map('trim', explode(",", $app['required_skills']));
            
            $skills_match = true;
            foreach ($required_skills as $skill) {
                if (!in_array($skill, $candidate_skills)) {
                    $skills_match = false;
                    break;
                }
            }
            
            if ($candidate_cgpa >= $cgpa_criteria && $skills_match) {
                $shortlist_data[] = ['apply_id' => $apply_id, 'status' => 'Shortlisted'];
            } else {
                $shortlist_data[] = ['apply_id' => $apply_id, 'status' => 'Rejected'];
            }
        }
        
        if (!empty($shortlist_data)) {
            $this->companyModel->insertShortlist($shortlist_data);
            $shortlisted_count = count(array_filter($shortlist_data, fn($item) => $item['status'] === 'Shortlisted'));
            $rejected_count = count(array_filter($shortlist_data, fn($item) => $item['status'] === 'Rejected'));
            $_SESSION['success_message'] = "Shortlisting completed! Shortlisted: $shortlisted_count, Rejected: $rejected_count";
        }
    }

    public function showShortlisted() {
        $company_id = $_SESSION['user']['id'];
        
        $shortlisted = $this->companyModel->getShortlistedCandidates($company_id);
        
        require __DIR__ . '/../views/company/shortlisted.php';
    }

    public function feedback() {
        $company_id = $_SESSION['user']['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');
            
            $errors = [];
            if (empty($subject)) $errors[] = "Subject is required.";
            if (empty($message)) $errors[] = "Message is required.";
            
            if (!$errors) {
                $feedbackModel = new FeedbackModel($this->pdo);
                if ($feedbackModel->submitFeedback('company', $company_id, $subject, $message)) {
                    $_SESSION['success_message'] = "Feedback submitted successfully! Admin will respond soon.";
                } else {
                    $_SESSION['error_message'] = "Failed to submit feedback. Please try again.";
                }
                header('Location: /PAS/public/company/feedback');
                exit;
            }
        }
        
        $feedbackModel = new FeedbackModel($this->pdo);
        $feedbacks = $feedbackModel->getUserFeedback('company', $company_id);
        
        $data = [
            'feedbacks' => $feedbacks,
            'errors' => $errors ?? []
        ];
        
        require __DIR__ . '/../views/company/feedback.php';
    }

    public function sendEmail() {
        $company_id = $_SESSION['user']['id'];
        require_once __DIR__ . '/../models/EmailModel.php';
        $emailModel = new EmailModel($this->pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_candidates') {
            header('Content-Type: application/json');
            
            $job_id = (int)($_GET['job_id'] ?? 0);
            if (!$job_id) {
                echo json_encode(['success' => false, 'message' => 'Invalid job ID']);
                exit;
            }
            
            $candidates = $emailModel->getShortlistedWithEmailStatus($company_id, $job_id);
            echo json_encode(['success' => true, 'candidates' => $candidates]);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_email') {
            header('Content-Type: application/json');
            
            $job_id = (int)($_POST['job_id'] ?? 0);
            $candidate_id = (int)($_POST['candidate_id'] ?? 0);
            
            if (!$job_id || !$candidate_id) {
                echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
                exit;
            }
            
            $result = $emailModel->sendShortlistEmail($company_id, $job_id, $candidate_id);
            echo json_encode($result);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_bulk') {
            header('Content-Type: application/json');
            
            $job_id = (int)($_POST['job_id'] ?? 0);
            $candidate_ids_str = $_POST['candidate_ids'] ?? '';
            $candidate_ids = array_map('intval', explode(',', $candidate_ids_str));
            
            if (!$job_id || empty($candidate_ids)) {
                echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
                exit;
            }
            
            $result = $emailModel->sendBulkEmails($company_id, $job_id, $candidate_ids);
            echo json_encode($result);
            exit;
        }
        
        $jobs = $this->companyModel->getJobsWithShortlisted($company_id);
        
        $emailHistory = $emailModel->getEmailHistory($company_id);
        
        $totalShortlisted = 0;
        foreach ($jobs as $job) {
            $totalShortlisted += $job['shortlisted_count'];
        }
        
        $jobsWithShortlisted = $jobs;
        
        require __DIR__ . '/../views/company/email.php';
    }

    public function logout() {
        session_destroy();
        header('Location: /PAS/public/auth/company/login');
        exit;
    }
}

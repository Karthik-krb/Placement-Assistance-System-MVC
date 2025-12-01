<?php


class AdminDashboardController {
    private $pdo;
    private $config;
    private $adminModel;

    public function __construct($config) {
        $this->config = $config;
        $this->pdo = Database::getInstance($config)->getConnection();
        $this->adminModel = new AdminModel($this->pdo);
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: /PAS/public/auth/admin/login');
            exit;
        }
    }

    public function index() {
        
        $stats = $this->adminModel->getDashboardStats();
        
        
        $analytics = $this->adminModel->getAnalyticsData();
        
    
        $feedbackModel = new FeedbackModel($this->pdo);
        $feedbackCounts = $feedbackModel->getFeedbackCounts();
        
        
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    public function showCandidates() {
    
        $candidates = $this->adminModel->getAllCandidates();
        
        // Pass data to view
        require __DIR__ . '/../views/admin/candidates.php';
    }

    public function showApplications() {
        // Fetch all applications
        $applications = $this->adminModel->getAllApplications();
        
        // Pass data to view
        require __DIR__ . '/../views/admin/applications.php';
    }

    public function showCompanies() {
        // Fetch all companies
        $companies = $this->adminModel->getAllCompanies();
        
        // Pass data to view
        require __DIR__ . '/../views/admin/companies.php';
    }

    public function showShortlisted() {
        
        $shortlisted = $this->adminModel->getAllShortlisted();
        
      
        require __DIR__ . '/../views/admin/shortlisted.php';
    }

    public function feedback() {
       
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_id']) && isset($_POST['admin_reply'])) {
            $feedback_id = (int)$_POST['feedback_id'];
            $admin_reply = trim($_POST['admin_reply']);
            
            if (!empty($admin_reply)) {
                $feedbackModel = new FeedbackModel($this->pdo);
                if ($feedbackModel->replyToFeedback($feedback_id, $admin_reply)) {
                    $_SESSION['success_message'] = "Reply sent successfully!";
                } else {
                    $_SESSION['error_message'] = "Failed to send reply. Please try again.";
                }
            } else {
                $_SESSION['error_message'] = "Reply message cannot be empty.";
            }
            
            header('Location: /PAS/public/admin/feedback');
            exit;
        }
        
       
        $filter = $_GET['status'] ?? 'all';
        $status = ($filter === 'pending' || $filter === 'replied') ? $filter : null;
        
       
        $feedbackModel = new FeedbackModel($this->pdo);
        $feedbacks = $feedbackModel->getAllFeedback($status);
        $counts = $feedbackModel->getFeedbackCounts();
        
        $data = [
            'feedbacks' => $feedbacks,
            'counts' => $counts,
            'current_filter' => $filter
        ];
        
        require __DIR__ . '/../views/admin/feedback.php';
    }

    public function logout() {
        session_destroy();
        header('Location: /PAS/public/auth/admin/login');
        exit;
    }
}

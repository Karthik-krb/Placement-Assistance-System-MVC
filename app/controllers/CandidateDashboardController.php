<?php
class CandidateDashboardController {
    private $pdo;
    private $config;

    public function __construct($config) {
        $this->config = $config;
        $this->pdo = Database::getInstance($config)->getConnection();
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'candidate') {
            header('Location: /PAS/public/auth/candidate/login');
            exit;
        }
    }

    public function index() {
        $candidate_id = $_SESSION['user']['id'];
        
        $candidateModel = new CandidateModel($this->pdo);
        $candidate = $candidateModel->getProfileById($candidate_id);
        
        if (!$candidate) {
            session_destroy();
            header('Location: /PAS/public/auth/candidate/login');
            exit;
        }
        
        $data = ['candidate' => $candidate];
        require __DIR__ . '/../views/candidate/dashboard.php';
    }

    public function jobPostings() {
        $candidate_id = $_SESSION['user']['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $candidateModel = new CandidateModel($this->pdo);
            
            if (isset($_POST['withdraw_job_id'])) {
                $job_id = (int)$_POST['withdraw_job_id'];
                if ($candidateModel->withdrawApplication($candidate_id, $job_id)) {
                    $_SESSION['success_message'] = 'Application withdrawn successfully!';
                } else {
                    $_SESSION['error_message'] = 'Cannot withdraw application. You may be shortlisted or application not found.';
                }
                header('Location: /PAS/public/candidate/job-postings');
                exit;
            }
            
            if (isset($_POST['job_id'])) {
                $job_id = (int)$_POST['job_id'];
                $result = $candidateModel->applyForJob($candidate_id, $job_id);
                
                if ($result === false) {
                    $_SESSION['error_message'] = 'Cannot apply. You may have already applied, job is full, or job not found.';
                } else {
                    $_SESSION['success_message'] = 'Application submitted successfully!';
                }
                header('Location: /PAS/public/candidate/job-postings');
                exit;
            }
        }
        
        $candidateModel = new CandidateModel($this->pdo);
        $jobs = $candidateModel->getAllJobPostings($candidate_id);
        
        require __DIR__ . '/../views/candidate/jobpostings.php';
    }

    public function checkApplications() {
        $candidate_id = $_SESSION['user']['id'];
        
        $candidateModel = new CandidateModel($this->pdo);
        $applications = $candidateModel->getApplications($candidate_id);
        
        require __DIR__ . '/../views/candidate/applications.php';
    }

    public function logout() {
        session_destroy();
        header('Location: /PAS/public/auth/candidate/login');
        exit;
    }

    public function showEditProfile($errors = [], $success = '') {
        $candidate_id = $_SESSION['user']['id'];
        
        $candidateModel = new CandidateModel($this->pdo);
        $candidate = $candidateModel->getProfileById($candidate_id);
        
        if (!$candidate) {
            header('Location: /PAS/public/candidate/dashboard');
            exit;
        }
        
        $data = [
            'candidate' => $candidate,
            'errors' => $errors,
            'success' => $success
        ];
        
        require __DIR__ . '/../views/candidate/edit-profile.php';
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->showEditProfile();
        }

        $candidate_id = $_SESSION['user']['id'];
        $candidateModel = new CandidateModel($this->pdo);
        $current_profile = $candidateModel->getProfileById($candidate_id);

        $c_name = trim($_POST['c_name'] ?? '');
        $c_phone = trim($_POST['c_phone'] ?? '');
        $c_age = !empty($_POST['c_age']) ? (int)$_POST['c_age'] : null;
        $c_sex = !empty($_POST['c_sex']) ? $_POST['c_sex'] : null;
        $c_address = trim($_POST['c_address'] ?? '');
        $c_college = trim($_POST['c_college'] ?? '');
        $c_university = trim($_POST['c_university'] ?? '');
        $c_degree = trim($_POST['c_degree'] ?? '');
        $c_department = trim($_POST['c_department'] ?? '');
        $c_cgpa = $_POST['c_cgpa'] ?? '';
        $c_skills = isset($_POST['c_skills']) ? implode(", ", $_POST['c_skills']) : '';
        $c_course_start_year = !empty($_POST['c_course_start_year']) ? (int)$_POST['c_course_start_year'] : null;
        $c_course_end_year = !empty($_POST['c_course_end_year']) ? (int)$_POST['c_course_end_year'] : null;
        $c_current_semester = !empty($_POST['c_current_semester']) ? (int)$_POST['c_current_semester'] : null;
        $c_supply_no = !empty($_POST['c_supply_no']) ? (int)$_POST['c_supply_no'] : 0;
        $c_linkedin = trim($_POST['c_linkedin'] ?? '');
        $c_github = trim($_POST['c_github'] ?? '');

        $errors = [];
        
        if (empty($c_name)) $errors[] = "Name is required.";
        if (empty($c_degree)) $errors[] = "Degree is required.";
        if (empty($c_cgpa) || $c_cgpa < 0 || $c_cgpa > 10) $errors[] = "Valid CGPA (0-10) is required.";
        if (empty($c_skills)) $errors[] = "Please select at least one skill.";
        
        if (!empty($c_phone) && !preg_match('/^[0-9]{10}$/', $c_phone)) {
            $errors[] = "Phone number must be exactly 10 digits.";
        }
        if ($c_age !== null && ($c_age < 17 || $c_age > 100)) {
            $errors[] = "Age must be between 17 and 100.";
        }
        if (!empty($c_linkedin) && !filter_var($c_linkedin, FILTER_VALIDATE_URL)) {
            $errors[] = "LinkedIn profile must be a valid URL.";
        }
        if (!empty($c_github) && !filter_var($c_github, FILTER_VALIDATE_URL)) {
            $errors[] = "GitHub profile must be a valid URL.";
        }
        if ($c_course_start_year !== null && $c_course_end_year !== null) {
            if ($c_course_start_year >= $c_course_end_year) {
                $errors[] = "Course end year must be after start year.";
            }
        }
        if ($c_supply_no < 0 || $c_supply_no > 20) {
            $errors[] = "Number of supplies must be between 0 and 20.";
        }

        $photo_path = $current_profile['photo'];
        
        if (isset($_FILES['c_photo']) && $_FILES['c_photo']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['jpg', 'jpeg', 'png'];
            $photo_type = strtolower(pathinfo($_FILES["c_photo"]["name"], PATHINFO_EXTENSION));
            
            if (!in_array($photo_type, $allowed_types)) {
                $errors[] = "Only JPG, JPEG, and PNG files are allowed for photo.";
            } elseif ($_FILES['c_photo']['size'] > 2 * 1024 * 1024) {
                $errors[] = "Photo size must not exceed 2MB.";
            } else {
                $photo_dir = '/Applications/XAMPP/xamppfiles/htdocs/PAS/public/uploads/photos/';
                if (!is_dir($photo_dir)) {
                    mkdir($photo_dir, 0777, true);
                }

                $photo_name = uniqid() . '_' . basename($_FILES["c_photo"]["name"]);
                $photo_target = $photo_dir . $photo_name;

                if (move_uploaded_file($_FILES["c_photo"]["tmp_name"], $photo_target)) {
                    if (!empty($current_profile['photo'])) {
                        $old_photo = '/Applications/XAMPP/xamppfiles/htdocs' . $current_profile['photo'];
                        if (file_exists($old_photo)) {
                            unlink($old_photo);
                        }
                    }
                    $photo_path = "/PAS/public/uploads/photos/" . $photo_name;
                } else {
                    $errors[] = "Photo upload failed.";
                }
            }
        }

        if ($errors) {
            return $this->showEditProfile($errors);
        }

        $data = [
            'name' => $c_name,
            'phone' => $c_phone,
            'age' => $c_age,
            'sex' => $c_sex,
            'address' => $c_address,
            'college' => $c_college,
            'university' => $c_university,
            'degree' => $c_degree,
            'department' => $c_department,
            'cgpa' => $c_cgpa,
            'skills' => $c_skills,
            'start_year' => $c_course_start_year,
            'end_year' => $c_course_end_year,
            'semester' => $c_current_semester,
            'supply_no' => $c_supply_no,
            'linkedin' => $c_linkedin,
            'github' => $c_github,
            'photo' => $photo_path
        ];

        if ($candidateModel->updateProfile($candidate_id, $data)) {
            $_SESSION['user']['name'] = $c_name;
            
            $_SESSION['success_message'] = "Profile updated successfully!";
            header('Location: /PAS/public/candidate/dashboard');
            exit;
        } else {
            $errors[] = "Failed to update profile. Please try again.";
            return $this->showEditProfile($errors);
        }
    }

    public function feedback() {
        $candidate_id = $_SESSION['user']['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');
            
            $errors = [];
            if (empty($subject)) $errors[] = "Subject is required.";
            if (empty($message)) $errors[] = "Message is required.";
            
            if (!$errors) {
                $feedbackModel = new FeedbackModel($this->pdo);
                if ($feedbackModel->submitFeedback('candidate', $candidate_id, $subject, $message)) {
                    $_SESSION['success_message'] = "Feedback submitted successfully! Admin will respond soon.";
                } else {
                    $_SESSION['error_message'] = "Failed to submit feedback. Please try again.";
                }
                header('Location: /PAS/public/candidate/feedback');
                exit;
            }
        }
        
        $feedbackModel = new FeedbackModel($this->pdo);
        $feedbacks = $feedbackModel->getUserFeedback('candidate', $candidate_id);
        
        $data = [
            'feedbacks' => $feedbacks,
            'errors' => $errors ?? []
        ];
        
        require __DIR__ . '/../views/candidate/feedback.php';
    }
}

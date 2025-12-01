<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'candidate') {
    header('Location: /PAS/public/auth/candidate/login');
    exit;
}

$candidate = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Candidate Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/PAS/public/css/candidate-dashboard.css">
    <link rel="stylesheet" href="/PAS/public/css/feedback.css">
</head>
<body class="dashboard-page">

  <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="#">Placement Assistance System</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-md-auto mx-5 px-4">
          <a class="nav-link" href="/PAS/public/candidate/dashboard">Home</a>
          <a class="nav-link" href="/PAS/public/candidate/job-postings">Job Postings</a>
          <a class="nav-link" href="/PAS/public/candidate/applications">Check Application</a>
          <a class="nav-link active" aria-current="page" href="/PAS/public/candidate/feedback">Feedback</a>
          <a class="nav-link" href="/PAS/public/candidate/logout">Log Out</a>
        </div>
      </div>
    </div>
  </nav>

    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-12">
                <h2><i class="bi bi-chat-square-text text-primary"></i> Feedback & Support</h2>
                <p class="text-muted">Send feedback or report issues to the admin</p>
            </div>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($_SESSION['success_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($_SESSION['error_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (!empty($data['errors'])): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($data['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-envelope"></i> Submit New Feedback</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/PAS/public/candidate/feedback">
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                <select class="form-select" id="subject" name="subject" required>
                                    <option value="">-- Select Subject --</option>
                                    <option value="Technical Issue">Technical Issue</option>
                                    <option value="Application Problem">Application Problem</option>
                                    <option value="Profile Update Issue">Profile Update Issue</option>
                                    <option value="Job Posting Query">Job Posting Query</option>
                                    <option value="Account Issue">Account Issue</option>
                                    <option value="Feature Request">Feature Request</option>
                                    <option value="General Feedback">General Feedback</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" rows="6" 
                                          placeholder="Describe your issue or feedback in detail..." required></textarea>
                                <div class="form-text">Please provide as much detail as possible</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send"></i> Submit Feedback
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Your Feedback History</h5>
                    </div>
                    <div class="card-body feedback-list">
                        <?php if (empty($data['feedbacks'])): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">No feedback submitted yet</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($data['feedbacks'] as $feedback): ?>
                                <div class="feedback-item mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="bi bi-tag"></i> <?= htmlspecialchars($feedback['subject']) ?>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar"></i> 
                                                <?= date('M d, Y h:i A', strtotime($feedback['created_at'])) ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-<?= $feedback['status'] === 'replied' ? 'success' : 'warning' ?>">
                                            <?= $feedback['status'] === 'replied' ? 'Replied' : 'Pending' ?>
                                        </span>
                                    </div>
                                    
                                    <div class="feedback-message mt-2">
                                        <p class="mb-1"><strong>Your Message:</strong></p>
                                        <p class="text-muted"><?= nl2br(htmlspecialchars($feedback['message'])) ?></p>
                                    </div>

                                    <?php if ($feedback['status'] === 'replied' && !empty($feedback['admin_reply'])): ?>
                                        <div class="admin-reply mt-3">
                                            <div class="alert alert-info mb-0">
                                                <p class="mb-1"><strong><i class="bi bi-shield-check"></i> Admin Reply:</strong></p>
                                                <p class="mb-1"><?= nl2br(htmlspecialchars($feedback['admin_reply'])) ?></p>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock"></i> 
                                                    <?= date('M d, Y h:i A', strtotime($feedback['replied_at'])) ?>
                                                </small>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

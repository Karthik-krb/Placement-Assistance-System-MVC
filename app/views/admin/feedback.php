<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: /PAS/public/auth/admin/login');
    exit;
}

$admin = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/PAS/public/css/admin-dashboard.css">
    <link rel="stylesheet" href="/PAS/public/css/feedback.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-shield-check"></i>
            <h2>Admin Panel</h2>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="/PAS/public/admin/dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/admin/candidates">
                    <i class="bi bi-people"></i>
                    <span>Candidates</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/admin/applications">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Applications</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/admin/companies">
                    <i class="bi bi-building"></i>
                    <span>Companies</span>
                </a>
            </li>
            <li>
                <a href="/PAS/public/admin/shortlisted">
                    <i class="bi bi-star"></i>
                    <span>Shortlisted</span>
                </a>
            </li>
            <li class="active">
                <a href="/PAS/public/admin/feedback">
                    <i class="bi bi-chat-square-text"></i>
                    <span>Feedback</span>
                    <?php if ($data['counts']['pending'] > 0): ?>
                        <span class="badge bg-danger rounded-pill ms-2"><?= $data['counts']['pending'] ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="logout">
                <a href="/PAS/public/admin/logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="main-content">
        <header class="page-header">
            <div>
                <h2><i class="bi bi-chat-square-text"></i> Feedback Management</h2>
                <p class="text-muted">Manage feedback from candidates and companies</p>
            </div>
            <div class="user-info">
                <i class="bi bi-person-circle"></i>
                <span><?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Admin'); ?></span>
            </div>
        </header>

        
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

        <section class="stats-grid mb-4">
            <div class="stat-card stat-blue">
                <div class="stat-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <div class="stat-details">
                    <h3><?= $data['counts']['total'] ?></h3>
                    <p>Total Feedback</p>
                </div>
            </div>
            <div class="stat-card stat-yellow">
                <div class="stat-icon">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stat-details">
                    <h3><?= $data['counts']['pending'] ?></h3>
                    <p>Pending</p>
                </div>
            </div>
            <div class="stat-card stat-green">
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-details">
                    <h3><?= $data['counts']['replied'] ?></h3>
                    <p>Replied</p>
                </div>
            </div>
        </section>

        <section class="content-section">
            <div class="section-header mb-3">
                <div class="btn-group" role="group">
                    <a href="/PAS/public/admin/feedback?status=all" 
                       class="btn btn-<?= $data['current_filter'] === 'all' ? 'primary' : 'outline-primary' ?>">
                        <i class="bi bi-list"></i> All (<?= $data['counts']['total'] ?>)
                    </a>
                    <a href="/PAS/public/admin/feedback?status=pending" 
                       class="btn btn-<?= $data['current_filter'] === 'pending' ? 'warning' : 'outline-warning' ?>">
                        <i class="bi bi-clock"></i> Pending (<?= $data['counts']['pending'] ?>)
                    </a>
                    <a href="/PAS/public/admin/feedback?status=replied" 
                       class="btn btn-<?= $data['current_filter'] === 'replied' ? 'success' : 'outline-success' ?>">
                        <i class="bi bi-check-circle"></i> Replied (<?= $data['counts']['replied'] ?>)
                    </a>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; background: #f8f9fa; padding: 0.5rem 1rem; border-radius: 0.5rem; min-width: 250px;">
                    <i class="bi bi-funnel" style="color: #6c757d;"></i>
                    <select class="form-select" id="userTypeFilter" style="border: none; background: transparent; box-shadow: none; padding: 0;">
                        <option value="all">All User Types</option>
                        <option value="candidate">Candidates Only</option>
                        <option value="company">Companies Only</option>
                    </select>
                </div>
            </div>

            <div class="table-container"><?php if (empty($data['feedbacks'])): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">No feedback found</p>
                            </div>
                        <?php else: ?>
                            <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>From</th>
                                            <th>User Type</th>
                                            <th>Subject</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="feedbackTableBody">
                                        <?php foreach ($data['feedbacks'] as $feedback): ?>
                                            <tr data-user-type="<?= $feedback['user_type'] ?>">
                                                <td><?= $feedback['feedback_id'] ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($feedback['user_name']) ?></strong><br>
                                                    <small class="text-muted"><?= htmlspecialchars($feedback['user_email']) ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $feedback['user_type'] === 'candidate' ? 'primary' : 'success' ?>">
                                                        <i class="bi bi-<?= $feedback['user_type'] === 'candidate' ? 'person' : 'building' ?>"></i>
                                                        <?= ucfirst($feedback['user_type']) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($feedback['subject']) ?></td>
                                                <td>
                                                    <div class="feedback-preview">
                                                        <?= htmlspecialchars(substr($feedback['message'], 0, 50)) ?>
                                                        <?= strlen($feedback['message']) > 50 ? '...' : '' ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small><?= date('M d, Y', strtotime($feedback['created_at'])) ?></small><br>
                                                    <small class="text-muted"><?= date('h:i A', strtotime($feedback['created_at'])) ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $feedback['status'] === 'replied' ? 'success' : 'warning' ?>">
                                                        <?= ucfirst($feedback['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#viewModal<?= $feedback['feedback_id'] ?>">
                                                        <i class="bi bi-eye"></i> View
                                                    </button>
                                                    <?php if ($feedback['status'] === 'pending'): ?>
                                                        <button class="btn btn-sm btn-primary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#replyModal<?= $feedback['feedback_id'] ?>">
                                                            <i class="bi bi-reply"></i> Reply
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
            </div>
        </section>
    </div>

    <?php if (!empty($data['feedbacks'])): ?>
        <?php foreach ($data['feedbacks'] as $feedback): ?>
            <div class="modal fade" id="viewModal<?= $feedback['feedback_id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-envelope-open"></i> Feedback Details
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <strong>From:</strong> <?= htmlspecialchars($feedback['user_name']) ?> 
                                (<?= htmlspecialchars($feedback['user_email']) ?>)
                            </div>
                            <div class="mb-3">
                                <strong>User Type:</strong> 
                                <span class="badge bg-<?= $feedback['user_type'] === 'candidate' ? 'primary' : 'success' ?>">
                                    <?= ucfirst($feedback['user_type']) ?>
                                </span>
                            </div>
                            <div class="mb-3">
                                <strong>Subject:</strong> <?= htmlspecialchars($feedback['subject']) ?>
                            </div>
                            <div class="mb-3">
                                <strong>Message:</strong>
                                <div class="p-3 bg-light rounded mt-2">
                                    <?= nl2br(htmlspecialchars($feedback['message'])) ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <strong>Submitted:</strong> 
                                <?= date('M d, Y h:i A', strtotime($feedback['created_at'])) ?>
                            </div>

                            <?php if ($feedback['status'] === 'replied'): ?>
                                <hr>
                                <div class="mb-3">
                                    <strong>Admin Reply:</strong>
                                    <div class="p-3 bg-info bg-opacity-10 rounded mt-2">
                                        <?= nl2br(htmlspecialchars($feedback['admin_reply'])) ?>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <strong>Replied At:</strong> 
                                    <?= date('M d, Y h:i A', strtotime($feedback['replied_at'])) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($data['feedbacks'])): ?>
        <?php foreach ($data['feedbacks'] as $feedback): ?>
            <?php if ($feedback['status'] === 'pending'): ?>
                <div class="modal fade" id="replyModal<?= $feedback['feedback_id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="bi bi-reply"></i> Reply to Feedback
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="/PAS/public/admin/feedback">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <strong>Original Message:</strong>
                                        <div class="p-3 bg-light rounded mt-2">
                                            <?= nl2br(htmlspecialchars($feedback['message'])) ?>
                                        </div>
                                    </div>

                                    <input type="hidden" name="feedback_id" value="<?= $feedback['feedback_id'] ?>">
                                    
                                    <div class="mb-3">
                                        <label for="admin_reply<?= $feedback['feedback_id'] ?>" class="form-label">
                                            <strong>Your Reply:</strong> <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control" 
                                                  id="admin_reply<?= $feedback['feedback_id'] ?>" 
                                                  name="admin_reply" 
                                                  rows="5" 
                                                  placeholder="Type your reply here..." 
                                                  required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send"></i> Send Reply
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <script>
        document.getElementById('userTypeFilter').addEventListener('change', function() {
            const filterValue = this.value;
            const rows = document.querySelectorAll('#feedbackTableBody tr[data-user-type]');
            
            let visibleCount = 0;
            rows.forEach(row => {
                if (filterValue === 'all' || row.dataset.userType === filterValue) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const tbody = document.getElementById('feedbackTableBody');
            const noResultsRow = document.getElementById('noResultsRow');
            
            if (visibleCount === 0 && !noResultsRow) {
                const newRow = document.createElement('tr');
                newRow.id = 'noResultsRow';
                newRow.innerHTML = '<td colspan="8" class="text-center text-muted">No feedback found for selected user type</td>';
                tbody.appendChild(newRow);
            } else if (visibleCount > 0 && noResultsRow) {
                noResultsRow.remove();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

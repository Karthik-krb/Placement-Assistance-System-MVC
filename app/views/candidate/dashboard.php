<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Candidate Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="/PAS/public/css/candidate-dashboard.css?v=6" rel="stylesheet" />
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
          <a class="nav-link active" aria-current="page" href="/PAS/public/candidate/dashboard">Home</a>
          <a class="nav-link" href="/PAS/public/candidate/job-postings">Job Postings</a>
          <a class="nav-link" href="/PAS/public/candidate/applications">Check Application</a>
          <a class="nav-link" href="/PAS/public/candidate/feedback">Feedback</a>
          <a class="nav-link" href="/PAS/public/candidate/logout">Log Out</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="toast-container">
    <?php if (isset($_SESSION['success_message'])): ?>
      <div class="toast custom-toast toast-success align-items-center show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4000">
        <div class="d-flex">
          <div class="toast-body">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= htmlspecialchars($_SESSION['success_message']) ?>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
      <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
      <div class="toast custom-toast toast-error align-items-center show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4000">
        <div class="d-flex">
          <div class="toast-body">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= htmlspecialchars($_SESSION['error_message']) ?>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
      <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
  </div>

  <section class="container">
    <div class="welcome-banner">
      <div class="welcome-content text-center">
        <h1><i class="bi bi-hand-thumbs-up-fill me-2"></i>Welcome</h1>
        <p class="fs-4"><?php echo htmlspecialchars($candidate['name'] ?? ''); ?></p>
      </div>
    </div>
  </section>

  <div class="container my-4 pb-5">
    <div class="row justify-content-center">
      <div class="col-lg-11">
        <div class="profile-card">
          <div class="profile-header">
            <h4><i class="bi bi-person-badge me-2"></i>My Profile</h4>
            <a href="/PAS/public/candidate/edit-profile" class="edit-btn">
              <i class="bi bi-pencil-square me-2"></i>Edit Profile
            </a>
          </div>
          <div class="card-body px-4 py-4">
            <div class="row">
              <div class="col-md-4 text-center border-end pe-4">
                <div class="mb-3">
                  <?php if (!empty($candidate['photo'])): ?>
                    <img src="<?php echo htmlspecialchars($candidate['photo']); ?>" 
                         alt="Profile Photo" 
                         class="profile-photo">
                  <?php else: ?>
                    <div class="profile-placeholder">
                      <?php echo strtoupper(substr($candidate['name'], 0, 1)); ?>
                    </div>
                  <?php endif; ?>
                </div>
                <h5 class="fw-bold" style="color: rgb(26, 69, 198);"><?php echo htmlspecialchars($candidate['name']); ?></h5>
                <p class="text-muted mb-1 fw-semibold">
                  <?php echo htmlspecialchars($candidate['department'] ?: 'Department not specified'); ?>
                </p>
                <p class="text-muted small">
                  <?php echo htmlspecialchars($candidate['college'] ?: 'College not specified'); ?>
                </p>
                
                <div class="mt-3 d-flex flex-column gap-2">
                  <?php if (!empty($candidate['linkedin'])): ?>
                    <a href="<?php echo htmlspecialchars($candidate['linkedin']); ?>" 
                       target="_blank" 
                       class="btn btn-outline-primary social-btn">
                      <i class="bi bi-linkedin me-2"></i>LinkedIn Profile
                    </a>
                  <?php endif; ?>
                  <?php if (!empty($candidate['github'])): ?>
                    <a href="<?php echo htmlspecialchars($candidate['github']); ?>" 
                       target="_blank" 
                       class="btn btn-outline-dark social-btn">
                      <i class="bi bi-github me-2"></i>GitHub Profile
                    </a>
                  <?php endif; ?>
                </div>
              </div>

              <div class="col-md-8 ps-4">
                <div class="mb-4">
                  <h6 class="section-title">
                    <i class="bi bi-person-circle me-2"></i>Personal Information
                  </h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <span class="info-label"><i class="bi bi-envelope-fill me-2"></i>Email:</span><br>
                      <span class="text-muted"><?php echo htmlspecialchars($candidate['email']); ?></span>
                    </div>
                    <div class="col-md-6 mb-3">
                      <span class="info-label"><i class="bi bi-phone-fill me-2"></i>Phone:</span><br>
                      <span class="text-muted"><?php echo htmlspecialchars($candidate['phone'] ?: 'Not provided'); ?></span>
                    </div>
                    <div class="col-md-6 mb-3">
                      <span class="info-label"><i class="bi bi-calendar-fill me-2"></i>Age:</span><br>
                      <span class="text-muted"><?php echo htmlspecialchars($candidate['age'] ?: 'Not provided'); ?></span>
                    </div>
                    <div class="col-md-6 mb-3">
                      <span class="info-label"><i class="bi bi-gender-ambiguous me-2"></i>Gender:</span><br>
                      <span class="text-muted"><?php echo htmlspecialchars($candidate['sex'] ?: 'Not specified'); ?></span>
                    </div>
                    <div class="col-12 mb-2">
                      <span class="info-label"><i class="bi bi-geo-alt-fill me-2"></i>Address:</span><br>
                      <span class="text-muted"><?php echo htmlspecialchars($candidate['address'] ?: 'Not provided'); ?></span>
                    </div>
                  </div>
                </div>

                <div class="mb-4">
                  <h6 class="section-title">
                    <i class="bi bi-mortarboard-fill me-2"></i>Academic Information
                  </h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <span class="info-label"><i class="bi bi-building me-2"></i>College:</span><br>
                      <span class="text-muted"><?php echo htmlspecialchars($candidate['college'] ?: 'Not provided'); ?></span>
                    </div>
                    <div class="col-md-6 mb-3">
                      <span class="info-label"><i class="bi bi-bank me-2"></i>University:</span><br>
                      <span class="text-muted"><?php echo htmlspecialchars($candidate['university'] ?: 'Not provided'); ?></span>
                    </div>
                    <div class="col-md-6 mb-3">
                      <span class="info-label"><i class="bi bi-book-fill me-2"></i>Department:</span><br>
                      <span class="text-muted"><?php echo htmlspecialchars($candidate['department'] ?: 'Not provided'); ?></span>
                    </div>
                    <div class="col-md-6 mb-3">
                      <span class="info-label"><i class="bi bi-trophy-fill me-2"></i>CGPA:</span><br>
                      <span class="badge bg-success fs-6"><?php echo htmlspecialchars($candidate['cgpa']); ?></span>
                    </div>
                    <div class="col-md-6 mb-3">
                      <span class="info-label"><i class="bi bi-calendar-range me-2"></i>Course Duration:</span><br>
                      <span class="text-muted">
                      <?php 
                        if ($candidate['course_start_year'] && $candidate['course_end_year']) {
                          echo htmlspecialchars($candidate['course_start_year'] . ' - ' . $candidate['course_end_year']);
                        } else {
                          echo 'Not provided';
                        }
                      ?>
                      </span>
                    </div>
                    <div class="col-md-6 mb-3">
                      <span class="info-label"><i class="bi bi-journal-bookmark-fill me-2"></i>Current Semester:</span><br>
                      <span class="text-muted"><?php echo htmlspecialchars($candidate['current_semester'] ?: 'Not provided'); ?></span>
                    </div>
                    <div class="col-md-6 mb-3">
                      <span class="info-label"><i class="bi bi-exclamation-circle-fill me-2"></i>Supplies/Backlogs:</span><br>
                      <?php 
                        $supply = $candidate['supply_no'] ?? 0;
                        if ($supply == 0) {
                          echo '<span class="badge bg-success fs-6">No Supplies</span>';
                        } else {
                          echo '<span class="badge bg-warning text-dark fs-6">' . htmlspecialchars($supply) . ' Supply(s)</span>';
                        }
                      ?>
                    </div>
                  </div>
                </div>

                <div class="mb-3">
                  <h6 class="section-title">
                    <i class="bi bi-gear-fill me-2"></i>Skills & Documents
                  </h6>
                  <div class="mb-3">
                    <span class="info-label">Skills:</span><br>
                    <div class="mt-2">
                      <?php 
                        $skills = explode(', ', $candidate['skills']);
                        foreach ($skills as $skill): 
                      ?>
                        <span class="badge bg-info text-dark me-1 mb-2 fs-6"><?php echo htmlspecialchars($skill); ?></span>
                      <?php endforeach; ?>
                    </div>
                  </div>
                  <?php if (!empty($candidate['resume'])): ?>
                    <div>
                      <span class="info-label"><i class="bi bi-file-pdf-fill me-2"></i>Resume:</span><br>
                      <a href="<?php echo htmlspecialchars($candidate['resume']); ?>" 
                         target="_blank" 
                         class="btn btn-primary mt-2 social-btn">
                        <i class="bi bi-download me-2"></i>Download Resume
                      </a>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var toastElList = [].slice.call(document.querySelectorAll('.toast'));
      var toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl);
      });
    });
  </script>
</body>
</html>

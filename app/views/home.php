<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Assistance System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/PAS/public/css/styles.css">
</head>
<body class="home-page">


<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="/PAS/public/">Placement Assistance System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-md-auto mx-5 px-4">
                <a class="nav-link active" href="/PAS/public/">Home</a>
                <a class="nav-link" href="/PAS/public/auth/candidate/login">Candidate</a>
                <a class="nav-link" href="/PAS/public/auth/company/login">Company</a>
                <a class="nav-link" href="/PAS/public/auth/admin/login">Admin</a>
            </div>
        </div>
    </div>
</nav>


<section id="main-container">
    <div class="container-fluid p-0">
        <div id="carouselExampleIndicators" class="carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/PAS/public/home/image_1.jpeg" class="d-block w-100" alt="Slide 1">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Your <span>Future</span> Starts Here</h5>
                        <p>"Success is where preparation and opportunity meet." – Bobby Unser</p>
                        <p class="d-inline-flex gap-1">
                            <a href="/PAS/public/auth/candidate/login" class="btn btn-primary">Log In</a>
                            <a href="/PAS/public/auth/candidate/register" class="btn btn-outline-light">Sign Up</a>
                        </p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/PAS/public/home/image_2.jpeg" class="d-block w-100" alt="Slide 2">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Unlock Your <span>Potential</span></h5>
                        <p>"The only limit to our realization of tomorrow is our doubts of today." – Franklin D. Roosevelt</p>
                        <p class="d-inline-flex gap-1">
                            <a href="/PAS/public/auth/candidate/login" class="btn btn-primary">Log In</a>
                            <a href="/PAS/public/auth/candidate/register" class="btn btn-outline-light">Sign Up</a>
                        </p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/PAS/public/home/image_3.jpeg" class="d-block w-100" alt="Slide 3">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Ace Your <span>Placements</span></h5>
                        <p>"Opportunities don't happen, you create them." – Chris Grosser</p>
                        <p class="d-inline-flex gap-1">
                            <a href="/PAS/public/auth/candidate/login" class="btn btn-primary">Log In</a>
                            <a href="/PAS/public/auth/candidate/register" class="btn btn-outline-light">Sign Up</a>
                        </p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/PAS/public/js/main.js"></script>
</body>
</html>
<?php
require_once 'config/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features - Invoice Generator</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Invoice Generator</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Left-aligned links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="makeinvoice.php">Generate Invoice</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutus.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="features.php">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contactus.php">Contact</a>
                    </li>
                </ul>

                <!-- Right-aligned buttons -->
                <div class="d-flex">
                    <?php if (!isLoggedIn()): ?>
                        <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#signInModal">Sign In</button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#signUpModal">Sign Up</button>
                    <?php else: ?>
                        <span class="me-3 align-self-center">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <button class="btn btn-outline-danger" id="signOutBtn">Sign Out</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center py-5 mt-5">
        <div class="container" style="margin-top: 60px;">
            <h1 class="display-4">Our Features</h1>
            <p class="lead">Discover what makes our Invoice Generator the perfect choice for your business</p>
        </div>
    </section>

    <!-- Main Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Professional Invoice Creation -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm hover-effect">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <i class="bi bi-file-earmark-text fs-1 text-primary me-3"></i>
                                <h3 class="card-title mb-0">Professional Invoice Creation</h3>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Customizable invoice templates</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Add your company logo and branding</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Multiple currency support</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Automatic calculations</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- PDF Generation -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm hover-effect">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <i class="bi bi-file-pdf fs-1 text-danger me-3"></i>
                                <h3 class="card-title mb-0">PDF Generation</h3>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>High-quality PDF exports</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Instant download options</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Professional formatting</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Digital backup copies</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Email Integration -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm hover-effect">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <i class="bi bi-envelope fs-1 text-info me-3"></i>
                                <h3 class="card-title mb-0">Email Integration</h3>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Direct email sending</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Customizable email templates</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Automated follow-ups</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Email tracking</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Data Security -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm hover-effect">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <i class="bi bi-shield-check fs-1 text-success me-3"></i>
                                <h3 class="card-title mb-0">Data Security</h3>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Secure data encryption</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Regular backups</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Privacy protection</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>GDPR compliance</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">More Great Features</h2>
            <div class="row g-4">
                <!-- Client Management -->
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="bi bi-people-fill fs-1 text-primary mb-3"></i>
                        <h4>Client Management</h4>
                        <p>Store and manage client information for quick invoice generation</p>
                    </div>
                </div>

                <!-- Payment Tracking -->
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="bi bi-credit-card fs-1 text-success mb-3"></i>
                        <h4>Payment Tracking</h4>
                        <p>Monitor payment status and send automatic reminders</p>
                    </div>
                </div>

                <!-- Analytics & Reporting -->
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="bi bi-graph-up fs-1 text-info mb-3"></i>
                        <h4>Analytics & Reporting</h4>
                        <p>Generate insights with detailed financial reports</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-5 text-center">
        <div class="container">
            <h2 class="mb-4">Ready to Get Started?</h2>
            <p class="lead mb-4">Join thousands of satisfied businesses using our Invoice Generator</p>
            <?php if (!isLoggedIn()): ?>
            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#signUpModal">Sign Up Now</button>
            <?php else: ?>
            <a href="makeinvoice.php" class="btn btn-primary btn-lg">Create Your First Invoice</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2025 Invoice Generator. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-decoration-none text-muted me-3">Privacy Policy</a>
                    <a href="#" class="text-decoration-none text-muted me-3">Terms of Service</a>
                    <a href="features.php" class="text-decoration-none text-muted">Features</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#signOutBtn').click(function() {
            $.ajax({
                url: 'process_logout.php',
                method: 'POST',
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        window.location.reload();
                    }
                }
            });
        });
    });
    </script>
</body>
</html>
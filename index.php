<?php
require_once 'config/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css_and_js/index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
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
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="makeinvoice.php">Generate Invoice</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="invoices.php">My Invoices</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="doc/aboutus.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contactus.php">Contact</a>
                    </li>
                </ul>

                <!-- Right-aligned buttons -->
                <div class="d-flex">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="loginsystem/signin.php" class="btn btn-outline-primary me-2 btn-signin">Sign In</a>
                        <a href="loginsystem/signup.php" class="btn btn-primary btn-signup">Sign Up</a>
                    <?php else: ?>
                        <span class="me-3 align-self-center" style="color: white;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <a href="loginsystem/signout.php" class="btn btn-outline-danger btn-signout">Sign Out</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container" style="margin-top: 100px;">
        <h1>Welcome to Invoice Generator</h1>
        <p class="lead">Create professional invoices with ease.</p>
        <div class="text-center mt-4">
            <?php if (isset($_SESSION['user_id'])): ?>
            <a href="makeinvoice.php" class="btn btn-primary btn-lg">
                Generate Invoice
                <i class="bi bi-file-earmark-text ms-2"></i>
            </a>
            <p class="mt-3">Start creating your professional invoices!</p>
            <?php else: ?>
            <a href="loginsystem/signin.php" class="btn btn-primary btn-lg">
                Sign In to Generate Invoice
                <i class="bi bi-file-earmark-text ms-2"></i>
            </a>
            <p class="mt-3 text-muted">Sign in to start creating professional invoices!</p>
            <?php endif; ?>
        </div>
    </div>
<br>
<div class="container">
    <h3 class="text-center mb-4">Features</h3>
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm hover-effect">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text fs-1 text-primary mb-3"></i>
                    <h5 class="card-title">Professional Invoices</h5>
                    <p class="card-text">Create professional invoices with ease.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm hover-effect">
            <div class="card-body text-center">
                <i class="bi bi-currency-exchange fs-1 text-info mb-3"></i>
                <h5 class="card-title">Multi-Currency Support</h5>
                <p class="card-text">Generate invoices in multiple currencies to cater to global clients.</p>
            </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm hover-effect">
                <div class="card-body text-center">
                    <i class="bi bi-file-pdf fs-1 text-danger mb-3"></i>
                    <h5 class="card-title">PDF Download</h5>
                    <p class="card-text">Download your invoices in PDF format.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm hover-effect">
            <div class="card-body text-center">
                <i class="bi bi-laptop fs-1 text-warning mb-3"></i>
                <h5 class="card-title">Beautiful Interface</h5>
                <p class="card-text">Enjoy a sleek, modern, and responsive design for seamless user experience.</p>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- Testimonials Section -->
<div class="container mt-5 mb-5">
    <h3 class="text-center mb-4">What Our Clients Say</h3>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-person-circle fs-1 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">John Smith</h5>
                            <small class="text-muted">Small Business Owner</small>
                        </div>
                    </div>
                    <p class="card-text">"This invoice generator has streamlined our billing process. It's intuitive and professional - exactly what we needed!"</p>
                    <div class="text-warning">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-person-circle fs-1 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">Sarah Johnson</h5>
                            <small class="text-muted">Freelance Designer</small>
                        </div>
                    </div>
                    <p class="card-text">"The PDF download and email integration features save me so much time. Highly recommended!"</p>
                    <div class="text-warning">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-person-circle fs-1 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">Michael Brown</h5>
                            <small class="text-muted">Tech Startup CEO</small>
                        </div>
                    </div>
                    <p class="card-text">"The customization options and professional templates have helped us maintain a consistent brand image."</p>
                    <div class="text-warning">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="container mt-5 mb-5">
    <h3 class="text-center mb-4">Frequently Asked Questions</h3>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            How do I get started?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Simply sign up for a free account and follow our easy step-by-step guide to create your first invoice. No credit card required for the free plan.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Can I customize my invoices?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes! You can customize your invoices with your company logo, colors, and preferred layout. Pro users get access to additional premium templates and customization options.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Is my data secure?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We take security seriously. All your data is encrypted and stored securely. We never share your information with third parties.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="container mt-5 mb-5">
    <h3 class="text-center mb-4">How It Works</h3>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card h-100 shadow-sm hover-effect">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-block mb-3">
                        <i class="bi bi-person-plus fs-1 text-primary"></i>
                    </div>
                    <h5 class="card-title">1. Create Account</h5>
                    <p class="card-text">Sign up for free and set up your business profile with logo and details.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm hover-effect">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                        <i class="bi bi-pencil-square fs-1 text-success"></i>
                    </div>
                    <h5 class="card-title">2. Enter Details</h5>
                    <p class="card-text">Fill in your client's information and itemize your products or services.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm hover-effect">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 d-inline-block mb-3">
                        <i class="bi bi-brush fs-1 text-info"></i>
                    </div>
                    <h5 class="card-title">3. Customize</h5>
                    <p class="card-text">Choose from our professional templates and customize to match your brand.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm hover-effect">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 d-inline-block mb-3">
                        <i class="bi bi-send fs-1 text-danger"></i>
                    </div>
                    <h5 class="card-title">4. Send & Track</h5>
                    <p class="card-text">Download as PDF or send directly to your client's email. Track payment status.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer -->
<footer class="footer py-4 bg-light">
    <div class="container">
        <div class="row g-4">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6">
                <h5 class="mb-3">Invoice Generator</h5>
                <p class="text-muted">Create professional invoices quickly and easily with our intuitive invoice generator tool.</p>
                <div class="social-links">
                    <a href="#" class="text-dark me-2 social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-dark me-2 social-icon"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-dark me-2 social-icon"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="text-dark social-icon"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="mb-3">Quick Links</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                    <li><a href="aboutus.php" class="text-decoration-none text-muted">About Us</a></li>
                    <li><a href="features.php" class="text-decoration-none text-muted">Features</a></li>
                    <li><a href="contactus.php" class="text-decoration-none text-muted">Contact</a></li>
                </ul>
            </div>
            <!-- Newsletter & Contact -->
            <div class="col-lg-4 col-md-6">
                <h5 class="mb-3">Contact Us</h5>
                <ul class="list-unstyled contact-info">
                    <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>123 Business Street, Suite 100, City, Country</li>
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i>support@invoicegenerator.com</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i>+1 (555) 123-4567</li>
                </ul>
            </div>
        </div>
        <!-- Copyright -->
        <div class="pt-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-md-0">© 2025 Invoice Generator. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="doc/privacy.php" class="text-decoration-none text-muted me-3">Privacy Policy</a>
                    <a href="doc/terms.php" class="text-decoration-none text-muted me-3">Terms of Service</a>
                    <a href="doc/help.php" class="text-decoration-none text-muted me-3">Help</a>
                    <a href="doc/documentation.html" class="text-decoration-none text-muted">Documentation</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Back to Top Button -->
    <button id="backToTop" class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4" style="display: none;">
        <i class="bi bi-arrow-up"></i>
    </button>
</footer>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Back to Top Button Script -->
    <script>
        // Show/Hide Back to Top button
        window.addEventListener('scroll', function() {
            var backToTop = document.getElementById('backToTop');
            if (window.scrollY > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });

        // Scroll to top when button is clicked
        document.getElementById('backToTop').addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>

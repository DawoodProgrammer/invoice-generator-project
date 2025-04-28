<?php
require_once '../config/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - Invoice Generator</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">Invoice Generator</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../makeinvoice.php">Generate Invoice</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../aboutus.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../contactus.php">Contact</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <?php if (!isLoggedIn()): ?>
                        <button class="btn btn-outline-primary me-2 btn-signin" data-bs-toggle="modal" data-bs-target="#signInModal">Sign In</button>
                        <button class="btn btn-primary btn-signup" data-bs-toggle="modal" data-bs-target="#signUpModal">Sign Up</button>
                    <?php else: ?>
                        <span class="me-3 align-self-center">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <button class="btn btn-outline-danger btn-signout" id="signOutBtn">Sign Out</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container" style="margin-top: 100px;">
        <h1>Help Center Quick Start Guide</h1>
        
        <!-- Quick Start Guide -->
        <section class="mb-5">
          
            <div class="card">
                <div class="card-body">
                    <h3>Getting Started</h3>
                    <ol>
                        <li>Create an account or sign in if you already have one</li>
                        <li>Click on "Generate Invoice" in the navigation menu</li>
                        <li>Fill in your business details and logo</li>
                        <li>Add client information</li>
                        <li>Add items to your invoice</li>
                        <li>Preview and download your invoice</li>
                    </ol>
                </div>
            </div>
        </section>

        <!-- FAQs -->
        <section class="mb-5">
            <h2>Frequently Asked Questions</h2>
            <div class="accordion" id="helpAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            How do I create my first invoice?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                        <div class="accordion-body">
                            After signing in, click on "Generate Invoice" in the navigation menu. Fill in your business details, add your logo, enter client information, and add items to your invoice. You can preview the invoice before downloading it.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Can I customize the invoice template?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                        <div class="accordion-body">
                            Yes, you can customize your invoice by adding your company logo, choosing colors, and selecting different layouts. We provide various templates to suit your needs.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            How do I save my invoices?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                        <div class="accordion-body">
                            All your invoices are automatically saved in your account. You can access them anytime by logging into your account and viewing your invoice history.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Support -->
        <section class="mb-5">
            <h2>Need More Help?</h2>
            <div class="card">
                <div class="card-body">
                    <p>If you can't find the answer you're looking for, our support team is here to help!</p>
                    <a href="../contactus.php" class="btn btn-primary">Contact Support</a>
                </div>
            </div>
        </section>
    </div>

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
                    <a href="../features.php" class="text-decoration-none text-muted me-3">Features</a>
                    <a href="help.php" class="text-decoration-none text-muted me-3">Help</a>
                    <a href="documentation.php" class="text-decoration-none text-muted">Documentation</a>
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
                url: '../process_logout.php',
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
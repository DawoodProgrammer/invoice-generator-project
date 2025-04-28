<?php
require_once '../config/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Invoice Generator</title>
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
        <h1>Terms of Service</h1>
        <div class="mt-4">
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing and using Invoice Generator, you accept and agree to be bound by the terms and provision of this agreement.</p>

            <h2>2. Description of Service</h2>
            <p>Invoice Generator provides an online platform for creating, managing, and sending professional invoices. The service is provided "as is" and on an "as available" basis.</p>

            <h2>3. User Account</h2>
            <p>To use certain features of the service, you must register for an account. You agree to provide accurate information and keep it updated.</p>

            <h2>4. User Conduct</h2>
            <p>You agree not to use the service for any unlawful purpose or in any way that could damage, disable, or impair the service.</p>

            <h2>5. Intellectual Property</h2>
            <p>The service and its original content, features, and functionality are owned by Invoice Generator and are protected by international copyright, trademark, and other intellectual property rights.</p>

            <h2>6. Limitation of Liability</h2>
            <p>Invoice Generator shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use or inability to use the service.</p>

            <h2>7. Changes to Terms</h2>
            <p>We reserve the right to modify these terms at any time. We will notify users of any changes by updating the date at the top of this agreement.</p>

            <h2>8. Governing Law</h2>
            <p>These terms shall be governed by and construed in accordance with the laws of the jurisdiction in which Invoice Generator operates.</p>
        </div>
    </div>

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
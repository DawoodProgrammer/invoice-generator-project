<?php
require_once '../config/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Invoice Generator</title>
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
        <h1>Privacy Policy</h1>
        <div class="mt-4">
            <h2>1. Information We Collect</h2>
            <p>We collect information that you provide directly to us, including:</p>
            <ul>
                <li>Name and contact information</li>
                <li>Account credentials</li>
                <li>Business information for invoicing</li>
                <li>Payment information</li>
            </ul>

            <h2>2. How We Use Your Information</h2>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Provide and maintain our services</li>
                <li>Process your transactions</li>
                <li>Send you technical notices and support messages</li>
                <li>Communicate with you about products, services, and events</li>
            </ul>

            <h2>3. Information Sharing</h2>
            <p>We do not sell or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
            <ul>
                <li>With your consent</li>
                <li>To comply with legal obligations</li>
                <li>To protect our rights and prevent fraud</li>
            </ul>

            <h2>4. Data Security</h2>
            <p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>

            <h2>5. Your Rights</h2>
            <p>You have the right to:</p>
            <ul>
                <li>Access your personal information</li>
                <li>Correct inaccurate data</li>
                <li>Request deletion of your data</li>
                <li>Object to data processing</li>
            </ul>

            <h2>6. Cookies</h2>
            <p>We use cookies and similar tracking technologies to track activity on our service and hold certain information to improve and analyze our service.</p>

            <h2>7. Changes to Privacy Policy</h2>
            <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page.</p>

            <h2>8. Contact Us</h2>
            <p>If you have any questions about this Privacy Policy, please contact us.</p>
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
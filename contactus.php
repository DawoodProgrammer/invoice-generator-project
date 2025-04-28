<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Invoice Generator</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css_and_js/index.css">


</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top ">
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
                    <li class="nav-item">
                        <a class="nav-link" href="doc/aboutus.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contactus.php">Contact</a>
                    </li>
                </ul>


            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center py-5 ">
        <div class="container" style="margin-top: 60px;" >
            <h1 class="display-4">Contact Us</h1>
            <p class="lead">We're here to help and answer any questions you might have</p>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-geo-alt display-4 text-primary mb-3"></i>
                            <h3>Visit Us</h3>
                            <p>123 Business Avenue<br>Islamabad, 44000<br>Pakistan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-telephone display-4 text-primary mb-3"></i>
                            <h3>Call Us</h3>
                            <p>Phone: +1 (555) 123-4567<br>Fax: +1 (555) 123-4568</p>
                            <p class="mb-0"><strong>Hours:</strong><br>Mon-Fri: 9:00 AM - 6:00 PM (PKT)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-envelope display-4 text-primary mb-3"></i>
                            <h3>Email Us</h3>
                            <p>General Inquiries:<br>info@invoicegenerator.com</p>
                            <p class="mb-0">Support:<br>support@invoicegenerator.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow contact-form-card">
                        <div class="card-body p-5">
                            <h2 class="text-center mb-4">Send Us a Message</h2>
                            <form id="contactForm">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" rows="5" required></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg btn-submit">Send Message</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-5">
        <div class="container">
            <div class="ratio ratio-21x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d212645.3107405034!2d72.93583678859824!3d33.61611318843451!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38dfbfd07891722f%3A0x6059515c3bdb02b6!2sIslamabad%2C%20Islamabad%20Capital%20Territory%2C%20Pakistan!5e0!3m2!1sen!2s!4v1699933000000!5m2!1sen!2s" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
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
                    <a href="#" class="text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Add loading state to button
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Sending...';
        submitBtn.disabled = true;
        
        // Simulate form submission
        setTimeout(() => {
            // Show success message
            const successAlert = document.createElement('div');
            successAlert.className = 'alert alert-success mt-3 animate__animated animate__fadeIn';
            successAlert.innerHTML = '<i class="bi bi-check-circle me-2"></i>Your message has been sent successfully!';
            this.appendChild(successAlert);
            
            // Reset form and button
            this.reset();
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            // Remove success message after 5 seconds
            setTimeout(() => {
                successAlert.classList.replace('animate__fadeIn', 'animate__fadeOut');
                setTimeout(() => successAlert.remove(), 1000);
            }, 5000);
        }, 2000);
    });
    </script>
    </body>
    </html>
</body>
</html>
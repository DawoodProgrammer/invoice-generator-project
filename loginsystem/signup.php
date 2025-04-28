<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    // Validate username
    if (empty($name)) {
        $errors['username'] = 'Username is required';
    } elseif (strlen($name) < 3) {
        $errors['username'] = 'Username must be at least 3 characters';
    }
    
    // Validate email
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    
    // Check if email or username already exists
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? OR name = ?');
    $stmt->bind_param('ss', $email, $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Check which field is duplicate
        $stmt = $conn->prepare('SELECT id FROM users WHERE name = ?');
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $nameResult = $stmt->get_result();
        if ($nameResult->num_rows > 0) {
            $errors['username'] = 'Username already exists';
        } else {
            $errors['email'] = 'Email already exists';
        }
    }
    
    // Validate password
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }
    
    // Validate confirm password
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }
    
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user
        $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $name, $email, $hashed_password);
        
        try {
            if ($stmt->execute()) {
                header('Location: signin.php');
                exit();
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Duplicate entry error
                $errors['username'] = 'Username already exists';
            } else {
                $errors['general'] = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Invoice Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css_and_js/loginsys.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</head>
<body>
    <main class="form-signup">
        <form method="POST" action="" class="needs-validation" novalidate>
            <h1 class="h3 mb-3 fw-normal text-center">Sign Up</h1>
            
            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
            <?php endif; ?>
            
            <div class="form-floating mb-3">
                <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                       id="username" name="username" placeholder="Username" 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                <label for="username">Username</label>
                <?php if (isset($errors['username'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-floating mb-3">
                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                       id="email" name="email" placeholder="name@example.com"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <label for="email">Email address</label>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                       id="password" name="password" placeholder="Password">
                <label for="password">Password</label>
                <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y" style="z-index: 10; padding: 0.75rem;" onclick="togglePassword('password', 'togglePassword')">
                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                </button>
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                       id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                <label for="confirm_password">Confirm Password</label>
                <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y" style="z-index: 10; padding: 0.75rem;" onclick="togglePassword('confirm_password', 'toggleConfirmPassword')">
                    <i class="bi bi-eye-slash" id="toggleConfirmPassword"></i>
                </button>
                <?php if (isset($errors['confirm_password'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                <?php endif; ?>
            </div>
            
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign Up</button>
            <p class="mt-3 text-center">Already have an account? <a href="signin.php">Sign In</a></p>
        </form>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side validation
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Toggle password visibility
        function togglePassword(inputId, toggleIconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(toggleIconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        }
    </script>
</body>
</html>
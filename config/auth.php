<?php
session_start();
require_once 'db.php';

function registerUser($name, $email, $password) {
    global $pdo;
    
    try {
        // Sanitize and validate email
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        // Convert email to lowercase for consistent comparison
        $email = strtolower(trim($email));
        
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $emailExists = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if username already exists (case-insensitive)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE LOWER(name) = LOWER(?)");
        $stmt->execute([$name]);
        $nameExists = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($emailExists) {
            return ['success' => false, 'message' => 'This email address is already registered. Please use a different email.'];
        }
        
        if ($nameExists) {
            return ['success' => false, 'message' => 'This username is already taken. Please choose a different username.'];
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword]);

        return ['success' => true, 'message' => 'Registration successful'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
    }
}

function loginUser($email, $password) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            return ['success' => true, 'message' => 'Login successful'];
        }

        return ['success' => false, 'message' => 'Invalid email or password'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Login failed: ' . $e->getMessage()];
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function logout() {
    if (isset($_SESSION['user_id'])) {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        session_destroy();
        return ['success' => true, 'message' => 'Logged out successfully'];
    }
    return ['success' => false, 'message' => 'No active session found'];
}
?>
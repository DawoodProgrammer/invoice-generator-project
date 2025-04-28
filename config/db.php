<?php
$host = 'localhost';
$dbname = 'invoice_db1';
$user = 'root';
$pass = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create mysqli connection for backward compatibility
    $conn = new mysqli($host, $user, $pass, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("mysqli connection failed: " . $conn->connect_error);
    }
} catch(PDOException $e) {
    die("PDO connection failed: " . $e->getMessage());
} catch(Exception $e) {
    die($e->getMessage());
}
?>
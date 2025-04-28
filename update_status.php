<?php
require('config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $invoiceId = $_POST['id'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE invoices SET status = ? WHERE id = ?");
        $stmt->execute([$status, $invoiceId]);
        header("Location: preview.php?id=$invoiceId");
        exit;
    } catch (PDOException $e) {
        die("Error updating status: " . $e->getMessage());
    }
} else {
    die("Invalid request");
}
?>
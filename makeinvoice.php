<?php
session_start();
require('config/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: loginsystem/signin.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
        // Get next invoice number from sequence
        $stmt = $pdo->query("SELECT next_number FROM invoice_sequence FOR UPDATE");
        $nextNumber = $stmt->fetchColumn();
        $pdo->exec("UPDATE invoice_sequence SET next_number = next_number + 1");
        
        // Handle logo upload
        $logo_path = null;
        if(isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            
            if(in_array($ext, $allowed)) {
                $upload_dir = 'uploads/';
                if(!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $logo_path = $upload_dir . uniqid('logo_') . '.' . $ext;
                move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path);
            }
        }

        $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cash';
        $bank_name = ($payment_method === 'bank' && isset($_POST['bank_name'])) ? $_POST['bank_name'] : null;
        $account_number = ($payment_method === 'bank' && isset($_POST['account_number'])) ? $_POST['account_number'] : null;
        $paypal_email = ($payment_method === 'paypal' && isset($_POST['paypal_email'])) ? $_POST['paypal_email'] : null;

        $tax_rate = !empty($_POST['tax_rate']) ? floatval($_POST['tax_rate']) : null;
        $shipping_cost = !empty($_POST['shipping_cost']) ? floatval($_POST['shipping_cost']) : null;

        $stmt = $pdo->prepare("INSERT INTO invoices (user_id, customer_name, customer_address, invoice_number, invoice_date, due_date, total_amount, currency, tax_rate, tax_amount, shipping_cost, logo_path, status, payment_method, bank_name, account_number, paypal_email, additional_notes, company_email) 
            VALUES (:user_id, :name, :address, :number, :date, :due_date, :total, :currency, :tax_rate, :tax_amount, :shipping_cost, :logo, :status, :payment_method, :bank_name, :account_number, :paypal_email, :additional_notes, :company_email)");
        
        $subtotal = 0;
        if(isset($_POST['item_qty']) && isset($_POST['item_price'])) {
            foreach($_POST['item_qty'] as $index => $qty) {
                if(is_numeric($qty) && isset($_POST['item_price'][$index]) && is_numeric($_POST['item_price'][$index])) {
                    $price = (float)$_POST['item_price'][$index];
                    if($price > 100000000000) {
                        throw new Exception("Item price cannot exceed 100,000,000,000");
                    }
                    $subtotal += (float)$qty * $price;
                }
            }
            if($subtotal > 100000000000) {
                throw new Exception("Total amount cannot exceed 100,000,000,000");
            }
        }

        $tax_amount = $tax_rate ? ($subtotal * $tax_rate / 100) : null;
        $total = $subtotal + ($tax_amount ?? 0) + ($shipping_cost ?? 0);
        
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':name' => $_POST['customer_name'],
            ':address' => $_POST['customer_address'],
            ':number' => $nextNumber,
            ':date' => $_POST['invoice_date'],
            ':due_date' => ($_POST['status'] === 'paid' ? null : $_POST['due_date']),
            ':total' => $total,
            ':currency' => $_POST['currency'],
            ':tax_rate' => $tax_rate,
            ':tax_amount' => $tax_amount,
            ':shipping_cost' => $shipping_cost,
            ':logo' => $logo_path,
            ':status' => $_POST['status'],
            ':payment_method' => $payment_method,
            ':bank_name' => $bank_name,
            ':account_number' => $account_number,
            ':paypal_email' => $paypal_email,
            ':additional_notes' => $_POST['additional_notes'],
            ':company_email' => isset($_POST['company_email']) ? $_POST['company_email'] : null
        ]);
        
        $invoiceId = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO items (invoice_id, description, quantity, price) 
            VALUES (:invoice_id, :desc, :qty, :price)");

        foreach($_POST['item_desc'] as $index => $desc) {
            $stmt->execute([
                ':invoice_id' => $invoiceId,
                ':desc' => $desc,
                ':qty' => $_POST['item_qty'][$index],
                ':price' => $_POST['item_price'][$index]
            ]);
        }

        $pdo->commit();
        header("Location: preview.php?id=$invoiceId");
        exit;

    } catch(Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
        if($error === "Item price cannot exceed 100,000,000" || $error === "Total amount cannot exceed 100,000,000") {
            $error = "Error: " . $error . ". Please enter a smaller amount.";
        } else {
            $error = "Error creating invoice: Invoice number already exists";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Generator Pro</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css_and_js/makeinovice.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top ">
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
                        <a class="nav-link active" href="contactus.html">Contact</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    <div class="container" style="margin-top: 70px;" >
        <div class="header">
            <h2 class="mt-4"><i class="fas fa-file-invoice-dollar"></i> Create New Invoice</h2>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-section">
                <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                            <label class="form-label label-icon"><i class="fas fa-image"></i> Company Logo</label>
                            <input type="file" class="form-control" name="logo" accept="image/*">
                            <small class="text-muted">Supported formats: JPG, JPEG, PNG, GIF</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label label-icon"><i class="fas fa-user"></i> Customer Name</label>
                            <input type="text" class="form-control" name="customer_name" placeholder="Enter customer name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label label-icon"><i class="fas fa-map-marker-alt"></i> Customer Address</label>
                            <textarea class="form-control" name="customer_address" rows="3" placeholder="Enter customer address" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label label-icon"><i class="fas fa-envelope"></i> Company Email</label>
                            <input type="email" class="form-control" name="company_email" placeholder="Enter company email (optional)">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label label-icon"><i class="fas fa-hashtag"></i> Invoice Number</label>
                            <?php
                            $stmt = $pdo->query("SELECT COALESCE(MAX(invoice_number) + 1, 1) as next_number FROM invoices");
                            $nextNumber = $stmt->fetchColumn();
                            ?>
                            <div class="form-control bg-light"><?= $nextNumber ?></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label label-icon"><i class="fas fa-calendar-alt"></i> Invoice Date</label>
                            <input type="date" class="form-control" name="invoice_date" required>
                        </div>
                        <div class="mb-3 due-date-field" style="display: none;">
                            <label class="form-label label-icon"><i class="fas fa-clock"></i> Due Date</label>
                            <input type="date" class="form-control" name="due_date" id="due_date">
                        </div>
                        <div class="mb-3">
                            <label class="form-label label-icon"><i class="fas fa-circle-check"></i> Status</label>
                            <select class="form-select" name="status" id="status" onchange="toggleDueDate()" required>
                                <option value="unpaid">Unpaid</option>
                                <option value="pending">Pending</option>
                                <!-- <option value="paid">Paid</option> -->
                            </select>
                        </div>
           
                        <div class="mb-3">
                            <label class="form-label label-icon"><i class="fas fa-coins"></i> Currency</label>
                            <select class="form-select" name="currency" required>
                                <option value="USD">USD</option>
                                <option value="PKR">PKR</option>
                                <option value="INR">INR</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                                <option value="AUD">AUD</option>
                                <option value="CAD">CAD</option>
                                <option value="SGD">SGD</option>
                                <option value="AED">AED</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label label-icon"><i class="fas fa-money-bill"></i> Payment Method</label>
                            <select class="form-select" name="payment_method" id="payment_method" required onchange="togglePaymentFields()">
                                <option value="cash">Cash</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                        <div class="mb-3 payment-fields bank-fields" style="display: none;">
                            <label class="form-label label-icon"><i class="fas fa-university"></i> Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" id="bank_name">
                            <label class="form-label label-icon mt-2"><i class="fas fa-credit-card"></i> Account Number</label>
                            <input type="text" class="form-control" name="account_number" id="account_number">
                        </div>
                        <div class="mb-3 payment-fields paypal-fields" style="display: none;">
                            <label class="form-label label-icon"><i class="fab fa-paypal"></i> PayPal Email</label>
                            <input type="email" class="form-control" name="paypal_email" id="paypal_email">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label label-icon"><i class="fas fa-percent"></i> Tax Rate (%)</label>
                        <input type="number" class="form-control" name="tax_rate" step="0.1" min="0" max="100" placeholder="Optional">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label label-icon"><i class="fas fa-truck"></i> Shipping Cost</label>
                        <input type="number" class="form-control" name="shipping_cost" step="0.01" min="0" placeholder="Optional">
                    </div>
                </div>
                <h4 class="mb-3"><i class="fas fa-shopping-cart me-2"></i>Items</h4>
                <div id="items-container">
                    <div class="item-row row align-items-end">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="item_desc[]" placeholder="Item Description" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="item_qty[]" placeholder="Qty" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control" name="item_price[]" placeholder="Price" step="0.01" max="1000000" required oninput="validatePrice(this)">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger w-100" onclick="removeItem(this)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-add mt-3" onclick="addItem()">
                    <i class="fas fa-plus-circle me-2"></i>Add Item
                </button>
            </div>

            <div class="form-section">
                <div class="mb-3">
                    <label class="form-label label-icon"><i class="fas fa-sticky-note"></i> Additional Notes</label>
                    <textarea class="form-control" name="additional_notes" rows="4" placeholder="Enter any additional notes or terms"></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Create Invoice
                </button>
            </div>
        </form>
    </div>
    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="css_and_js/makeinovice.js"></script>
</body>
</html>
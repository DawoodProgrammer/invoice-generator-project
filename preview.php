<?php
require('config/db.php');

if(!isset($_GET['id'])) {
    die('Invalid invoice ID');
}

$invoiceId = $_GET['id'];

// Get invoice details
$stmt = $pdo->prepare("SELECT * FROM invoices WHERE id = ?");
$stmt->execute([$invoiceId]);
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

// Get invoice items
$stmt = $pdo->prepare("SELECT * FROM items WHERE invoice_id = ?");
$stmt->execute([$invoiceId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(!$invoice) {
    die('Invoice not found');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $invoice['invoice_number'] ?> - Preview</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous">
    <link rel="stylesheet" href="css_and_js/preview.css">

</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <?php if($invoice['logo_path'] && file_exists($invoice['logo_path'])): ?>
                <img src="<?= $invoice['logo_path'] ?>" alt="Company Logo" class="company-logo">
            <?php endif; ?>
            <h1><i class="fas fa-file-invoice-dollar"></i> Invoice #<?= $invoice['invoice_number'] ?></h1>
            <div class="row">
                <div class="col-md-6">
                    <h4><i class="fas fa-user me-2"></i>Bill To:</h4>
                    <p class="mb-0"><?= $invoice['customer_name'] ?></p>
                    <p class="text-white-75"><?= nl2br($invoice['customer_address']) ?></p>
                    <?php if(!empty($invoice['company_name'])): ?>
                        <p class="text-white-75"><i class="fas fa-building me-2"></i><?= $invoice['company_name'] ?></p>
                    <?php endif; ?>
                    <?php if(!empty($invoice['company_address'])): ?>
                        <p class="text-white-75"><i class="fas fa-map-marker-alt me-2"></i><?= nl2br($invoice['company_address']) ?></p>
                    <?php endif; ?>
                    <?php if(!empty($invoice['company_email'])): ?>
                        <p class="text-white-75"><i class="fas fa-envelope me-2"></i><?= $invoice['company_email'] ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <p><i class="fas fa-calendar-alt me-2"></i>Invoice Date: <?= date('F j, Y', strtotime($invoice['invoice_date'])) ?></p>
                    <?php if($invoice['status'] !== 'paid' && $invoice['due_date']): ?>
                        <p><i class="fas fa-clock me-2"></i>Due Date: <?= date('F j, Y', strtotime($invoice['due_date'])) ?></p>
                    <?php endif; ?>
                    <p>
                        <i class="fas fa-circle-check me-2"></i>Status: 
                        <span class="status-badge status-<?= $invoice['status'] ?>">
                            <?= ucfirst($invoice['status']) ?>
                        </span>
                        <form method="post" action="update_status.php" class="d-inline ms-2">
                            <input type="hidden" name="id" value="<?= $invoiceId ?>">
                            <select name="status" class="form-select d-inline w-auto" onchange="this.form.submit()">
                                <!-- <option value="paid" <?= $invoice['status'] === 'paid' ? 'selected' : '' ?>>Paid</option> -->
                                <option value="pending" <?= $invoice['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="unpaid" <?= $invoice['status'] === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                            </select>
                        </form>
                    </p>
                </div>
            </div>
        </div>

        <div class="invoice-details">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><i class="fas fa-list me-2"></i>Description</th>
                        <th><i class="fas fa-sort-numeric-up me-2"></i>Quantity</th>
                        <th><i class="fas fa-money-bill me-2"></i>Unit Price</th>
                        <th><i class="fas fa-money-bill me-2"></i>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items as $item): ?>
                    <tr>
                        <td data-label="Description"><?= $item['description'] ?></td>
                        <td data-label="Quantity"><?= $item['quantity'] ?></td>
                        <td data-label="Unit Price"><?= $invoice['currency']  ?> <b> : </b> <?=  number_format($item['price'], 2) ?></td>
                        <td data-label="Total"><?= $invoice['currency'] ?><b> : </b><?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="payment-section mb-4">
                <h4 class="mb-3"><i class="fas fa-money-bill me-2"></i>Payment Information</h4>
                <div class="card">
                    <div class="card-body">
                        <p class="mb-2"><strong>Method:</strong> <?= ucfirst($invoice['payment_method']) ?></p>
                        <?php if($invoice['payment_method'] === 'bank'): ?>
                            <p class="mb-2"><strong>Bank Name:</strong> <?= $invoice['bank_name'] ?></p>
                            <p class="mb-2"><strong>Account Number:</strong> <?= $invoice['account_number'] ?></p>
                        <?php elseif($invoice['payment_method'] === 'paypal'): ?>
                            <p class="mb-2"><strong>PayPal Email:</strong> <?= $invoice['paypal_email'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="total-section">
                <div class="row mb-2">
                    <div class="col-md-8 text-end">
                        <p class="mb-1">Subtotal:</p>
                        <?php if($invoice['tax_rate']): ?>
                            <p class="mb-1">Tax (<?= number_format($invoice['tax_rate'], 1) ?>%):</p>
                        <?php endif; ?>
                        <?php if($invoice['shipping_cost']): ?>
                            <p class="mb-1">Shipping:</p>
                        <?php endif; ?>
                        <h4 class="mb-0">Total Due:</h4>
                    </div>
                    <div class="col-md-4 text-end">
                        <p class="mb-1"><?= $invoice['currency'] ?><b> : </b><?= number_format($invoice['total_amount'] - ($invoice['tax_amount'] ?? 0) - ($invoice['shipping_cost'] ?? 0), 2) ?></p>
                        <?php if($invoice['tax_rate']): ?>
                            <p class="mb-1"><?= $invoice['currency'] ?><b> : </b><?= number_format($invoice['tax_amount'], 2) ?></p>
                        <?php endif; ?>
                        <?php if($invoice['shipping_cost']): ?>
                            <p class="mb-1"><?= $invoice['currency'] ?><b> : </b><?= number_format($invoice['shipping_cost'], 2) ?></p>
                        <?php endif; ?>
                        <h4 class="mb-0 text-success"><?= $invoice['currency'] ?><b> : </b><?= number_format($invoice['total_amount'], 2) ?></h4>
                    </div>
                </div>
            </div>

            <?php if(!empty($invoice['additional_notes'])): ?>
            <div class="notes-section mt-4">
                <h4 class="mb-3"><i class="fas fa-sticky-note me-2"></i>Additional Notes</h4>
                <div class="card">
                    <div class="card-body">
                        <?= nl2br(htmlspecialchars($invoice['additional_notes'])) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-download me-2">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                <a href="pdf.php?id=<?= $invoiceId ?>" class="btn btn-download">
                    <i class="fas fa-download me-2"></i>Download as PDF
                </a>
            </div>
        </div>
    </div>

    <div class="watermark">INVOICE</div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
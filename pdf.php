<?php
require('config/db.php');
require('vendor/autoload.php');

if (!isset($_GET['id'])) {
    die('Invalid invoice ID');
}

$invoiceId = $_GET['id'];

// Get invoice data
try {
    $stmt = $pdo->prepare("SELECT * FROM invoices WHERE id = ?");
    $stmt->execute([$invoiceId]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get items data
    $stmt = $pdo->prepare("SELECT * FROM items WHERE invoice_id = ?");
    $stmt->execute([$invoiceId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$invoice) {
        die('Invoice not found');
    }
} catch (PDOException $e) {
    die('Database error: ' . $e->getMessage());
}

class PDF extends FPDF {
    private $invoice;

    function __construct($invoice) {
        parent::__construct('P', 'mm', 'A4');
        $this->invoice = $invoice;
    }

    private $headerHeight = 40;

    function Header() {
        // Save current position
        $this->SetFillColor(30, 58, 138);
        $this->SetTextColor(255);
        $this->SetDrawColor(59, 130, 246);

        $this->Rect(0, 0, 210, $this->headerHeight, 'F');

        $this->SetFont('Arial', 'B', 20); // Increased from 20
        $this->SetXY(10, 10);
        $this->Cell(0, 10, 'INVOICE', 0, 1, 'C');

        if (isset($this->invoice['logo_path']) && file_exists($this->invoice['logo_path'])) {
            try {
                $x = 90;
                $y = 20;
                $r = 15;

                $this->_out('q');
                $this->Circle($x + $r, $y + $r, $r, 'CNZ');
                $this->Image($this->invoice['logo_path'], $x, $y, 30, 30);
                $this->_out('Q');
            } catch (Exception $e) {
                error_log('Image loading error: ' . $e->getMessage());
            }
        }
        
        // Reset position to below header
        $this->SetY($this->headerHeight + 10);
    }

    // Circle drawing helper functions
    protected function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $x1 = floatval($x1);
        $y1 = floatval($y1);
        $x2 = floatval($x2);
        $y2 = floatval($y2);
        $x3 = floatval($x3);
        $y3 = floatval($y3);
        
        $x1 *= $this->k;
        $x2 *= $this->k;
        $x3 *= $this->k;
        $y1 = ($h - $y1) * $this->k;
        $y2 = ($h - $y2) * $this->k;
        $y3 = ($h - $y3) * $this->k;
        
        $this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F c', 
            $x1, $y1, $x2, $y2, $x3, $y3));
    }

    function Circle($x, $y, $r, $style='D') {
        $this->_out(sprintf('q %.2F %.2F m', ($x+$r)*$this->k, ($this->h-$y)*$this->k));
        $this->_Arc($x+$r, $y-$r*0.552, $x+$r*0.552, $y-$r, $x, $y-$r);
        $this->_Arc($x-$r*0.552, $y-$r, $x-$r, $y-$r*0.552, $x-$r, $y);
        $this->_Arc($x-$r, $y+$r*0.552, $x-$r*0.552, $y+$r, $x, $y+$r);
        $this->_Arc($x+$r*0.552, $y+$r, $x+$r, $y+$r*0.552, $x+$r, $y);
        $this->_out('h');
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $this->_out($op);
    }

    function Footer() {
        $this->SetY(-25);
        $this->SetFont('Arial', 'I', 8); // Changed from 15 to 8
        $this->SetTextColor(100, 100, 100);

        $this->SetDrawColor(200, 200, 200);
        $this->Line(10, $this->GetY(), 200, $this->GetY());

        $this->Ln(5);
        $this->Cell(0, 5, 'Page ' . $this->PageNo() . ' of {nb}', 0, 0, 'C');
        $this->Cell(0, 5, 'Generated on ' . date('F j, Y'), 0, 1, 'R');
    }

    function InvoiceTableHeader() {
        $this->SetFont('Arial', 'B', 20); // Increased from 11
        $this->SetFillColor(30, 58, 138);
        $this->SetTextColor(255);

        $this->SetFont('Arial', 'B', 10); // Reduced font size
        $this->Cell(70, 8, 'Description', 1, 0, 'C', true); // Increased height from 10
        $this->Cell(30, 8, 'Quantity', 1, 0, 'C', true);
        $this->Cell(45, 8, 'Unit Price', 1, 0, 'C', true);
        $this->Cell(45, 8, 'Total', 1, 1, 'C', true);
    }

    function InvoiceTableRow($description, $quantity, $price, $total) {
        // Check if we need a new page
        if ($this->GetY() > 250) {
            $this->AddPage();
            $this->InvoiceTableHeader();
        }
        
        $this->SetFont('Arial', '', 10); // Increased from 10
        $this->SetTextColor(0);
        $this->SetFillColor(245, 247, 250);

        $this->Cell(70, 8, $description, 1, 0, 'L', true); // Increased height from 8
        $this->Cell(30, 8, $quantity, 1, 0, 'R', true);
        $this->Cell(45, 8, $this->invoice['currency'] . ' ' . number_format($price, 2), 1, 0, 'R', true);
        $this->Cell(45, 8, $this->invoice['currency'] . ' ' . number_format($total, 2), 1, 1, 'R', true);
    }
}

try {
    $pdf = new PDF($invoice);
    $pdf->AliasNbPages();
    $pdf->AddPage();

    // Adjusted Y position to accommodate logo
    $pdf->SetY(50); // Move content down to avoid overlapping with logo
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->SetTextColor(30, 58, 138);
    $pdf->Cell(0, 10, 'Invoice #' . $invoice['invoice_number'], 0, 1, 'R');
    $pdf->SetFont('Arial', '', 11);


    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 6, 'Date: ' . date('F j, Y', strtotime($invoice['invoice_date'])), 0, 1, 'R');
    if ($invoice['status'] !== 'paid' && $invoice['due_date']) {
        $pdf->Cell(0, 6, 'Due Date: ' . date('F j, Y', strtotime($invoice['due_date'])), 0, 1, 'R');
    }
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 20);
    $pdf->SetTextColor(30, 58, 138);
    $pdf->Cell(0, 8, 'Bill To:', 0, 1);
    $pdf->SetFont('Arial', '', 11);

    $pdf->SetTextColor(0);
    $customerInfo = $invoice['customer_name'] . "\n" . $invoice['customer_address'];
    if (!empty($invoice['company_email'])) {
        $customerInfo .= "\n\nCompany Email: " . $invoice['company_email'];
    }
    $pdf->MultiCell(80, 6, $customerInfo, 0, 'L');
    $pdf->Ln(5);
    $pdf->Ln(10);

    $pdf->InvoiceTableHeader();

    foreach ($items as $item) {
        $total = $item['quantity'] * $item['price'];
        $pdf->InvoiceTableRow($item['description'], $item['quantity'], $item['price'], $total);
    }

    $pdf->Ln(10);
    
    // Check if we need a new page before payment information
    if ($pdf->GetY() > 200) {
        $pdf->AddPage();
        $pdf->SetY(40); // Set a proper starting position on the new page
    }
    
    // Payment Information Section
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->SetTextColor(30, 58, 138);
    $pdf->Cell(0, 8, 'Payment Information', 0, 1);
    $pdf->SetFont('Arial', '', 11);

    // Additional Notes Section

    $pdf->SetTextColor(0);
    $pdf->Cell(40, 6, 'Method:', 0, 0);
    $pdf->Cell(0, 6, ucfirst($invoice['payment_method']), 0, 1);
    
    if ($invoice['payment_method'] === 'bank') {
        $pdf->Cell(40, 6, 'Bank Name:', 0, 0);
        $pdf->Cell(0, 6, $invoice['bank_name'], 0, 1);
        $pdf->Cell(40, 6, 'Account Number:', 0, 0);
        $pdf->Cell(0, 6, $invoice['account_number'], 0, 1);
    } elseif ($invoice['payment_method'] === 'paypal') {
        $pdf->Cell(40, 6, 'PayPal Email:', 0, 0);
        $pdf->Cell(0, 6, $invoice['paypal_email'], 0, 1);
    }
    
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(30, 58, 138);

    // Calculate subtotal from items
    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item['quantity'] * $item['price'];
    }

    // Calculate tax amount
    $tax_amount = $invoice['tax_rate'] ? ($subtotal * $invoice['tax_rate'] / 100) : 0;

    // Calculate total
    $total_amount = $subtotal + $tax_amount + ($invoice['shipping_cost'] ?? 0);

    // Ensure enough space for the totals section
    if ($pdf->GetY() > 220) {
        $pdf->AddPage();
        $pdf->SetY(40);
    }
    $pdf->Cell(145, 8, 'Subtotal', 1, 0, 'R');
    $pdf->Cell(45, 8, $invoice['currency'] . ' ' . number_format($subtotal, 2), 1, 1, 'R');

    if ($invoice['tax_rate']) {
        $pdf->Cell(145, 8, 'Tax (' . number_format($invoice['tax_rate'], 1) . '%)', 1, 0, 'R');
        $pdf->Cell(45, 8, $invoice['currency'] . ' ' . number_format($tax_amount, 2), 1, 1, 'R');
    }

    if ($invoice['shipping_cost']) {
        $pdf->Cell(145, 8, 'Shipping', 1, 0, 'R');
        $pdf->Cell(45, 8, $invoice['currency'] . ' ' . number_format($invoice['shipping_cost'], 2), 1, 1, 'R');
    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(16, 185, 129);
    $pdf->Cell(145, 10, 'Grand Total', 1, 0, 'R', true);
    $pdf->Cell(45, 10, $invoice['currency'] . ' ' . number_format($total_amount, 2), 1, 1, 'R', true);

    // Add Additional Notes after the grand total
    if (!empty($invoice['additional_notes'])) {
        // Check if we need a new page for additional notes
        if ($pdf->GetY() > 220) {
            $pdf->AddPage();
        }
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetTextColor(30, 58, 138);
        $pdf->Cell(0, 8, 'Additional Notes', 0, 1);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor(0);
        $pdf->MultiCell(0, 6, $invoice['additional_notes'], 0, 'L');
    }
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="invoice_' . $invoice['invoice_number'] . '.pdf"');
    $pdf->Output('D');
} catch (Exception $e) {
    die('PDF generation error: ' . $e->getMessage());
}
?>
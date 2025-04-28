-- Create database
CREATE DATABASE IF NOT EXISTS invoice_db1;
USE invoice_db1;

-- Create sequence table for invoice numbers
CREATE TABLE IF NOT EXISTS invoice_sequence (
    id INT PRIMARY KEY AUTO_INCREMENT,
    next_number INT NOT NULL DEFAULT 1,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Initialize sequence
INSERT IGNORE INTO invoice_sequence (next_number) VALUES (1);

-- Create invoices table
CREATE TABLE IF NOT EXISTS invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    invoice_number INT NOT NULL UNIQUE,
    customer_name VARCHAR(255) NOT NULL,
    customer_address TEXT NOT NULL,
    invoice_date DATE NOT NULL,
    due_date DATE,
    total_amount DECIMAL(10,2) NOT NULL,
    currency CHAR(3) NOT NULL DEFAULT 'USD',
    tax_rate DECIMAL(5,2),
    tax_amount DECIMAL(10,2),
    shipping_cost DECIMAL(10,2),
    status ENUM('paid', 'pending', 'unpaid') NOT NULL DEFAULT 'unpaid',
    logo_path VARCHAR(255),
    payment_method ENUM('cash', 'bank', 'paypal') NOT NULL DEFAULT 'cash',
    bank_name VARCHAR(255),
    account_number VARCHAR(50),
    paypal_email VARCHAR(255),
    additional_notes TEXT,
    company_email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_invoice_number (invoice_number)
);

-- Create items table
CREATE TABLE IF NOT EXISTS items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id INT NOT NULL,
    description VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) GENERATED ALWAYS AS (quantity * price) STORED,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    INDEX idx_invoice_id (invoice_id)
);

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    INDEX idx_email (email)
);


-- Database schema for Business Management System
CREATE DATABASE IF NOT EXISTS business_db;
USE business_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'user') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Customers table
CREATE TABLE IF NOT EXISTS customers (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  contact VARCHAR(100) NOT NULL,
  email VARCHAR(255),
  address TEXT,
  balance DECIMAL(10,2) DEFAULT 0.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Suppliers table
CREATE TABLE IF NOT EXISTS suppliers (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  contact VARCHAR(100) NOT NULL,
  email VARCHAR(255),
  address TEXT,
  credit_limit DECIMAL(10,2),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Warehouses table
CREATE TABLE IF NOT EXISTS warehouses (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  location VARCHAR(255) NOT NULL,
  capacity INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  cost DECIMAL(10,2),
  stock INT DEFAULT 0,
  warehouse_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (warehouse_id) REFERENCES warehouses(id)
);

-- Warehouse transfers
CREATE TABLE IF NOT EXISTS warehouse_transfers (
  id INT PRIMARY KEY AUTO_INCREMENT,
  from_warehouse INT NOT NULL,
  to_warehouse INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  transfer_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  notes TEXT,
  FOREIGN KEY (from_warehouse) REFERENCES warehouses(id),
  FOREIGN KEY (to_warehouse) REFERENCES warehouses(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Invoices table
CREATE TABLE IF NOT EXISTS invoices (
  id INT PRIMARY KEY AUTO_INCREMENT,
  type ENUM('SALES', 'PURCHASE', 'RETURN') NOT NULL,
  customer_id INT,
  supplier_id INT,
  total DECIMAL(10,2) NOT NULL,
  status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
  invoice_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  due_date DATETIME,
  notes TEXT,
  FOREIGN KEY (customer_id) REFERENCES customers(id),
  FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);

-- Treasury accounts
CREATE TABLE IF NOT EXISTS treasuries (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  balance DECIMAL(10,2) DEFAULT 0.00,
  currency VARCHAR(3) DEFAULT 'USD',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Treasury transactions
CREATE TABLE IF NOT EXISTS treasury_transactions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  type ENUM('deposit', 'withdrawal', 'transfer') NOT NULL,
  from_treasury INT,
  to_treasury INT,
  amount DECIMAL(10,2) NOT NULL,
  description TEXT,
  transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (from_treasury) REFERENCES treasuries(id),
  FOREIGN KEY (to_treasury) REFERENCES treasuries(id)
);

-- Create initial admin user
INSERT INTO users (email, password, role) 
VALUES ('admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
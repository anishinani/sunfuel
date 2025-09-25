-- Migration 015: Create Payments Table
-- Description: Creates the payments table for payment tracking

USE sunfuel;

CREATE TABLE IF NOT EXISTS payments (
    paymentId INT AUTO_INCREMENT PRIMARY KEY,
    loanId INT,
    amount DECIMAL(10,2) NOT NULL,
    paymentDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    paymentMethod VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (loanId) REFERENCES loan(loanId)
);

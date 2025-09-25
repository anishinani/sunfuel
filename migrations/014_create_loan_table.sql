-- Migration 014: Create Loan Table
-- Description: Creates the loan table for loan management

USE sunfuel;

CREATE TABLE IF NOT EXISTS loan (
    loanId INT AUTO_INCREMENT PRIMARY KEY,
    boadUserId VARCHAR(20) NOT NULL, -- Phone number reference
    loanAmount DECIMAL(10,2) NOT NULL,
    LoanInterest DECIMAL(10,2) DEFAULT 0,
    loan_penalty DECIMAL(10,2) DEFAULT 0,
    status TINYINT DEFAULT 0, -- 0=paid, 1=unpaid
    stageId INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (stageId) REFERENCES stage(stageId)
);

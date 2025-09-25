-- Migration 018: Create User Totals Table
-- Description: Creates the user_totals_per_day table for daily reporting

USE sunfuel;

CREATE TABLE IF NOT EXISTS user_totals_per_day (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_users INT DEFAULT 0,
    total_loans DECIMAL(10,2) DEFAULT 0,
    total_deposits DECIMAL(10,2) DEFAULT 0,
    date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

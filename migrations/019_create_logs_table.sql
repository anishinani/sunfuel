-- Migration 019: Create Logs Table
-- Description: Creates the logs table for activity logging

USE sunfuel;

CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    ipAddress VARCHAR(45),
    activity VARCHAR(100) NOT NULL,
    account_id VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

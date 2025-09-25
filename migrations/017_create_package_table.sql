-- Migration 017: Create Package Table
-- Description: Creates the package table for loan packages

USE sunfuel;

CREATE TABLE IF NOT EXISTS package (
    packageId INT AUTO_INCREMENT PRIMARY KEY,
    packageName VARCHAR(255) NOT NULL,
    packageAmount DECIMAL(10,2) NOT NULL,
    packageDescription TEXT,
    packageStatus TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Migration 008: Create Territories Table
-- Description: Creates the territories table for geographic management

USE sunfuel;

CREATE TABLE IF NOT EXISTS territories (
    territoryId INT AUTO_INCREMENT PRIMARY KEY,
    territoryName VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

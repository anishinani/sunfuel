-- Migration 009: Create Territory Districts Table
-- Description: Creates the territory_districts table for district management

USE sunfuel;

CREATE TABLE IF NOT EXISTS territory_districts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    territoryId INT NOT NULL,
    districtName VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (territoryId) REFERENCES territories(territoryId)
);

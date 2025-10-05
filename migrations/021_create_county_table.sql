-- Migration 021: Create County Table
-- Description: Creates the county table for geographic management

USE sunfuel;

CREATE TABLE IF NOT EXISTS county (
    countyCode VARCHAR(10) PRIMARY KEY,
    countyName VARCHAR(255) NOT NULL,
    districtId INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (districtId) REFERENCES territory_districts(id)
);

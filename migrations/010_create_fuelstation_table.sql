-- Migration 010: Create Fuel Station Table
-- Description: Creates the fuelstation table for fuel station management

USE sunfuel;

CREATE TABLE IF NOT EXISTS fuelstation (
    fuelStationId INT AUTO_INCREMENT PRIMARY KEY,
    fuelStationName VARCHAR(255) NOT NULL,
    fuelStationLocation VARCHAR(255),
    fuelStationStatus TINYINT DEFAULT 1, -- 1=active, 0=inactive
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

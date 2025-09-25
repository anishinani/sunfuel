-- Migration 011: Create Fuel Station Views
-- Description: Creates views for active and inactive fuel stations

USE sunfuel;

-- Active fuel stations view
CREATE TABLE IF NOT EXISTS activefuelstation (
    fuelStationId INT AUTO_INCREMENT PRIMARY KEY,
    fuelStationName VARCHAR(255) NOT NULL,
    fuelStationLocation VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inactive fuel stations view
CREATE TABLE IF NOT EXISTS inactivefuelstation (
    fuelStationId INT AUTO_INCREMENT PRIMARY KEY,
    fuelStationName VARCHAR(255) NOT NULL,
    fuelStationLocation VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Migration 016: Create Deposits Table
-- Description: Creates the deposits table for deposit tracking

USE sunfuel;

CREATE TABLE IF NOT EXISTS deposits (
    depositId INT AUTO_INCREMENT PRIMARY KEY,
    fuelStationId INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    depositDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fuelStationId) REFERENCES fuelstation(fuelStationId)
);

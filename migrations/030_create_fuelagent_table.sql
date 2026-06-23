-- Migration 030: Create Fuel Agent Table
-- Description: Creates the fuelagent table for station agent management

USE sunfuel;

CREATE TABLE IF NOT EXISTS fuelagent (
    fuelAgentId INT AUTO_INCREMENT PRIMARY KEY,
    fuelAgentName VARCHAR(255) NOT NULL,
    fuelAgentPhoneNumber VARCHAR(20) NOT NULL,
    fuelAgentNIN VARCHAR(20),
    stationId INT NOT NULL,
    frontIDPhoto VARCHAR(255),
    backIDPhoto VARCHAR(255),
    anotherPhoneNumber VARCHAR(20),
    status TINYINT DEFAULT 0,
    pin VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (stationId) REFERENCES fuelstation(fuelStationId)
);

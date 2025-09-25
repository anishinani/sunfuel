-- Migration 012: Create Stage Table
-- Description: Creates the stage table for boda stage management

USE sunfuel;

CREATE TABLE IF NOT EXISTS stage (
    stageId INT AUTO_INCREMENT PRIMARY KEY,
    stageName VARCHAR(255) NOT NULL,
    stageLocation VARCHAR(255),
    stageStatus TINYINT DEFAULT 1, -- 0=inactive, 1=active, 2=suspended
    chairmanId INT,
    fuelStationId INT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fuelStationId) REFERENCES fuelstation(fuelStationId)
);

-- Migration 013: Create Boda User Table
-- Description: Creates the bodauser table for boda rider management

USE sunfuel;

CREATE TABLE IF NOT EXISTS bodauser (
    bodaUserId INT AUTO_INCREMENT PRIMARY KEY,
    bodaUserName VARCHAR(255) NOT NULL,
    bodaUserNIN VARCHAR(20) UNIQUE,
    bodaUserBodaNumber VARCHAR(50),
    bodaUserPhoneNumber VARCHAR(20),
    bodaUserFrontPhoto VARCHAR(255),
    bodaUserBackPhoto VARCHAR(255),
    bodaUserRole VARCHAR(50),
    alternativePhotoNumber VARCHAR(20),
    bodaUserStatus TINYINT DEFAULT 0, -- 0=not activated, 1=activated, 2=pending payment, 3=suspended
    fuelStationId INT,
    stageId INT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fuelStationId) REFERENCES fuelstation(fuelStationId),
    FOREIGN KEY (stageId) REFERENCES stage(stageId)
);

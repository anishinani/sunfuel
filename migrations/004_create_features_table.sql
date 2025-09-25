-- Migration 004: Create Features Table
-- Description: Creates the features table for system features

USE sunfuel;

CREATE TABLE IF NOT EXISTS features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    featureName VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default features
INSERT INTO features (featureName, description) VALUES 
('users', 'User management'),
('loans', 'Loan management'),
('deposits', 'Deposit management'),
('fuelstations', 'Fuel station management'),
('stages', 'Stage management'),
('territories', 'Territory management'),
('reports', 'Reporting system')
ON DUPLICATE KEY UPDATE featureName=VALUES(featureName);

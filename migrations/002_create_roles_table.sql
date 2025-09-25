-- Migration 002: Create Roles Table
-- Description: Creates the roles table for user role management

USE sunfuel;

CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default roles
INSERT INTO roles (name, description) VALUES 
('Super Admin', 'Full system access'),
('Admin', 'Administrative access'),
('Manager', 'Management level access'),
('Agent', 'Field agent access')
ON DUPLICATE KEY UPDATE name=VALUES(name);

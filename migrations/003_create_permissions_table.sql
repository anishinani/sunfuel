-- Migration 003: Create Permissions Table
-- Description: Creates the permissions table for system permissions

USE sunfuel;

CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    permissionName VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default permissions
INSERT INTO permissions (permissionName, description) VALUES 
('create', 'Create new records'),
('read', 'View records'),
('update', 'Modify existing records'),
('delete', 'Delete records'),
('export', 'Export data')
ON DUPLICATE KEY UPDATE permissionName=VALUES(permissionName);

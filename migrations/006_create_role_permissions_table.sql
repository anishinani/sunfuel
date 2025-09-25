-- Migration 006: Create Role Permissions Table
-- Description: Creates the role_permissions table for role-based access control

USE sunfuel;

CREATE TABLE IF NOT EXISTS role_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    feature_id INT NOT NULL,
    permission VARCHAR(100),
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (feature_id) REFERENCES features(id)
);

-- Grant permissions to Super Admin role
INSERT INTO role_permissions (role_id, feature_id, permission, status) 
SELECT 1, f.id, p.permissionName, 1 
FROM features f, permissions p
ON DUPLICATE KEY UPDATE status=1;

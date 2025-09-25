-- Migration 007: Create Role Modules Table
-- Description: Creates the role_modules table for module-based permissions

USE sunfuel;

CREATE TABLE IF NOT EXISTS role_modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    module_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

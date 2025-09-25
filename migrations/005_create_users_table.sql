-- Migration 005: Create Users Table
-- Description: Creates the users table for system administrators

USE sunfuel;

CREATE TABLE IF NOT EXISTS users (
    adminId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phoneNumber VARCHAR(20),
    gender ENUM('Male', 'Female') DEFAULT 'Male',
    roleId INT NOT NULL,
    password VARCHAR(255),
    setPassword VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (roleId) REFERENCES roles(id)
);

-- Create default admin user (password: admin123)
INSERT INTO users (name, email, phoneNumber, gender, roleId, password) VALUES 
('System Administrator', 'admin@sunfuel.ug', '+256700000000', 'Male', 1, '$2y$12$ET3t..zzkvUgu2gu9eKrkOhl58cpGeisO/XK00QN2e6rRCB8Z1dJK')
ON DUPLICATE KEY UPDATE email=VALUES(email);

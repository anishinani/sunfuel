-- Migration 020: Create Modules Table
-- Description: Creates the modules table for system modules

USE sunfuel;

CREATE TABLE IF NOT EXISTS modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(100) DEFAULT 'fas fa-circle',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default modules
INSERT INTO modules (name, description) VALUES 
('Dashboard', 'Main dashboard module'),
('Users', 'User management module'),
('Roles', 'Role management module'),
('Territories', 'Territory management module'),
('Fuel Stations', 'Fuel station management module'),
('Stages', 'Stage management module'),
('Boda Users', 'Boda user management module'),
('Loans', 'Loan management module'),
('Payments', 'Payment management module'),
('Deposits', 'Deposit management module'),
('Packages', 'Package management module'),
('Reports', 'Reporting module')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Migration 027: Create Fuel Loan System Tables
-- Description: Creates tables for the fuel loan workflow system

USE sunfuel;

-- Table for fuel activation codes
CREATE TABLE IF NOT EXISTS fuel_activation_codes (
    activationId INT AUTO_INCREMENT PRIMARY KEY,
    bodaUserId INT NOT NULL,
    activationCode VARCHAR(6) NOT NULL UNIQUE,
    fuelAmount DECIMAL(10,2) NOT NULL,
    packageId INT NOT NULL,
    fuelStationId INT NOT NULL,
    stageId INT NOT NULL,
    status ENUM('pending', 'used', 'expired') DEFAULT 'pending',
    expiresAt TIMESTAMP NOT NULL,
    usedAt TIMESTAMP NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bodaUserId) REFERENCES bodauser(bodaUserId),
    FOREIGN KEY (packageId) REFERENCES package(packageId),
    FOREIGN KEY (fuelStationId) REFERENCES fuelstation(fuelStationId),
    FOREIGN KEY (stageId) REFERENCES stage(stageId)
);

-- Table for fuel loans (enhanced from existing loan table)
CREATE TABLE IF NOT EXISTS fuel_loans (
    fuelLoanId INT AUTO_INCREMENT PRIMARY KEY,
    activationId INT NOT NULL,
    bodaUserId INT NOT NULL,
    loanAmount DECIMAL(10,2) NOT NULL,
    interestRate DECIMAL(5,2) DEFAULT 0.00,
    interestAmount DECIMAL(10,2) DEFAULT 0.00,
    totalAmount DECIMAL(10,2) NOT NULL,
    fuelStationId INT NOT NULL,
    stageId INT NOT NULL,
    status ENUM('active', 'paid', 'overdue') DEFAULT 'active',
    loanDate DATE NOT NULL,
    dueDate DATE NOT NULL,
    paidAt TIMESTAMP NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (activationId) REFERENCES fuel_activation_codes(activationId),
    FOREIGN KEY (bodaUserId) REFERENCES bodauser(bodaUserId),
    FOREIGN KEY (fuelStationId) REFERENCES fuelstation(fuelStationId),
    FOREIGN KEY (stageId) REFERENCES stage(stageId)
);

-- Table for USSD sessions
CREATE TABLE IF NOT EXISTS ussd_sessions (
    sessionId INT AUTO_INCREMENT PRIMARY KEY,
    phoneNumber VARCHAR(20) NOT NULL,
    sessionCode VARCHAR(50) NOT NULL,
    currentMenu VARCHAR(100) DEFAULT 'main',
    userData JSON NULL,
    status ENUM('active', 'completed', 'timeout') DEFAULT 'active',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expiresAt TIMESTAMP NOT NULL,
    INDEX idx_phone_session (phoneNumber, sessionCode),
    INDEX idx_expires (expiresAt)
);

-- Table for SMS logs
CREATE TABLE IF NOT EXISTS sms_logs (
    smsId INT AUTO_INCREMENT PRIMARY KEY,
    phoneNumber VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    messageType ENUM('activation_code', 'fuel_received', 'payment_reminder', 'payment_confirmed', 'general') NOT NULL,
    status ENUM('sent', 'failed', 'delivered') DEFAULT 'sent',
    referenceId INT NULL, -- Can reference activationId, fuelLoanId, etc.
    referenceType VARCHAR(50) NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_phone (phoneNumber),
    INDEX idx_reference (referenceId, referenceType)
);

-- Table for fuel station float tracking
CREATE TABLE IF NOT EXISTS fuel_station_float (
    floatId INT AUTO_INCREMENT PRIMARY KEY,
    fuelStationId INT NOT NULL,
    currentFloat DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    minFloat DECIMAL(15,2) NOT NULL DEFAULT 100000.00,
    maxFloat DECIMAL(15,2) NOT NULL DEFAULT 1000000.00,
    lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fuelStationId) REFERENCES fuelstation(fuelStationId),
    UNIQUE KEY unique_station (fuelStationId)
);

-- Add new columns to existing bodauser table
ALTER TABLE bodauser 
ADD COLUMN IF NOT EXISTS packageId INT NULL,
ADD COLUMN IF NOT EXISTS maxDailyLoan DECIMAL(10,2) DEFAULT 15000.00,
ADD COLUMN IF NOT EXISTS lastLoanDate DATE NULL,
ADD COLUMN IF NOT EXISTS canBorrowToday BOOLEAN DEFAULT TRUE,
ADD FOREIGN KEY (packageId) REFERENCES package(packageId);

-- Add new columns to existing package table
ALTER TABLE package 
ADD COLUMN IF NOT EXISTS maxLoanAmount DECIMAL(10,2) DEFAULT 15000.00,
ADD COLUMN IF NOT EXISTS interestRate DECIMAL(5,2) DEFAULT 5.00,
ADD COLUMN IF NOT EXISTS borrowStartTime TIME DEFAULT '06:00:00',
ADD COLUMN IF NOT EXISTS borrowEndTime TIME DEFAULT '12:00:00',
ADD COLUMN IF NOT EXISTS paymentStartTime TIME DEFAULT '17:00:00',
ADD COLUMN IF NOT EXISTS paymentEndTime TIME DEFAULT '23:59:59';

-- Add new columns to existing fuelstation table
ALTER TABLE fuelstation 
ADD COLUMN IF NOT EXISTS currentFloat DECIMAL(15,2) DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS minFloat DECIMAL(15,2) DEFAULT 100000.00,
ADD COLUMN IF NOT EXISTS maxFloat DECIMAL(15,2) DEFAULT 1000000.00;

-- Create indexes for performance
CREATE INDEX idx_activation_code ON fuel_activation_codes(activationCode);
CREATE INDEX idx_activation_status ON fuel_activation_codes(status);
CREATE INDEX idx_activation_expires ON fuel_activation_codes(expiresAt);
CREATE INDEX idx_fuel_loan_status ON fuel_loans(status);
CREATE INDEX idx_fuel_loan_date ON fuel_loans(loanDate);
CREATE INDEX idx_fuel_loan_due ON fuel_loans(dueDate);
CREATE INDEX idx_boda_borrow_today ON bodauser(canBorrowToday);
CREATE INDEX idx_boda_last_loan ON bodauser(lastLoanDate);

-- Insert default package if not exists
INSERT IGNORE INTO package (packageName, packageAmount, packageStatus, maxLoanAmount, interestRate) 
VALUES ('Basic Package', 15000.00, 1, 15000.00, 5.00);

-- Create trigger to update fuel station float when loan is created
DELIMITER //
CREATE TRIGGER IF NOT EXISTS update_fuel_float_on_loan
AFTER INSERT ON fuel_loans
FOR EACH ROW
BEGIN
    UPDATE fuelstation 
    SET currentFloat = currentFloat - NEW.loanAmount
    WHERE fuelStationId = NEW.fuelStationId;
    
    INSERT INTO fuel_station_float (fuelStationId, currentFloat)
    VALUES (NEW.fuelStationId, (SELECT currentFloat FROM fuelstation WHERE fuelStationId = NEW.fuelStationId))
    ON DUPLICATE KEY UPDATE 
    currentFloat = (SELECT currentFloat FROM fuelstation WHERE fuelStationId = NEW.fuelStationId);
END//
DELIMITER ;

-- Create trigger to update fuel station float when loan is paid
DELIMITER //
CREATE TRIGGER IF NOT EXISTS update_fuel_float_on_payment
AFTER UPDATE ON fuel_loans
FOR EACH ROW
BEGIN
    IF NEW.status = 'paid' AND OLD.status != 'paid' THEN
        UPDATE fuelstation 
        SET currentFloat = currentFloat + NEW.totalAmount
        WHERE fuelStationId = NEW.fuelStationId;
        
        UPDATE fuel_station_float 
        SET currentFloat = (SELECT currentFloat FROM fuelstation WHERE fuelStationId = NEW.fuelStationId)
        WHERE fuelStationId = NEW.fuelStationId;
    END IF;
END//
DELIMITER ;

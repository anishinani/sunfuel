-- Migration 031: Extend payments table for mobile money payment tracking
-- The payments UI and webhook handlers expect legacy columns (msisdn, status, etc.)

USE sunfuel;

ALTER TABLE payments
    CHANGE paymentId id INT AUTO_INCREMENT,
    ADD COLUMN status VARCHAR(50) DEFAULT 'pending' AFTER amount,
    ADD COLUMN narrative VARCHAR(255) NULL AFTER status,
    ADD COLUMN msisdn VARCHAR(20) NULL AFTER narrative,
    ADD COLUMN external_ref VARCHAR(255) NULL AFTER msisdn,
    ADD COLUMN network_ref VARCHAR(255) NULL AFTER external_ref,
    ADD COLUMN transactionStatus VARCHAR(10) NULL AFTER network_ref,
    ADD COLUMN date_time DATETIME NULL AFTER transactionStatus,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;

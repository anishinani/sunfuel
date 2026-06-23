-- Migration 032: Schema fixes for DataTables endpoints

USE sunfuel;

ALTER TABLE deposits
    ADD COLUMN IF NOT EXISTS depositedBy VARCHAR(255) NULL AFTER amount;

ALTER TABLE territories
    ADD COLUMN IF NOT EXISTS territoryManager INT NULL AFTER territoryName,
    ADD COLUMN IF NOT EXISTS status TINYINT DEFAULT 0 AFTER territoryManager;

ALTER TABLE territory_districts
    ADD COLUMN IF NOT EXISTS districtCode VARCHAR(50) NULL AFTER districtName,
    ADD COLUMN IF NOT EXISTS status TINYINT DEFAULT 0 AFTER districtCode;

ALTER TABLE bodauser
    ADD COLUMN IF NOT EXISTS bodaUserPin VARCHAR(20) NULL AFTER bodaUserPhoneNumber;

ALTER TABLE stage
    ADD COLUMN IF NOT EXISTS territoryId INT NULL AFTER fuelStationId,
    ADD COLUMN IF NOT EXISTS districtCode VARCHAR(50) NULL AFTER territoryId;

DROP TABLE IF EXISTS activefuelstation;
DROP TABLE IF EXISTS inactivefuelstation;

CREATE VIEW activefuelstation AS
SELECT * FROM fuelstation WHERE fuelStationStatus = 1;

CREATE VIEW inactivefuelstation AS
SELECT * FROM fuelstation WHERE fuelStationStatus = 0;

CREATE OR REPLACE VIEW activebodausers AS
SELECT bodauser.*, fuelstation.fuelStationName, stage.stageName
FROM bodauser
INNER JOIN fuelstation ON fuelstation.fuelStationId = bodauser.fuelStationId
INNER JOIN stage ON stage.stageId = bodauser.stageId
WHERE bodauser.bodaUserStatus = 1;

CREATE OR REPLACE VIEW inactivebodausers AS
SELECT bodauser.*, fuelstation.fuelStationName, stage.stageName
FROM bodauser
INNER JOIN fuelstation ON fuelstation.fuelStationId = bodauser.fuelStationId
INNER JOIN stage ON stage.stageId = bodauser.stageId
WHERE bodauser.bodaUserStatus = 0;

CREATE OR REPLACE VIEW defaultedbodausers AS
SELECT bodauser.*, fuelstation.fuelStationName, stage.stageName
FROM bodauser
INNER JOIN fuelstation ON fuelstation.fuelStationId = bodauser.fuelStationId
INNER JOIN stage ON stage.stageId = bodauser.stageId
WHERE bodauser.bodaUserStatus = 2;

UPDATE stage SET territoryId = 1 WHERE territoryId IS NULL;

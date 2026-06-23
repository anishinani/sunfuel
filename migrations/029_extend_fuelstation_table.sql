-- Migration 029: Extend fuelstation table
-- Description: Adds columns required by fuel station management views

USE sunfuel;

ALTER TABLE fuelstation
    ADD COLUMN IF NOT EXISTS fuelStationAddress VARCHAR(255) NULL AFTER fuelStationLocation,
    ADD COLUMN IF NOT EXISTS fuelStationContactPerson VARCHAR(255) NULL AFTER fuelStationAddress,
    ADD COLUMN IF NOT EXISTS fuelStationContactPhone VARCHAR(20) NULL AFTER fuelStationContactPerson,
    ADD COLUMN IF NOT EXISTS NIN VARCHAR(20) NULL AFTER fuelStationContactPhone,
    ADD COLUMN IF NOT EXISTS frontIDPhoto VARCHAR(255) NULL AFTER NIN,
    ADD COLUMN IF NOT EXISTS backIDPhoto VARCHAR(255) NULL AFTER frontIDPhoto,
    ADD COLUMN IF NOT EXISTS bankName VARCHAR(255) NULL AFTER backIDPhoto,
    ADD COLUMN IF NOT EXISTS bankBranch VARCHAR(255) NULL AFTER bankName,
    ADD COLUMN IF NOT EXISTS AccName VARCHAR(255) NULL AFTER bankBranch,
    ADD COLUMN IF NOT EXISTS AccNumber VARCHAR(50) NULL AFTER AccName,
    ADD COLUMN IF NOT EXISTS merchantCode VARCHAR(10) NULL AFTER AccNumber,
    ADD COLUMN IF NOT EXISTS districtCode VARCHAR(50) NULL AFTER merchantCode,
    ADD COLUMN IF NOT EXISTS countyCode VARCHAR(50) NULL AFTER districtCode,
    ADD COLUMN IF NOT EXISTS subCountyCode VARCHAR(50) NULL AFTER countyCode,
    ADD COLUMN IF NOT EXISTS parishCode VARCHAR(50) NULL AFTER subCountyCode,
    ADD COLUMN IF NOT EXISTS villageCode VARCHAR(50) NULL AFTER parishCode;

UPDATE fuelstation
SET fuelStationAddress = fuelStationLocation
WHERE fuelStationAddress IS NULL AND fuelStationLocation IS NOT NULL;

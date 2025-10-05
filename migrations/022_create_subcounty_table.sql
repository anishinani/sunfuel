-- Migration 022: Create Subcounty Table
-- Description: Creates the subcounty table for geographic management

USE sunfuel;

CREATE TABLE IF NOT EXISTS subcounty (
    subCountyCode VARCHAR(10) PRIMARY KEY,
    subCountyName VARCHAR(255) NOT NULL,
    countyCode VARCHAR(10) NOT NULL,
    districtId INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (countyCode) REFERENCES county(countyCode),
    FOREIGN KEY (districtId) REFERENCES territory_districts(id)
);

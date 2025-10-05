-- Migration 023: Create Parishes Table
-- Description: Creates the parishes table for geographic management

USE sunfuel;

CREATE TABLE IF NOT EXISTS parishes (
    parishCode VARCHAR(10) PRIMARY KEY,
    parishName VARCHAR(255) NOT NULL,
    subCountyCode VARCHAR(10) NOT NULL,
    countyCode VARCHAR(10) NOT NULL,
    districtCode VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (subCountyCode) REFERENCES subcounty(subCountyCode),
    FOREIGN KEY (countyCode) REFERENCES county(countyCode),
    FOREIGN KEY (districtCode) REFERENCES territory_districts(id)
);

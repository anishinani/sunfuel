-- Migration 026: Additional Uganda Geographic Data
-- Description: Adds more comprehensive geographic data for Uganda

USE sunfuel;

-- Insert additional counties for Wakiso District
INSERT INTO county (countyCode, countyName, districtCode) VALUES 
('WKS001001', 'Busiro North', 2),
('WKS001002', 'Busiro South', 2),
('WKS001003', 'Busiro East', 2),
('WKS002001', 'Kyadondo North', 2),
('WKS002002', 'Kyadondo South', 2),
('WKS002003', 'Kyadondo East', 2),
('WKS003001', 'Entebbe Municipality', 2),
('WKS003002', 'Kira Municipality', 2),
('WKS003003', 'Nansana Municipality', 2);

-- Insert subcounties for Busiro North
INSERT INTO subcounty (subCountyCode, subCountyName, countyCode, districtCode) VALUES 
('WKS001001001', 'Gayaza', 'WKS001001', 2),
('WKS001001002', 'Kakiri', 'WKS001001', 2),
('WKS001001003', 'Kasanje', 'WKS001001', 2),
('WKS001001004', 'Kira', 'WKS001001', 2),
('WKS001001005', 'Nangabo', 'WKS001001', 2);

-- Insert subcounties for Busiro South
INSERT INTO subcounty (subCountyCode, subCountyName, countyCode, districtCode) VALUES 
('WKS001002001', 'Busukuma', 'WKS001002', 2),
('WKS001002002', 'Gombe', 'WKS001002', 2),
('WKS001002003', 'Kakiri', 'WKS001002', 2),
('WKS001002004', 'Kasanje', 'WKS001002', 2),
('WKS001002005', 'Nangabo', 'WKS001002', 2);

-- Insert parishes for Gayaza
INSERT INTO parishes (parishCode, parishName, subCountyCode, countyCode, districtCode) VALUES 
('WKS001001001001', 'Gayaza Central', 'WKS001001001', 'WKS001001', 2),
('WKS001001001002', 'Gayaza Market', 'WKS001001001', 'WKS001001', 2),
('WKS001001001003', 'Gayaza School', 'WKS001001001', 'WKS001001', 2);

-- Insert parishes for Kakiri
INSERT INTO parishes (parishCode, parishName, subCountyCode, countyCode, districtCode) VALUES 
('WKS001001002001', 'Kakiri Central', 'WKS001001002', 'WKS001001', 2),
('WKS001001002002', 'Kakiri Market', 'WKS001001002', 'WKS001001', 2),
('WKS001001002003', 'Kakiri Industrial', 'WKS001001002', 'WKS001001', 2);

-- Insert villages for Gayaza Central
INSERT INTO villages (villageCode, villageName, parishCode, subCountyCode, countyCode, districtCode) VALUES 
('WKS001001001001001', 'Gayaza Town', 'WKS001001001001', 'WKS001001001', 'WKS001001', 2),
('WKS001001001001002', 'Gayaza University', 'WKS001001001001', 'WKS001001001', 'WKS001001', 2),
('WKS001001001001003', 'Gayaza Hospital', 'WKS001001001001', 'WKS001001001', 'WKS001001', 2);

-- Insert villages for Kakiri Central
INSERT INTO villages (villageCode, villageName, parishCode, subCountyCode, countyCode, districtCode) VALUES 
('WKS001001002001001', 'Kakiri Town', 'WKS001001002001', 'WKS001001002', 'WKS001001', 2),
('WKS001001002001002', 'Kakiri Market', 'WKS001001002001', 'WKS001001002', 'WKS001001', 2),
('WKS001001002001003', 'Kakiri School', 'WKS001001002001', 'WKS001001002', 'WKS001001', 2);

-- Insert additional counties for Jinja District
INSERT INTO county (countyCode, countyName, districtCode) VALUES 
('JNJ001001', 'Jinja Municipality', 12),
('JNJ001002', 'Jinja Rural', 12),
('JNJ002001', 'Buwenge Municipality', 12),
('JNJ002002', 'Buwenge Rural', 12);

-- Insert subcounties for Jinja Municipality
INSERT INTO subcounty (subCountyCode, subCountyName, countyCode, districtCode) VALUES 
('JNJ001001001', 'Jinja Central', 'JNJ001001', 12),
('JNJ001001002', 'Jinja Market', 'JNJ001001', 12),
('JNJ001001003', 'Jinja Industrial', 'JNJ001001', 12);

-- Insert parishes for Jinja Central
INSERT INTO parishes (parishCode, parishName, subCountyCode, countyCode, districtCode) VALUES 
('JNJ001001001001', 'Jinja Town', 'JNJ001001001', 'JNJ001001', 12),
('JNJ001001001002', 'Jinja Port', 'JNJ001001001', 'JNJ001001', 12),
('JNJ001001001003', 'Jinja Hospital', 'JNJ001001001', 'JNJ001001', 12);

-- Insert villages for Jinja Town
INSERT INTO villages (villageCode, villageName, parishCode, subCountyCode, countyCode, districtCode) VALUES 
('JNJ001001001001001', 'Jinja City Center', 'JNJ001001001001', 'JNJ001001001', 'JNJ001001', 12),
('JNJ001001001001002', 'Jinja Market', 'JNJ001001001001', 'JNJ001001001', 'JNJ001001', 12),
('JNJ001001001001003', 'Jinja Railway', 'JNJ001001001001', 'JNJ001001001', 'JNJ001001', 12);

-- Insert additional counties for Masaka District
INSERT INTO county (countyCode, countyName, districtCode) VALUES 
('MSK001', 'Masaka Municipality', 41),
('MSK002', 'Bukoto Central', 41),
('MSK003', 'Bukoto East', 41),
('MSK004', 'Bukoto West', 41);

-- Insert subcounties for Masaka Municipality
INSERT INTO subcounty (subCountyCode, subCountyName, countyCode, districtCode) VALUES 
('MSK001001', 'Masaka Central', 'MSK001', 41),
('MSK001002', 'Masaka Market', 'MSK001', 41),
('MSK001003', 'Masaka Industrial', 'MSK001', 41);

-- Insert parishes for Masaka Central
INSERT INTO parishes (parishCode, parishName, subCountyCode, countyCode, districtCode) VALUES 
('MSK001001001', 'Masaka Town', 'MSK001001', 'MSK001', 41),
('MSK001001002', 'Masaka Hospital', 'MSK001001', 'MSK001', 41),
('MSK001001003', 'Masaka University', 'MSK001001', 'MSK001', 41);

-- Insert villages for Masaka Town
INSERT INTO villages (villageCode, villageName, parishCode, subCountyCode, countyCode, districtCode) VALUES 
('MSK001001001001', 'Masaka City Center', 'MSK001001001', 'MSK001001', 'MSK001', 41),
('MSK001001001002', 'Masaka Market', 'MSK001001001', 'MSK001001', 'MSK001', 41),
('MSK001001001003', 'Masaka Bus Park', 'MSK001001001', 'MSK001001', 'MSK001', 41);

-- Insert additional counties for Gulu District
INSERT INTO county (countyCode, countyName, districtCode) VALUES 
('GLU001', 'Gulu Municipality', 43),
('GLU002', 'Aswa', 43),
('GLU003', 'Omoro', 43);

-- Insert subcounties for Gulu Municipality
INSERT INTO subcounty (subCountyCode, subCountyName, countyCode, districtCode) VALUES 
('GLU001001', 'Gulu Central', 'GLU001', 43),
('GLU001002', 'Gulu Market', 'GLU001', 43),
('GLU001003', 'Gulu Industrial', 'GLU001', 43);

-- Insert parishes for Gulu Central
INSERT INTO parishes (parishCode, parishName, subCountyCode, countyCode, districtCode) VALUES 
('GLU001001001', 'Gulu Town', 'GLU001001', 'GLU001', 43),
('GLU001001002', 'Gulu Hospital', 'GLU001001', 'GLU001', 43),
('GLU001001003', 'Gulu University', 'GLU001001', 'GLU001', 43);

-- Insert villages for Gulu Town
INSERT INTO villages (villageCode, villageName, parishCode, subCountyCode, countyCode, districtCode) VALUES 
('GLU001001001001', 'Gulu City Center', 'GLU001001001', 'GLU001001', 'GLU001', 43),
('GLU001001001002', 'Gulu Market', 'GLU001001001', 'GLU001001', 'GLU001', 43),
('GLU001001001003', 'Gulu Bus Park', 'GLU001001001', 'GLU001001', 'GLU001', 43);

-- Migration 025: Seed Uganda Geographic Data
-- Description: Populates the geographic tables with Uganda's districts, counties, subcounties, parishes, and villages

USE sunfuel;

-- First, let's insert some territories (regions)
INSERT INTO territories (territoryName) VALUES 
('Central Region'),
('Eastern Region'),
('Northern Region'),
('Western Region');

-- Insert districts for Central Region
INSERT INTO territory_districts (territoryId, districtName) VALUES 
(1, 'Kampala'),
(1, 'Wakiso'),
(1, 'Mukono'),
(1, 'Mpigi'),
(1, 'Luweero'),
(1, 'Nakaseke'),
(1, 'Nakasongola'),
(1, 'Kayunga'),
(1, 'Buikwe'),
(1, 'Buvuma');

-- Insert districts for Eastern Region
INSERT INTO territory_districts (territoryId, districtName) VALUES 
(2, 'Jinja'),
(2, 'Iganga'),
(2, 'Kamuli'),
(2, 'Buyende'),
(2, 'Kaliro'),
(2, 'Namutumba'),
(2, 'Bugiri'),
(2, 'Bugweri'),
(2, 'Mayuge'),
(2, 'Namayingo'),
(2, 'Busia'),
(2, 'Tororo'),
(2, 'Butaleja'),
(2, 'Pallisa'),
(2, 'Kibuku'),
(2, 'Butebo'),
(2, 'Budaka'),
(2, 'Mbale'),
(2, 'Manafwa'),
(2, 'Namisindwa'),
(2, 'Bulambuli'),
(2, 'Sironko'),
(2, 'Kapchorwa'),
(2, 'Kween'),
(2, 'Bukwo'),
(2, 'Kumi'),
(2, 'Ngora'),
(2, 'Serere'),
(2, 'Soroti'),
(2, 'Amuria'),
(2, 'Katakwi'),
(2, 'Kaberamaido'),
(2, 'Kalaki');

-- Insert districts for Northern Region
INSERT INTO territory_districts (territoryId, districtName) VALUES 
(3, 'Gulu'),
(3, 'Amuru'),
(3, 'Nwoya'),
(3, 'Omoro'),
(3, 'Pader'),
(3, 'Agago'),
(3, 'Kitgum'),
(3, 'Lamwo'),
(3, 'Lira'),
(3, 'Alebtong'),
(3, 'Amolatar'),
(3, 'Dokolo'),
(3, 'Kole'),
(3, 'Otuke'),
(3, 'Oyam'),
(3, 'Apac'),
(3, 'Kwania'),
(3, 'Arua'),
(3, 'Koboko'),
(3, 'Maracha'),
(3, 'Terego'),
(3, 'Yumbe'),
(3, 'Madi-Okollo'),
(3, 'Obongi'),
(3, 'Moyo'),
(3, 'Adjumani'),
(3, 'Pakwach'),
(3, 'Nebbi'),
(3, 'Zombo');

-- Insert districts for Western Region
INSERT INTO territory_districts (territoryId, districtName) VALUES 
(4, 'Masaka'),
(4, 'Kalungu'),
(4, 'Bukomansimbi'),
(4, 'Lwengo'),
(4, 'Sembabule'),
(4, 'Rakai'),
(4, 'Kyotera'),
(4, 'Mpigi'),
(4, 'Butambala'),
(4, 'Gomba'),
(4, 'Kalangala'),
(4, 'Mityana'),
(4, 'Kassanda'),
(4, 'Mubende'),
(4, 'Kassanda'),
(4, 'Kiboga'),
(4, 'Kyankwanzi'),
(4, 'Hoima'),
(4, 'Kikuube'),
(4, 'Kakumiro'),
(4, 'Kibaale'),
(4, 'Masindi'),
(4, 'Buliisa'),
(4, 'Kiryandongo'),
(4, 'Kabarole'),
(4, 'Bunyangabu'),
(4, 'Kyegegwa'),
(4, 'Kyenjojo'),
(4, 'Ntoroko'),
(4, 'Bundibugyo'),
(4, 'Kasese'),
(4, 'Kamwenge'),
(4, 'Kitagwenda'),
(4, 'Rukiga'),
(4, 'Rubanda'),
(4, 'Kanungu'),
(4, 'Rukungiri'),
(4, 'Kisoro'),
(4, 'Bushenyi'),
(4, 'Mitooma'),
(4, 'Rubirizi'),
(4, 'Sheema'),
(4, 'Buhweju'),
(4, 'Ibanda'),
(4, 'Isingiro'),
(4, 'Kiruhura'),
(4, 'Mbarara'),
(4, 'Ntungamo'),
(4, 'Kazo');

-- Insert counties for Kampala District
INSERT INTO county (countyCode, countyName, districtCode) VALUES 
('KMP001', 'Kampala Central', 1),
('KMP002', 'Kawempe', 1),
('KMP003', 'Makindye', 1),
('KMP004', 'Nakawa', 1),
('KMP005', 'Rubaga', 1);

-- Insert counties for Wakiso District
INSERT INTO county (countyCode, countyName, districtCode) VALUES 
('WKS001', 'Busiro', 2),
('WKS002', 'Kyadondo', 2),
('WKS003', 'Entebbe', 2);

-- Insert counties for Jinja District
INSERT INTO county (countyCode, countyName, districtCode) VALUES 
('JNJ001', 'Jinja Municipality', 12),
('JNJ002', 'Buwenge', 12),
('JNJ003', 'Butembe', 12);

-- Insert subcounties for Kampala Central
INSERT INTO subcounty (subCountyCode, subCountyName, countyCode, districtCode) VALUES 
('KMP001001', 'Central Division', 'KMP001', 1),
('KMP001002', 'Nakasero', 'KMP001', 1),
('KMP001003', 'Old Kampala', 'KMP001', 1);

-- Insert subcounties for Kawempe
INSERT INTO subcounty (subCountyCode, subCountyName, countyCode, districtCode) VALUES 
('KMP002001', 'Kawempe Division', 'KMP002', 1),
('KMP002002', 'Kawempe North', 'KMP002', 1),
('KMP002003', 'Kawempe South', 'KMP002', 1);

-- Insert subcounties for Makindye
INSERT INTO subcounty (subCountyCode, subCountyName, countyCode, districtCode) VALUES 
('KMP003001', 'Makindye Division', 'KMP003', 1),
('KMP003002', 'Kibuli', 'KMP003', 1),
('KMP003003', 'Lubaga', 'KMP003', 1);

-- Insert parishes for Central Division
INSERT INTO parishes (parishCode, parishName, subCountyCode, countyCode, districtCode) VALUES 
('KMP001001001', 'Nakasero Parish', 'KMP001001', 'KMP001', 1),
('KMP001001002', 'Kampala Road Parish', 'KMP001001', 'KMP001', 1),
('KMP001001003', 'Parliament Avenue Parish', 'KMP001001', 'KMP001', 1);

-- Insert parishes for Kawempe Division
INSERT INTO parishes (parishCode, parishName, subCountyCode, countyCode, districtCode) VALUES 
('KMP002001001', 'Kawempe Parish', 'KMP002001', 'KMP002', 1),
('KMP002001002', 'Bwaise Parish', 'KMP002001', 'KMP002', 1),
('KMP002001003', 'Mulago Parish', 'KMP002001', 'KMP002', 1);

-- Insert villages for Nakasero Parish
INSERT INTO villages (villageCode, villageName, parishCode, subCountyCode, countyCode, districtCode) VALUES 
('KMP001001001001', 'Nakasero Hill', 'KMP001001001', 'KMP001001', 'KMP001', 1),
('KMP001001001002', 'Kololo', 'KMP001001001', 'KMP001001', 'KMP001', 1),
('KMP001001001003', 'Nakasero Market', 'KMP001001001', 'KMP001001', 'KMP001', 1);

-- Insert villages for Kawempe Parish
INSERT INTO villages (villageCode, villageName, parishCode, subCountyCode, countyCode, districtCode) VALUES 
('KMP002001001001', 'Kawempe Market', 'KMP002001001', 'KMP002001', 'KMP002', 1),
('KMP002001001002', 'Kawempe Ttula', 'KMP002001001', 'KMP002001', 'KMP002', 1),
('KMP002001001003', 'Kawempe Kiganda', 'KMP002001001', 'KMP002001', 'KMP002', 1);

-- Insert more counties for other districts
INSERT INTO county (countyCode, countyName, districtCode) VALUES 
('MKN001', 'Mukono Municipality', 3),
('MKN002', 'Nakifuma', 3),
('MKN003', 'Mukono', 3);

-- Insert subcounties for Mukono
INSERT INTO subcounty (subCountyCode, subCountyName, countyCode, districtCode) VALUES 
('MKN001001', 'Mukono Municipality', 'MKN001', 3),
('MKN001002', 'Ntinda', 'MKN001', 3),
('MKN001003', 'Kireka', 'MKN001', 3);

-- Insert parishes for Mukono Municipality
INSERT INTO parishes (parishCode, parishName, subCountyCode, countyCode, districtCode) VALUES 
('MKN001001001', 'Mukono Central', 'MKN001001', 'MKN001', 3),
('MKN001001002', 'Mukono Market', 'MKN001001', 'MKN001', 3),
('MKN001001003', 'Mukono Industrial', 'MKN001001', 'MKN001', 3);

-- Insert villages for Mukono Central
INSERT INTO villages (villageCode, villageName, parishCode, subCountyCode, countyCode, districtCode) VALUES 
('MKN001001001001', 'Mukono Town', 'MKN001001001', 'MKN001001', 'MKN001', 3),
('MKN001001001002', 'Mukono University', 'MKN001001001', 'MKN001001', 'MKN001', 3),
('MKN001001001003', 'Mukono Hospital', 'MKN001001001', 'MKN001001', 'MKN001', 3);

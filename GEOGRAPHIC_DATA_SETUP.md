# Geographic Data Setup for Sunfuel

This document explains how to set up the geographic data (districts, counties, subcounties, parishes, villages) for the fuel station registration system.

## Overview

The fuel station registration form at `views/fuelstation/create.php` requires geographic data to populate the dropdown menus for:
- District selection
- County selection (based on district)
- Subcounty selection (based on county)
- Parish selection (based on subcounty)
- Village selection (based on parish)

## Files Created

### Migration Files
- `migrations/021_create_county_table.sql` - Creates the county table
- `migrations/022_create_subcounty_table.sql` - Creates the subcounty table
- `migrations/023_create_parishes_table.sql` - Creates the parishes table
- `migrations/024_create_villages_table.sql` - Creates the villages table
- `migrations/025_seed_uganda_geographic_data.sql` - Seeds basic Uganda data
- `migrations/026_additional_uganda_data.sql` - Seeds additional Uganda data

### Setup Scripts
- `setup_geographic_data.php` - Web interface for setup
- `seed_geographic_data.php` - Command-line setup script

## Setup Methods

### Method 1: Web Interface (Recommended)

1. Navigate to: `http://127.0.0.1/sunfuel/setup_geographic_data.php`
2. Click "Setup Geographic Data" button
3. Wait for the process to complete
4. Check the data summary

### Method 2: Command Line

1. Open terminal/command prompt
2. Navigate to the sunfuel directory
3. Run: `php seed_geographic_data.php`

### Method 3: Manual SQL Execution

1. Open phpMyAdmin or MySQL command line
2. Execute the migration files in order (021-026)
3. Verify the data was inserted correctly

## Database Structure

### Tables Created

1. **county**
   - `countyCode` (VARCHAR, Primary Key)
   - `countyName` (VARCHAR)
   - `districtCode` (VARCHAR, Foreign Key)

2. **subcounty**
   - `subCountyCode` (VARCHAR, Primary Key)
   - `subCountyName` (VARCHAR)
   - `countyCode` (VARCHAR, Foreign Key)
   - `districtCode` (VARCHAR, Foreign Key)

3. **parishes**
   - `parishCode` (VARCHAR, Primary Key)
   - `parishName` (VARCHAR)
   - `subCountyCode` (VARCHAR, Foreign Key)
   - `countyCode` (VARCHAR, Foreign Key)
   - `districtCode` (VARCHAR, Foreign Key)

4. **villages**
   - `villageCode` (VARCHAR, Primary Key)
   - `villageName` (VARCHAR)
   - `parishCode` (VARCHAR, Foreign Key)
   - `subCountyCode` (VARCHAR, Foreign Key)
   - `countyCode` (VARCHAR, Foreign Key)
   - `districtCode` (VARCHAR, Foreign Key)

## Data Included

The seed data includes:

### Regions (4)
- Central Region
- Eastern Region
- Northern Region
- Western Region

### Districts (50+)
Including major districts like:
- Kampala
- Wakiso
- Mukono
- Jinja
- Masaka
- Gulu
- And many more...

### Counties, Subcounties, Parishes, and Villages
Complete hierarchical data for the major districts, with sample data for:
- Kampala (Central Division, Kawempe, Makindye, etc.)
- Wakiso (Gayaza, Kakiri, Kira, etc.)
- Jinja (Jinja Municipality, Buwenge, etc.)
- Masaka (Masaka Municipality, etc.)
- Gulu (Gulu Municipality, etc.)

## Testing the Integration

After setup, test the fuel station form:

1. Go to: `http://127.0.0.1/sunfuel/views/fuelstation/create.php`
2. Select a district from the dropdown
3. Verify that counties populate based on district selection
4. Continue through the hierarchy (subcounty → parish → village)

## Troubleshooting

### Common Issues

1. **Tables already exist**: The setup will skip existing tables and add new data
2. **Foreign key errors**: Ensure territories and territory_districts tables exist first
3. **Permission errors**: Check database user permissions
4. **Connection errors**: Verify database connection settings in `utils/dbaccess.php`

### Verification Queries

Run these queries to verify the setup:

```sql
-- Check territories
SELECT COUNT(*) FROM territories;

-- Check districts
SELECT COUNT(*) FROM territory_districts;

-- Check counties
SELECT COUNT(*) FROM county;

-- Check subcounties
SELECT COUNT(*) FROM subcounty;

-- Check parishes
SELECT COUNT(*) FROM parishes;

-- Check villages
SELECT COUNT(*) FROM villages;
```

## Adding More Data

To add more geographic data:

1. Create additional SQL files following the naming convention
2. Use the existing code structure for consistency
3. Ensure proper foreign key relationships
4. Test the integration with the form

## Support

If you encounter issues:

1. Check the database connection
2. Verify table permissions
3. Review the error messages in the setup interface
4. Ensure all migration files are present and readable

The setup process is designed to be safe and can be run multiple times without issues.

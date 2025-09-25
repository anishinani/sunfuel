# Sunfuel Web Application - XAMPP Deployment Guide

## Project Overview
This is a Sunfuel web application built in PHP with the following features:
- User management and authentication
- Loan management system
- Deposit tracking
- Fuel station management
- Territory management
- Role-based access control
- Excel export functionality (PhpSpreadsheet)

## Prerequisites
- XAMPP installed on your system
- PHP 7.4+ (tested with PHP 8.4)
- MySQL/MariaDB
- Composer (for dependency management)

## Deployment Steps

### 1. Start XAMPP Services
```bash
# Start XAMPP (requires admin privileges)
sudo /Applications/XAMPP/xamppfiles/xampp start

# Or use the XAMPP Control Panel GUI
```

### 2. Database Setup
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Create a new database named `sunfuel`
3. Run the database migrations using one of these methods:

#### Method A: Command Line (Recommended)
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/sunfuel/migrations
php migrate.php
```

#### Method B: Web Interface
Navigate to: http://localhost/sunfuel/migrations/web_migrate.php
Click "Run All Migrations"

#### Method C: Manual Import
Import the individual migration files from the `migrations/` directory in numerical order.

4. The migrations include all necessary tables and a default admin user:
   - **Email**: admin@sunfuel.ug
   - **Password**: admin123

### 3. Configuration Updates (Already Done)
The following files have been updated for XAMPP compatibility:

#### `utils/dbaccess.php`
- Database password set to empty string (XAMPP default)
- Database name: `bodacredit`

#### `utils/config.php`
- Database configuration updated for XAMPP
- Password set to empty string

#### `composer.json`
- Updated PhpSpreadsheet version to be compatible with PHP 8.4
- Removed platform constraints

### 4. Dependencies Installation (Already Done)
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/sunfuel
composer install
```

### 5. Access the Application
1. Open your web browser
2. Navigate to: http://localhost/sunfuel
3. You should see the login page

## File Structure
```
sunfuel/
├── api/                    # API endpoints
├── controllers/            # Business logic controllers
├── views/                  # User interface templates
├── utils/                  # Utility classes and configuration
├── plugins/                # Third-party libraries (AdminLTE, jQuery, etc.)
├── dist/                   # Compiled assets
├── vendor/                 # Composer dependencies
├── jobs/                   # Scheduled tasks
└── composer.json           # Dependency configuration
```

## Key Features
- **Authentication**: Email/password login system
- **Dashboard**: AdminLTE-based interface
- **User Management**: Role-based access control
- **Loan System**: Loan calculations and management
- **Deposit Tracking**: Financial deposit management
- **Fuel Stations**: Fuel station and agent management
- **Territories**: Geographic territory management
- **Excel Export**: PhpSpreadsheet integration for reports

## Database Configuration
- **Host**: localhost
- **Username**: root
- **Password**: !Log19tan88
- **Database**: sunfuel

## Troubleshooting

### Common Issues:

1. **Database Connection Error**
   - Ensure MySQL is running in XAMPP
   - Check database credentials in `utils/dbaccess.php`
   - Verify database `bodacredit` exists

2. **Composer Dependencies**
   - Run `composer install` if vendor directory is missing
   - Check PHP version compatibility

3. **File Permissions**
   - Ensure web server has read access to all files
   - Check write permissions for logs and uploads

4. **Session Issues**
   - Check PHP session configuration
   - Ensure session directory is writable

## Security Notes
- Change default database passwords in production
- Update salt values in `utils/config.php`
- Implement proper file permissions
- Use HTTPS in production environment

## Development vs Production
- Current configuration is set for XAMPP development environment
- For production deployment:
  - Update database credentials
  - Change base URLs in configuration
  - Implement proper security measures
  - Use environment-specific configuration files

## Support
This application appears to be from 2023 and may require updates for:
- PHP version compatibility
- Security patches
- Database schema updates
- Third-party library updates

## Next Steps
1. Start XAMPP services
2. Create the `bodacredit` database
3. Import database schema (if available)
4. Test login functionality
5. Verify all features are working correctly

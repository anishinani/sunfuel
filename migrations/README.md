# Sunfuel Database Migrations

This directory contains database migration files for the Sunfuel application. Migrations are used to set up and maintain the database schema in a version-controlled manner.

## Migration Files

The migrations are numbered sequentially and should be run in order:

1. **001_create_database.sql** - Creates the main database
2. **002_create_roles_table.sql** - Creates roles table with default roles
3. **003_create_permissions_table.sql** - Creates permissions table with default permissions
4. **004_create_features_table.sql** - Creates features table with default features
5. **005_create_users_table.sql** - Creates users table with default admin user
6. **006_create_role_permissions_table.sql** - Creates role permissions table
7. **007_create_role_modules_table.sql** - Creates role modules table
8. **008_create_territories_table.sql** - Creates territories table
9. **009_create_territory_districts_table.sql** - Creates territory districts table
10. **010_create_fuelstation_table.sql** - Creates fuel station table
11. **011_create_fuelstation_views.sql** - Creates fuel station views
12. **012_create_stage_table.sql** - Creates stage table
13. **013_create_bodauser_table.sql** - Creates boda user table
14. **014_create_loan_table.sql** - Creates loan table
15. **015_create_payments_table.sql** - Creates payments table
16. **016_create_deposits_table.sql** - Creates deposits table
17. **017_create_package_table.sql** - Creates package table
18. **018_create_user_totals_table.sql** - Creates user totals table

## Running Migrations

### Method 1: Command Line (Recommended)

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/sunfuel/migrations
php migrate.php
```

Available commands:
- `php migrate.php run` - Execute all pending migrations (default)
- `php migrate.php status` - Show migration status
- `php migrate.php reset` - Reset all migrations (DANGEROUS)

### Method 2: Web Interface

1. Open your browser and navigate to:
   ```
   http://localhost/sunfuel/migrations/web_migrate.php
   ```

2. Click "Run All Migrations" to execute pending migrations
3. Click "Check Status" to see which migrations have been executed

### Method 3: Manual Execution

You can also run individual migration files manually in phpMyAdmin or MySQL command line:

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select the `bodacredit` database
3. Go to the SQL tab
4. Copy and paste the contents of each migration file
5. Execute them in numerical order

## Default Data

The migrations include default data:

### Default Admin User
- **Email**: admin@sunfuel.ug
- **Password**: admin123
- **Role**: Super Admin

### Default Roles
- Super Admin (Full system access)
- Admin (Administrative access)
- Manager (Management level access)
- Agent (Field agent access)

### Default Permissions
- create (Create new records)
- read (View records)
- update (Modify existing records)
- delete (Delete records)
- export (Export data)

### Default Features
- users (User management)
- loans (Loan management)
- deposits (Deposit management)
- fuelstations (Fuel station management)
- stages (Stage management)
- territories (Territory management)
- reports (Reporting system)

## Migration Tracking

The migration system automatically tracks which migrations have been executed using a `migrations` table. This prevents migrations from being run multiple times and ensures they are executed in the correct order.

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Ensure MySQL is running in XAMPP
   - Check database credentials in `utils/dbaccess.php`
   - Verify the `bodacredit` database exists

2. **Migration Fails**
   - Check the error message for specific issues
   - Ensure previous migrations completed successfully
   - Verify file permissions on migration files

3. **Permission Denied**
   - Ensure the web server has read access to migration files
   - Check that the database user has CREATE, INSERT, and UPDATE permissions

### Resetting Migrations

If you need to start over (DANGEROUS - this will delete all data):

```bash
php migrate.php reset
```

This will:
- Drop all tables
- Drop the database
- Clear the migration tracking table

## Security Notes

- The web migration interface should only be accessible during development
- Consider removing or protecting the web migration interface in production
- Always backup your database before running migrations in production
- Change default passwords after initial setup

## Adding New Migrations

To add new migrations:

1. Create a new SQL file with the next sequential number
2. Follow the naming convention: `XXX_description.sql`
3. Include the `USE bodacredit;` statement at the beginning
4. Test the migration thoroughly before deployment
5. Update this README with the new migration information

# SunFuel Server Deployment Guide
## Server: 165.227.18.128

### Prerequisites
- Server IP: 165.227.18.128
- SSH User: root
- SSH Password: svq!U9&BMz^fkQX

### Step 1: Server Setup Commands

Once you have SSH access, run these commands:

```bash
# Update system
apt update && apt upgrade -y

# Install Apache
apt install apache2 -y
systemctl start apache2
systemctl enable apache2

# Install MySQL
apt install mysql-server -y
systemctl start mysql
systemctl enable mysql

# Install PHP and required extensions
apt install php php-mysql php-curl php-json php-mbstring php-xml php-zip php-gd php-cli -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Create web directory
mkdir -p /var/www/html/sunfuel
chown -R www-data:www-data /var/www/html/sunfuel
chmod -R 755 /var/www/html/sunfuel
```

### Step 2: Database Setup

```bash
# Secure MySQL installation
mysql_secure_installation

# Create database and user
mysql -u root -p
```

Run these SQL commands in MySQL:
```sql
CREATE DATABASE sunfuel;
CREATE USER 'sunfuel_user'@'localhost' IDENTIFIED BY 'SecurePassword123!';
GRANT ALL PRIVILEGES ON sunfuel.* TO 'sunfuel_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: File Upload

Upload all SunFuel files to `/var/www/html/sunfuel/`

### Step 4: Configuration Updates

Update the following files with production settings:

1. **utils/dbaccess.php** - Update database credentials
2. **utils/config.php** - Update base URL and security settings
3. **composer.json** - Install dependencies

### Step 5: Database Import

Import the database schema from the migrations folder.

### Step 6: Apache Configuration

Create virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName 165.227.18.128
    DocumentRoot /var/www/html/sunfuel
    
    <Directory /var/www/html/sunfuel>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/sunfuel_error.log
    CustomLog ${APACHE_LOG_DIR}/sunfuel_access.log combined
</VirtualHost>
```

### Step 7: Final Steps

```bash
# Enable Apache modules
a2enmod rewrite
a2enmod php

# Restart services
systemctl restart apache2
systemctl restart mysql

# Install Composer dependencies
cd /var/www/html/sunfuel
composer install
```

### Access URLs

- Main Application: http://165.227.18.128/sunfuel/
- Admin Login: http://165.227.18.128/sunfuel/login.php
- Default Admin: admin@sunfuel.ug / admin123

### Troubleshooting

1. **Permission Issues**: `chown -R www-data:www-data /var/www/html/sunfuel`
2. **Database Connection**: Check credentials in utils/dbaccess.php
3. **Apache Errors**: Check `/var/log/apache2/error.log`
4. **PHP Errors**: Check `/var/log/apache2/php_errors.log`

### Security Notes

- Change default passwords
- Update salt values in config.php
- Enable HTTPS in production
- Configure firewall rules
- Regular security updates

#!/bin/bash

# SunFuel Server Deployment Script for Digital Ocean
# Run this script on your Digital Ocean droplet

echo "🚀 Starting SunFuel Server Deployment..."

# Update system
echo "📦 Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install Apache
echo "🌐 Installing Apache..."
sudo apt install apache2 -y
sudo systemctl start apache2
sudo systemctl enable apache2

# Install MySQL
echo "🗄️ Installing MySQL..."
sudo apt install mysql-server -y
sudo systemctl start mysql
sudo systemctl enable mysql

# Install PHP and extensions
echo "🐘 Installing PHP and extensions..."
sudo apt install php php-mysql php-curl php-json php-mbstring php-xml php-zip php-gd php-cli -y

# Install Composer
echo "📦 Installing Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Create database
echo "🗄️ Setting up database..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS sunfuel;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'sunfuel_user'@'localhost' IDENTIFIED BY 'SunFuel2024!';"
sudo mysql -e "GRANT ALL PRIVILEGES ON sunfuel.* TO 'sunfuel_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Set up web directory
echo "📁 Setting up web directory..."
sudo mkdir -p /var/www/sunfuel
sudo chown -R www-data:www-data /var/www/sunfuel
sudo chmod -R 755 /var/www/sunfuel

# Configure Apache virtual host
echo "⚙️ Configuring Apache virtual host..."
sudo tee /etc/apache2/sites-available/sunfuel.conf > /dev/null <<EOF
<VirtualHost *:80>
    ServerName sunfuel.local
    DocumentRoot /var/www/sunfuel
    
    <Directory /var/www/sunfuel>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/sunfuel_error.log
    CustomLog \${APACHE_LOG_DIR}/sunfuel_access.log combined
</VirtualHost>
EOF

# Enable site and modules
sudo a2ensite sunfuel.conf
sudo a2enmod rewrite
sudo systemctl reload apache2

echo "✅ Server setup complete!"
echo "📋 Next steps:"
echo "1. Upload SunFuel files to /var/www/sunfuel"
echo "2. Import database schema"
echo "3. Configure database connection"
echo "4. Set proper file permissions"

echo "🌐 Your server is ready at: http://$(curl -s ifconfig.me)"

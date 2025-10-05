<?php
// Production Server Configuration for SunFuel
// This file contains the configuration needed for deployment to 165.227.18.128

// Database Configuration for Production
$production_db_config = [
    'host' => 'localhost',
    'username' => 'sunfuel_user',
    'password' => 'secure_password_here', // CHANGE THIS TO A SECURE PASSWORD
    'database' => 'sunfuel'
];

// Web Server Configuration
$production_web_config = [
    'base_url' => 'http://165.227.18.128/sunfuel/',
    'document_root' => '/var/www/html/sunfuel/',
    'site_name' => 'SunFuel Production'
];

// Security Configuration
$production_security_config = [
    'salt_1' => 'prod_salt_1_' . bin2hex(random_bytes(16)),
    'salt_2' => 'prod_salt_2_' . bin2hex(random_bytes(16)),
    'email_from' => 'no-reply@sunfuel.ug'
];

echo "Production configuration prepared. Please update the password in this file before deployment.\n";
echo "Database: " . $production_db_config['database'] . "\n";
echo "Web URL: " . $production_web_config['base_url'] . "\n";
?>

#!/bin/bash

DATABASE_NAME="swiftsalesLocal"

sudo mysql -u root --execute="CREATE DATABASE IF NOT EXISTS ${DATABASE_NAME};"

export APP_ENV=local
export QUEUE_CONNECTION=sync
php artisan migrate:fresh --seed

rm -rf /var/www/swiftsales-api/public/uploads/*

# Restart PHP process to clear caches
sudo service php8.1-fpm restart

sudo /var/www/swiftsales-api/after-up.sh

# Exit with success
exit 0

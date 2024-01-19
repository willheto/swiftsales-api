#!/bin/bash

DATABASE_NAME="swiftsalesLocal"

sudo mysql -u root --execute="CREATE DATABASE IF NOT EXISTS ${DATABASE_NAME};"

sudo rm -rf ./public/uploads/*

export APP_ENV=local
export QUEUE_CONNECTION=sync
php artisan migrate:fresh --seed

# Restart PHP process to clear caches
sudo service php8.1-fpm restart

/var/www/html/after-up.sh

# Exit with success
exit 0

#!/bin/bash

# Set the backup directory and filename
backup_dir="~/swiftsales-api-db-backups"
backup_filename="db_backup_$(date +\%Y\%m\%d).sql.gz"

# Set the MySQL/MariaDB credentials
mysql_user="swiftsales"
mysql_password="HAscwByEOL5hl9LvTZOc"
mysql_database="swiftsalesProduction"

# Backup the database
mysqldump -u $mysql_user -p$mysql_password $mysql_database | gzip > "$backup_dir/$backup_filename"

# Delete backups older than 30 days
find $backup_dir -type f -name "db_backup_*" -mtime +30 -exec rm {} \;

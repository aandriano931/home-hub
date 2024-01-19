#!/bin/bash

# Database connection details
DB_USER="$MYSQL_USER"
DB_PASSWORD="$MYSQL_PASSWORD"
DB_NAME="home-hub"

# Backup directory
DOCKER_BACKUP_DIR="/backup/familyhub"
HOST_BACKUP_DIR="/home/arnaud/dev/backup"
HOST_REPO_DIR="/home/arnaud/dev/backup/home-hub-backup"

# MySQL dump command to create a backup
docker exec mysql mysqldump -u$DB_USER -p$DB_PASSWORD $DB_NAME > $BACKUP_DIR/$DB_NAME.sql

# Compress the backup file
tar -czvf $HOST_BACKUP_DIR/$DB_NAME.tar.gz $HOST_BACKUP_DIR/$DB_NAME.sql

# Move the file to the repository
mv $HOST_BACKUP_DIR/$DB_NAME.tar.gz  $HOST_REPO_DIR/$DB_NAME.tar.gz

# Remove the uncompressed SQL file (optional)
rm $HOST_BACKUP_DIR/$DB_NAME.sql

# Upload the compressed backup file to GitHub repository
cd $HOST_REPO_DIR
git add .
git commit -m "Add database backup file $DB_NAME.sql"
git push


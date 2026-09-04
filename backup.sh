#!/bin/bash
# Database Backup Script for Winky Store

BACKUP_DIR="./backup"
DATE=$(date +%Y%m%d_%H%M%S)
DB_PATH="database/database.sqlite"

# Create backup directory if not exists
mkdir -p "$BACKUP_DIR"

# Check if database exists
if [ -f "$DB_PATH" ]; then
    # Copy SQLite database
    cp "$DB_PATH" "$BACKUP_DIR/database_$DATE.sqlite"
    
    # Compress backup
    gzip "$BACKUP_DIR/database_$DATE.sqlite"
    
    echo "Backup created: $BACKUP_DIR/database_$DATE.sqlite.gz"
    
    # Remove backups older than 7 days
    find "$BACKUP_DIR" -name "database_*.sqlite.gz" -mtime +7 -delete
    
    echo "Old backups cleaned up (older than 7 days)"
else
    echo "Error: Database file not found at $DB_PATH"
    exit 1
fi
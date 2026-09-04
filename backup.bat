@echo off
:: Database Backup Script for Winky Store (Windows)
set BACKUP_DIR=.\backup
set DATE=%date:~-4%%date:~7,2%%date:~10,2%_%time:~0,2%%time:~3,2%
set DB_PATH=.\database\database.sqlite

mkdir %BACKUP_DIR%

if exist %DB_PATH% (
    copy %DB_PATH% %BACKUP_DIR%\%DATE%.sqlite
    compressed %BACKUP_DIR%\%DATE%.sqlite
    echo Backup created: %BACKUP_DIR%\%DATE%.sqlite.gz
    forfiles /p %BACKUP_DIR% /s /m "database_*.sqlite.gz" /d -7 /c "cmd /c del @path"
    echo Old backups cleaned up (older than 7 days)
) else (
    echo Error: Database file not found at %DB_PATH%
    exit /b 1
)
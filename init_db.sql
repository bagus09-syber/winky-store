-- Database initialization for Winky Store
-- This script runs on container start

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS ${DB_DATABASE:-winky_store};

-- Create user if not exists (tidak menyimpan password di sini)
-- User dan password harus di-set melalui environment variables

-- Grant privileges
GRANT ALL PRIVILEGES ON ${DB_DATABASE:-winky_store}.* TO '${DB_USERNAME:-root}'@'%';
FLUSH PRIVILEGES;
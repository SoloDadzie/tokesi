CREATE DATABASE IF NOT EXISTS tokesi_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'tokesi_user'@'localhost' IDENTIFIED BY 'tokesi_local_2026';
GRANT ALL PRIVILEGES ON tokesi_local.* TO 'tokesi_user'@'localhost';
FLUSH PRIVILEGES;

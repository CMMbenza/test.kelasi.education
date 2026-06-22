-- SQL Fix for V07: Update users.role ENUM to include 'manager'
-- Run this against your database to sync the ENUM with the PHP application roles.

ALTER TABLE `users`
MODIFY COLUMN `role` ENUM('promoteur', 'admin', 'prof', 'eleve', 'manager') NOT NULL;

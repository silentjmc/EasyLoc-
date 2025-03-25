-- Create the 'easyloc' database if it does not already exist
CREATE DATABASE IF NOT EXISTS `easyloc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
-- Select the 'easyloc' database
USE `easyloc`;

-- Create the 'contract' table if it does not already exist
CREATE TABLE IF NOT EXISTS contract (
                id INT PRIMARY KEY AUTO_INCREMENT,
                vehicle_uid CHAR(255) NOT NULL,
                customer_uid CHAR(255) NOT NULL,
                sign_datetime DATETIME NOT NULL,
                loc_begin_datetime DATETIME NOT NULL,
                loc_end_datetime DATETIME NOT NULL,
                returning_datetime DATETIME NULL,
                price DECIMAL(10,2) NOT NULL
            );

-- Create the 'billing' table if it does not already exist
CREATE TABLE IF NOT EXISTS billing (
                id INT PRIMARY KEY AUTO_INCREMENT,
                contract_id INT NOT NULL,
                amount DECIMAL(10,2) NOT NULL
            );
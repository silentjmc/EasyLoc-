-- Insert multiple records into the 'contract' table
INSERT INTO contract (id, vehicle_uid, customer_uid, sign_datetime, loc_begin_datetime, loc_end_datetime, returning_datetime, price) VALUES
(1, 'VH001', 'CU001', '2025-01-01 10:00:00', '2025-01-02 08:00:00', '2025-01-10 10:00:00', '2025-01-10 09:50:00', 500),
(2, 'VH002', 'CU002', '2025-01-01 12:00:00', '2025-01-02 09:00:00', '2025-01-15 12:00:00', '2025-01-16 14:00:00', 800), 
(3, 'VH003', 'CU003', '2025-02-10 14:00:00', '2025-02-11 10:00:00', '2025-02-18 14:00:00', '2025-02-19 16:00:00', 600), 
(4, 'VH004', 'CU001', '2025-03-01 15:00:00', '2025-03-05 08:00:00', '2025-03-12 18:00:00', '2025-03-12 17:50:00', 750), 
(5, 'VH005', 'CU004', '2025-03-10 10:00:00', '2025-03-12 09:00:00', '2025-03-20 12:00:00', '2025-03-22 14:00:00', 950), 
(6, 'VH006', 'CU005', '2025-03-15 08:00:00', '2025-03-16 07:00:00', '2025-08-24 09:00:00', NULL, 550),
(7, 'VH001', 'CU006', '2025-03-20 14:00:00', '2025-03-22 11:00:00', '2025-07-05 15:00:00', NULL, 700),
(8, 'VH002', 'CU007', '2025-03-22 17:00:00', '2025-03-24 10:00:00', '2025-07-10 16:00:00', NULL, 500),
(9, 'VH003', 'CU008', '2025-03-25 11:00:00', '2025-03-26 09:00:00', '2025-08-15 13:00:00', NULL, 600), 
(10, 'VH004', 'CU009', '2025-03-28 13:00:00', '2025-03-30 08:00:00', '2025-07-20 14:00:00', NULL, 900), 
(11, 'VH005', 'CU002', '2025-08-28 10:00:00', '2025-03-01 07:00:00', '2025-08-12 10:00:00', NULL, 800), 
(12, 'VH006', 'CU003', '2025-03-01 09:00:00', '2025-03-05 10:00:00', '2025-03-25 12:00:00', '2025-03-25 16:10:00', 1000), 
(13, 'VH001', 'CU001', '2025-03-05 08:00:00', '2025-03-07 09:00:00', '2025-08-18 11:00:00', NULL, 550),
(14, 'VH002', 'CU005', '2025-03-10 12:00:00', '2025-03-15 08:00:00', '2025-07-28 14:00:00', NULL, 850), 
(15, 'VH003', 'CU004', '2025-03-15 14:00:00', '2025-03-18 10:00:00', '2025-08-10 15:00:00', NULL, 750);

-- Insert multiple records into the 'billing' table
INSERT INTO billing (contract_id, amount) VALUES
(1, 500),
(2, 400), (2, 400),
(3, 600), 
(4, 750), 
(5, 400), (5, 400), 
(7, 350), (7, 350), 
(8, 250), (8, 200),
(9, 600), 
(10, 450), (10, 450),
(11, 300), 
(12, 500), (12, 400), 
(13, 275), (13, 275),
(14, 500), (14, 300), 
(15, 500); 

-- Select a record from the 'contract' table by id
SELECT * FROM contract WHERE id = 1;

-- Update an existing record in the 'contract' table
UPDATE contract 
SET vehicle_uid = 'VH001_updated', customer_uid = 'CU001_updated', sign_datetime = '2024-12-01 10:00:00', loc_begin_datetime = '2024-12-02 08:00:00', loc_end_datetime = '2024-12-10 10:00:00', returning_datetime = '2024-12-10 09:50:00', price = 550.00 
WHERE id = 1;

-- Delete a record from the 'contract' table
DELETE FROM contract WHERE id = 3;

-- Select a record from the 'billing' table by id
SELECT * FROM billing WHERE id = 2;

-- Update an existing record in the 'billing' table
UPDATE billing 
SET contract_id = 2, amount = 450.00 
WHERE id = 2;

-- Delete a record from the 'billing' table
DELETE FROM billing WHERE id = 4;
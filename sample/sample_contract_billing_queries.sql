-- List all contracts associated with a customer UID
SELECT * FROM contract WHERE customer_uid = 'CU001';

-- List all contracts where a certain vehicle was used
SELECT * FROM contract WHERE vehicle_uid = 'VH001';

-- List all ongoing rentals associated with a customer UID
SELECT * FROM contract 
WHERE customer_uid = 'CU001' 
AND loc_begin_datetime <= NOW() 
AND loc_end_datetime >= NOW() 
AND returning_datetime IS NULL;

-- List all overdue rentals
SELECT * FROM contract 
WHERE (returning_datetime IS NULL AND loc_end_datetime < NOW() - INTERVAL 1 HOUR) 
OR (returning_datetime IS NOT NULL AND returning_datetime > loc_end_datetime + INTERVAL 1 HOUR);

-- Count the number of overdue rentals between two given dates
SELECT COUNT(*) FROM contract 
WHERE (returning_datetime IS NULL AND loc_end_datetime < NOW() - INTERVAL 1 HOUR) 
OR (returning_datetime IS NOT NULL AND returning_datetime > loc_end_datetime + INTERVAL 1 HOUR) 
AND loc_end_datetime BETWEEN '2025-03-01' AND '2025-03-31';

-- List all payments associated with a rental
SELECT * FROM billing WHERE contract_id = 1;

-- Check if a rental has been fully paid
SELECT c.price, COALESCE(SUM(b.amount),0) AS total_payments 
FROM contract c
LEFT JOIN billing b ON c.id = b.contract_id
WHERE c.id = 1
GROUP BY c.id;

-- List all unpaid rentals
SELECT c.*, COALESCE(SUM(b.amount),0) AS total_payments
FROM contract c
LEFT JOIN billing b ON c.id = b.contract_id
GROUP BY c.id
HAVING total_payments < c.price;
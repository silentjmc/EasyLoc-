-- List all contracts associated with a customer UID
-- The placeholder :customer_uid should be replaced with the actual customer UID value when executing the query
SELECT * FROM contract WHERE customer_uid = :customer_uid;

-- List all contracts where a certain vehicle was used
-- The placeholder :vehicle_uid should be replaced with the actual vehicle UID value when executing the query
SELECT * FROM contract WHERE vehicle_uid = :vehicle_uid;

-- Retrieve all contracts grouped by vehicles
SELECT vehicle_uid, JSON_ARRAYAGG(JSON_OBJECT('id', id, 'customer_uid', customer_uid, 'sign_datetime', sign_datetime, 'loc_begin_datetime', loc_begin_datetime, 'loc_end_datetime', loc_end_datetime, 'returning_datetime', returning_datetime, 'price', price)) AS contracts 
FROM contract 
GROUP BY vehicle_uid;

-- Retrieve all contracts grouped by customers
SELECT customer_uid, JSON_ARRAYAGG(JSON_OBJECT('id', id, 'vehicle_uid', vehicle_uid, 'sign_datetime', sign_datetime, 'loc_begin_datetime', loc_begin_datetime, 'loc_end_datetime', loc_end_datetime, 'returning_datetime', returning_datetime, 'price', price)) AS contracts 
FROM contract 
GROUP BY customer_uid;

-- List all ongoing rentals associated with a customer UID
-- The placeholder :customer_uid should be replaced with the actual customer UID value when executing the query
SELECT * FROM contract 
WHERE customer_uid = :customer_uid 
AND loc_begin_datetime <= NOW() 
AND loc_end_datetime >= NOW() 
AND returning_datetime IS NULL;

-- List all overdue rentals (a rental is considered overdue if returning_datetime is more than 1 hour past loc_end_datetime)
SELECT * FROM contract 
WHERE (returning_datetime IS NULL AND loc_end_datetime < NOW() - INTERVAL 1 HOUR) 
OR (returning_datetime IS NOT NULL AND returning_datetime > loc_end_datetime + INTERVAL 1 HOUR);

-- Count the number of overdue rentals between two given dates
-- The placeholders :start and :end should be replaced with the actual start and end date values when executing the query
SELECT COUNT(*) FROM contract 
WHERE (returning_datetime IS NULL AND loc_end_datetime < NOW() - INTERVAL 1 HOUR) 
OR (returning_datetime IS NOT NULL AND returning_datetime > loc_end_datetime + INTERVAL 1 HOUR) 
AND loc_end_datetime BETWEEN :start AND :end;

-- Count the average number of overdue rentals per customer
SELECT AVG(overdue_count) AS average_overdue_count 
FROM (SELECT customer_uid, count(*) AS overdue_count FROM contract WHERE (returning_datetime IS NULL AND loc_end_datetime < NOW() - INTERVAL 1 HOUR) OR (returning_datetime IS NOT NULL AND returning_datetime > loc_end_datetime + INTERVAL 1 HOUR) group by customer_uid) AS overdue_data;

-- Get the average delay time per vehicle
SELECT vehicle_uid, AVG(TIMESTAMPDIFF(MINUTE, loc_end_datetime, returning_datetime)) AS average_time_overdue 
FROM contract WHERE returning_datetime IS NOT NULL AND returning_datetime > loc_end_datetime + INTERVAL 1 HOUR 
GROUP BY vehicle_uid;

-- List all payments associated with a rental
-- The placeholder :contract_id should be replaced with the actual contract ID value when executing the query
SELECT * FROM billing WHERE contract_id = :contract_id;

-- Check if a rental has been fully paid
-- The placeholder :contract_id should be replaced with the actual contract ID value when executing the query
SELECT c.price, COALESCE(SUM(b.amount),0) AS total_payments 
                FROM contract c
                LEFT JOIN billing b ON c.id = b.contract_id
                WHERE c.id = :contract_id
                GROUP BY c.id;

-- List all unpaid rentals
SELECT c.*, COALESCE(SUM(b.amount),0) AS total_payments
                FROM contract c
                LEFT JOIN billing b ON c.id = b.contract_id
                GROUP BY c.id
                HAVING total_payments < c.price;
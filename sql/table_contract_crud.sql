-- Insert a new record into the 'contract' table
-- The placeholders (e.g., :vehicle_uid) should be replaced with actual values when executing the query
-- The sign_datetime field is set to the current date and time using the NOW() function
INSERT INTO contract (vehicle_uid, customer_uid, sign_datetime, loc_begin_datetime, loc_end_datetime, returning_datetime, price)
VALUES (:vehicle_uid, :customer_uid, NOW(), :loc_begin_datetime, :loc_end_datetime, :returning_datetime, :price);

-- Insert a new record into the 'contract' table with a specified date and time
-- The placeholders (e.g., :vehicle_uid) should be replaced with actual values when executing the query
-- The sign_datetime field should be replaced with the desired date and time
INSERT INTO contract (vehicle_uid, customer_uid, sign_datetime, loc_begin_datetime, loc_end_datetime, returning_datetime, price)
            VALUES (:vehicle_uid, :customer_uid, :sign_datetime, :loc_begin_datetime, :loc_end_datetime, :returning_datetime, :price)

-- Update an existing record in the 'contract' table
-- The placeholders (e.g., :vehicle_uid) should be replaced with actual values when executing the query
UPDATE contract SET vehicle_uid = :vehicle_uid , customer_uid = :customer_uid, sign_datetime = :sign_datetime, loc_begin_datetime = :loc_begin_datetime, loc_end_datetime = :loc_end_datetime, returning_datetime = :returning_datetime, price = :price 
                WHERE id = :id

-- Delete a record from the 'contract' table
-- The placeholder :id should be replaced with the actual id value when executing the query
DELETE FROM contract WHERE id = :id

-- Select a record from the 'contract' table by id
-- The placeholder :id should be replaced with the actual id value when executing the query
SELECT * FROM Contract WHERE id = :id
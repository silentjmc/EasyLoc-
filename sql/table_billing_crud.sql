-- Insert a new record into the 'billing' table
-- The placeholders (e.g., :contract_id, :amount) should be replaced with actual values when executing the query
INSERT INTO billing (contract_id, amount)
            VALUES (:contract_id, :amount);

-- Update an existing record in the 'billing' table
-- The placeholders (e.g., :contract_id, :amount) should be replaced with actual values when executing the query
UPDATE billing SET contract_id = :contract_id, amount = :amount WHERE id = :id;

-- Delete a record from the 'billing' table
-- The placeholder :id should be replaced with the actual id value when executing the query
DELETE FROM billing WHERE id = :id;

-- Select a record from the 'billing' table by id
-- The placeholder :id should be replaced with the actual id value when executing the query
SELECT * FROM billing WHERE id = :id;
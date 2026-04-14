-- Align connections table with expected student-level structure
USE memoryverse;

-- Remove and recreate to ensure clean slate
DROP TABLE IF EXISTS connections;
CREATE TABLE connections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_id INT NOT NULL,
    to_id INT NOT NULL
);

-- Ensure memories table has correct column names
ALTER TABLE memories CHANGE COLUMN IF EXISTS world_x worldX FLOAT;
ALTER TABLE memories CHANGE COLUMN IF EXISTS world_y worldY FLOAT;

-- Ensure description column exists and clean up old NULL records
ALTER TABLE memories MODIFY COLUMN description TEXT;
UPDATE memories SET description = 'No description' WHERE description IS NULL OR description = '';

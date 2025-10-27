-- Fix daftar_ulangs table - add missing deleted_at column
-- Run this in MySQL: mysql -u username -p database_name < fix-daftar-ulangs.sql

USE siakad_db;

-- Add deleted_at column if not exists
ALTER TABLE daftar_ulangs
ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL AFTER updated_at;

-- Verify the column was added
DESCRIBE daftar_ulangs;

-- Mark migration as completed (so Laravel knows it's done)
INSERT IGNORE INTO migrations (migration, batch)
VALUES ('2025_10_26_195402_create_daftar_ulangs_table',
        (SELECT IFNULL(MAX(batch), 0) + 1 FROM (SELECT batch FROM migrations) as temp));

SELECT 'Fix completed successfully!' as status;

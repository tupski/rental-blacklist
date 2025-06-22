-- Backup script untuk data sebelum refactor sistem
-- Jalankan script ini untuk backup data penting sebelum menghapus sistem topup

-- Backup tabel user_balances
CREATE TABLE IF NOT EXISTS backup_user_balances AS SELECT * FROM user_balances;

-- Backup tabel balance_transactions  
CREATE TABLE IF NOT EXISTS backup_balance_transactions AS SELECT * FROM balance_transactions;

-- Backup tabel topup_requests
CREATE TABLE IF NOT EXISTS backup_topup_requests AS SELECT * FROM topup_requests;

-- Backup tabel user_unlocks
CREATE TABLE IF NOT EXISTS backup_user_unlocks AS SELECT * FROM user_unlocks;

-- Backup users dengan role 'user' yang akan dihapus
CREATE TABLE IF NOT EXISTS backup_regular_users AS 
SELECT * FROM users WHERE role = 'user';

-- Informasi statistik sebelum refactor
SELECT 
    'Total Users' as metric,
    COUNT(*) as count
FROM users
UNION ALL
SELECT 
    'Regular Users (akan dihapus)' as metric,
    COUNT(*) as count
FROM users WHERE role = 'user'
UNION ALL
SELECT 
    'Rental Owners' as metric,
    COUNT(*) as count
FROM users WHERE role = 'pengusaha_rental'
UNION ALL
SELECT 
    'Admins' as metric,
    COUNT(*) as count
FROM users WHERE role = 'admin'
UNION ALL
SELECT 
    'Total Balance Records' as metric,
    COUNT(*) as count
FROM user_balances
UNION ALL
SELECT 
    'Total Balance Transactions' as metric,
    COUNT(*) as count
FROM balance_transactions
UNION ALL
SELECT 
    'Total Topup Requests' as metric,
    COUNT(*) as count
FROM topup_requests
UNION ALL
SELECT 
    'Total User Unlocks' as metric,
    COUNT(*) as count
FROM user_unlocks;

-- Sample test data for TBL_USERS table
-- Use this to insert test users for validating the API

INSERT INTO TBL_USERS (
    no_gaji, 
    katalaluan, 
    fullname, 
    userlevel, 
    isactive, 
    ispaid, 
    hpno, 
    email, 
    created_at, 
    updated_at
) VALUES 
(
    '9327', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Ahmad bin Ali', 
    'ADMIN', 
    'ACTIVE', 
    'PAID', 
    '0123456789', 
    'ahmad@company.com', 
    NOW(), 
    NOW()
),
(
    '1234', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Siti Nurhaliza', 
    'CUSTOMER', 
    'ACTIVE', 
    'PAID', 
    '0129876543', 
    'siti@company.com', 
    NOW(), 
    NOW()
),
(
    '5678', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Muhammad Rahman', 
    'CUSTOMER', 
    'ACTIVE', 
    'NOT PAID', 
    '0111234567', 
    'rahman@company.com', 
    NOW(), 
    NOW()
),
(
    '9999', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Inactive User', 
    'CUSTOMER', 
    'NOT ACTIVE', 
    'PAID', 
    '0198765432', 
    'inactive@company.com', 
    NOW(), 
    NOW()
);

-- Test queries to verify data:
-- SELECT * FROM TBL_USERS WHERE no_gaji = '9327' AND isactive = 'ACTIVE';
-- SELECT * FROM TBL_USERS WHERE no_gaji = '1234' AND isactive = 'ACTIVE';
-- SELECT * FROM TBL_USERS WHERE no_gaji = '9999' AND isactive = 'ACTIVE'; -- Should return no results (inactive)

-- API Test Cases:
-- Valid employee IDs: 9327, 1234, 5678
-- Invalid employee ID: 9999 (inactive user)
-- Non-existent employee ID: 0000
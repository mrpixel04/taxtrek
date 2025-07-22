CREATE TABLE IF NOT EXISTS TBL_USERS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_gaji VARCHAR(50) UNIQUE NOT NULL,
    katalaluan VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    userlevel ENUM('CUSTOMER', 'ADMIN') DEFAULT 'CUSTOMER',
    last_login_datetime DATETIME NULL,
    isactive ENUM('ACTIVE', 'NOT ACTIVE') DEFAULT 'ACTIVE',
    ispaid ENUM('PAID', 'NOT PAID') DEFAULT 'NOT PAID',
    hpno VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample users
INSERT INTO TBL_USERS (no_gaji, katalaluan, fullname, userlevel, isactive, ispaid, hpno, email) VALUES
('ADMIN001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Sistem', 'ADMIN', 'ACTIVE', 'PAID', '0123456789', 'admin@taxtrek.com'),
('EMP001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pekerja Satu', 'CUSTOMER', 'ACTIVE', 'PAID', '0123456788', 'pekerja1@taxtrek.com');

-- Default password for both users is: password123 
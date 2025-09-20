-- =====================================================
-- SISTEM PERPUSTAKAAN DIGITAL SMK - DATABASE SCHEMA
-- Generated: September 2025
-- Laravel Version: 11.x
-- MySQL Version: 8.0+
-- =====================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS `laravel_perpustakaan`
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `laravel_perpustakaan`;

-- =====================================================
-- 1. USERS TABLE - Tabel Pengguna (Siswa & Admin)
-- =====================================================
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key auto increment',
    `name` VARCHAR(255) NOT NULL COMMENT 'Nama lengkap pengguna',
    `email` VARCHAR(255) NOT NULL COMMENT 'Email unik untuk login',
    `email_verified_at` TIMESTAMP NULL COMMENT 'Waktu verifikasi email',
    `password` VARCHAR(255) NOT NULL COMMENT 'Password hash (bcrypt)',
    `role` ENUM('admin', 'student') NOT NULL DEFAULT 'student' COMMENT 'Role pengguna',
    `is_approved` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Status approval admin',
    `approved_by` BIGINT UNSIGNED NULL COMMENT 'ID admin yang menyetujui',
    `approved_at` TIMESTAMP NULL COMMENT 'Waktu approval',
    `remember_token` VARCHAR(100) NULL COMMENT 'Token Remember Me',
    `created_at` TIMESTAMP NULL COMMENT 'Waktu pembuatan record',
    `updated_at` TIMESTAMP NULL COMMENT 'Waktu update terakhir',

    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`),
    KEY `users_role_is_approved_index` (`role`, `is_approved`),
    KEY `users_approved_by_foreign` (`approved_by`),

    CONSTRAINT `users_approved_by_foreign`
        FOREIGN KEY (`approved_by`)
        REFERENCES `users` (`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel pengguna sistem (siswa dan admin)';

-- =====================================================
-- 2. BOOKS TABLE - Tabel Koleksi Buku
-- =====================================================
CREATE TABLE `books` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key auto increment',
    `title` VARCHAR(255) NOT NULL COMMENT 'Judul buku',
    `author` VARCHAR(255) NOT NULL COMMENT 'Nama penulis',
    `isbn` VARCHAR(255) NOT NULL COMMENT 'ISBN unik buku',
    `published_year` INT NULL COMMENT 'Tahun terbit',
    `quantity` INT NOT NULL DEFAULT 0 COMMENT 'Total eksemplar',
    `available_quantity` INT NOT NULL DEFAULT 0 COMMENT 'Eksemplar tersedia',
    `created_at` TIMESTAMP NULL COMMENT 'Waktu pembuatan record',
    `updated_at` TIMESTAMP NULL COMMENT 'Waktu update terakhir',

    PRIMARY KEY (`id`),
    UNIQUE KEY `books_isbn_unique` (`isbn`),
    KEY `books_title_index` (`title`),
    KEY `books_author_index` (`author`),
    KEY `books_available_quantity_index` (`available_quantity`),

    CONSTRAINT `books_quantity_check` CHECK (`available_quantity` <= `quantity`),
    CONSTRAINT `books_quantity_positive` CHECK (`quantity` >= 0),
    CONSTRAINT `books_available_positive` CHECK (`available_quantity` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel koleksi buku perpustakaan';

-- =====================================================
-- 3. LOANS TABLE - Tabel Transaksi Peminjaman
-- =====================================================
CREATE TABLE `loans` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key auto increment',
    `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID user peminjam',
    `book_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID buku yang dipinjam',
    `borrowed_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Tanggal peminjaman',
    `due_date` TIMESTAMP NOT NULL COMMENT 'Tanggal jatuh tempo',
    `returned_date` TIMESTAMP NULL COMMENT 'Tanggal pengembalian (NULL = belum dikembalikan)',
    `created_at` TIMESTAMP NULL COMMENT 'Waktu pembuatan record',
    `updated_at` TIMESTAMP NULL COMMENT 'Waktu update terakhir',

    PRIMARY KEY (`id`),
    KEY `loans_user_id_index` (`user_id`),
    KEY `loans_book_id_index` (`book_id`),
    KEY `loans_due_date_index` (`due_date`),
    KEY `loans_returned_date_index` (`returned_date`),
    KEY `loans_active_index` (`user_id`, `returned_date`),
    KEY `loans_overdue_index` (`due_date`, `returned_date`),

    CONSTRAINT `loans_user_id_foreign`
        FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT `loans_book_id_foreign`
        FOREIGN KEY (`book_id`)
        REFERENCES `books` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT `loans_due_date_check` CHECK (`due_date` > `borrowed_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel transaksi peminjaman buku';

-- =====================================================
-- 4. TWO FACTOR TOKENS TABLE - Tabel Token 2FA
-- =====================================================
CREATE TABLE `two_factor_tokens` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key auto increment',
    `email` VARCHAR(255) NOT NULL COMMENT 'Email tujuan token',
    `token` VARCHAR(6) NOT NULL COMMENT 'Kode 6 digit',
    `type` ENUM('login', 'register') NOT NULL COMMENT 'Jenis token',
    `expires_at` TIMESTAMP NOT NULL COMMENT 'Waktu kadaluarsa',
    `used` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Status penggunaan token',
    `created_at` TIMESTAMP NULL COMMENT 'Waktu pembuatan record',
    `updated_at` TIMESTAMP NULL COMMENT 'Waktu update terakhir',

    PRIMARY KEY (`id`),
    KEY `two_factor_tokens_email_token_type_index` (`email`, `token`, `type`),
    KEY `two_factor_tokens_expires_at_index` (`expires_at`),
    KEY `two_factor_tokens_cleanup_index` (`expires_at`, `used`),

    CONSTRAINT `two_factor_tokens_token_format` CHECK (CHAR_LENGTH(`token`) = 6 AND `token` REGEXP '^[0-9]{6}$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel token 2FA untuk verifikasi email';

-- =====================================================
-- 5. PASSWORD RESET TOKENS TABLE - Laravel System
-- =====================================================
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL COMMENT 'Email untuk reset password',
    `token` VARCHAR(255) NOT NULL COMMENT 'Reset token',
    `created_at` TIMESTAMP NULL COMMENT 'Waktu pembuatan token',

    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel token reset password Laravel';

-- =====================================================
-- 6. SESSIONS TABLE - Laravel Session Management
-- =====================================================
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL COMMENT 'Session ID',
    `user_id` BIGINT UNSIGNED NULL COMMENT 'ID user yang login',
    `ip_address` VARCHAR(45) NULL COMMENT 'IP Address user',
    `user_agent` TEXT NULL COMMENT 'Browser user agent',
    `payload` LONGTEXT NOT NULL COMMENT 'Session data payload',
    `last_activity` INT NOT NULL COMMENT 'Timestamp aktivitas terakhir',

    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel session management Laravel';

-- =====================================================
-- 7. CACHE TABLES - Laravel Cache System
-- =====================================================
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL COMMENT 'Cache key',
    `value` MEDIUMTEXT NOT NULL COMMENT 'Cache value',
    `expiration` INT NOT NULL COMMENT 'Waktu kadaluarsa cache',

    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel cache Laravel';

CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL COMMENT 'Lock key',
    `owner` VARCHAR(255) NOT NULL COMMENT 'Lock owner',
    `expiration` INT NOT NULL COMMENT 'Waktu kadaluarsa lock',

    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel cache locks Laravel';

-- =====================================================
-- 8. QUEUE TABLES - Laravel Queue System
-- =====================================================
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Job ID',
    `queue` VARCHAR(255) NOT NULL COMMENT 'Nama queue',
    `payload` LONGTEXT NOT NULL COMMENT 'Job payload',
    `attempts` TINYINT UNSIGNED NOT NULL COMMENT 'Jumlah percobaan',
    `reserved_at` INT UNSIGNED NULL COMMENT 'Waktu reserved',
    `available_at` INT UNSIGNED NOT NULL COMMENT 'Waktu tersedia',
    `created_at` INT UNSIGNED NOT NULL COMMENT 'Waktu pembuatan',

    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel antrian job Laravel';

CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL COMMENT 'Batch ID',
    `name` VARCHAR(255) NOT NULL COMMENT 'Nama batch',
    `total_jobs` INT NOT NULL COMMENT 'Total job dalam batch',
    `pending_jobs` INT NOT NULL COMMENT 'Job yang menunggu',
    `failed_jobs` INT NOT NULL COMMENT 'Job yang gagal',
    `failed_job_ids` LONGTEXT NOT NULL COMMENT 'ID job yang gagal',
    `options` MEDIUMTEXT NULL COMMENT 'Opsi batch',
    `cancelled_at` INT NULL COMMENT 'Waktu pembatalan',
    `created_at` INT NOT NULL COMMENT 'Waktu pembuatan',
    `finished_at` INT NULL COMMENT 'Waktu selesai',

    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel batch job Laravel';

CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Failed job ID',
    `uuid` VARCHAR(255) NOT NULL COMMENT 'UUID unik job',
    `connection` TEXT NOT NULL COMMENT 'Database connection',
    `queue` TEXT NOT NULL COMMENT 'Queue name',
    `payload` LONGTEXT NOT NULL COMMENT 'Job payload',
    `exception` LONGTEXT NOT NULL COMMENT 'Exception message',
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu gagal',

    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel job yang gagal Laravel';

-- =====================================================
-- SAMPLE DATA INSERTION
-- =====================================================

-- Insert Default Admin User
INSERT INTO `users` (
    `name`, `email`, `email_verified_at`, `password`,
    `role`, `is_approved`, `created_at`, `updated_at`
) VALUES (
    'Super Admin',
    'admin@smk.sch.id',
    NOW(),
    '$2y$12$LQv3c1yqBwlfXo5TLtcHye8v2TkYF.0qzDo6iOCQfhz7aAXrxgqC6', -- password: 'password'
    'admin',
    1,
    NOW(),
    NOW()
);

-- Insert Sample Students
INSERT INTO `users` (
    `name`, `email`, `email_verified_at`, `password`,
    `role`, `is_approved`, `created_at`, `updated_at`
) VALUES
('Ahmad Rizki Pratama', 'ahmad.rizki@student.smk.id', NOW(), '$2y$12$LQv3c1yqBwlfXo5TLtcHye8v2TkYF.0qzDo6iOCQfhz7aAXrxgqC6', 'student', 1, NOW(), NOW()),
('Siti Nurhaliza', 'siti.nurhaliza@student.smk.id', NOW(), '$2y$12$LQv3c1yqBwlfXo5TLtcHye8v2TkYF.0qzDo6iOCQfhz7aAXrxgqC6', 'student', 1, NOW(), NOW()),
('Budi Santoso', 'budi.santoso@student.smk.id', NOW(), '$2y$12$LQv3c1yqBwlfXo5TLtcHye8v2TkYF.0qzDo6iOCQfhz7aAXrxgqC6', 'student', 1, NOW(), NOW()),
('Rini Sartika', 'rini.sartika@student.smk.id', NOW(), '$2y$12$LQv3c1yqBwlfXo5TLtcHye8v2TkYF.0qzDo6iOCQfhz7aAXrxgqC6', 'student', 1, NOW(), NOW());

-- Insert Sample Books
INSERT INTO `books` (
    `title`, `author`, `isbn`, `published_year`,
    `quantity`, `available_quantity`, `created_at`, `updated_at`
) VALUES
('Pemrograman Web dengan Laravel 11', 'Andi Prasetyo', '978-623-001-001-1', 2024, 5, 3, NOW(), NOW()),
('Database MySQL untuk Pemula', 'Sari Indah Permata', '978-623-001-002-8', 2023, 3, 2, NOW(), NOW()),
('Algoritma dan Struktur Data', 'Dr. Budi Raharjo', '978-623-001-003-5', 2023, 4, 4, NOW(), NOW()),
('Jaringan Komputer Dasar', 'Eko Nugroho, S.Kom', '978-623-001-004-2', 2022, 2, 1, NOW(), NOW()),
('HTML CSS JavaScript Modern', 'Rini Sartika', '978-623-001-005-9', 2024, 6, 5, NOW(), NOW()),
('Python untuk Data Science', 'Ahmad Rahman', '978-623-001-006-6', 2024, 3, 3, NOW(), NOW()),
('Mobile App Development', 'Sinta Dewi', '978-623-001-007-3', 2023, 2, 2, NOW(), NOW()),
('Cyber Security Fundamentals', 'Arief Budiman', '978-623-001-008-0', 2024, 4, 3, NOW(), NOW()),
('UI/UX Design Principles', 'Maya Sari', '978-623-001-009-7', 2023, 3, 2, NOW(), NOW()),
('Cloud Computing Essentials', 'Rahman Ali', '978-623-001-010-3', 2024, 2, 1, NOW(), NOW());

-- Insert Sample Active Loans
INSERT INTO `loans` (
    `user_id`, `book_id`, `borrowed_date`, `due_date`,
    `returned_date`, `created_at`, `updated_at`
) VALUES
(2, 1, '2025-09-15 10:30:00', '2025-09-29 23:59:59', NULL, '2025-09-15 10:30:00', '2025-09-15 10:30:00'),
(3, 2, '2025-09-18 14:20:00', '2025-10-02 23:59:59', NULL, '2025-09-18 14:20:00', '2025-09-18 14:20:00'),
(4, 4, '2025-09-20 09:15:00', '2025-10-04 23:59:59', NULL, '2025-09-20 09:15:00', '2025-09-20 09:15:00'),
(5, 8, '2025-09-22 11:45:00', '2025-10-06 23:59:59', NULL, '2025-09-22 11:45:00', '2025-09-22 11:45:00'),
(2, 9, '2025-09-23 15:20:00', '2025-10-07 23:59:59', NULL, '2025-09-23 15:20:00', '2025-09-23 15:20:00');

-- Insert Sample Returned Loans (Historical Data)
INSERT INTO `loans` (
    `user_id`, `book_id`, `borrowed_date`, `due_date`,
    `returned_date`, `created_at`, `updated_at`
) VALUES
(3, 1, '2025-09-01 11:00:00', '2025-09-15 23:59:59', '2025-09-14 16:30:00', '2025-09-01 11:00:00', '2025-09-14 16:30:00'),
(2, 3, '2025-09-05 13:45:00', '2025-09-19 23:59:59', '2025-09-18 10:20:00', '2025-09-05 13:45:00', '2025-09-18 10:20:00'),
(4, 5, '2025-09-08 08:30:00', '2025-09-22 23:59:59', '2025-09-21 17:15:00', '2025-09-08 08:30:00', '2025-09-21 17:15:00'),
(5, 6, '2025-09-10 14:00:00', '2025-09-24 23:59:59', '2025-09-23 12:45:00', '2025-09-10 14:00:00', '2025-09-23 12:45:00'),
(3, 7, '2025-09-12 16:20:00', '2025-09-26 23:59:59', '2025-09-25 09:30:00', '2025-09-12 16:20:00', '2025-09-25 09:30:00');

-- =====================================================
-- USEFUL VIEWS FOR REPORTING
-- =====================================================

-- View: Active Loans dengan Detail User dan Book
CREATE VIEW `active_loans_view` AS
SELECT
    l.id as loan_id,
    u.name as user_name,
    u.email as user_email,
    u.role as user_role,
    b.title as book_title,
    b.author as book_author,
    b.isbn as book_isbn,
    l.borrowed_date,
    l.due_date,
    DATEDIFF(l.due_date, NOW()) as days_remaining,
    CASE
        WHEN l.due_date < NOW() THEN 'OVERDUE'
        WHEN DATEDIFF(l.due_date, NOW()) <= 3 THEN 'DUE_SOON'
        ELSE 'NORMAL'
    END as status
FROM loans l
INNER JOIN users u ON l.user_id = u.id
INNER JOIN books b ON l.book_id = b.id
WHERE l.returned_date IS NULL
ORDER BY l.due_date ASC;

-- View: Book Statistics
CREATE VIEW `book_statistics_view` AS
SELECT
    b.id,
    b.title,
    b.author,
    b.isbn,
    b.quantity,
    b.available_quantity,
    (b.quantity - b.available_quantity) as currently_borrowed,
    COUNT(l.id) as total_loans,
    COUNT(CASE WHEN l.returned_date IS NULL THEN 1 END) as active_loans,
    COALESCE(AVG(DATEDIFF(l.returned_date, l.borrowed_date)), 0) as avg_loan_days
FROM books b
LEFT JOIN loans l ON b.id = l.book_id
GROUP BY b.id, b.title, b.author, b.isbn, b.quantity, b.available_quantity
ORDER BY total_loans DESC;

-- View: User Loan Summary
CREATE VIEW `user_loan_summary_view` AS
SELECT
    u.id,
    u.name,
    u.email,
    u.role,
    COUNT(l.id) as total_loans,
    COUNT(CASE WHEN l.returned_date IS NULL THEN 1 END) as active_loans,
    COUNT(CASE WHEN l.due_date < NOW() AND l.returned_date IS NULL THEN 1 END) as overdue_loans,
    MAX(l.borrowed_date) as last_borrow_date
FROM users u
LEFT JOIN loans l ON u.id = l.user_id
WHERE u.role = 'student'
GROUP BY u.id, u.name, u.email, u.role
ORDER BY total_loans DESC;

-- =====================================================
-- STORED PROCEDURES FOR COMMON OPERATIONS
-- =====================================================

DELIMITER $$

-- Procedure: Borrow Book
CREATE PROCEDURE `BorrowBook`(
    IN p_user_id BIGINT UNSIGNED,
    IN p_book_id BIGINT UNSIGNED,
    IN p_due_date TIMESTAMP,
    OUT p_result VARCHAR(100)
)
BEGIN
    DECLARE v_available_qty INT DEFAULT 0;
    DECLARE v_user_active_loans INT DEFAULT 0;
    DECLARE v_user_role VARCHAR(20);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_result = 'ERROR: Transaction failed';
    END;

    START TRANSACTION;

    -- Check user role and active loans
    SELECT role INTO v_user_role FROM users WHERE id = p_user_id;
    SELECT COUNT(*) INTO v_user_active_loans
    FROM loans
    WHERE user_id = p_user_id AND returned_date IS NULL;

    -- Check book availability
    SELECT available_quantity INTO v_available_qty
    FROM books
    WHERE id = p_book_id FOR UPDATE;

    -- Validate constraints
    IF v_available_qty <= 0 THEN
        SET p_result = 'ERROR: Book not available';
        ROLLBACK;
    ELSEIF v_user_role = 'student' AND v_user_active_loans >= 5 THEN
        SET p_result = 'ERROR: Maximum loan limit reached (5 books)';
        ROLLBACK;
    ELSE
        -- Create loan record
        INSERT INTO loans (user_id, book_id, borrowed_date, due_date, created_at, updated_at)
        VALUES (p_user_id, p_book_id, NOW(), p_due_date, NOW(), NOW());

        -- Update book availability
        UPDATE books
        SET available_quantity = available_quantity - 1,
            updated_at = NOW()
        WHERE id = p_book_id;

        SET p_result = 'SUCCESS: Book borrowed successfully';
        COMMIT;
    END IF;
END$$

-- Procedure: Return Book
CREATE PROCEDURE `ReturnBook`(
    IN p_loan_id BIGINT UNSIGNED,
    OUT p_result VARCHAR(100)
)
BEGIN
    DECLARE v_book_id BIGINT UNSIGNED;
    DECLARE v_is_returned INT DEFAULT 0;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_result = 'ERROR: Transaction failed';
    END;

    START TRANSACTION;

    -- Check if loan exists and not returned
    SELECT book_id,
           CASE WHEN returned_date IS NULL THEN 0 ELSE 1 END
    INTO v_book_id, v_is_returned
    FROM loans
    WHERE id = p_loan_id;

    IF v_book_id IS NULL THEN
        SET p_result = 'ERROR: Loan not found';
        ROLLBACK;
    ELSEIF v_is_returned = 1 THEN
        SET p_result = 'ERROR: Book already returned';
        ROLLBACK;
    ELSE
        -- Update loan record
        UPDATE loans
        SET returned_date = NOW(),
            updated_at = NOW()
        WHERE id = p_loan_id;

        -- Update book availability
        UPDATE books
        SET available_quantity = available_quantity + 1,
            updated_at = NOW()
        WHERE id = v_book_id;

        SET p_result = 'SUCCESS: Book returned successfully';
        COMMIT;
    END IF;
END$$

DELIMITER ;

-- =====================================================
-- TRIGGERS FOR DATA INTEGRITY
-- =====================================================

DELIMITER $$

-- Trigger: Auto cleanup expired 2FA tokens
CREATE TRIGGER `cleanup_expired_tokens`
AFTER INSERT ON `two_factor_tokens`
FOR EACH ROW
BEGIN
    DELETE FROM two_factor_tokens
    WHERE expires_at < DATE_SUB(NOW(), INTERVAL 1 DAY);
END$$

-- Trigger: Validate book availability before loan
CREATE TRIGGER `validate_loan_before_insert`
BEFORE INSERT ON `loans`
FOR EACH ROW
BEGIN
    DECLARE v_available_qty INT DEFAULT 0;

    SELECT available_quantity INTO v_available_qty
    FROM books WHERE id = NEW.book_id;

    IF v_available_qty <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Book is not available for borrowing';
    END IF;
END$$

DELIMITER ;

-- =====================================================
-- INDEXES FOR PERFORMANCE OPTIMIZATION
-- =====================================================

-- Additional performance indexes
CREATE INDEX `idx_loans_user_active` ON `loans` (`user_id`, `returned_date`, `due_date`);
CREATE INDEX `idx_loans_book_active` ON `loans` (`book_id`, `returned_date`);
CREATE INDEX `idx_loans_overdue` ON `loans` (`due_date`, `returned_date`);
CREATE INDEX `idx_users_role_status` ON `users` (`role`, `is_approved`, `created_at`);
CREATE INDEX `idx_books_search` ON `books` (`title`, `author`);

-- Full-text search index for books (optional)
-- ALTER TABLE `books` ADD FULLTEXT(`title`, `author`);

-- =====================================================
-- MAINTENANCE EVENTS (Optional - requires EVENT scheduler)
-- =====================================================

-- Enable event scheduler (run manually if needed)
-- SET GLOBAL event_scheduler = ON;

-- Event: Clean expired 2FA tokens daily
DELIMITER $$
CREATE EVENT IF NOT EXISTS `clean_expired_tokens`
ON SCHEDULE EVERY 1 DAY
STARTS '2025-01-01 02:00:00'
DO
BEGIN
    DELETE FROM two_factor_tokens
    WHERE expires_at < DATE_SUB(NOW(), INTERVAL 1 DAY);
END$$
DELIMITER ;

-- Event: Clean old sessions weekly
DELIMITER $$
CREATE EVENT IF NOT EXISTS `clean_old_sessions`
ON SCHEDULE EVERY 1 WEEK
STARTS '2025-01-01 03:00:00'
DO
BEGIN
    DELETE FROM sessions
    WHERE last_activity < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY));
END$$
DELIMITER ;

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Verify database structure
SELECT
    TABLE_NAME,
    TABLE_ROWS,
    DATA_LENGTH,
    INDEX_LENGTH,
    TABLE_COMMENT
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'laravel_perpustakaan'
ORDER BY TABLE_NAME;

-- Verify foreign key constraints
SELECT
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'laravel_perpustakaan'
  AND REFERENCED_TABLE_NAME IS NOT NULL;

-- =====================================================
-- END OF SCHEMA
-- =====================================================

-- Database schema created successfully!
-- Don't forget to:
-- 1. Configure Laravel .env with correct database credentials
-- 2. Run 'php artisan migrate' to sync Laravel migrations
-- 3. Run 'php artisan db:seed' if you have seeders
-- 4. Test the application thoroughly
-- 5. Set up regular backups for production

SELECT 'Database schema for Sistem Perpustakaan Digital SMK created successfully!' as status;
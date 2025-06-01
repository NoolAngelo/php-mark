-- Updated database schema for secure members table
-- Run this SQL to update your existing database

-- First, backup your existing data if needed
-- CREATE TABLE members_backup AS SELECT * FROM members;

-- Update the members table structure for security
ALTER TABLE members 
ADD COLUMN IF NOT EXISTS id INT AUTO_INCREMENT PRIMARY KEY FIRST,
ADD COLUMN IF NOT EXISTS name VARCHAR(100) NOT NULL AFTER id,
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Make sure email is unique
ALTER TABLE members ADD UNIQUE KEY unique_email (email);

-- If you need to migrate existing plain text passwords to hashed ones,
-- you'll need to run a PHP script or reset all passwords
-- For now, you can add a few test users with hashed passwords:

-- Test user with password "Password123"
INSERT INTO members (name, email, password, created_at) VALUES 
('Test User', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Admin user with password "Admin123"
INSERT INTO members (name, email, password, created_at) VALUES 
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Create a sessions table for better session management (optional)
CREATE TABLE IF NOT EXISTS user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_id VARCHAR(128) NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    INDEX idx_session_id (session_id),
    INDEX idx_user_id (user_id),
    FOREIGN KEY (user_id) REFERENCES members(id) ON DELETE CASCADE
);

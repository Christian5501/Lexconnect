-- LexConnect Database Setup
-- Run this SQL in phpMyAdmin or MySQL command line

CREATE DATABASE IF NOT EXISTS lexconnect_db;
USE lexconnect_db;

-- Users table (stores clients, secretaries, and lawyers)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('client', 'secretary', 'lawyer') NOT NULL DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Appointments table
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    lawyer_id INT DEFAULT NULL,
    secretary_id INT DEFAULT NULL,
    problem_description TEXT NOT NULL,
    status ENUM('pending', 'forwarded', 'confirmed', 'done') DEFAULT 'pending',
    consultation_datetime DATETIME DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(id),
    FOREIGN KEY (lawyer_id) REFERENCES users(id),
    FOREIGN KEY (secretary_id) REFERENCES users(id)
);

-- Insert sample accounts (password for all = "password123")
-- Password hash for "password123"
INSERT INTO users (full_name, email, password, phone, role) VALUES
('Sarah Nzeyimana', 'secretary@lexconnect.rw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0788000001', 'secretary'),
('Advocate Jean Habimana', 'lawyer@lexconnect.rw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0788000002', 'lawyer'),
('John Client', 'client@lexconnect.rw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0788000003', 'client');

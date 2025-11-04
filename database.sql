CREATE DATABASE registration_db;
USE registration_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    birth_date DATE,
    birth_time TIME,
    birth_month VARCHAR(7),
    birth_week VARCHAR(7),
    birth_datetime DATETIME,
    website_url VARCHAR(255),
    gender ENUM('male', 'female') NOT NULL,
    profile_color VARCHAR(7) DEFAULT '#000000',
    salary_range INT DEFAULT 50000,
    bio TEXT,
    profile_image VARCHAR(255),
    newsletter BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
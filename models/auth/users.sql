CREATE TABLE users (
    userID VARCHAR(50) PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(15) UNIQUE,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,  -- Store hashed and salted password
    role ENUM('admin', 'moderator', 'coordinator', 'student', 'user') DEFAULT 'user',
    status ENUM('verified', 'pending', 'deleted') DEFAULT 'pending',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

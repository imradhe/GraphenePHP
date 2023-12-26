CREATE TABLE users (
    email VARCHAR(255) PRIMARY KEY,
    phone VARCHAR(10),
    name VARCHAR(255),
    password VARCHAR(255),
    role ENUM('admin', 'moderator', 'coordinator', 'user') DEFAULT 'user',
    status ENUM('verified', 'pending', 'deleted') 
);
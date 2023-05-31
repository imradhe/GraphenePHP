CREATE TABLE users (
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    phone varchar(10) NOT NULL,
    password varchar(255) NOT NULL,
    role ENUM ('user', 'moderator', 'admin') DEFAULT 'user',
    status int DEFAULT 0,
    PRIMARY KEY (email)
);
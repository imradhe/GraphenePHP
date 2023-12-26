CREATE TABLE logs (
    loginId VARCHAR(255) PRIMARY KEY,
    email VARCHAR(255),
    loggedInAt DATETIME,
    ip VARCHAR(255),
    os VARCHAR(255),
    browser VARCHAR(255),
    loggedOutAt DATETIME
);
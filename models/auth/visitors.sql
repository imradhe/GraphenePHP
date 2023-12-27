-- visitors Table
CREATE TABLE visitors (
    visitorId VARCHAR(255) PRIMARY KEY,
    ip VARCHAR(255) NOT NULL,
    os VARCHAR(255),
    browser VARCHAR(255),
    visitedAt DATETIME,
    url VARCHAR(255)
);

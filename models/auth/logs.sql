CREATE TABLE logs (
    loginID varchar(32) NOT NULL,
    email varchar(30) NOT NULL,
    loggedinat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip varchar(255) NOT NULL,
    os varchar(255) NOT NULL,
    browser varchar(255) NOT NULL,
    loggedout int DEFAULT 0,
    loggedoutat TIMESTAMP NULL,
    FOREIGN KEY (email) REFERENCES users(email),
    PRIMARY KEY (loginID)
);
INSERT INTO users (`name`, `email`, `password`, `phone`, `role`)
VALUES (
        "Arun",
        "arun@graphenephp.com",
        "900150983cd24fb0d6963f7d28e17f72",
        "9121325466",
        "admin"
    )
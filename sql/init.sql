CREATE TABLE pays (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE region (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    pays_id INT,
    FOREIGN KEY (pays_id) REFERENCES pays(id)
);

CREATE TABLE info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    region_id INT,
    FOREIGN KEY (region_id) REFERENCES region(id)
);  

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    pwd VARCHAR(255) NOT NULL
);

CREATE TABLE image_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idinfo INT NOT NULL,           
    path VARCHAR(255) NOT NULL,    
    FOREIGN KEY (idinfo) REFERENCES info(id) ON DELETE CASCADE
);

insert into users (username, pwd) values ('admin', '$2y$10$HGEZJYPKp0Hymje1JMGxgeV2hf87qT.sdHhMEpyFxrhfToiOGSnDm');
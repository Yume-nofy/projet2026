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
    slug VARCHAR(255) ,
    meta_description VARCHAR(160),
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
    image_alt VARCHAR(100),   
    FOREIGN KEY (idinfo) REFERENCES info(id) ON DELETE CASCADE
);

insert into users (username, pwd) values ('admin', '$2y$10$HGEZJYPKp0Hymje1JMGxgeV2hf87qT.sdHhMEpyFxrhfToiOGSnDm');


-- Insertion du pays
INSERT INTO pays (nom) VALUES ('Iran');

-- Insertion des régions (provinces où des événements majeurs ont eu lieu)
INSERT INTO region (nom, pays_id) VALUES 
('Province de Téhéran', 1),
('Province d''Ispahan', 1),
('Province de Fars (Chiraz)', 1),
('Province du Khorasan Razavi (Machhad)', 1),
('Province de Kermanshah', 1),
('Province du Kurdistan', 1),
('Province du Golestan', 1),
('Province du Khuzestan', 1);

-- Insertion des utilisateurs
-- INSERT INTO users (username, pwd) VALUES 
-- ('redacteur_actualites', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8');
-- (le mot de passe est "password" - à remplacer par un hash sécurisé en production)

-- ============================================================
-- ACTUALITÉS RÉELLES : MANIFESTATIONS MASSIVES (décembre 2025 - mars 2026)
-- ============================================================

INSERT INTO info (titre, slug, contenu, date_publication, region_id) VALUES 
('Des manifestations massives éclatent dans les 31 provinces d''Iran à partir du 28 décembre 2025', 
 'manifestations-massives-iran-28-decembre-2025',
 'Les manifestations, déclenchées initialement par des griefs économiques, ont vu le rial iranien chuter à un niveau record d''environ 1,45 million pour un dollar américain, avec une inflation dépassant les 40 % et des prix alimentaires en hausse de plus de 70 %. Le mouvement a débuté avec une grève des commerçants du Grand Bazar de Téhéran, puis s''est étendu à tout le pays. Les revendications des manifestants ont évolué de réformes économiques à des slogans politiques appelant au renversement du guide suprême Ali Khamenei et du régime clérical.', 
 '2026-01-05 10:00:00', 1),

('L''Iran impose une coupure nationale d''Internet le 8 janvier 2026', 
 'iran-coupure-internet-8-janvier-2026',
 'Les autorités iraniennes ont coupé l''accès à Internet sur tout le territoire national pour empêcher la communauté internationale de témoigner d''éventuelles atrocités et perturber la coordination des manifestations pacifiques. Cette "coupure numérique" a rendu difficile l''évaluation de l''ampleur continue des manifestations par les observateurs internationaux. Le réseau Starlink d''Elon Musk a tenté de rétablir l''accès à Internet. Fin janvier 2026, l''accès à Internet reste limité malgré une demande du président Pezeshkian de rétablir la connexion.', 
 '2026-01-09 08:30:00', 1),

('Le guide suprême Khamenei reconnaît "plusieurs milliers" de morts dans la répression', 
 'khamenei-reconnaissance-morts-manifestations',
 'L''ayatollah Ali Khamenei a reconnu que "plusieurs milliers" de personnes ont été tuées pendant les manifestations, accusant les États-Unis et Israël d''avoir incité aux troubles. La Fondation des martyrs, financée par l''État, a fait état d''environ 3 117 personnes tuées, tandis que les organisations de défense des droits humains avancent des chiffres plus élevés. Iran Human Rights a rapporté qu''au moins 3 428 manifestants ont été tués au 22 janvier 2026, avec environ 40 000 arrestations.', 
 '2026-01-20 14:15:00', 1),

('La rapporteuse spéciale de l''ONU fait état d''une "répression généralisée" incluant des raids dans les hôpitaux', 
 'onu-rapport-repression-generale-iran',
 'La rapporteuse spéciale de l''ONU Mai Sato a présenté un rapport au Conseil des droits de l''homme de l''ONU détaillant que les forces de sécurité ont pris d''assaut les hôpitaux et arrêté les manifestants blessés directement de leurs lits. Les autorités ont émis des directives obligeant les hôpitaux à signaler les manifestants blessés et ont arrêté le personnel médical qui les soignait, "criminalisant ainsi l''acte de sauver des vies". Près de 100 "confessions forcées" ont été diffusées sur la télévision d''État.', 
 '2026-03-16 09:00:00', 1);
-- ============================================================
-- IMAGES (chemins représentatifs - les images réelles proviendraient des agences de presse)
-- ============================================================

-- INSERT INTO image_info (idinfo, path) VALUES 
-- (1, '/images/iran/2026/manifestations_teheran_grand_bazar_jan2026.jpg'),
-- (1, '/images/iran/2026/carte_manifestations_8_janvier_2026.jpg'),
-- (2, '/images/iran/2026/coupure_internet_iran_jan2026.jpg'),
-- (3, '/images/iran/2026/khamenei_declaration_jan2026.jpg'),
-- (4, '/images/iran/2026/rapport_onu_repression_iran_mar2026.jpg'),
-- (5, '/images/iran/2026/frappes_us_iran_28_fevrier_2026.jpg'),
-- (6, '/images/iran/2026/riposte_missile_iran_mar2026.jpg'),
-- (7, '/images/iran/2026/mojtaba_khamenei_nomination_mar2026.jpg'),
-- (8, '/images/iran/2026/centrale_nucleaire_natanz.jpg'),
-- (9, '/images/iran/2026/sardar_azmoun_iran_football.jpg'),
-- (10, '/images/iran/2026/sanctions_ue_iran_mar2026.jpg'),
-- (11, '/images/iran/2026/renseignement_us_iran_radical_mar2026.jpg');
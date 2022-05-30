

CREATE DATABASE IF NOT EXISTS projetfs;

-- Création d'un utilisateur mysql 
CREATE USER IF NOT EXISTS student@localhost IDENTIFIED BY "password"; 

-- On donne les droits à l'utilisateur student sur tous les objets de la base infotech
GRANT ALL ON projetfs.* TO student@localhost; 

USE projetfs; 

CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    email VARCHAR(255) UNIQUE, 
    password VARCHAR(255)
); 

CREATE TABLE article (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    titre VARCHAR(255),
    corps TEXT, 
    created_at DATE, 
    updated_at DATE, 
    id_utilisateur INT, 
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
);
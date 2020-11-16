CREATE database mygarden;

USE mygarden;

CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    email VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE gardens (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(80) NOT NULL,
    width SMALLINT UNSIGNED NOT NULL,
    height SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE plants (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    english_name VARCHAR(80) NOT NULL,
    latin_name VARCHAR(255) NULL,
    PRIMARY KEY (id)
);

CREATE TABLE gardens_plants (
    garden_id INT NOT NULL,
    plant_id INT NOT NULL,
    x_coordinate SMALLINT UNSIGNED NOT NULL,
    y_coordinate SMALLINT UNSIGNED NOT NULL
);
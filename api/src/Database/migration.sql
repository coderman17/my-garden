-- noinspection SpellCheckingInspectionForFile

CREATE database my_garden;

USE my_garden;

CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    email VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE gardens (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    name VARCHAR(80) NOT NULL,
    width SMALLINT UNSIGNED NOT NULL,
    height SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE plants (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    english_name VARCHAR(80) NOT NULL,
    latin_name VARCHAR(255) NOT NULL,
    image_link VARCHAR(500) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE gardens_plants (
    garden_id INT UNSIGNED NOT NULL,
    plant_id INT UNSIGNED NOT NULL,
    x_coordinate SMALLINT UNSIGNED NOT NULL,
    y_coordinate SMALLINT UNSIGNED NOT NULL
);

INSERT INTO users (username, email, password)
VALUES
    ('dan', 'dan@email.com', '$2y$10$W4ixd.rF03iRVuECIP1Hu.yVH/eyStgiKgTmOqqEHBI9vuSoYnxvi');

INSERT INTO plants (user_id, english_name, latin_name, image_link)
VALUES
    (1, 'Rose', 'Rosa kordesii', 'https://static3.depositphotos.com/1001651/136/i/600/depositphotos_1365974-stock-photo-roses-bush.jpg'),
    (1, 'Cherry Tree', 'Prunus serrula', 'https://c8.alamy.com/comp/PGM15F/prunus-serrula-an-ornamental-tree-with-distinctive-red-shiny-bark-also-known-as-the-tibetan-cherry-PGM15F.jpg'),
    (1, 'Holly', 'Ilex aquifolium', 'https://cdn.shopify.com/s/files/1/0212/1030/0480/products/nelliestevenshollyTree_1.png'),
    (1, 'Grass', 'Lolium Perenne', 'https://hips.hearstapps.com/hmg-prod.s3.amazonaws.com/images/background-of-green-and-healthy-grass-royalty-free-image-1586800097.jpg'),
    (1, 'English Vine', 'Toxicodendron radicans', 'https://www.thespruce.com/thmb/3I4dYlSaqS0OmA7yMH4NyJ_ExnU=/4001x3001/smart/filters:no_upscale()/english-ivy-plants-2132215-hero-03-0a8ee662826b418d86a5b6e08ee7a207.jpg'),
    (1, 'Peony', 'Paeonia suffruticosa', 'https://www.bsfa.org/wp-content/uploads/2018/06/peonies.jpg');

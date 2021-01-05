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
    id CHAR(13) NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    name VARCHAR(80) NOT NULL,
    dimension_x TINYINT UNSIGNED NOT NULL,
    dimension_y TINYINT UNSIGNED NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE plants (
    id CHAR(13) NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    english_name VARCHAR(80) NOT NULL,
    latin_name VARCHAR(255) NOT NULL,
    image_link VARCHAR(500) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE gardens_plants (
    garden_id CHAR(13) NOT NULL,
    plant_id CHAR(13) NOT NULL,
    coordinate_x SMALLINT UNSIGNED NOT NULL,
    coordinate_y SMALLINT UNSIGNED NOT NULL
);

INSERT INTO users (username, email, password)
VALUES
    ('dan', 'dan@email.com', '$2y$10$W4ixd.rF03iRVuECIP1Hu.yVH/eyStgiKgTmOqqEHBI9vuSoYnxvi');

INSERT INTO plants (id, user_id, english_name, latin_name, image_link)
VALUES
    ('5fea8ef735b2b', 1, 'Rose', 'Rosa kordesii', 'https://static3.depositphotos.com/1001651/136/i/600/depositphotos_1365974-stock-photo-roses-bush.jpg'),
    ('5fea8ef735b2c', 1, 'Cherry Tree', 'Prunus serrula', 'https://c8.alamy.com/comp/PGM15F/prunus-serrula-an-ornamental-tree-with-distinctive-red-shiny-bark-also-known-as-the-tibetan-cherry-PGM15F.jpg'),
    ('5fea8ef735b2d', 1, 'Holly', 'Ilex aquifolium', 'https://cdn.shopify.com/s/files/1/0212/1030/0480/products/nelliestevenshollyTree_1.png'),
    ('5fea8ef735b2e', 1, 'Grass', 'Lolium Perenne', 'https://hips.hearstapps.com/hmg-prod.s3.amazonaws.com/images/background-of-green-and-healthy-grass-royalty-free-image-1586800097.jpg'),
    ('5fea8ef735b2f', 1, 'English Vine', 'Toxicodendron radicans', 'https://www.thespruce.com/thmb/3I4dYlSaqS0OmA7yMH4NyJ_ExnU=/4001x3001/smart/filters:no_upscale()/english-ivy-plants-2132215-hero-03-0a8ee662826b418d86a5b6e08ee7a207.jpg'),
    ('5fea8ef735b2g', 1, 'Peony', 'Paeonia suffruticosa', 'https://www.bsfa.org/wp-content/uploads/2018/06/peonies.jpg'),
    ('51ea8ef735b2g', 1, 'Carrot', 'Daucus carota', 'https://gardenerspath.com/wp-content/uploads/2020/01/Carrots-in-Containers-Vertical-Image.jpg'),
    ('31ea8ef735b2g', 1, 'Cabbage', 'Brassica oleracea', 'https://post.greatist.com/wp-content/uploads/sites/2/2019/04/Cabbage_20111025_featured_1-732x437.jpg'),
    ('11ea8ef735b2g', 1, 'Tomatoes', 'Solanum lycopersicum', 'https://www.almanac.com/sites/default/files/styles/primary_image_in_article/public/image_nodes/tomatoes_helios4eos_gettyimages-edit.jpeg');

INSERT INTO gardens (id, user_id, name, dimension_x, dimension_y)
VALUES
    ('5fed05c8c6423', 1, 'Vegetable Patch', 4, 8),
    ('5fed061b036cf', 1, 'Front Garden', 10, 4),
    ('5fed061b036ce', 1, 'Back Garden', 10, 10);

INSERT INTO gardens_plants (garden_id, plant_id, coordinate_x, coordinate_y)
VALUES
    ('5fed05c8c6423', '51ea8ef735b2g', 1, 1),
    ('5fed05c8c6423', '51ea8ef735b2g', 1, 2),
    ('5fed05c8c6423', '51ea8ef735b2g', 1, 3),
    ('5fed05c8c6423', '51ea8ef735b2g', 1, 4),
    ('5fed05c8c6423', '11ea8ef735b2g', 1, 5),
    ('5fed05c8c6423', '11ea8ef735b2g', 1, 6),
    ('5fed05c8c6423', '11ea8ef735b2g', 1, 7),
    ('5fed05c8c6423', '11ea8ef735b2g', 1, 8),
    ('5fed05c8c6423', '11ea8ef735b2g', 2, 5),
    ('5fed05c8c6423', '11ea8ef735b2g', 2, 6),
    ('5fed05c8c6423', '11ea8ef735b2g', 2, 7),
    ('5fed05c8c6423', '11ea8ef735b2g', 2, 8),
    ('5fed05c8c6423', '51ea8ef735b2g', 2, 1),
    ('5fed05c8c6423', '51ea8ef735b2g', 2, 2),
    ('5fed05c8c6423', '51ea8ef735b2g', 2, 3),
    ('5fed05c8c6423', '51ea8ef735b2g', 2, 4),
    ('5fed05c8c6423', '31ea8ef735b2g', 3, 1),
    ('5fed05c8c6423', '31ea8ef735b2g', 3, 2),
    ('5fed05c8c6423', '31ea8ef735b2g', 3, 3),
    ('5fed05c8c6423', '31ea8ef735b2g', 3, 4),
    ('5fed05c8c6423', '31ea8ef735b2g', 4, 1),
    ('5fed05c8c6423', '31ea8ef735b2g', 4, 2),
    ('5fed05c8c6423', '31ea8ef735b2g', 4, 3),
    ('5fed05c8c6423', '31ea8ef735b2g', 4, 4);

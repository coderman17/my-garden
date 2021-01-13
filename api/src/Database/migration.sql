-- noinspection SpellCheckingInspectionForFile

CREATE database my_garden;

USE my_garden;

CREATE TABLE users (
    id CHAR(13) NOT NULL,
    username VARCHAR(30) NOT NULL,
    email VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE gardens (
    id CHAR(13) NOT NULL,
    user_id CHAR(13) NOT NULL,
    name VARCHAR(80) NOT NULL,
    dimension_x TINYINT UNSIGNED NOT NULL,
    dimension_y TINYINT UNSIGNED NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE plants (
    id CHAR(13) NOT NULL,
    user_id CHAR(13) NOT NULL,
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

INSERT INTO users (id, username, email, password)
VALUES
    ('21ea8ea135b2g', 'dan', 'dan@email.com', '$2y$10$W4ixd.rF03iRVuECIP1Hu.yVH/eyStgiKgTmOqqEHBI9vuSoYnxvi');

INSERT INTO plants (id, user_id, english_name, latin_name, image_link)
VALUES
    ('11ea8ef735b2g', '21ea8ea135b2g', 'Tomatoes', 'Solanum lycopersicum', 'https://www.almanac.com/sites/default/files/styles/primary_image_in_article/public/image_nodes/tomatoes_helios4eos_gettyimages-edit.jpeg'),
    ('51ea8ef735b2g', '21ea8ea135b2g', 'Carrot', 'Daucus carota', 'https://gardenerspath.com/wp-content/uploads/2020/01/Carrots-in-Containers-Vertical-Image.jpg'),
    ('5fea8ef735b2b', '21ea8ea135b2g', 'Rose', 'Rosa kordesii', 'https://static3.depositphotos.com/1001651/136/i/600/depositphotos_1365974-stock-photo-roses-bush.jpg'),
    ('5fea8ef735b2c', '21ea8ea135b2g', 'Cherry Tree', 'Prunus serrula', 'https://c8.alamy.com/comp/PGM15F/prunus-serrula-an-ornamental-tree-with-distinctive-red-shiny-bark-also-known-as-the-tibetan-cherry-PGM15F.jpg'),
    ('5fea8ef735b2d', '21ea8ea135b2g', 'Holly', 'Ilex aquifolium', 'https://cdn.shopify.com/s/files/1/0212/1030/0480/products/nelliestevenshollyTree_1.png'),
    ('5fea8ef735b2e', '21ea8ea135b2g', 'Grass', 'Lolium Perenne', 'https://hips.hearstapps.com/hmg-prod.s3.amazonaws.com/images/background-of-green-and-healthy-grass-royalty-free-image-1586800097.jpg'),
    ('5fea8ef735b2f', '21ea8ea135b2g', 'English Vine', 'Toxicodendron radicans', 'https://www.thespruce.com/thmb/3I4dYlSaqS0OmA7yMH4NyJ_ExnU=/4001x3001/smart/filters:no_upscale()/english-ivy-plants-2132215-hero-03-0a8ee662826b418d86a5b6e08ee7a207.jpg'),
    ('5fea8ef735b2g', '21ea8ea135b2g', 'Peony', 'Paeonia suffruticosa', 'https://www.bsfa.org/wp-content/uploads/2018/06/peonies.jpg'),
    ('5ff3c19090c4d', '21ea8ea135b2g', 'Stone Slab', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Stone_texture_on_wall.jpg/1280px-Stone_texture_on_wall.jpg'),
    ('5ff3c5f957704', '21ea8ea135b2g', 'Iceberg Lettuce', '', 'https://www.almanac.com/sites/default/files/users/AlmanacStaffArchive/lettuce_full_width.jpg'),
    ('5ff3c7a20dc10', '21ea8ea135b2g', 'Gravel', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ae/STONE_TEXTURE.jpg/1280px-STONE_TEXTURE.jpg'),
    ('5ff3c89ca3183', '21ea8ea135b2g', 'Fire Pit', '', 'https://cdn11.bigcommerce.com/s-n2c2quqe5p/images/stencil/500x659/products/4929/9930/Rd%20Grandeur%20Fire%20PIt__80160.1543245357.jpg'),
    ('5ff3cb9424e3b', '21ea8ea135b2g', 'Cardamine', '', 'https://www.americansouthwest.net/plants/photographs700/cardamine-cordifolia1.jpg'),
    ('5ff3ceb92bb80', '21ea8ea135b2g', 'Yellow Tulips', '', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDbEw81tra013uHQ2C-tpCK3IeEkstyhrGgA&usqp=CAU'),
    ('5ff3dc20ac037', '21ea8ea135b2g', 'Cauliflower', '', 'https://bloximages.chicago2.vip.townnews.com/journalstar.com/content/tncms/assets/v3/editorial/5/a2/5a245362-ed0a-5765-a4be-4ea0aa514ed2/5362947842c8d.preview-699.jpg?crop=325%2C325%2C190%2C60');

INSERT INTO gardens (id, user_id, name, dimension_x, dimension_y)
VALUES
    ('5ff3be4703a1d', '21ea8ea135b2g', 'Show Garden', 9, 10),
    ('6fed05c8c6423', '21ea8ea135b2g', 'Vegetable Patch', 4, 8);

INSERT INTO gardens_plants (garden_id, plant_id, coordinate_x, coordinate_y)
VALUES
    ('6fed05c8c6423', '51ea8ef735b2g', 1, 1),
    ('6fed05c8c6423', '51ea8ef735b2g', 1, 2),
    ('6fed05c8c6423', '51ea8ef735b2g', 1, 3),
    ('6fed05c8c6423', '51ea8ef735b2g', 1, 4),
    ('6fed05c8c6423', '11ea8ef735b2g', 1, 5),
    ('6fed05c8c6423', '11ea8ef735b2g', 1, 6),
    ('6fed05c8c6423', '11ea8ef735b2g', 1, 7),
    ('6fed05c8c6423', '11ea8ef735b2g', 1, 8),
    ('6fed05c8c6423', '11ea8ef735b2g', 2, 5),
    ('6fed05c8c6423', '11ea8ef735b2g', 2, 6),
    ('6fed05c8c6423', '11ea8ef735b2g', 2, 7),
    ('6fed05c8c6423', '11ea8ef735b2g', 2, 8),
    ('6fed05c8c6423', '51ea8ef735b2g', 2, 1),
    ('6fed05c8c6423', '51ea8ef735b2g', 2, 2),
    ('6fed05c8c6423', '51ea8ef735b2g', 2, 3),
    ('6fed05c8c6423', '51ea8ef735b2g', 2, 4),
    ('6fed05c8c6423', '5ff3c5f957704', 3, 1),
    ('6fed05c8c6423', '5ff3c5f957704', 3, 2),
    ('6fed05c8c6423', '5ff3c5f957704', 3, 3),
    ('6fed05c8c6423', '5ff3c5f957704', 3, 4),
    ('6fed05c8c6423', '5ff3dc20ac037', 3, 5),
    ('6fed05c8c6423', '5ff3dc20ac037', 3, 6),
    ('6fed05c8c6423', '5ff3dc20ac037', 3, 7),
    ('6fed05c8c6423', '5ff3dc20ac037', 3, 8),
    ('6fed05c8c6423', '5ff3c5f957704', 4, 1),
    ('6fed05c8c6423', '5ff3c5f957704', 4, 2),
    ('6fed05c8c6423', '5ff3c5f957704', 4, 3),
    ('6fed05c8c6423', '5ff3c5f957704', 4, 4),
    ('6fed05c8c6423', '5ff3dc20ac037', 4, 5),
    ('6fed05c8c6423', '5ff3dc20ac037', 4, 6),
    ('6fed05c8c6423', '5ff3dc20ac037', 4, 7),
    ('6fed05c8c6423', '5ff3dc20ac037', 4, 8);

-- Show garden:
INSERT INTO gardens_plants (garden_id, plant_id, coordinate_x, coordinate_y)
VALUES
('5ff3be4703a1d', '5fea8ef735b2b', '1', '4'),
('5ff3be4703a1d', '5fea8ef735b2b', '1', '5'),
('5ff3be4703a1d', '5fea8ef735b2b', '1', '6'),
('5ff3be4703a1d', '5fea8ef735b2b', '1', '3'),
('5ff3be4703a1d', '5fea8ef735b2b', '1', '2'),
('5ff3be4703a1d', '5fea8ef735b2b', '1', '1'),
('5ff3be4703a1d', '5fea8ef735b2b', '1', '7'),
('5ff3be4703a1d', '5fea8ef735b2g', '1', '8'),
('5ff3be4703a1d', '5fea8ef735b2g', '1', '9'),
('5ff3be4703a1d', '5fea8ef735b2g', '1', '10'),
('5ff3be4703a1d', '5fea8ef735b2e', '2', '1'),
('5ff3be4703a1d', '5fea8ef735b2e', '2', '2'),
('5ff3be4703a1d', '5fea8ef735b2e', '2', '3'),
('5ff3be4703a1d', '5fea8ef735b2e', '2', '4'),
('5ff3be4703a1d', '5fea8ef735b2e', '2', '5'),
('5ff3be4703a1d', '5fea8ef735b2e', '2', '6'),
('5ff3be4703a1d', '5fea8ef735b2e', '2', '7'),
('5ff3be4703a1d', '5fea8ef735b2e', '2', '8'),
('5ff3be4703a1d', '5fea8ef735b2e', '2', '9'),
('5ff3be4703a1d', '5fea8ef735b2g', '2', '10'),
('5ff3be4703a1d', '5fea8ef735b2e', '3', '1'),
('5ff3be4703a1d', '5ff3ceb92bb80', '3', '2'),
('5ff3be4703a1d', '5ff3cb9424e3b', '3', '3'),
('5ff3be4703a1d', '5ff3ceb92bb80', '3', '4'),
('5ff3be4703a1d', '5fea8ef735b2e', '3', '5'),
('5ff3be4703a1d', '5fea8ef735b2e', '3', '6'),
('5ff3be4703a1d', '5fea8ef735b2e', '3', '7'),
('5ff3be4703a1d', '5fea8ef735b2e', '3', '8'),
('5ff3be4703a1d', '5fea8ef735b2e', '3', '9'),
('5ff3be4703a1d', '5ff3ceb92bb80', '3', '10'),
('5ff3be4703a1d', '5fea8ef735b2e', '4', '1'),
('5ff3be4703a1d', '5fea8ef735b2e', '4', '2'),
('5ff3be4703a1d', '5fea8ef735b2e', '4', '3'),
('5ff3be4703a1d', '5fea8ef735b2e', '4', '4'),
('5ff3be4703a1d', '5fea8ef735b2e', '4', '5'),
('5ff3be4703a1d', '5ff3c7a20dc10', '4', '6'),
('5ff3be4703a1d', '5ff3c7a20dc10', '4', '7'),
('5ff3be4703a1d', '5ff3c7a20dc10', '4', '8'),
('5ff3be4703a1d', '5fea8ef735b2e', '4', '9'),
('5ff3be4703a1d', '5ff3ceb92bb80', '4', '10'),
('5ff3be4703a1d', '5ff3c7a20dc10', '5', '1'),
('5ff3be4703a1d', '5ff3c7a20dc10', '5', '2'),
('5ff3be4703a1d', '5ff3c7a20dc10', '5', '3'),
('5ff3be4703a1d', '5ff3c7a20dc10', '5', '4'),
('5ff3be4703a1d', '5ff3c7a20dc10', '5', '5'),
('5ff3be4703a1d', '5ff3c7a20dc10', '5', '6'),
('5ff3be4703a1d', '5ff3c89ca3183', '5', '7'),
('5ff3be4703a1d', '5ff3c7a20dc10', '5', '8'),
('5ff3be4703a1d', '5fea8ef735b2e', '5', '9'),
('5ff3be4703a1d', '5ff3ceb92bb80', '5', '10'),
('5ff3be4703a1d', '5fea8ef735b2e', '6', '1'),
('5ff3be4703a1d', '5fea8ef735b2e', '6', '2'),
('5ff3be4703a1d', '5fea8ef735b2e', '6', '3'),
('5ff3be4703a1d', '5fea8ef735b2e', '6', '4'),
('5ff3be4703a1d', '5fea8ef735b2e', '6', '5'),
('5ff3be4703a1d', '5ff3c7a20dc10', '6', '6'),
('5ff3be4703a1d', '5ff3c7a20dc10', '6', '7'),
('5ff3be4703a1d', '5ff3c7a20dc10', '6', '8'),
('5ff3be4703a1d', '5fea8ef735b2e', '6', '9'),
('5ff3be4703a1d', '5ff3ceb92bb80', '6', '10'),
('5ff3be4703a1d', '5fea8ef735b2e', '7', '1'),
('5ff3be4703a1d', '5ff3ceb92bb80', '7', '2'),
('5ff3be4703a1d', '5ff3cb9424e3b', '7', '3'),
('5ff3be4703a1d', '5ff3ceb92bb80', '7', '4'),
('5ff3be4703a1d', '5fea8ef735b2e', '7', '5'),
('5ff3be4703a1d', '5fea8ef735b2e', '7', '6'),
('5ff3be4703a1d', '5fea8ef735b2e', '7', '7'),
('5ff3be4703a1d', '5fea8ef735b2e', '7', '8'),
('5ff3be4703a1d', '5fea8ef735b2e', '7', '9'),
('5ff3be4703a1d', '5ff3ceb92bb80', '7', '10'),
('5ff3be4703a1d', '5fea8ef735b2e', '8', '1'),
('5ff3be4703a1d', '5fea8ef735b2e', '8', '2'),
('5ff3be4703a1d', '5fea8ef735b2e', '8', '3'),
('5ff3be4703a1d', '5fea8ef735b2e', '8', '4'),
('5ff3be4703a1d', '5fea8ef735b2e', '8', '5'),
('5ff3be4703a1d', '5fea8ef735b2e', '8', '6'),
('5ff3be4703a1d', '5fea8ef735b2e', '8', '7'),
('5ff3be4703a1d', '5fea8ef735b2e', '8', '8'),
('5ff3be4703a1d', '5fea8ef735b2e', '8', '9'),
('5ff3be4703a1d', '5fea8ef735b2g', '8', '10'),
('5ff3be4703a1d', '5fea8ef735b2b', '9', '4'),
('5ff3be4703a1d', '5fea8ef735b2b', '9', '5'),
('5ff3be4703a1d', '5fea8ef735b2b', '9', '6'),
('5ff3be4703a1d', '5fea8ef735b2b', '9', '3'),
('5ff3be4703a1d', '5fea8ef735b2b', '9', '2'),
('5ff3be4703a1d', '5fea8ef735b2b', '9', '1'),
('5ff3be4703a1d', '5fea8ef735b2b', '9', '7'),
('5ff3be4703a1d', '5fea8ef735b2g', '9', '10'),
('5ff3be4703a1d', '5fea8ef735b2g', '9', '9'),
('5ff3be4703a1d', '5fea8ef735b2g', '9', '8');

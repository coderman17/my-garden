-- noinspection SpellCheckingInspectionForFile

CREATE database my_garden;

USE my_garden;

CREATE TABLE users (
    id CHAR(15) NOT NULL,
    username VARCHAR(30) NOT NULL,
    email VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE gardens (
    id CHAR(15) NOT NULL,
    user_id CHAR(15) NOT NULL,
    name VARCHAR(80) NOT NULL,
    dimension_x TINYINT UNSIGNED NOT NULL,
    dimension_y TINYINT UNSIGNED NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE plants (
    id CHAR(15) NOT NULL,
    user_id CHAR(15) NOT NULL,
    english_name VARCHAR(80) NOT NULL,
    latin_name VARCHAR(255) NOT NULL,
    image_link VARCHAR(500) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE gardens_plants (
    garden_id CHAR(15) NOT NULL,
    plant_id CHAR(15) NOT NULL,
    coordinate_x SMALLINT UNSIGNED NOT NULL,
    coordinate_y SMALLINT UNSIGNED NOT NULL
);

INSERT INTO users (id, username, email, password)
VALUES
    ('MG21ea8ea135b2g', 'dan', 'dan@email.com', '$2y$10$W4ixd.rF03iRVuECIP1Hu.yVH/eyStgiKgTmOqqEHBI9vuSoYnxvi');

INSERT INTO plants (id, user_id, english_name, latin_name, image_link)
VALUES
    ('MG11ea8ef735b2g', 'MG21ea8ea135b2g', 'Tomatoes', 'Solanum lycopersicum', 'https://www.almanac.com/sites/default/files/styles/primary_image_in_article/public/image_nodes/tomatoes_helios4eos_gettyimages-edit.jpeg'),
    ('MG51ea8ef735b2g', 'MG21ea8ea135b2g', 'Carrot', 'Daucus carota', 'https://gardenerspath.com/wp-content/uploads/2020/01/Carrots-in-Containers-Vertical-Image.jpg'),
    ('MG5fea8ef735b2b', 'MG21ea8ea135b2g', 'Rose', 'Rosa kordesii', 'https://static3.depositphotos.com/1001651/136/i/600/depositphotos_1365974-stock-photo-roses-bush.jpg'),
    ('MG5fea8ef735b2c', 'MG21ea8ea135b2g', 'Cherry Tree', 'Prunus serrula', 'https://c8.alamy.com/comp/PGM15F/prunus-serrula-an-ornamental-tree-with-distinctive-red-shiny-bark-also-known-as-the-tibetan-cherry-PGM15F.jpg'),
    ('MG5fea8ef735b2d', 'MG21ea8ea135b2g', 'Holly', 'Ilex aquifolium', 'https://cdn.shopify.com/s/files/1/0212/1030/0480/products/nelliestevenshollyTree_1.png'),
    ('MG5fea8ef735b2e', 'MG21ea8ea135b2g', 'Grass', 'Lolium Perenne', 'https://hips.hearstapps.com/hmg-prod.s3.amazonaws.com/images/background-of-green-and-healthy-grass-royalty-free-image-1586800097.jpg'),
    ('MG5fea8ef735b2f', 'MG21ea8ea135b2g', 'English Vine', 'Toxicodendron radicans', 'https://www.thespruce.com/thmb/3I4dYlSaqS0OmA7yMH4NyJ_ExnU=/4001x3001/smart/filters:no_upscale()/english-ivy-plants-2132215-hero-03-0a8ee662826b418d86a5b6e08ee7a207.jpg'),
    ('MG5fea8ef735b2g', 'MG21ea8ea135b2g', 'Peony', 'Paeonia suffruticosa', 'https://www.bsfa.org/wp-content/uploads/2018/06/peonies.jpg'),
    ('MG5ff3c19090c4d', 'MG21ea8ea135b2g', 'Stone Slab', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Stone_texture_on_wall.jpg/1280px-Stone_texture_on_wall.jpg'),
    ('MG5ff3c5f957704', 'MG21ea8ea135b2g', 'Iceberg Lettuce', '', 'https://www.almanac.com/sites/default/files/users/AlmanacStaffArchive/lettuce_full_width.jpg'),
    ('MG5ff3c7a20dc10', 'MG21ea8ea135b2g', 'Gravel', '', 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ae/STONE_TEXTURE.jpg/1280px-STONE_TEXTURE.jpg'),
    ('MG5ff3c89ca3183', 'MG21ea8ea135b2g', 'Fire Pit', '', 'https://cdn11.bigcommerce.com/s-n2c2quqe5p/images/stencil/500x659/products/4929/9930/Rd%20Grandeur%20Fire%20PIt__80160.1543245357.jpg'),
    ('MG5ff3cb9424e3b', 'MG21ea8ea135b2g', 'Cardamine', '', 'https://www.americansouthwest.net/plants/photographs700/cardamine-cordifolia1.jpg'),
    ('MG5ff3ceb92bb80', 'MG21ea8ea135b2g', 'Yellow Tulips', '', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDbEw81tra013uHQ2C-tpCK3IeEkstyhrGgA&usqp=CAU'),
    ('MG5ff3dc20ac037', 'MG21ea8ea135b2g', 'Cauliflower', '', 'https://bloximages.chicago2.vip.townnews.com/journalstar.com/content/tncms/assets/v3/editorial/5/a2/5a245362-ed0a-5765-a4be-4ea0aa514ed2/5362947842c8d.preview-699.jpg?crop=325%2C325%2C190%2C60');

INSERT INTO gardens (id, user_id, name, dimension_x, dimension_y)
VALUES
    ('MG5ff3be4703a1d', 'MG21ea8ea135b2g', 'Show Garden', 9, 10),
    ('MG6fed05c8c6423', 'MG21ea8ea135b2g', 'Vegetable Patch', 4, 8);

INSERT INTO gardens_plants (garden_id, plant_id, coordinate_x, coordinate_y)
VALUES
    ('MG6fed05c8c6423', 'MG51ea8ef735b2g', 1, 1),
    ('MG6fed05c8c6423', 'MG51ea8ef735b2g', 1, 2),
    ('MG6fed05c8c6423', 'MG51ea8ef735b2g', 1, 3),
    ('MG6fed05c8c6423', 'MG51ea8ef735b2g', 1, 4),
    ('MG6fed05c8c6423', 'MG11ea8ef735b2g', 1, 5),
    ('MG6fed05c8c6423', 'MG11ea8ef735b2g', 1, 6),
    ('MG6fed05c8c6423', 'MG11ea8ef735b2g', 1, 7),
    ('MG6fed05c8c6423', 'MG11ea8ef735b2g', 1, 8),
    ('MG6fed05c8c6423', 'MG11ea8ef735b2g', 2, 5),
    ('MG6fed05c8c6423', 'MG11ea8ef735b2g', 2, 6),
    ('MG6fed05c8c6423', 'MG11ea8ef735b2g', 2, 7),
    ('MG6fed05c8c6423', 'MG11ea8ef735b2g', 2, 8),
    ('MG6fed05c8c6423', 'MG51ea8ef735b2g', 2, 1),
    ('MG6fed05c8c6423', 'MG51ea8ef735b2g', 2, 2),
    ('MG6fed05c8c6423', 'MG51ea8ef735b2g', 2, 3),
    ('MG6fed05c8c6423', 'MG51ea8ef735b2g', 2, 4),
    ('MG6fed05c8c6423', 'MG5ff3c5f957704', 3, 1),
    ('MG6fed05c8c6423', 'MG5ff3c5f957704', 3, 2),
    ('MG6fed05c8c6423', 'MG5ff3c5f957704', 3, 3),
    ('MG6fed05c8c6423', 'MG5ff3c5f957704', 3, 4),
    ('MG6fed05c8c6423', 'MG5ff3dc20ac037', 3, 5),
    ('MG6fed05c8c6423', 'MG5ff3dc20ac037', 3, 6),
    ('MG6fed05c8c6423', 'MG5ff3dc20ac037', 3, 7),
    ('MG6fed05c8c6423', 'MG5ff3dc20ac037', 3, 8),
    ('MG6fed05c8c6423', 'MG5ff3c5f957704', 4, 1),
    ('MG6fed05c8c6423', 'MG5ff3c5f957704', 4, 2),
    ('MG6fed05c8c6423', 'MG5ff3c5f957704', 4, 3),
    ('MG6fed05c8c6423', 'MG5ff3c5f957704', 4, 4),
    ('MG6fed05c8c6423', 'MG5ff3dc20ac037', 4, 5),
    ('MG6fed05c8c6423', 'MG5ff3dc20ac037', 4, 6),
    ('MG6fed05c8c6423', 'MG5ff3dc20ac037', 4, 7),
    ('MG6fed05c8c6423', 'MG5ff3dc20ac037', 4, 8);

-- Show garden:
INSERT INTO gardens_plants (garden_id, plant_id, coordinate_x, coordinate_y)
VALUES
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '1', '4'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '1', '5'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '1', '6'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '1', '3'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '1', '2'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '1', '1'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '1', '7'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2g', '1', '8'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2g', '1', '9'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2g', '1', '10'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '2', '1'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '2', '2'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '2', '3'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '2', '4'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '2', '5'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '2', '6'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '2', '7'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '2', '8'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '2', '9'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2g', '2', '10'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '3', '1'),
('MG5ff3be4703a1d', 'MG5ff3ceb92bb80', '3', '2'),
('MG5ff3be4703a1d', 'MG5ff3cb9424e3b', '3', '3'),
('MG5ff3be4703a1d', 'MG5ff3ceb92bb80', '3', '4'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '3', '5'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '3', '6'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '3', '7'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '3', '8'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '3', '9'),
('MG5ff3be4703a1d', 'MG5ff3ceb92bb80', '3', '10'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '4', '1'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '4', '2'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '4', '3'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '4', '4'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '4', '5'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '4', '6'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '4', '7'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '4', '8'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '4', '9'),
('MG5ff3be4703a1d', 'MG5ff3ceb92bb80', '4', '10'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '5', '1'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '5', '2'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '5', '3'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '5', '4'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '5', '5'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '5', '6'),
('MG5ff3be4703a1d', 'MG5ff3c89ca3183', '5', '7'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '5', '8'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '5', '9'),
('MG5ff3be4703a1d', 'MG5ff3ceb92bb80', '5', '10'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '6', '1'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '6', '2'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '6', '3'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '6', '4'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '6', '5'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '6', '6'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '6', '7'),
('MG5ff3be4703a1d', 'MG5ff3c7a20dc10', '6', '8'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '6', '9'),
('MG5ff3be4703a1d', 'MG5ff3ceb92bb80', '6', '10'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '7', '1'),
('MG5ff3be4703a1d', 'MG5ff3ceb92bb80', '7', '2'),
('MG5ff3be4703a1d', 'MG5ff3cb9424e3b', '7', '3'),
('MG5ff3be4703a1d', 'MG5ff3ceb92bb80', '7', '4'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '7', '5'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '7', '6'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '7', '7'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '7', '8'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '7', '9'),
('MG5ff3be4703a1d', 'MG5ff3ceb92bb80', '7', '10'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '8', '1'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '8', '2'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '8', '3'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '8', '4'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '8', '5'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '8', '6'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '8', '7'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '8', '8'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2e', '8', '9'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2g', '8', '10'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '9', '4'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '9', '5'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '9', '6'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '9', '3'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '9', '2'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '9', '1'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2b', '9', '7'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2g', '9', '10'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2g', '9', '9'),
('MG5ff3be4703a1d', 'MG5fea8ef735b2g', '9', '8');

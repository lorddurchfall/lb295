CREATE DATABASE IF NOT EXISTS lb295
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE lb295;

-- Drop the product table first because it references category.
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS category;

-- Create the category table.
CREATE TABLE category
(
    category_id INT NOT NULL AUTO_INCREMENT,
    active TINYINT(1) NOT NULL,
    name VARCHAR(500) NOT NULL,

    PRIMARY KEY (category_id)
);

-- Create the product table.
CREATE TABLE product
(
    product_id INT NOT NULL AUTO_INCREMENT,
    sku VARCHAR(100) NOT NULL,
    active TINYINT(1) NOT NULL,
    id_category INT NULL,
    name VARCHAR(500) NOT NULL,
    image VARCHAR(1000) NULL,
    description TEXT NULL,
    price DECIMAL(65,2) NOT NULL,
    stock INT NOT NULL,

    PRIMARY KEY (product_id),

    UNIQUE (sku),

    CONSTRAINT fk_product_category
        FOREIGN KEY (id_category)
            REFERENCES category(category_id)
);
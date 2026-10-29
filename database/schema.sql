-- Bai 5 (ca nhan) - Quan ly sach
-- Chay: /Applications/XAMPP/xamppfiles/bin/mysql -u root < database/schema.sql
CREATE DATABASE IF NOT EXISTS hvanhub_books
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hvanhub_books;

DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS categories;

-- Bang cha: the loai
CREATE TABLE categories (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(100) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_categories_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bang con: sach (1 the loai - nhieu sach)
CREATE TABLE books (
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  category_id    INT UNSIGNED NOT NULL,
  code           VARCHAR(20)  NOT NULL,
  title          VARCHAR(150) NOT NULL,
  author         VARCHAR(100) NOT NULL,
  price          DECIMAL(12,2) NOT NULL DEFAULT 0,
  quantity       INT UNSIGNED NOT NULL DEFAULT 0,
  published_year SMALLINT UNSIGNED NULL,
  created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_books_code (code),
  KEY idx_books_category (category_id),
  CONSTRAINT fk_books_category FOREIGN KEY (category_id)
    REFERENCES categories (id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE DATABASE IF NOT EXISTS crud_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'crud_user'@'localhost' IDENTIFIED BY 'crud_password';
GRANT ALL PRIVILEGES ON crud_app.* TO 'crud_user'@'localhost';
FLUSH PRIVILEGES;

USE crud_app;

CREATE TABLE IF NOT EXISTS items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO items (title, description) VALUES
  ('Exemple 1', 'Premier item'),
  ('Exemple 2', 'Deuxième item');

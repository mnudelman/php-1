-- Создание схемы БД gallery
-- --------------------------------------
CREATE DATABASE IF NOT EXISTS gallery;
-- --------------------------------------
-- users - список пользователей
CREATE TABLE IF NOT EXISTS users (
  userId   INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  login    VARCHAR(20) UNIQUE,
  password CHAR(32)
);
-- --------------------------------------
-- Профиль пользователя
CREATE TABLE IF NOT EXISTS userprofile (
  id         INTEGER NOT NULL  AUTO_INCREMENT PRIMARY KEY,
  userid     INTEGER
    REFERENCES users (userid)
      ON DELETE CASCADE,
  firstname  VARCHAR(40),
  middlename VARCHAR(40),
  lastname   VARCHAR(40),
  fileFoto   VARCHAR(100), -- файл с фотографией
  tel        VARCHAR(15),
  email      VARCHAR(40),
  sex        CHAR(1)           DEFAULT 'm',
  birthday   DATE,
  CHECK (sex IN ('m', 'w'))
);
-- --------------------------------------
-- Список галерей
CREATE TABLE IF NOT EXISTS gallery (
  galleryid INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  userid    INTEGER
    REFERENCES users (userid)
      ON DELETE CASCADE,
  themeName VARCHAR(40), -- тема галереи (имяальбома)
  comment   VARCHAR(100),
  UNIQUE (userid, themeName)   -- у владельца только одна галерея с именем  themename
);
-- --------------------------------------
-- galleryContent -Содержание галереи (список файлов-изображений
CREATE TABLE IF NOT EXISTS galleryContent (
  contentid INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  galleryId INTEGER
    REFERENCES gallery (galleryid)
      ON DELETE CASCADE,
  fileImg   VARCHAR(100), -- файл изображение
  comment   VARCHAR(100), -- комментарий(подпись под картинкой)
  UNIQUE (galleryid, fileImg)    -- в гелерее только один файл с именем fileImg
);
-- --------------------------------------
-- строка в userprofile появляется вместе с users
CREATE TRIGGER insert_user AFTER INSERT ON users
FOR EACH ROW
  INSERT INTO userprofile (userid) VALUES (new.userId);
-- --------------------------------------
-- --------------------------------------
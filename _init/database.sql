-- sets up the schema and seeds dummy data
-- tables created: pbook.users, pbook.persons
-- @author: ismar.sehic <sheha@github.com>

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema phonebook
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `phonebook` ;

CREATE SCHEMA IF NOT EXISTS `phonebook` DEFAULT CHARACTER SET utf8 ;
USE `phonebook` ;

-- -----------------------------------------------------
-- Table `phonebook`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `phonebook`.`users` ;

CREATE TABLE IF NOT EXISTS `phonebook`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(64) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
  ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `phonebook`.`persons`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `phonebook`.`persons` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `user_id` INT,
  PRIMARY KEY (`id`),
  -- keep it clean for this type of project, no permanent storage
  FOREIGN KEY ( `user_id` ) REFERENCES phonebook.users( `id` ) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------------------------
-- Seed for `phonebook`.`persons`
-- -----------------------------------------------------
INSERT INTO `persons` (`first_name`, `last_name`, `gender`, `address`, `dob`, `user_id`)
VALUES
  ('Airi', 'Satou', 'female', 'Tokyo', '1964-03-04', 1),
  ('Garrett', 'Winters', 'male', 'Tokyo', '1988-09-02', 1),
  ('John', 'Doe', 'male', 'Kansas', '1972-11-02', 1),
  ('Tatyana', 'Fitzpatrick', 'male', 'London', '1989-01-01', 1),
  ('Quinn', 'Flynn', 'male', 'Edinburgh', '1977-03-24', 1);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

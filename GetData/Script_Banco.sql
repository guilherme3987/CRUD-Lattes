-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema database_lattes
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `database_lattes` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `database_lattes` ;

-- -----------------------------------------------------
-- Table `database_lattes`.`pesquisador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `database_lattes`.`pesquisador` (
  `id_lattes` VARCHAR(50) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `senha` VARCHAR(255) NOT NULL, -- Hash bcrypt (60+) ou md5 (32) legado
  `nome_completo` VARCHAR(255) NOT NULL,
  `pais_nascimento` VARCHAR(100) NOT NULL,
  `cidade_nascimento` VARCHAR(100) NOT NULL,
  `orcid_id` VARCHAR(100) NOT NULL,
  `resumo_cv` TEXT NOT NULL,
  PRIMARY KEY (`id_lattes`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  UNIQUE INDEX `id_lattes_UNIQUE` (`id_lattes` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `database_lattes`.`atuacao_profissional`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `database_lattes`.`atuacao_profissional` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_lattes` VARCHAR(50) NOT NULL,
  `instituicao` VARCHAR(255) NOT NULL,
  `ano_inicio` YEAR NOT NULL,
  `ano_fim` YEAR NOT NULL,
  `tipo_vinculo` VARCHAR(150) NOT NULL,
  `enquadramento_funcional` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `id_lattes` (`id_lattes` ASC) VISIBLE,
  CONSTRAINT `atuacao_profissional_ibfk_1`
    FOREIGN KEY (`id_lattes`)
    REFERENCES `database_lattes`.`pesquisador` (`id_lattes`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `database_lattes`.`formacao_academica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `database_lattes`.`formacao_academica` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_lattes` VARCHAR(50) NOT NULL,
  `nivel` VARCHAR(50) NOT NULL,
  `instituicao` VARCHAR(255) NOT NULL,
  `curso` VARCHAR(255) NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `ano_inicio` YEAR NOT NULL,
  `ano_conclusao` YEAR NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `id_lattes` (`id_lattes` ASC) VISIBLE,
  CONSTRAINT `formacao_academica_ibfk_1`
    FOREIGN KEY (`id_lattes`)
    REFERENCES `database_lattes`.`pesquisador` (`id_lattes`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
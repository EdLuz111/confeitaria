-- Mudanças nas configurações do MySQL
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- Criação do Schema `confeitaria`
CREATE SCHEMA IF NOT EXISTS `confeitaria` DEFAULT CHARACTER SET utf8mb4;
USE `confeitaria`;

-- Tabela `clientes`
CREATE TABLE IF NOT EXISTS `clientes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(45) NOT NULL,
    `numero` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 10
  DEFAULT CHARACTER SET = utf8mb4;

-- Tabela `pedidos`
CREATE TABLE IF NOT EXISTS `pedidos` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_cliente` INT(11) NOT NULL,
    `tamanho` VARCHAR(45) NOT NULL,
    `data_para_entrega` DATE NOT NULL,
    `observacoes` VARCHAR(100) NOT NULL,
    `preco` DECIMAL(10,2) NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'pendente', -- Adicionando a coluna `status`
    PRIMARY KEY (`id`),
    INDEX `id_cliente` (`id_cliente` ASC),
    CONSTRAINT `fk_id_cliente`
        FOREIGN KEY (`id_cliente`)
        REFERENCES `clientes` (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 8
  DEFAULT CHARACTER SET = utf8mb4;


-- Revertendo configurações do MySQL
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

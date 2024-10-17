-- phpMyAdmin SQL Dump
-- version 4.3.7
-- http://www.phpmyadmin.net
--
-- Host: mysql48-farm1.kinghost.net
-- Tempo de geração: 13/10/2024 às 10:30
-- Versão do servidor: 11.4.3-MariaDB-log
-- Versão do PHP: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de dados: `frbinfo`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE PROCEDURE `atualiza_emprestimos`()
BEGIN
    DECLARE resposta INT;

    -- Seleciona o id_cliente onde valor_aberto é 0
    SELECT id_cliente INTO resposta
    FROM emprestimos
    WHERE valor_aberto = 0
    LIMIT 1;  -- Limitando a um único registro

    -- Atualiza a tabela clientes, se um ID foi encontrado
    IF resposta IS NOT NULL THEN
        UPDATE clientes 
        SET emprestimo_ativo = '0' 
        WHERE id = resposta;

        -- Atualiza a tabela emprestimos para mudar o estado
        UPDATE emprestimos 
        SET estado = '0' 
        WHERE valor_aberto = 0;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `documento` varchar(255) DEFAULT NULL,
  `comprovante_residencia` varchar(255) DEFAULT NULL,
  `indicacao` varchar(255) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `emprestimo_ativo` int(11) NOT NULL,
  `ativo` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `emprestimos`
--

CREATE TABLE IF NOT EXISTS `emprestimos` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `duracao_meses` varchar(50) NOT NULL,
  `valor_aberto` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos`
--

CREATE TABLE IF NOT EXISTS `pagamentos` (
  `id` int(11) NOT NULL,
  `id_emprestimo` int(11) DEFAULT NULL,
  `valor_pago` decimal(10,2) DEFAULT NULL,
  `data_pagamento` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `parcelas`
--

CREATE TABLE IF NOT EXISTS `parcelas` (
  `id` int(11) NOT NULL,
  `id_emprestimo` int(11) NOT NULL,
  `numero_parcela` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_vencimento` date NOT NULL,
  `pago` varchar(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` varchar(20) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `session_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Fazendo dump de dados para tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `senha`, `perfil`, `ativo`, `session_id`) VALUES
(1, 'admin', '$2y$10$qTzVn14CkPIjcm2dWluO/OFvOIcEwL1Ymrc5IrDiytXt9d5Rav.8a', 'admin', 1, NULL),
(2, 'Felipe', '$2y$10$qTzVn14CkPIjcm2dWluO/OFvOIcEwL1Ymrc5IrDiytXt9d5Rav.8a', 'admin', 1, '00b12d24c257c867b6230f57ab395e4d'),
(3, 'Silvano', '$2y$10$qTzVn14CkPIjcm2dWluO/OFvOIcEwL1Ymrc5IrDiytXt9d5Rav.8a', 'admin', 1, NULL);

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`), ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `emprestimos`
--
ALTER TABLE `emprestimos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id`), ADD KEY `id_emprestimo` (`id_emprestimo`);

--
-- Índices de tabela `parcelas`
--
ALTER TABLE `parcelas`
  ADD PRIMARY KEY (`id`), ADD KEY `id_emprestimo` (`id_emprestimo`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de tabela `emprestimos`
--
ALTER TABLE `emprestimos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `parcelas`
--
ALTER TABLE `parcelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=74;
--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `clientes`
--
ALTER TABLE `clientes`
ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pagamentos`
--
ALTER TABLE `pagamentos`
ADD CONSTRAINT `pagamentos_ibfk_1` FOREIGN KEY (`id_emprestimo`) REFERENCES `emprestimos` (`id`);

--
-- Restrições para tabelas `parcelas`
--
ALTER TABLE `parcelas`
ADD CONSTRAINT `parcelas_ibfk_1` FOREIGN KEY (`id_emprestimo`) REFERENCES `emprestimos` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03-Jul-2023 às 17:46
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cartesian`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `points.data`
--

CREATE TABLE `points.data` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `x` double NOT NULL,
  `y` double NOT NULL,
  `last_att` datetime NOT NULL DEFAULT current_timestamp(),
  `user_last_att` int(11) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `points.data`
--

INSERT INTO `points.data` (`id`, `id_user`, `name`, `x`, `y`, `last_att`, `user_last_att`, `deleted`) VALUES
(1, 1, 'Ponto Teste', 10, 10, '2023-06-28 18:10:33', 1, 0),
(2, 1, 'Teste', 2, 5, '2023-06-28 18:47:58', 1, 0),
(3, 1, 'Oi', 50, 50, '2023-06-28 19:13:08', 1, 0),
(4, 1, 'A', 50, 50, '2023-06-28 19:16:34', 1, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `position.data`
--

CREATE TABLE `position.data` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `x` double NOT NULL,
  `y` double NOT NULL,
  `data` datetime NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `position.data`
--

INSERT INTO `position.data` (`id`, `id_user`, `x`, `y`, `data`, `deleted`) VALUES
(1, 1, 100, 100, '2023-07-03 01:38:10', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `system.logged`
--

CREATE TABLE `system.logged` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_action` datetime NOT NULL,
  `remember` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `system.logged`
--

INSERT INTO `system.logged` (`id`, `token`, `user`, `password`, `last_action`, `remember`) VALUES
(1, '64a251657f389', 'admin.indusol', '$2y$09$FEnn3tEv.ATZT.s5Sks7juwFWgP0DruBOfXZ/Iu3lLf6vRiKEW8Rq', '2023-07-03 01:45:21', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `system.users`
--

CREATE TABLE `system.users` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `office` int(11) NOT NULL,
  `permission` varchar(255) NOT NULL,
  `last_att` datetime NOT NULL,
  `user_last_att` int(11) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `system.users`
--

INSERT INTO `system.users` (`id`, `user`, `password`, `name`, `office`, `permission`, `last_att`, `user_last_att`, `deleted`) VALUES
(1, 'admin.indusol', '$2y$09$FEnn3tEv.ATZT.s5Sks7juwFWgP0DruBOfXZ/Iu3lLf6vRiKEW8Rq', 'Admin Cartesian', 0, '1||1||1', '2023-06-26 13:58:29', 1, 0),
(11, 'matheus', '$2y$09$pG7QGcCbaAhyehvKqDrll..uJZQIum2kV4lXgBbxwJy/f4NoBTDxW', 'Matheus', 10, '1||0||0', '2023-06-28 18:24:00', 1, 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `points.data`
--
ALTER TABLE `points.data`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `position.data`
--
ALTER TABLE `position.data`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system.logged`
--
ALTER TABLE `system.logged`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `system.users`
--
ALTER TABLE `system.users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `points.data`
--
ALTER TABLE `points.data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `position.data`
--
ALTER TABLE `position.data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `system.logged`
--
ALTER TABLE `system.logged`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `system.users`
--
ALTER TABLE `system.users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

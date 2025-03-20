-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20/03/2025 às 22:32
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `revista`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `conteudo` text NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissoes`
--

CREATE TABLE `permissoes` (
  `id` int(3) NOT NULL,
  `perfil` varchar(40) NOT NULL,
  `postar` enum('S','N') NOT NULL,
  `editar` enum('S','N') NOT NULL,
  `solicitar_post` enum('S','N') NOT NULL,
  `editar_usuarios` enum('S','N') NOT NULL,
  `visionar_post` enum('S','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `permissoes`
--

INSERT INTO `permissoes` (`id`, `perfil`, `postar`, `editar`, `solicitar_post`, `editar_usuarios`, `visionar_post`) VALUES
(1, 'visitante', 'N', 'N', 'N', 'N', 'N'),
(2, 'aluno', 'N', 'N', 'S', 'N', 'N'),
(3, 'professor', 'S', 'S', 'N', 'N', 'S'),
(4, 'admin', 'S', 'S', 'N', 'S', 'N');

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `id_solicitacoes` int(11) NOT NULL,
  `grupo` int(11) NOT NULL,
  `id_usuario_solicitacoes` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `tema` varchar(100) NOT NULL,
  `conteudo` text NOT NULL,
  `img` varchar(225) DEFAULT NULL,
  `sugestao` text NOT NULL,
  `status` enum('pendente','aprovado','rejeitado','revisar') NOT NULL,
  `data_solicitacao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `posts`
--

INSERT INTO `posts` (`id_solicitacoes`, `grupo`, `id_usuario_solicitacoes`, `titulo`, `tema`, `conteudo`, `img`, `sugestao`, `status`, `data_solicitacao`) VALUES
(18, 1, 2, 'Primeira Guerra Mundial', 'HI', 'sdasdasdasdasdasdasdasdas', 'image-cf6d21a81ece4cd595356e05b45e64ae.webp', '', 'rejeitado', '2025-03-01 20:44:31'),
(19, 2, 2, 'sdffdssdfsdfdsfsdffsdfsdf', 'FI', 'sdfsdfsdfsdfsdf', 'image-cf6d21a81ece4cd595356e05b45e64ae.webp', '', 'rejeitado', '2025-03-01 20:47:58'),
(20, 3, 2, 'wqeqweeqweqweqw', 'MA', 'qweqweqwe', 'image-cf6d21a81ece4cd595356e05b45e64ae.webp', '', 'aprovado', '2025-03-01 20:49:23'),
(21, 3, 2, 'wqeqweeqweqweqw', 'MA', 'qweqweqweqweqweqweqwe', 'image-cf6d21a81ece4cd595356e05b45e64ae.webp', '', 'aprovado', '2025-03-01 20:49:23'),
(22, 3, 2, 'wqeqweeqweqweqw', 'MA', 'qweqweqweqweqwe', 'image-cf6d21a81ece4cd595356e05b45e64ae.webp', '', 'aprovado', '2025-03-01 20:49:23'),
(23, 4, 2, 'fghgfhf', 'GEO', 'fghfghfgghfghfghf', NULL, '', 'aprovado', '2025-03-01 20:50:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(140) NOT NULL,
  `senha` varchar(40) NOT NULL,
  `id_permissoes_usuario` int(3) NOT NULL,
  `perfi_foto` varchar(225) NOT NULL,
  `bio` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `id_permissoes_usuario`, `perfi_foto`, `bio`) VALUES
(1, 'Eric', 'eric@gmail.com', '123', 3, '', ''),
(2, 'Roberto', 'aluno@aluno.com', 'aluno', 2, '', '');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_comentario` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Índices de tabela `permissoes`
--
ALTER TABLE `permissoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_solicitacoes`),
  ADD KEY `fk_usuario_solicitacao` (`id_usuario_solicitacoes`);

--
-- Índices de tabela `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_id` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_permissoes` (`id_permissoes_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `permissoes`
--
ALTER TABLE `permissoes`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id_solicitacoes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id_solicitacoes`),
  ADD CONSTRAINT `usuario_comentario` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_usuario_solicitacao` FOREIGN KEY (`id_usuario_solicitacoes`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id_solicitacoes`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuario_permissoes` FOREIGN KEY (`id_permissoes_usuario`) REFERENCES `permissoes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

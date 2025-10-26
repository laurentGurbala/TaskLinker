-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 26 oct. 2025 à 14:12
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tasklinker`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230909181712', '2025-10-01 11:48:27', 216),
('DoctrineMigrations\\Version20230909203112', '2025-10-01 11:48:27', 4),
('DoctrineMigrations\\Version20230909214156', '2025-10-01 11:48:27', 29),
('DoctrineMigrations\\Version20250418095403', '2025-10-01 11:48:27', 28);

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `email` varchar(180) NOT NULL,
  `statut` varchar(255) DEFAULT NULL,
  `date_arrivee` date DEFAULT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `employe`
--

INSERT INTO `employe` (`id`, `nom`, `prenom`, `email`, `statut`, `date_arrivee`, `roles`, `password`) VALUES
(1, 'Dillon', 'Natalie', 'natalie@driblet.com', 'CDI', '2019-06-14', '[\"ROLE_ADMIN\"]', '$2y$13$XVbKR8D1odwBI2m8Cm.FX.VoEGSaeMxgV7wvcnu4vxdO/HRlhNqee'),
(2, 'Baker', 'Demi', 'demi@driblet.com', 'CDD', '2022-09-01', '[]', '$2y$13$9umwS/ND0OTvA9PXGCUXFO9R5zK/Tsav5d9Wnjh7Rn4nLen/pYZAa'),
(3, 'Dupont', 'Marie', 'marie@driblet.com', 'Freelance', '2021-12-20', '[]', '$2y$13$l5AgtKhwE.Qy092Qv.ZiVOdzINr534046Yt6cgPpiw9.vmE3HlKwO');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

CREATE TABLE `projet` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `archive` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `projet`
--

INSERT INTO `projet` (`id`, `nom`, `archive`) VALUES
(1, 'TaskLinker', 0),
(2, 'Application mobile Grand Nancy', 1),
(3, 'Site vitrine Les Soeurs Marchand', 0);

-- --------------------------------------------------------

--
-- Structure de la table `projet_employe`
--

CREATE TABLE `projet_employe` (
  `projet_id` int(11) NOT NULL,
  `employe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `projet_employe`
--

INSERT INTO `projet_employe` (`projet_id`, `employe_id`) VALUES
(1, 1),
(1, 2),
(2, 2),
(2, 3),
(3, 1),
(3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

CREATE TABLE `statut` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `statut`
--

INSERT INTO `statut` (`id`, `libelle`) VALUES
(1, 'To Do'),
(2, 'Doing'),
(3, 'Done');

-- --------------------------------------------------------

--
-- Structure de la table `tache`
--

CREATE TABLE `tache` (
  `id` int(11) NOT NULL,
  `employe_id` int(11) DEFAULT NULL,
  `projet_id` int(11) NOT NULL,
  `statut_id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `deadline` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tache`
--

INSERT INTO `tache` (`id`, `employe_id`, `projet_id`, `statut_id`, `titre`, `description`, `deadline`) VALUES
(1, 2, 1, 3, 'Développement de la structure globale', 'Intégrer les maquettes', '2025-09-24'),
(2, 1, 1, 3, 'Développement de la page projet', 'Page projet avec liste des tâches, édition, modification, suppression et création des tâches', NULL),
(3, 2, 1, 2, 'Développement de la page employé', 'Page employé avec liste des employés, édition, modification, suppression et création des employés', '2025-10-05'),
(4, NULL, 1, 1, 'Gestion des droits d\'accès', 'Un employé ne peut accéder qu\'à ses projets', '2025-10-13'),
(5, 3, 3, 2, 'Réalisation des maquettes', 'À faire sur Figma', '2025-09-13'),
(6, 1, 3, 1, 'Intégration des maquettes', 'Bien faire attention au responsive', NULL),
(7, NULL, 3, 1, 'Optimisation du référencement', NULL, '2025-08-27'),
(8, 1, 1, 2, 'nouvelle tâche', NULL, '2025-10-26'),
(9, NULL, 1, 1, '1523', NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F804D3B9E7927C74` (`email`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `projet`
--
ALTER TABLE `projet`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `projet_employe`
--
ALTER TABLE `projet_employe`
  ADD PRIMARY KEY (`projet_id`,`employe_id`),
  ADD KEY `IDX_7A2E8EC8C18272` (`projet_id`),
  ADD KEY `IDX_7A2E8EC81B65292` (`employe_id`);

--
-- Index pour la table `statut`
--
ALTER TABLE `statut`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tache`
--
ALTER TABLE `tache`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_938720751B65292` (`employe_id`),
  ADD KEY `IDX_93872075C18272` (`projet_id`),
  ADD KEY `IDX_93872075F6203804` (`statut_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `employe`
--
ALTER TABLE `employe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `projet`
--
ALTER TABLE `projet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `statut`
--
ALTER TABLE `statut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `tache`
--
ALTER TABLE `tache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `projet_employe`
--
ALTER TABLE `projet_employe`
  ADD CONSTRAINT `FK_7A2E8EC81B65292` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_7A2E8EC8C18272` FOREIGN KEY (`projet_id`) REFERENCES `projet` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tache`
--
ALTER TABLE `tache`
  ADD CONSTRAINT `FK_938720751B65292` FOREIGN KEY (`employe_id`) REFERENCES `employe` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_93872075C18272` FOREIGN KEY (`projet_id`) REFERENCES `projet` (`id`),
  ADD CONSTRAINT `FK_93872075F6203804` FOREIGN KEY (`statut_id`) REFERENCES `statut` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

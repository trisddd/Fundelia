-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 26 mai 2025 à 21:59
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
-- Base de données : `fundelia`
--

DROP DATABASE IF EXISTS `fundelia`;
CREATE DATABASE `fundelia` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `fundelia`;
-- --------------------------------------------------------

--
-- Structure de la table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `account_type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `IBAN` varchar(27) NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `private_key` varchar(60) DEFAULT NULL,
  `API_key` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `accounts`
--

INSERT INTO `accounts` (`id`, `account_type_id`, `name`, `IBAN`, `balance`, `creation_date`, `private_key`, `API_key`) VALUES
(1, 1, 'Compte principal', 'FR8275076000010180398961919', 10000.00, '2025-05-26 19:04:21', NULL, NULL),
(2, 1, 'Compte principal', 'FR8775076000010356433055419', 100000.00, '2025-05-26 19:06:38', NULL, NULL),
(3, 1, 'Fruit Corporation', 'FR8675076000010130909811719', 200.00, '2025-05-26 19:19:35', '$2y$10$XMIBs0imGjxj0EAexkJU4eYpIcqm2FFgcoix0p3fujOQKwCNJIvmC', '$2y$10$kKLye3WnnPTy6sTgQ292eu6c3B15rZpYc1GL8aS3IGI8NfSoqvgyu');

-- --------------------------------------------------------

--
-- Structure de la table `account_types`
--

CREATE TABLE `account_types` (
  `id` int(11) NOT NULL,
  `name` varchar(44) NOT NULL,
  `ceiling` int(11) DEFAULT NULL,
  `remuneration_rate` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `account_types`
--

INSERT INTO `account_types` (`id`, `name`, `ceiling`, `remuneration_rate`) VALUES
(1, 'Compte Courant', NULL, 0.00),
(2, 'Livret A', 22950, 2.40),
(3, 'LDDS', 12000, 2.40),
(4, 'PER', NULL, 3.60),
(5, 'Assurance Vie', NULL, 2.80);

-- --------------------------------------------------------

--
-- Structure de la table `advisers`
--

CREATE TABLE `advisers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` varchar(60) NOT NULL,
  `birthdate` date NOT NULL,
  `country` varchar(32) NOT NULL,
  `genre` enum('male','female','non-binary','agender') NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `BIN` varchar(6) NOT NULL,
  `bank_code` varchar(5) NOT NULL,
  `private_key` varchar(60) NOT NULL,
  `API_key` varchar(60) NOT NULL,
  `contact_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `banks`
--

INSERT INTO `banks` (`id`, `name`, `BIN`, `bank_code`, `private_key`, `API_key`, `contact_url`) VALUES
  (1, 'Fundelia', '435912', '75076', '$2y$10$FUNMTZflMDFPHk2EWwaLvuwwIAQ1k.Td4RRnGwD6lNMWiv7CBBaHa', '$2y$10$OnaFqUVnU.hqEKL8ccSHQ.xonkiF7I9SwDRi2eAwzA2q1YsiQwEWm', 'http://localhost/fundelia/confirm_transaction');


-- --------------------------------------------------------

--
-- Structure de la table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `IBAN` varchar(27) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `card_type` enum('normal','short-lived') DEFAULT 'normal',
  `card_numbers` bigint(20) NOT NULL,
  `CSC` int(3) DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `name` varchar(32) DEFAULT 'CarteBancaire',
  `holder_name` varchar(64) NOT NULL,
  `freeze` tinyint(1) DEFAULT 0,
  `creation_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cards`
--

INSERT INTO `cards` (`id`, `user_id`, `account_id`, `card_type`, `card_numbers`, `CSC`, `expiration_date`, `name`, `holder_name`, `freeze`, `creation_reason`) VALUES
(1, 1, 1, 'normal', 4359120128968349, 114, '2028-05-26', 'Carte principale', 'Alice', 0, 'Creation de la premiere carte bancaire lors de la cration du compte '),
(2, 2, 2, 'normal', 4359120131149945, 655, '2028-05-26', 'Carte principale', 'Bob', 0, 'Creation de la premiere carte bancaire lors de la cration du compte ');

-- --------------------------------------------------------

--
-- Structure de la table `businesses`
--

CREATE TABLE `businesses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `SIREN` int(9) NULL,
  `email` varchar(320) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_verified` BOOLEAN DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `business_user`
--

CREATE TABLE `business_user` (
  `user_id` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `role` enum('manager','employee') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `labels`
--

CREATE TABLE `labels` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `colour` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `owners`
--

CREATE TABLE `owners` (
  `user_id` int(11) NOT NULL,
  `business_id` int(11) NULL,
  `account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `owners`
--

INSERT INTO `owners` (`user_id`, `account_id`) VALUES
(1, 1),
(2, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `ticket_type` enum('personal_modification','account_closure','identity_validation','help_contact_ticket','add_business_request') NOT NULL,
  `ticket_object` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `adviser_id` int(11) NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `importance_level` enum('1','2','3') NOT NULL,
  `ticket_state` enum('new','in progress','done') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `emitter_IBAN` varchar(27) NOT NULL,
  `emitter_name` varchar(100) NOT NULL,
  `beneficiary_IBAN` varchar(27) NOT NULL,
  `beneficiary_name` varchar(100) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `wording` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `transaction_type` enum('transfer','payment') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `transactions`
--

INSERT INTO `transactions` (`id`, `emitter_IBAN`, `emitter_name`, `beneficiary_IBAN`, `beneficiary_name`, `amount`, `wording`, `date`, `transaction_type`) VALUES
(1, 'FR8275076000010180398961919', 'Alice Feladuni', 'FR8775076000010356433055419', 'Bob Feladuni', 50.00, 'Remboursement Cadeau Candice', '2025-05-26 19:30:06', 'transfer'),
(2, 'FR8775076000010356433055419', 'Bob Feladuni', 'FR8275076000010180398961919', 'Alice Feladuni', 32.00, 'Courses', '2025-05-26 19:47:26', 'transfer'),
(3, 'FR8275076000010180398961919', 'Alice', 'FR8675076000010130909811719', 'Fruit Corporation', 234.00, 'payement de 234 effectue chez Fruit Corporation', '2025-05-26 19:56:22', 'payment');

-- --------------------------------------------------------

--
-- Structure de la table `transactions_labels`
--

CREATE TABLE `transactions_labels` (
  `transaction_id` int(11) NOT NULL,
  `label_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` varchar(60) NOT NULL,
  `birthdate` date NOT NULL,
  `country` varchar(32) DEFAULT NULL,
  `genre` enum('male','female','non-binary','agender') NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_verified` tinyint(1) DEFAULT 0,
  `code` VARCHAR(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `birthdate`, `country`, `genre`, `creation_date`, `is_verified`, `code`) VALUES
(1, 'Alice', 'Feladuni', 'alicefeladuni@gmail.com', '$2y$10$WJ2sNxoiWg8DiUSc5vV8ieTVeF37uQ3DvW.1c4e.McZ0xTXn6x0OK', '2000-01-20', NULL, 'female', '2025-05-26 19:04:21', 1, '$2y$10$22QmsiOysxyIPxbtjbHyiu/0HAxFxtuBQAUx0ZZ9MaNqZ5sGI3BTW'),
(2, 'Bob', 'Feladuni', 'bobfeladuni@gmail.com', '$2y$10$q1cJA3uGA7mWG9rpQB8H3.jDY1gI6iByz/WLjG9vjqdcl0SnWp472', '2001-06-15', NULL, 'male', '2025-05-26 19:06:38', 1, '$2y$10$22QmsiOysxyIPxbtjbHyiu/0HAxFxtuBQAUx0ZZ9MaNqZ5sGI3BTW');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IBAN` (`IBAN`),
  ADD KEY `account_type_id` (`account_type_id`);

--
-- Index pour la table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `advisers`
--
ALTER TABLE `advisers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `BIN` (`BIN`),
  ADD UNIQUE KEY `bank_code` (`bank_code`);

--
-- Index pour la table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `card_numbers` (`card_numbers`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Index pour la table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `SIREN` (`SIREN`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `business_user`
--
ALTER TABLE `business_user`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `business_id` (`business_id`);

--
-- Index pour la table `labels`
--
ALTER TABLE `labels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `owners`
--
ALTER TABLE `owners`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `business_id` (`business_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Index pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `adviser_id` (`adviser_id`);

--
-- Index pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `transactions_labels`
--
ALTER TABLE `transactions_labels`
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `label_id` (`label_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `advisers`
--
ALTER TABLE `advisers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- AUTO_INCREMENT pour la table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `labels`
--
ALTER TABLE `labels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`account_type_id`) REFERENCES `account_types` (`id`);

--
-- Contraintes pour la table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD CONSTRAINT `beneficiaries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cards_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Contraintes pour la table `business_user`
--
ALTER TABLE `business_user`
  ADD CONSTRAINT `business_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `business_user_ibfk_2` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `labels`
--
ALTER TABLE `labels`
  ADD CONSTRAINT `labels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `owners`
--
ALTER TABLE `owners`
  ADD CONSTRAINT `owners_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `owners_ibfk_2` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `owners_ibfk_3` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`adviser_id`) REFERENCES `advisers` (`id`);

--
-- Contraintes pour la table `transactions_labels`
--
ALTER TABLE `transactions_labels`
  ADD CONSTRAINT `transactions_labels_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `transactions_labels_ibfk_2` FOREIGN KEY (`label_id`) REFERENCES `labels` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

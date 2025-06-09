-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 09 juin 2025 à 22:54
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
-- Base de données : `ecoride`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id_utilisateur_validé` int(4) DEFAULT NULL,
  `id_chauffeur_validé` int(4) DEFAULT NULL,
  `commentaire_validé` varchar(255) DEFAULT NULL,
  `note_validé` int(4) DEFAULT NULL,
  `id_covoiturage_validé` int(4) DEFAULT NULL,
  `id_avis` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `avis_en_cours`
--

CREATE TABLE `avis_en_cours` (
  `id_utilisateur_en_cours` int(4) DEFAULT NULL,
  `id_chauffeur_en_cours` int(4) DEFAULT NULL,
  `commentaire_en_cours` varchar(255) DEFAULT NULL,
  `note_en_cours` int(4) DEFAULT NULL,
  `id_covoiturage_en_cours` int(4) DEFAULT NULL,
  `id_avis_en_cours` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `covoiturage`
--

CREATE TABLE `covoiturage` (
  `covoiturage_id` int(11) NOT NULL,
  `date_depart` date NOT NULL,
  `heure_depart` time DEFAULT NULL,
  `lieu_depart` varchar(50) NOT NULL,
  `date_arrivee` date NOT NULL,
  `heure_arrivee` time DEFAULT NULL,
  `lieu_arrivee` varchar(50) NOT NULL,
  `statut` enum('prévu','en_cours','terminé','annulé') DEFAULT 'prévu',
  `nb_place_dispo` int(11) DEFAULT NULL,
  `prix_personne` float NOT NULL,
  `adresse_depart` varchar(100) DEFAULT NULL,
  `adresse_arrivee` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `covoiturage`
--

INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut`, `nb_place_dispo`, `prix_personne`, `adresse_depart`, `adresse_arrivee`) VALUES
(81, '2025-06-13', '10:00:00', 'Paris', '2025-06-13', '14:00:00', 'Lyon', 'prévu', 3, 5, '10 rue de tokyo', '5 rue des bastilles'),
(82, '2025-06-13', '08:00:00', 'Paris', '2025-06-13', '11:00:00', 'Lyon', 'prévu', 1, 6, '1 rue des balis', '10 rue des baumettes');

-- --------------------------------------------------------

--
-- Structure de la table `gain_plateforme`
--

CREATE TABLE `gain_plateforme` (
  `id_gain` int(4) NOT NULL,
  `gain` int(2) NOT NULL,
  `date_de_paiement` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `id_paiement` int(4) NOT NULL,
  `id_chauffeur_paye_ok` int(4) DEFAULT NULL,
  `id_passager_paye_ok` int(4) DEFAULT NULL,
  `id_covoiturage_paye_ok` int(4) DEFAULT NULL,
  `nb_credit_paye_ok` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement_en_cours`
--

CREATE TABLE `paiement_en_cours` (
  `id_paiement_en_cours` int(4) NOT NULL,
  `id_chauffeur` int(4) DEFAULT NULL,
  `id_passager` int(4) DEFAULT NULL,
  `id_covoiturage_paye` int(4) DEFAULT NULL,
  `nb_credit` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `utilisateur_id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `telephone` varchar(50) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `date_naissance` varchar(50) NOT NULL,
  `photo` blob DEFAULT NULL,
  `pseudo` varchar(50) NOT NULL,
  `parametre` varchar(10) DEFAULT 'valide',
  `credit` int(11) DEFAULT NULL,
  `note` decimal(3,2) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `preferences` varchar(1000) DEFAULT NULL,
  `animal` varchar(3) DEFAULT NULL,
  `fumeur` varchar(3) DEFAULT NULL,
  `compte_employee` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `photo`, `pseudo`, `parametre`, `credit`, `note`, `role`, `preferences`, `animal`, `fumeur`, `compte_employee`) VALUES
(79, 'Pitt', 'Brad', 'brad@gmail.com', '$2y$10$DDSE.DeFQYTaAOZJG8fwce8kqrwYnnKa1Ep5L4Ki5UHR/9o0O2KRu', '0632415245', '5 rue des lilas', '1980-07-21', 0x4272616420706974742e706e67, 'Brad', 'valide', 20, NULL, 'passager', '', 'non', 'non', NULL),
(80, 'Clooney', 'Georges', 'georges@gmail.com', '$2y$10$eAMyBsSOielDfdfpg9XXYOgB9GHr0yH38sEZeovLVXWmc3fLXvMGa', '0625415263', '10 rue royale', '1967-07-21', 0x4765726f6765732e706e67, 'Georgy', 'valide', 20, NULL, 'passager', '', 'non', 'non', NULL),
(81, 'Wahlberg', 'Marc', 'marc@gmail.com', '$2y$10$seYnsezwT819HITp2tu0PuOeWeeEvRztuqkrPQcsNzsslBffU05GG', '0652547585', '21 Hollywood Street', '1973-07-21', 0x6d6172632e706e67, 'Marco', 'valide', 20, NULL, 'chauffeur', 'J\'aime le métal et la boxe.', 'oui', 'oui', NULL),
(82, 'Cruise', 'Tom', 'tom@gmail.com', '$2y$10$6ECazGWI0rihdMqFPZhWuO2neL6/n3l8vN.S8v8iAQOjhm2lLdeQq', '0652535859', '18 rue de la paix', '1975-07-21', 0x746f6d2e706e67, 'Maverick', 'valide', 20, NULL, 'passager&chauffeur', '', 'oui', 'non', NULL),
(83, 'Alba', 'Jessica', 'jessica@gmail.com', '$2y$10$c0RMJyDb2aF4KdFrPwZJQeCWowBwzNx4.KHDtRXc9CMrvPPTobC2u', '0624457565', '20 rue des roses', '1991-04-15', 0x4a6573736963612e706e67, 'Jess la pro', 'valide', 20, NULL, 'chauffeur', 'J\'aime grignoter des beignets', 'non', 'non', 'employé'),
(84, 'Stallone', 'Sylvester', 'rocky@gmail.com', '$2y$10$Y1d6BnOl34Yi6YpnP35wKOVMrfFo.3N9ZgQW6pl1Z/N4b6SwxEdWm', '0651877441', '10 rue du ring', '1946-07-06', 0x706174726f6e2e706e67, 'Patron', 'valide', 20, NULL, 'passager&chauffeur', '', 'non', 'oui', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_participe_covoiturage`
--

CREATE TABLE `utilisateur_participe_covoiturage` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_covoiturage` int(11) NOT NULL,
  `avis_envoye` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_possede_voiture`
--

CREATE TABLE `utilisateur_possede_voiture` (
  `id_utilisateur_possede_voiture` int(4) DEFAULT NULL,
  `id_voiture_possede_utilisateur` int(4) DEFAULT NULL,
  `id_possede` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur_possede_voiture`
--

INSERT INTO `utilisateur_possede_voiture` (`id_utilisateur_possede_voiture`, `id_voiture_possede_utilisateur`, `id_possede`) VALUES
(81, 49, 34),
(82, 50, 35),
(83, 51, 36),
(84, 52, 37);

-- --------------------------------------------------------

--
-- Structure de la table `voiture`
--

CREATE TABLE `voiture` (
  `voiture_id` int(11) NOT NULL,
  `modele` varchar(50) NOT NULL,
  `immatriculation` varchar(50) NOT NULL,
  `energie` varchar(50) NOT NULL,
  `couleur` varchar(50) NOT NULL,
  `date_premiere_immatriculation` varchar(50) NOT NULL,
  `marque` varchar(50) DEFAULT NULL,
  `nb_place_voiture` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `voiture`
--

INSERT INTO `voiture` (`voiture_id`, `modele`, `immatriculation`, `energie`, `couleur`, `date_premiere_immatriculation`, `marque`, `nb_place_voiture`) VALUES
(49, '208', 'FG-987-PJ', 'Thermique', 'Bleue', '2020-07-21', 'Peugeot', 5),
(50, 'Twingo', 'AD-543-78', 'Thermique', 'Rouge', '2024-06-23', 'Renault', 4),
(51, 'XL', 'SD-789-BG', 'Electrique', 'Verte', '2025-01-23', 'Mini Cooper', 4),
(52, 'S', 'JH-444-798', 'Electrique', 'Grise', '2024-07-21', 'Tesla', 2);

-- --------------------------------------------------------

--
-- Structure de la table `voiture_utilise_covoiturage`
--

CREATE TABLE `voiture_utilise_covoiturage` (
  `id_utilise` int(4) NOT NULL,
  `id_voiture_utilise_covoiturage` int(4) NOT NULL,
  `id_covoiturage_utilise_voiture` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `voiture_utilise_covoiturage`
--

INSERT INTO `voiture_utilise_covoiturage` (`id_utilise`, `id_voiture_utilise_covoiturage`, `id_covoiturage_utilise_voiture`) VALUES
(65, 50, 81),
(66, 52, 82);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id_avis`);

--
-- Index pour la table `avis_en_cours`
--
ALTER TABLE `avis_en_cours`
  ADD PRIMARY KEY (`id_avis_en_cours`);

--
-- Index pour la table `covoiturage`
--
ALTER TABLE `covoiturage`
  ADD PRIMARY KEY (`covoiturage_id`);

--
-- Index pour la table `gain_plateforme`
--
ALTER TABLE `gain_plateforme`
  ADD PRIMARY KEY (`id_gain`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id_paiement`);

--
-- Index pour la table `paiement_en_cours`
--
ALTER TABLE `paiement_en_cours`
  ADD PRIMARY KEY (`id_paiement_en_cours`),
  ADD KEY `paiement_en_cours` (`id_covoiturage_paye`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`utilisateur_id`),
  ADD KEY `parametre` (`parametre`);

--
-- Index pour la table `utilisateur_participe_covoiturage`
--
ALTER TABLE `utilisateur_participe_covoiturage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_participe_covoiturage_ibfk_1` (`id_utilisateur`),
  ADD KEY `utilisateur_participe_covoiturage_ibfk_2` (`id_covoiturage`);

--
-- Index pour la table `utilisateur_possede_voiture`
--
ALTER TABLE `utilisateur_possede_voiture`
  ADD PRIMARY KEY (`id_possede`),
  ADD KEY `id_utilisateur_possede_voiture` (`id_utilisateur_possede_voiture`),
  ADD KEY `id_voiture_possede_utilisateur` (`id_voiture_possede_utilisateur`);

--
-- Index pour la table `voiture`
--
ALTER TABLE `voiture`
  ADD PRIMARY KEY (`voiture_id`);

--
-- Index pour la table `voiture_utilise_covoiturage`
--
ALTER TABLE `voiture_utilise_covoiturage`
  ADD PRIMARY KEY (`id_utilise`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id_avis` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `avis_en_cours`
--
ALTER TABLE `avis_en_cours`
  MODIFY `id_avis_en_cours` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT pour la table `covoiturage`
--
ALTER TABLE `covoiturage`
  MODIFY `covoiturage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT pour la table `gain_plateforme`
--
ALTER TABLE `gain_plateforme`
  MODIFY `id_gain` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id_paiement` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `paiement_en_cours`
--
ALTER TABLE `paiement_en_cours`
  MODIFY `id_paiement_en_cours` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `utilisateur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT pour la table `utilisateur_participe_covoiturage`
--
ALTER TABLE `utilisateur_participe_covoiturage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT pour la table `utilisateur_possede_voiture`
--
ALTER TABLE `utilisateur_possede_voiture`
  MODIFY `id_possede` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `voiture`
--
ALTER TABLE `voiture`
  MODIFY `voiture_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pour la table `voiture_utilise_covoiturage`
--
ALTER TABLE `voiture_utilise_covoiturage`
  MODIFY `id_utilise` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `paiement_en_cours`
--
ALTER TABLE `paiement_en_cours`
  ADD CONSTRAINT `paiement_en_cours` FOREIGN KEY (`id_covoiturage_paye`) REFERENCES `covoiturage` (`covoiturage_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateur_participe_covoiturage`
--
ALTER TABLE `utilisateur_participe_covoiturage`
  ADD CONSTRAINT `utilisateur_participe_covoiturage_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`utilisateur_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `utilisateur_participe_covoiturage_ibfk_2` FOREIGN KEY (`id_covoiturage`) REFERENCES `covoiturage` (`covoiturage_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateur_possede_voiture`
--
ALTER TABLE `utilisateur_possede_voiture`
  ADD CONSTRAINT `utilisateur_possede_voiture_ibfk_1` FOREIGN KEY (`id_utilisateur_possede_voiture`) REFERENCES `utilisateur` (`utilisateur_id`),
  ADD CONSTRAINT `utilisateur_possede_voiture_ibfk_2` FOREIGN KEY (`id_voiture_possede_utilisateur`) REFERENCES `voiture` (`voiture_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

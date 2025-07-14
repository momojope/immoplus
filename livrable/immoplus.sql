-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 08 juil. 2025 à 17:34
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `immoplus`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `bien_id` int(11) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `note` int(11) DEFAULT NULL CHECK (`note` between 1 and 5),
  `date_avis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `client_id`, `bien_id`, `commentaire`, `note`, `date_avis`) VALUES
(1, 1, 2, 'keur gui mol na wayyy !!!, mais haliss amoul', 5, '2025-07-03 21:19:07');

-- --------------------------------------------------------

--
-- Structure de la table `biens`
--

CREATE TABLE `biens` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('villa','appartement','terrain') NOT NULL,
  `statut` enum('vente','location') NOT NULL,
  `prix` decimal(12,2) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `image_principale` varchar(255) DEFAULT NULL,
  `autres_images` text DEFAULT NULL,
  `date_publication` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `biens`
--

INSERT INTO `biens` (`id`, `titre`, `description`, `type`, `statut`, `prix`, `adresse`, `image_principale`, `autres_images`, `date_publication`) VALUES
(1, 'Villa Bord de Mer', 'Belle villa moderne avec piscine et vue sur mer.', 'villa', 'vente', '150000000.00', 'Dakar, Almadies', 'villa1.jpg', 'A1.jpg, A2.JPG', '2025-07-03 20:30:28'),
(2, 'Appartement Standing', 'Appartement de 3 chambres proche centre-ville.', 'appartement', 'location', '350000.00', 'Dakar, Plateau', 'appart1.jpg', NULL, '2025-07-03 20:30:28'),
(3, 'Terrain Constructible', 'Terrain de 500m², idéal pour projet immobilier.', 'terrain', 'vente', '25000000.00', 'Diamniadio', 'M.jpg', '', '2025-07-03 20:30:28'),
(4, 'Villa Luxueuse', 'Villa 5 chambres, jardin et garage.', 'villa', 'vente', '200000000.00', 'Dakar, Mermoz', 'G1.jpg', '', '2025-07-03 20:30:28'),
(5, 'Appartement Meublé', 'Appartement tout équipé, prêt à louer.', 'appartement', 'location', '400000.00', 'Dakar, Point E', 'I1.jpg', '', '2025-07-03 20:30:28'),
(6, 'Terrain Agricole', 'Grand terrain agricole, accès facile.', 'terrain', 'vente', '12000000.00', 'Thiès', 'O.jpg', '', '2025-07-03 20:30:28'),
(7, 'Villa Contemporaine', 'Architecture moderne, piscine privée.', 'villa', 'vente', '180000000.00', 'Dakar, Ngor', 'immeuble senxeweul.jpg', '', '2025-07-03 20:30:28'),
(8, 'Appartement Familial', '4 chambres, balcon, proche écoles.', 'appartement', 'location', '300000.00', 'Dakar, Liberté 6', 'C1.jpg', '', '2025-07-03 20:30:28'),
(9, 'Terrain Viabilisé', 'Terrain viabilisé, tous documents disponibles.', 'terrain', 'vente', '35000000.00', 'Saly', 'N.jpg', '', '2025-07-03 20:30:28'),
(10, 'Villa Duplex', 'Villa duplex avec terrasse panoramique.', 'villa', 'vente', '220000000.00', 'Dakar, Sacré Cœur', 'K1.jpg', '', '2025-07-03 20:30:28'),
(11, 'Studio Moderne', 'Studio équipé, idéal étudiant ou jeune actif.', 'appartement', 'location', '200000.00', 'Dakar, Médina', 'J1.jpg', '', '2025-07-03 20:30:28'),
(12, 'Terrain Résidentiel', 'Beau terrain plat, quartier résidentiel.', 'terrain', 'vente', '30000000.00', 'Rufisque', 'H2.jpg', '', '2025-07-03 20:30:28'),
(13, 'Villa Résidence Sécurisée', 'Villa dans résidence fermée, sécurité 24h/24.', 'villa', 'vente', '175000000.00', 'Dakar, Fann', 'H1.jpg', '', '2025-07-03 20:30:28'),
(14, 'Appartement Vue Mer', 'Appartement avec terrasse et vue imprenable.', 'appartement', 'location', '500000.00', 'Dakar, Corniche Ouest', 'K2.jpg', '', '2025-07-03 20:30:28'),
(15, 'Terrain en Zone Urbaine', 'Terrain proche route principale.', 'terrain', 'vente', '28000000.00', 'Keur Massar', 'B3.jpg', '', '2025-07-03 20:30:28'),
(16, 'keurGui', NULL, 'villa', 'vente', '12000000.00', NULL, 'B1.jpg', NULL, '2025-07-04 04:03:05');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `date_inscription` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `nom`, `email`, `telephone`, `mot_de_passe`, `date_inscription`) VALUES
(1, 'Ndeye drame', 'ndeye.drame@gmail.com', '774585669', '$2y$10$uW/grJSlrT0c0XUMqhFdD.elM.aJpjbAccHLa2I3SZY8bQbWUEZ2q', '2025-07-03 17:12:36');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `bien_id` int(11) DEFAULT NULL,
  `date_commande` datetime DEFAULT current_timestamp(),
  `statut` enum('en_attente','confirmée','payée','annulée') NOT NULL DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `client_id`, `bien_id`, `date_commande`, `statut`) VALUES
(3, 1, 16, '2025-07-07 02:45:44', 'payée'),
(4, 1, 1, '2025-07-07 02:52:51', 'payée'),
(5, 1, 1, '2025-07-07 03:15:35', 'confirmée');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_envoi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `nom`, `email`, `message`, `date_envoi`) VALUES
(1, 'Ndeye drame', 'ndeye.drame@gmail.com', 'sama location do lene ko valider mou gaw', '2025-07-04 03:06:06');

-- --------------------------------------------------------

--
-- Structure de la table `newsletters`
--

CREATE TABLE `newsletters` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_inscription` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `newsletters`
--

INSERT INTO `newsletters` (`id`, `email`, `date_inscription`) VALUES
(1, 'ndeye.drame@gmail.com', '2025-07-03 21:18:00');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `bien_id` int(11) DEFAULT NULL,
  `date_reservation` datetime DEFAULT current_timestamp(),
  `statut` enum('en_attente','confirmée','payée','annulée') NOT NULL DEFAULT 'en_attente',
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `client_id`, `bien_id`, `date_reservation`, `statut`, `date_debut`, `date_fin`) VALUES
(5, 1, 2, '2025-07-07 02:57:56', 'payée', '2025-07-07', '2025-07-14'),
(6, 1, 5, '2025-07-08 15:04:08', 'confirmée', '2025-07-10', '2025-08-10');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `bien_id` (`bien_id`);

--
-- Index pour la table `biens`
--
ALTER TABLE `biens`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `bien_id` (`bien_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `bien_id` (`bien_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `biens`
--
ALTER TABLE `biens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`bien_id`) REFERENCES `biens` (`id`);

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`bien_id`) REFERENCES `biens` (`id`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`bien_id`) REFERENCES `biens` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 05 oct. 2021 à 08:33
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `room_project`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id_avis` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) NOT NULL,
  `id_salle` int(3) NOT NULL,
  `commentaire` text NOT NULL,
  `note` int(2) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `id_membre` (`id_membre`),
  KEY `id_salle` (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id_avis`, `id_membre`, `id_salle`, `commentaire`, `note`, `date_enregistrement`) VALUES
(1, 3, 3, 'super salle !', 5, '2021-10-01 10:07:31'),
(2, 3, 5, 'manque de lumiere', 2, '2021-10-01 10:07:44'),
(3, 3, 9, 'magnifique il y a tout ce qu\'il faut', 5, '2021-10-01 10:08:05'),
(4, 3, 3, 'tres bien', 4, '2021-10-01 10:46:52'),
(5, 3, 8, 'xbxbxcbxx', 4, '2021-10-01 10:48:50'),
(6, 3, 8, 'cxvbxvbx', 3, '2021-10-01 10:48:59'),
(7, 3, 8, 'cxvbxvbx', 3, '2021-10-01 12:15:12'),
(8, 3, 8, 'cxvbxvbx', 3, '2021-10-01 13:43:12'),
(9, 3, 5, 'dfgdfgdg', 3, '2021-10-01 16:06:02'),
(10, 3, 5, 'fgdgdfgfd', 3, '2021-10-01 16:06:12'),
(11, 3, 5, 'sdfdsfsdf', 5, '2021-10-01 16:06:24'),
(13, 3, 1, 'bof', 3, '2021-10-02 20:58:47'),
(14, 3, 10, 'top', 4, '2021-10-02 20:59:00'),
(19, 9, 10, 'super bureau', 5, '2021-10-04 20:39:00');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) DEFAULT NULL,
  `id_produit` int(3) DEFAULT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `id_membre` (`id_membre`),
  KEY `id_produit` (`id_produit`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_membre`, `id_produit`, `date_enregistrement`) VALUES
(1, 3, 8, '2021-09-30 18:36:50'),
(3, 4, 13, '2021-10-01 20:53:50'),
(4, 4, 17, '2021-10-01 20:53:56'),
(5, 4, 19, '2021-10-01 20:54:01'),
(6, 8, 20, '2021-10-02 20:44:24'),
(7, 8, 21, '2021-10-02 20:44:33'),
(8, 9, 18, '2021-10-02 20:45:03'),
(9, 9, 14, '2021-10-02 20:45:07'),
(10, 3, 23, '2021-10-03 19:14:46'),
(11, 3, 15, '2021-10-03 19:16:14'),
(12, 5, 16, '2021-10-03 22:34:59');

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(3) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` int(1) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_membre`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(3, 'admin', '$2y$10$l1PhyDCm7uDaBCuLG7ccGeU97HoHMVJqXbWAjlrQUby1Q0KOBBXAO', 'raf', 'rafik', 'raf@mail.fr', 'm', 2, '2021-09-09 18:14:12'),
(4, 'test', '$2y$10$KnHW6pNISF7.GMq.ykjaUOiqQUi0eIFynRaHwtoFzQuxK5/tnI84m', 'raf2', 'rafik', 'raf@mail.fr', 'm', 1, '2021-09-09 18:14:26'),
(5, 'miss', '$2y$10$9QcyXfs/mdsnrvI80GPiMuXNhqRv7QMARq1cY8flw4zfDrZa8iN2W', 'mi', 'mi', 'mi@me.com', 'f', 1, '2021-09-15 16:53:01'),
(6, 'rafik22', '$2y$10$ONrGtc5juNVXKTYQEF/nbuMftvKM4p7VNqdasZc/bpXUQ1YrQ2FtK', 'dsfsdfsdf', 'liiiiiiiiiiiiiiiiiiiiiiiiiii', 'fssf@mail.com', 'm', 1, '2021-09-15 22:40:42'),
(8, 'testmail', '$2y$10$BdTfl8NI9DZlboFhmllH1eKzpuAN0OOvgY0YHHq3s3Nv5pI5401x.', 'testmail', 'test', 'oulmou.rafik@gmail.com', 'm', 1, '2021-09-20 19:27:08'),
(9, 'testmail2', '$2y$10$u49Sl8aXNugmB5pFm2hd1OBVs8wNlp7TfAcqfLIOE0a2lOkSmcQuK', 'testmail2', 'test2', 'oulmou.rafik@gmail.com', 'm', 1, '2021-09-20 19:31:51'),
(10, 'testmail3', '$2y$10$5b4pzLe9BhToJ.Q1mwq1AeYvJxL1xBA49mtwd2QJjXMshz.uPizVe', 'testmail2', 'test2', 'oulmou.rafik@gmail.com', 'm', 1, '2021-09-20 19:33:45'),
(11, 'testmail4', '$2y$10$/JsTdUg.14ETdFZxH4PdZ.OSZDtOqhE5rKZKmdJjqdDXMqjFzs/5m', 'testmail2', 'test2', 'oulmou.rafik@gmail.com', 'm', 1, '2021-09-20 19:34:35'),
(12, 'testmail5', '$2y$10$OGhmkBl4R/COj63vaRpv.efJWUClzttJ1mLYQas.qIRIa5WWtBp72', 'testmail2', 'test2', 'oulmou.rafik@gmail.com', 'm', 1, '2021-09-20 19:36:51'),
(13, 'test44', '$2y$10$gcrA.NJOBs4HzmUonF9WUeaP.ybQQxUg8K2zyqjVTloFAbi0D94LK', 'oulm', 'raf', 'oulmou.rafik@gmail.com', 'm', 1, '2021-09-26 20:09:52');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id_produit` int(3) NOT NULL AUTO_INCREMENT,
  `id_salle` int(3) DEFAULT NULL,
  `date_arrivee` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `prix` int(4) NOT NULL,
  `etat` enum('libre','réservé') NOT NULL DEFAULT 'libre',
  PRIMARY KEY (`id_produit`),
  KEY `id_salle` (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `id_salle`, `date_arrivee`, `date_depart`, `prix`, `etat`) VALUES
(8, 4, '2021-10-10 00:00:00', '2021-10-15 00:00:00', 190, 'réservé'),
(9, 10, '2021-09-25 00:00:00', '2021-09-28 00:00:00', 120, 'libre'),
(11, 3, '2021-10-02 00:00:00', '2021-10-06 00:00:00', 560, 'libre'),
(12, 7, '2021-10-15 00:00:00', '2021-10-20 00:00:00', 380, 'réservé'),
(13, 8, '2021-10-10 00:00:00', '2021-10-14 00:00:00', 890, 'réservé'),
(14, 9, '2021-11-15 00:00:00', '2021-11-20 00:00:00', 500, 'réservé'),
(15, 5, '2021-11-15 00:00:00', '2021-11-18 00:00:00', 1200, 'réservé'),
(16, 3, '2021-11-08 00:00:00', '2021-11-13 00:00:00', 350, 'réservé'),
(17, 6, '2021-10-08 00:00:00', '2021-10-12 00:00:00', 780, 'réservé'),
(18, 1, '2021-11-12 00:00:00', '2021-12-15 00:00:00', 650, 'réservé'),
(19, 2, '2021-10-16 00:00:00', '2021-10-20 00:00:00', 640, 'réservé'),
(20, 4, '2021-11-01 00:00:00', '2021-11-06 00:00:00', 210, 'réservé'),
(21, 7, '2021-12-01 00:00:00', '2021-12-05 00:00:00', 350, 'réservé'),
(22, 10, '2021-12-04 00:00:00', '2021-12-06 00:00:00', 180, 'libre'),
(23, 1, '2021-12-01 00:00:00', '2021-12-01 00:00:00', 180, 'réservé');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `id_salle` int(3) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `pays` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `cp` varchar(5) NOT NULL,
  `capacite` int(3) NOT NULL,
  `categorie` enum('réunion','bureau','formation') NOT NULL,
  `plan` longtext NOT NULL,
  PRIMARY KEY (`id_salle`),
  UNIQUE KEY `titre` (`titre`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `titre`, `description`, `photo`, `pays`, `ville`, `adresse`, `cp`, `capacite`, `categorie`, `plan`) VALUES
(1, 'Mozart', 'Cette salle est parfaite pour vos réunions d\'entreprise. Notre équipe sera ravie de vous accueillir en vous offrant un petit-déjeuner dès votre arrivée.', 'Mozart-pic770.jpg', 'France', 'Paris', '17, rue de Turbigo', '75002', 30, 'réunion', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.6872891707085!2d2.347384715674588!3d48.864173179288024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1999a4a543%3A0x4eeed6ec125a0203!2s17%20Rue%20de%20Turbigo%2C%2075002%20Paris!5e0!3m2!1sfr!2sfr!4v1632058002699!5m2!1sfr!2sfr'),
(2, 'Beethoven', 'Lumineuse et spacieuse, cette salle est parfaite pour vos réunions d\'entreprise. Notre équipe sera ravie de vous accueillir en vous offrant un petit-déjeuner dès votre arrivée.', 'Beethoven-pic443.jpg', 'France', 'Paris', '17, rue de Turbigo', '75002', 20, 'réunion', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.6872891707085!2d2.347384715674588!3d48.864173179288024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1999a4a543%3A0x4eeed6ec125a0203!2s17%20Rue%20de%20Turbigo%2C%2075002%20Paris!5e0!3m2!1sfr!2sfr!4v1632058002699!5m2!1sfr!2sfr'),
(3, 'Wagner', 'Idéale pour vos réunions en petit comité, cette salle vous permettra de travailler au calme. Notre équipe sera ravie de vous accueillir en vous offrant un petit-déjeuner dès votre arrivée.', 'Wagner-pic542.jpg', 'France', 'Paris', '17, rue de Turbigo', '75002', 6, 'réunion', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.6872891707085!2d2.347384715674588!3d48.864173179288024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1999a4a543%3A0x4eeed6ec125a0203!2s17%20Rue%20de%20Turbigo%2C%2075002%20Paris!5e0!3m2!1sfr!2sfr!4v1632058002699!5m2!1sfr!2sfr'),
(4, 'Bach', 'Ce bureau vous permettra de rester concentré. Notre équipe sera ravie de vous accueillir en vous offrant un petit-déjeuner dès votre arrivée.', 'Bach-pic397.jpg', 'France', 'Paris', '17, rue de Turbigo', '75002', 1, 'bureau', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.6872891707085!2d2.347384715674588!3d48.864173179288024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1999a4a543%3A0x4eeed6ec125a0203!2s17%20Rue%20de%20Turbigo%2C%2075002%20Paris!5e0!3m2!1sfr!2sfr!4v1632058002699!5m2!1sfr!2sfr'),
(5, 'Ravel', 'Cette salle spacieuse vous permettra de dispenser une formation à vos nombreux collaborateurs. Notre équipe sera ravie de vous accueillir en vous offrant un petit-déjeuner dès votre arrivée.', 'Ravel-pic227.jpg', 'France', 'Lyon', '28, Quai Claude Bernard', '69007', 50, 'formation', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2784.136691930459!2d4.832353215652863!3d45.748405522455116!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47f4ea4918bfb52d%3A0xb575da893eda2e99!2s28%20Quai%20Claude%20Bernard%2C%2069007%20Lyon!5e0!3m2!1sfr!2sfr!4v1632060177847!5m2!1sfr!2sfr'),
(6, 'Berlioz', 'Disposant de tous les équipements nécessaires, cette salle spacieuse vous permettra de travailler au calme. Notre équipe sera ravie de vous accueillir en vous offrant un petit-déjeuner dès votre arrivée.', 'Berlioz-pic368.jpg', 'France', 'Lyon', '28, Quai Claude Bernard', '69007', 15, 'réunion', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2784.136691930459!2d4.832353215652863!3d45.748405522455116!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47f4ea4918bfb52d%3A0xb575da893eda2e99!2s28%20Quai%20Claude%20Bernard%2C%2069007%20Lyon!5e0!3m2!1sfr!2sfr!4v1632060177847!5m2!1sfr!2sfr'),
(7, 'Debussy', 'Ce bureau dispose des équipements nécessaires pour vous permettre de travailler en comité restreint. Notre équipe sera ravie de vous accueillir en vous offrant un petit-déjeuner dès votre arrivée.', 'Debussy-pic741.jpg', 'France', 'Lyon', '28, Quai Claude Bernard', '69007', 6, 'bureau', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2784.136691930459!2d4.832353215652863!3d45.748405522455116!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47f4ea4918bfb52d%3A0xb575da893eda2e99!2s28%20Quai%20Claude%20Bernard%2C%2069007%20Lyon!5e0!3m2!1sfr!2sfr!4v1632060177847!5m2!1sfr!2sfr'),
(8, 'Vivaldi', 'Cette salle spacieuse vous permettra de réunir plusieurs collaborateurs pour vos réunions d\'entreprise. Notre équipe sera ravie de vous accueillir en vous offrant un petit-déjeuner dès votre arrivée.', 'Vivaldi-pic123.jpg', 'France', 'Marseille', '10, Boulevard de la Libération', '13001', 30, 'réunion', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2903.7132418992164!2d5.384384915588404!3d43.29932658325879!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12c9c0a2788600fd%3A0xdd4b1c8cde4c6af!2s10%20Bd%20de%20la%20Lib%C3%A9ration%2C%2013001%20Marseille!5e0!3m2!1sfr!2sfr!4v1632060237986!5m2!1sfr!2sfr'),
(9, 'Verdi', 'Cette salle lumineuse est parfaite pour tout type de réunions. Notre équipe sera ravie de vous accueillir en vous offrant un petit-déjeuner dès votre arrivée.', 'Verdi-pic641.jpg', 'France', 'Marseille', '10, Boulevard de la Libération', '13001', 6, 'réunion', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2903.7132418992164!2d5.384384915588404!3d43.29932658325879!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12c9c0a2788600fd%3A0xdd4b1c8cde4c6af!2s10%20Bd%20de%20la%20Lib%C3%A9ration%2C%2013001%20Marseille!5e0!3m2!1sfr!2sfr!4v1632060237986!5m2!1sfr!2sfr'),
(10, 'Bellini', 'Idéalement situé, ce bureau vous permettra de vous retrouver au calme. Notre équipe sera ravie de vous accueillir en vous offrant un petit-déjeuner dès votre arrivée.', 'Bellini-pic917.jpg', 'France', 'Marseille', '10, Boulevard de la Libération', '13001', 1, 'bureau', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2903.7132418992164!2d5.384384915588404!3d43.29932658325879!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12c9c0a2788600fd%3A0xdd4b1c8cde4c6af!2s10%20Bd%20de%20la%20Lib%C3%A9ration%2C%2013001%20Marseille!5e0!3m2!1sfr!2sfr!4v1632060237986!5m2!1sfr!2sfr');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

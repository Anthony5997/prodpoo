-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 14 mai 2019 à 02:28
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `prodpoo`
--
CREATE DATABASE IF NOT EXISTS `prodpoo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `prodpoo`;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `prod_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_sku` varchar(13) NOT NULL,
  `prod_desc` varchar(250) NOT NULL,
  `typprod_id` int(11) NOT NULL,
  `prod_img` varchar(250) NOT NULL,
  `prod_prix` double(7,2) NOT NULL,
  `prod_stock` int(11) NOT NULL,
  PRIMARY KEY (`prod_id`),
  KEY `type_produit` (`typprod_id`,`prod_desc`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`prod_id`, `prod_sku`, `prod_desc`, `typprod_id`, `prod_img`, `prod_prix`, `prod_stock`) VALUES
(1, '0101', 'Samsung 27\" LED - C27FG70FQU', 1, 'LD0004120132_2.jpg', 300.00, 50),
(2, '0102', 'Samsung 24\" LED - C24FG73FQ', 1, 'LD0004994400_2.jpg', 230.00, 50),
(3, '0103', 'Samsung 27\" LED - S27H850QFU', 1, '53120239_9588406981.jpg', 400.00, 50),
(4, '0204', 'HP Elite 8200', 2, 'tour3.jpg', 200.00, 50),
(5, '0205', 'DELL OPTIPLEX 7010 SFF - WINDOWS 10', 2, 'tour1.jpg', 400.00, 50),
(6, '0206', 'Inspiron gaming PC', 2, 'tour2.png', 700.00, 50),
(7, '0207', 'Fox Meca Red', 3, 'foxmecared.jpg', 100.00, 50),
(8, '0208', 'Aorus K7', 3, 'aorusk7.jpg', 130.00, 50),
(9, '0209', 'ASUS Cerberus Mech RGB (Kaihua Red)', 3, 'asus.jpg', 130.00, 50),
(26, '0226', 'Microsoft Clavier Surface', 3, 'surface.jpg', 120.00, 50);

-- --------------------------------------------------------

--
-- Structure de la table `produit_carac`
--

DROP TABLE IF EXISTS `produit_carac`;
CREATE TABLE IF NOT EXISTS `produit_carac` (
  `prod_id` int(11) NOT NULL,
  `typprod_caracnum` int(11) NOT NULL,
  `prod_caracval` varchar(250) NOT NULL,
  PRIMARY KEY (`prod_id`,`typprod_caracnum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produit_carac`
--

INSERT INTO `produit_carac` (`prod_id`, `typprod_caracnum`, `prod_caracval`) VALUES
(1, 1, '24'),
(2, 1, '27'),
(3, 1, '27'),
(4, 1, 'i5'),
(5, 1, 'i3'),
(6, 1, 'i7'),
(7, 1, 'azerty'),
(7, 2, '101'),
(8, 1, 'azerty'),
(8, 2, '101'),
(9, 1, 'azerty'),
(9, 2, '101'),
(26, 1, 'azerty'),
(26, 2, '101');

-- --------------------------------------------------------

--
-- Structure de la table `typprod`
--

DROP TABLE IF EXISTS `typprod`;
CREATE TABLE IF NOT EXISTS `typprod` (
  `typprod_id` int(11) NOT NULL AUTO_INCREMENT,
  `typprod_desc` varchar(250) NOT NULL,
  PRIMARY KEY (`typprod_id`),
  KEY `type_description` (`typprod_desc`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `typprod`
--

INSERT INTO `typprod` (`typprod_id`, `typprod_desc`) VALUES
(3, 'Clavier'),
(1, 'Ecran'),
(2, 'Tour');

-- --------------------------------------------------------

--
-- Structure de la table `typprod_carac`
--

DROP TABLE IF EXISTS `typprod_carac`;
CREATE TABLE IF NOT EXISTS `typprod_carac` (
  `typprod_id` int(11) NOT NULL,
  `typprod_caracnum` int(11) NOT NULL,
  `typprod_caracdesc` varchar(250) NOT NULL,
  PRIMARY KEY (`typprod_id`,`typprod_caracnum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `typprod_carac`
--

INSERT INTO `typprod_carac` (`typprod_id`, `typprod_caracnum`, `typprod_caracdesc`) VALUES
(1, 1, 'Taille'),
(2, 1, 'Processeur'),
(3, 1, 'Disposition Clavier'),
(3, 2, 'Nombre touches');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

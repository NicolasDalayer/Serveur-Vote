-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 09 Janvier 2014 à 19:51
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `db`
--

-- --------------------------------------------------------

--
-- Structure de la table `candidat`
--

CREATE TABLE IF NOT EXISTS `candidat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(35) NOT NULL,
  `vote` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vote` (`vote`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `candidat`
--

INSERT INTO `candidat` (`id`, `nom`, `vote`) VALUES
(3, 'FN Front National', 5),
(4, 'UMP', 5),
(5, 'PS Parti Socialiste', 5),
(6, 'EELV', 5),
(7, 'PCF', 5),
(8, 'POUR', 6),
(9, 'CONTRE', 6);

-- --------------------------------------------------------

--
-- Structure de la table `resultat`
--

CREATE TABLE IF NOT EXISTS `resultat` (
  `vote` int(11) NOT NULL,
  `idcandidat` int(11) NOT NULL,
  `nbvoies` int(3) NOT NULL,
  `winner` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `resultat`
--

INSERT INTO `resultat` (`vote`, `idcandidat`, `nbvoies`, `winner`) VALUES
(6, 8, 3, 1),
(6, 9, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `token` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `token`) VALUES
(3, 'test@test.com', 'test', '71d39b2d963ae75f68c0f8ec7ad83b0f'),
(4, 'test1@test.fr', 'test', '51767292ea251cc8a9a8dc2302fbe176'),
(34, 'nicolas.dalayer@gmail.com', 'nicolas', '0adca0761cdf8ce9d4ed40c92c59e690'),
(35, 'alexandre.ortiz@gmail.com', 'alexandre', '31570d2eaf8918fe501c6da0f815a90f'),
(39, 'test@test.fr', 'test', '4da4f88a92221bbd501462dff38421bb');

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

CREATE TABLE IF NOT EXISTS `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  `deadline` datetime NOT NULL,
  `complete` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `vote`
--

INSERT INTO `vote` (`id`, `name`, `description`, `deadline`, `complete`) VALUES
(5, 'Municipales 2014 FRANCE', 'Municipales 2014 en France', '2014-03-16 00:00:00', 0),
(6, 'Cumul des Mandats', 'Etes-vous pour ou contre le cumul des mandats', '2014-01-09 15:00:00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `idcandidat` int(11) NOT NULL,
  `idvote` int(11) NOT NULL,
  `token` varchar(45) NOT NULL,
  KEY `idvote` (`idvote`),
  KEY `token` (`token`),
  KEY `idcandidat` (`idcandidat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `votes`
--

INSERT INTO `votes` (`idcandidat`, `idvote`, `token`) VALUES
(3, 5, '51767292ea251cc8a9a8dc2302fbe176'),
(8, 6, '51767292ea251cc8a9a8dc2302fbe176'),
(9, 6, '31570d2eaf8918fe501c6da0f815a90f'),
(8, 6, '0adca0761cdf8ce9d4ed40c92c59e690'),
(8, 6, '71d39b2d963ae75f68c0f8ec7ad83b0f'),
(5, 5, '71d39b2d963ae75f68c0f8ec7ad83b0f');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `candidat`
--
ALTER TABLE `candidat`
  ADD CONSTRAINT `candidat_ibfk_2` FOREIGN KEY (`vote`) REFERENCES `vote` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_10` FOREIGN KEY (`idcandidat`) REFERENCES `candidat` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `votes_ibfk_8` FOREIGN KEY (`idvote`) REFERENCES `vote` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `votes_ibfk_9` FOREIGN KEY (`token`) REFERENCES `user` (`token`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

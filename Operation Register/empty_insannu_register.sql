-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Dim 04 Novembre 2012 à 11:17
-- Version du serveur: 5.5.24-9
-- Version de PHP: 5.4.4-7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `insannu_register`
--
CREATE DATABASE `insannu_register` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `insannu_register`;

-- --------------------------------------------------------

--
-- Structure de la table `load_logo`
--

CREATE TABLE IF NOT EXISTS `load_logo` (
  `id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `ip_address` text NOT NULL,
  `user_agent` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `registrations`
--

CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `ip_address` text NOT NULL,
  `user_agent` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL,
  `room` text NOT NULL,
  `ip_address` text NOT NULL,
  `department` text NOT NULL,
  `gender` text NOT NULL,
  `year` tinyint(4) NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `picture` tinyint(1) NOT NULL,
  `student_id` int(11) NOT NULL,
  `mail` text NOT NULL,
  `id_confirm` int(11) NOT NULL,
  `file` text NOT NULL,
  `groupe` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

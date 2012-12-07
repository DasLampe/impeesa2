-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 07, 2012 at 12:18 PM
-- Server version: 5.5.28
-- PHP Version: 5.3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `impeesa2`
--

-- --------------------------------------------------------

--
-- Table structure for table `impeesa2_config`
--

CREATE TABLE IF NOT EXISTS `impeesa2_config` (
  `config_key` text NOT NULL,
  `config_value` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `impeesa2_config`
--

INSERT INTO `impeesa2_config` (`config_key`, `config_value`, `description`) VALUES
('adminEmail', 'daslampe@lano-crew.org', ''),
('unitname', 'Impeesa2 - CMS for Scouts', 'Name des Stamm'),
('version', '2.0.3', 'Impeesa2 Version'),
('scoutNetId', '7', 'ScoutNet.de ID'),
('pictureWatermark', '1', 'Wasserzeichen über Bilder');

-- --------------------------------------------------------

--
-- Table structure for table `impeesa2_content`
--

CREATE TABLE IF NOT EXISTS `impeesa2_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `menu_title` text NOT NULL,
  `in_nav` tinyint(1) NOT NULL,
  `nav_order` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `impeesa2_content`
--

INSERT INTO `impeesa2_content` (`id`, `name`, `title`, `content`, `menu_title`, `in_nav`, `nav_order`, `parent`) VALUES
(1, 'home', 'Startseite', '<h1>Willkommen bei Impeesa2</h2>', 'Startseite', 1, 0, 0),
(2, 'calender', 'Kalender', '', 'Kalender', 1, 1, 0),
(3, 'contact', 'Kontakt', '', 'Kontakt', 1, 2, 0),
(4, 'picture', 'Bilder', '', 'Bilder', 1, 3, 0),
(5, 'news', 'Neuigkeiten', '', 'Neuigkeiten', 1, 4, 0),
(6, 'admin', 'Intern', '', 'Intern', 1, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `impeesa2_debug`
--

CREATE TABLE IF NOT EXISTS `impeesa2_debug` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `timestamp` int(11) NOT NULL,
  `exception_id` varchar(5) NOT NULL,
  `trace` text NOT NULL,
  `exception_file` text NOT NULL,
  `exception_line` int(11) NOT NULL,
  `request_uri` text NOT NULL,
  `request_referer` text NOT NULL,
  `request_method` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `impeesa2_news`
--

CREATE TABLE IF NOT EXISTS `impeesa2_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `headline` text NOT NULL,
  `content` text NOT NULL,
  `publish` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `impeesa2_news`
--

INSERT INTO `impeesa2_news` (`id`, `headline`, `content`, `publish`) VALUES
(14, 'Und der nächste Test', 'Klar geht das ;) Soooo nice!!', 1346018400);

-- --------------------------------------------------------

--
-- Table structure for table `impeesa2_users`
--

CREATE TABLE IF NOT EXISTS `impeesa2_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `salt` text NOT NULL,
  `first_name` text NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `can_contact` tinyint(1) NOT NULL DEFAULT '1',
  `session_key` varchar(6) NOT NULL,
  `session_fingerprint` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `impeesa2_users`
--

INSERT INTO `impeesa2_users` (`id`, `username`, `password`, `salt`, `first_name`, `name`, `email`, `can_contact`, `session_key`, `session_fingerprint`) VALUES
(1, 'DasLampe', '16b28af92465d7b93e2f4c5c15558b7a743832ce02c23c7646d81c768ed220f6d2757cc7b8b0d98f21f0e3bb0491f7cdf9525bca30f66cc004784ebfa7be1f64', 'ca0330bc9ee215bc8cbb093880a15ada', 'Andre', '', 'daslampe@lano-crew.org', 1, '0dfd85', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

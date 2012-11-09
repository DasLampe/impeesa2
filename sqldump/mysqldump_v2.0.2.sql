-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 07, 2012 at 02:21 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

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
  `session_key` varchar(6) NOT NULL,
  `session_fingerprint` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO `impeesa2_config` (`config_key`, `config_value`, `description`) VALUES
('adminEmail', 'daslampe@lano-crew.org', 'Webmaster Email'),
('unitname', 'Impeesa2 - CMS for Scouts', 'Name des Stamm'),
('version', '2.0.2', 'Impeesa2 Version'),
('scoutNetId', '7', 'ScoutNet.de ID');

-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb5+lenny4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 01, 2010 at 01:52 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `pass`
--

-- --------------------------------------------------------

--
-- Table structure for table `passwords`
--

CREATE TABLE IF NOT EXISTS `passwords` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `description` longtext NOT NULL,
  `link` varchar(60) NOT NULL,
  `username` varchar(60) NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `active` (`active`),
  KEY `username` (`username`),
  KEY `link` (`link`),
  KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `passwords`
--

INSERT INTO `passwords` (`id`, `name`, `description`, `link`, `username`, `active`) VALUES
(1, 'Super Secure Site', 'This is the password for super secure site.\r\nRemember, if you''re going to use this log in, you need to contact our marketing department first, and let them know what you''re changing.\r\nSeveral systems rely on this login for API 
access, do don''t change the password without checking with everyone first.\r\nThis login is also related to Super Secure Site 2', 'www.example.com', 'AwesomeUser', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_properties`
--

CREATE TABLE IF NOT EXISTS `password_properties` (
  `password_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY  (`password_id`,`name`(10))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `password_properties`
--


-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `last_active` datetime NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(60) NOT NULL,
  `password` varchar(64) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--



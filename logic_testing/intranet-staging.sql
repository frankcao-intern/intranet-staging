-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 15, 2016 at 03:44 PM
-- Server version: 5.5.28
-- PHP Version: 5.3.18

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `intranet-staging`
--

-- --------------------------------------------------------

--
-- Table structure for table `fn_audit`
--

DROP TABLE IF EXISTS `fn_audit`;
CREATE TABLE IF NOT EXISTS `fn_audit` (
  `audit_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) NOT NULL,
  `what` enum('created','edited','deleted','undeleted','published','unpublished','public','private') NOT NULL,
  `when` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `who` bigint(20) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `fk_audit_users_idx` (`who`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=114187 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_comments`
--

DROP TABLE IF EXISTS `fn_comments`;
CREATE TABLE IF NOT EXISTS `fn_comments` (
  `comment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) NOT NULL,
  `comment_text` mediumtext NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `response_to` bigint(20) DEFAULT NULL COMMENT 'response to id',
  PRIMARY KEY (`comment_id`),
  KEY `fk_comments_pages_idx` (`page_id`),
  KEY `fk_comments_users_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='comments for each page that allows comments. ' AUTO_INCREMENT=7101 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_daysout`
--

DROP TABLE IF EXISTS `fn_daysout`;
CREATE TABLE IF NOT EXISTS `fn_daysout` (
  `user_id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `day_type_id` varchar(2) NOT NULL,
  `status` enum('planned','pending','confirmed') NOT NULL DEFAULT 'planned',
  `request_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`date`),
  KEY `fk_fn_items_fn_users1` (`user_id`),
  KEY `daysout_request_id` (`request_id`),
  KEY `fk_atnd_items_atnd_day_types1` (`day_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fn_daysout_months`
--

DROP TABLE IF EXISTS `fn_daysout_months`;
CREATE TABLE IF NOT EXISTS `fn_daysout_months` (
  `user_id` bigint(20) NOT NULL,
  `month` tinyint(4) NOT NULL,
  `year` int(11) NOT NULL,
  `status` enum('planned','pending','confirmed') NOT NULL DEFAULT 'pending',
  `request_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`month`,`year`),
  KEY `daysout_months_month` (`month`),
  KEY `daysout_months_year` (`year`),
  KEY `fk_fn_daysout_months_fn_users1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fn_day_types`
--

DROP TABLE IF EXISTS `fn_day_types`;
CREATE TABLE IF NOT EXISTS `fn_day_types` (
  `short_name` varchar(2) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`short_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fn_events`
--

DROP TABLE IF EXISTS `fn_events`;
CREATE TABLE IF NOT EXISTS `fn_events` (
  `event_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `event_title` varchar(255) NOT NULL,
  `where` varchar(255) NOT NULL,
  `where_room` varchar(255) DEFAULT NULL,
  `organizer` varchar(45) NOT NULL,
  `organizer_name` varchar(255) NOT NULL,
  `event_desc` mediumtext NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `all_day` tinyint(1) DEFAULT NULL,
  `rec_rule` varchar(50) DEFAULT NULL,
  `rec_factor` varchar(50) DEFAULT NULL,
  `rec_serial` varchar(510) NOT NULL,
  `creator` bigint(20) NOT NULL COMMENT 'user_id',
  `is_exception_of` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  KEY `fk_events_events` (`is_exception_of`),
  KEY `fk_events_users_idx` (`creator`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2970 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_favorites`
--

DROP TABLE IF EXISTS `fn_favorites`;
CREATE TABLE IF NOT EXISTS `fn_favorites` (
  `favorite_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - url\n1 - rss feed',
  PRIMARY KEY (`favorite_id`),
  KEY `fk_favorites_users_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='keep the user selected favorite pages and rss feeds' AUTO_INCREMENT=2623 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_groups`
--

DROP TABLE IF EXISTS `fn_groups`;
CREATE TABLE IF NOT EXISTS `fn_groups` (
  `group_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(60) NOT NULL,
  `group_type` enum('security','department') NOT NULL DEFAULT 'security',
  `group_parent` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_name_UNIQUE` (`group_name`),
  KEY `index_groups_group_name` (`group_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2147 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_groups_users`
--

DROP TABLE IF EXISTS `fn_groups_users`;
CREATE TABLE IF NOT EXISTS `fn_groups_users` (
  `group_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`group_id`,`user_id`),
  KEY `fk_group_user_relationships_groups_idx` (`group_id`),
  KEY `fk_group_user_relationships_users_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fn_mail_queue`
--

DROP TABLE IF EXISTS `fn_mail_queue`;
CREATE TABLE IF NOT EXISTS `fn_mail_queue` (
  `email_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `from` text,
  `to` text NOT NULL,
  PRIMARY KEY (`email_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2882 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_migrations`
--

DROP TABLE IF EXISTS `fn_migrations`;
CREATE TABLE IF NOT EXISTS `fn_migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fn_pages`
--

DROP TABLE IF EXISTS `fn_pages`;
CREATE TABLE IF NOT EXISTS `fn_pages` (
  `page_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT 'page title this is the full title, as it appears in the <title></title> tag.',
  `template_id` int(11) NOT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '1',
  `published` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'true = page is published and be seen by anyone.\nfalse = page can only be seen by people with editing rights to it',
  `date_published` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `show_until` date DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `featured_from` date DEFAULT NULL,
  `featured_until` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL COMMENT 'in case a page needs to be up for a while then dissappear.',
  `page_views` bigint(20) NOT NULL DEFAULT '0',
  `allow_comments` tinyint(1) NOT NULL DEFAULT '1',
  `redirect_url` varchar(255) DEFAULT NULL COMMENT 'page scpecific routing override',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`),
  KEY `fk_pages_templates_idx` (`template_id`),
  KEY `fk_pages_users_idx` (`created_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='this table holds all pages in the site' AUTO_INCREMENT=6177 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_pages_events`
--

DROP TABLE IF EXISTS `fn_pages_events`;
CREATE TABLE IF NOT EXISTS `fn_pages_events` (
  `page_id` bigint(20) NOT NULL,
  `event_id` bigint(20) NOT NULL,
  PRIMARY KEY (`page_id`,`event_id`),
  KEY `fk_section_event_relationships_events1_idx` (`event_id`),
  KEY `fk_section_event_relationships_pages1_idx` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fn_pages_pages`
--

DROP TABLE IF EXISTS `fn_pages_pages`;
CREATE TABLE IF NOT EXISTS `fn_pages_pages` (
  `page_id` bigint(20) NOT NULL,
  `section_id` bigint(20) NOT NULL,
  PRIMARY KEY (`page_id`,`section_id`),
  KEY `fk_page2page_pages1_idx` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fn_pages_users`
--

DROP TABLE IF EXISTS `fn_pages_users`;
CREATE TABLE IF NOT EXISTS `fn_pages_users` (
  `page_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`page_id`,`user_id`),
  KEY `fk_user_subscriptions_pages1_idx` (`page_id`),
  KEY `fk_user_subscriptions_users1_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fn_permissions`
--

DROP TABLE IF EXISTS `fn_permissions`;
CREATE TABLE IF NOT EXISTS `fn_permissions` (
  `page_id` bigint(20) NOT NULL,
  `group_id` bigint(20) NOT NULL,
  `access` tinyint(4) NOT NULL,
  PRIMARY KEY (`page_id`,`group_id`),
  KEY `fk_permissions_page_idx` (`page_id`),
  KEY `fk_permissions_groups_idx` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fn_pubdates`
--

DROP TABLE IF EXISTS `fn_pubdates`;
CREATE TABLE IF NOT EXISTS `fn_pubdates` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `article` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `pubDate` date NOT NULL,
  `expDate` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='used to track publication dates' AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_revisions`
--

DROP TABLE IF EXISTS `fn_revisions`;
CREATE TABLE IF NOT EXISTS `fn_revisions` (
  `revision_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `revision_text` mediumtext NOT NULL,
  `page_id` bigint(20) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) NOT NULL,
  PRIMARY KEY (`revision_id`),
  KEY `fk_revisions_pages_idx` (`page_id`),
  KEY `fk_revisions_users_idx` (`created_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53091 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_searchindex`
--

DROP TABLE IF EXISTS `fn_searchindex`;
CREATE TABLE IF NOT EXISTS `fn_searchindex` (
  `obj_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `obj_type` enum('page','event') NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_content` mediumtext NOT NULL,
  `page_date_published` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `section_id` bigint(20) DEFAULT NULL,
  `section_title` varchar(255) DEFAULT NULL,
  `tag_name` varchar(60) DEFAULT NULL,
  `group_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`obj_id`,`obj_type`),
  KEY `fk_searchindex_events_idx` (`obj_id`),
  FULLTEXT KEY `ft_si_page_title` (`page_title`),
  FULLTEXT KEY `ft_si_page_content` (`page_content`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='searchindex for pages' AUTO_INCREMENT=6147 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_sessions`
--

DROP TABLE IF EXISTS `fn_sessions`;
CREATE TABLE IF NOT EXISTS `fn_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) DEFAULT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table for Codeigniter to store its session information';

-- --------------------------------------------------------

--
-- Table structure for table `fn_stores`
--

DROP TABLE IF EXISTS `fn_stores`;
CREATE TABLE IF NOT EXISTS `fn_stores` (
  `number` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `street_address1` varchar(255) DEFAULT NULL,
  `street_address2` varchar(255) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `manager` bigint(20) DEFAULT NULL,
  `assistant_mgr` bigint(20) DEFAULT NULL,
  `metadata` text,
  `type` enum('specialty','retail','company','wholesale') NOT NULL DEFAULT 'specialty',
  `link` varchar(255) DEFAULT NULL,
  `latitude` float(9,6) DEFAULT NULL,
  `longitude` float(9,6) DEFAULT NULL,
  PRIMARY KEY (`number`),
  KEY `fk_fn_stores_fn_users1_idx` (`manager`),
  KEY `fk_fn_stores_fn_users2_idx` (`assistant_mgr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fn_suggested_stores`
--

DROP TABLE IF EXISTS `fn_suggested_stores`;
CREATE TABLE IF NOT EXISTS `fn_suggested_stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(45) NOT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `street_address1` varchar(255) NOT NULL,
  `street_address2` varchar(255) DEFAULT NULL,
  `city` varchar(45) NOT NULL,
  `state` varchar(45) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `brands` text NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_tags`
--

DROP TABLE IF EXISTS `fn_tags`;
CREATE TABLE IF NOT EXISTS `fn_tags` (
  `tag_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(60) NOT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `tag_name_UNIQUE` (`tag_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23496 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_tag_matches`
--

DROP TABLE IF EXISTS `fn_tag_matches`;
CREATE TABLE IF NOT EXISTS `fn_tag_matches` (
  `page_id` bigint(20) NOT NULL,
  `tag_id` bigint(20) NOT NULL,
  PRIMARY KEY (`page_id`,`tag_id`),
  KEY `fk_tag_match_taglist_idx` (`tag_id`),
  KEY `fk_tag_match_pages_idx` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fn_templates`
--

DROP TABLE IF EXISTS `fn_templates`;
CREATE TABLE IF NOT EXISTS `fn_templates` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(45) NOT NULL,
  `template_title` varchar(60) NOT NULL,
  `category` enum('system','Articles','Videos','Team Pages','Sections','Special Templates') NOT NULL COMMENT 'the category this template belongs to.',
  `page_type` enum('page','section','calendar') NOT NULL,
  `redirect_url` varchar(255) DEFAULT NULL COMMENT 'default routing for a template, can be overriden by a page',
  PRIMARY KEY (`template_id`),
  UNIQUE KEY `name_UNIQUE` (`template_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='this table defines the templates and its attributes' AUTO_INCREMENT=85 ;

-- --------------------------------------------------------

--
-- Table structure for table `fn_users`
--

DROP TABLE IF EXISTS `fn_users`;
CREATE TABLE IF NOT EXISTS `fn_users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL COMMENT 'samaccountname from AD',
  `display_name` varchar(255) NOT NULL,
  `first_name` varchar(60) NOT NULL,
  `last_name` varchar(60) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(20) DEFAULT NULL,
  `cellphone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `location` varchar(60) DEFAULT NULL,
  `biography` mediumtext,
  `joboverview` text,
  `extra_contact_info` tinytext,
  `status` varchar(255) DEFAULT NULL,
  `pref_contact_method` enum('Phone','Email','Cellphone','Fax','Instant Messenger','Face to Face') NOT NULL DEFAULT 'Phone',
  `role` enum('admin','editor','user','payroll') NOT NULL DEFAULT 'user',
  `preferences` text COMMENT 'JSON structure with user preferences. not sure what will go here but is good to have.',
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'last time the user logged in to the site. Not related to last_login from AD.',
  `person_id` bigint(20) DEFAULT NULL,
  `payroll_id` bigint(20) DEFAULT NULL,
  `payroll_code` varchar(3) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `leader` bigint(20) DEFAULT NULL,
  `rate_type` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8180 ;

-- --------------------------------------------------------

--
-- Table structure for table `tableajax`
--

DROP TABLE IF EXISTS `tableajax`;
CREATE TABLE IF NOT EXISTS `tableajax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentname` varchar(100) NOT NULL,
  `gender` varchar(8) NOT NULL,
  `phone` varchar(22) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fn_audit`
--
ALTER TABLE `fn_audit`
  ADD CONSTRAINT `fk_audit_users` FOREIGN KEY (`who`) REFERENCES `fn_users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `fn_comments`
--
ALTER TABLE `fn_comments`
  ADD CONSTRAINT `fk_comments_pages` FOREIGN KEY (`page_id`) REFERENCES `fn_pages` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_users` FOREIGN KEY (`user_id`) REFERENCES `fn_users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `fn_daysout`
--
ALTER TABLE `fn_daysout`
  ADD CONSTRAINT `fk_fn_daysout_fn_day_types1` FOREIGN KEY (`day_type_id`) REFERENCES `fn_day_types` (`short_name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fn_daysout_fn_users1` FOREIGN KEY (`user_id`) REFERENCES `fn_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fn_daysout_months`
--
ALTER TABLE `fn_daysout_months`
  ADD CONSTRAINT `fk_fn_daysout_months_fn_users1` FOREIGN KEY (`user_id`) REFERENCES `fn_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fn_events`
--
ALTER TABLE `fn_events`
  ADD CONSTRAINT `fk_events_event` FOREIGN KEY (`is_exception_of`) REFERENCES `fn_events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_events_users` FOREIGN KEY (`creator`) REFERENCES `fn_users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `fn_favorites`
--
ALTER TABLE `fn_favorites`
  ADD CONSTRAINT `fk_favorites_users` FOREIGN KEY (`user_id`) REFERENCES `fn_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fn_groups_users`
--
ALTER TABLE `fn_groups_users`
  ADD CONSTRAINT `fk_group_user_relationships_groups` FOREIGN KEY (`group_id`) REFERENCES `fn_groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_group_user_relationships_users` FOREIGN KEY (`user_id`) REFERENCES `fn_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fn_pages`
--
ALTER TABLE `fn_pages`
  ADD CONSTRAINT `fk_pages_templates` FOREIGN KEY (`template_id`) REFERENCES `fn_templates` (`template_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pages_users` FOREIGN KEY (`created_by`) REFERENCES `fn_users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `fn_pages_events`
--
ALTER TABLE `fn_pages_events`
  ADD CONSTRAINT `fk_section_event_relationships_events1` FOREIGN KEY (`event_id`) REFERENCES `fn_events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_section_event_relationships_pages1` FOREIGN KEY (`page_id`) REFERENCES `fn_pages` (`page_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `fn_pages_pages`
--
ALTER TABLE `fn_pages_pages`
  ADD CONSTRAINT `fk_page2page_pages1` FOREIGN KEY (`section_id`) REFERENCES `fn_pages` (`page_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_section_page_relationships_pages1` FOREIGN KEY (`page_id`) REFERENCES `fn_pages` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fn_pages_users`
--
ALTER TABLE `fn_pages_users`
  ADD CONSTRAINT `fk_user_subscriptions_pages1` FOREIGN KEY (`page_id`) REFERENCES `fn_pages` (`page_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_subscriptions_users1` FOREIGN KEY (`user_id`) REFERENCES `fn_users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `fn_permissions`
--
ALTER TABLE `fn_permissions`
  ADD CONSTRAINT `fk_permissions_groups` FOREIGN KEY (`group_id`) REFERENCES `fn_groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_permissions_page` FOREIGN KEY (`page_id`) REFERENCES `fn_pages` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fn_revisions`
--
ALTER TABLE `fn_revisions`
  ADD CONSTRAINT `fk_revisions_pages` FOREIGN KEY (`page_id`) REFERENCES `fn_pages` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_revisions_users` FOREIGN KEY (`created_by`) REFERENCES `fn_users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `fn_tag_matches`
--
ALTER TABLE `fn_tag_matches`
  ADD CONSTRAINT `fk_tag_match_pages` FOREIGN KEY (`page_id`) REFERENCES `fn_pages` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tag_match_taglist` FOREIGN KEY (`tag_id`) REFERENCES `fn_tags` (`tag_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

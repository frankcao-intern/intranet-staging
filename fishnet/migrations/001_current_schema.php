<?php
/**
 * Created by: cravelo
 * Date: 1/9/12
 * Time: 10:09 AM
 */

/**
 * @property CI_DB_active_record $db
 */
class Migration_Current_schema extends CI_Migration {

	public function up(){
		/*$this->db->query("SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;");
		$this->db->query("SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;");
		$this->db->query("SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';");

		$this->db->query("CREATE SCHEMA IF NOT EXISTS `intranet` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;");
		$this->db->query("USE `intranet` ;");

		// -----------------------------------------------------
		// Table `intranet`.`templates`
		// -----------------------------------------------------
		$this->db->query("
			CREATE  TABLE IF NOT EXISTS `intranet`.`templates` (
			  `template_id` INT NOT NULL AUTO_INCREMENT ,
			  `template_name` VARCHAR(45) NOT NULL ,
			  `template_title` VARCHAR(60) NOT NULL ,
			  `category` ENUM('system','articles', 'videos','team_pages') NOT NULL COMMENT 'the category this template belongs to.' ,
			  `description` VARCHAR(255) NOT NULL COMMENT 'description of the the template is and possible uses.' ,
			  PRIMARY KEY (`template_id`) ,
			  UNIQUE INDEX `name_UNIQUE` (`template_name` ASC) )
			ENGINE = InnoDB
			AUTO_INCREMENT = 11
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_swedish_ci
			COMMENT = 'this table defines the templates and its attributes'
		");


		// -----------------------------------------------------
		// Table `intranet`.`users`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`users` (
			  `user_id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
			  `username` VARCHAR(45) NULL DEFAULT NULL COMMENT 'samaccountname from AD' ,
			  `displayname` VARCHAR(255) NOT NULL ,
			  `firstname` VARCHAR(60) NOT NULL ,
			  `lastname` VARCHAR(60) NULL DEFAULT NULL ,
			  `email` VARCHAR(255) NULL DEFAULT NULL ,
			  `title` VARCHAR(255) NULL DEFAULT NULL ,
			  `phonenumber` VARCHAR(20) NULL DEFAULT NULL ,
			  `cellphone` VARCHAR(20) NULL DEFAULT NULL ,
			  `fax` VARCHAR(20) NULL DEFAULT NULL ,
			  `location` VARCHAR(60) NULL DEFAULT NULL ,
			  `biography` MEDIUMTEXT NULL DEFAULT NULL ,
			  `joboverview` TEXT NULL DEFAULT NULL ,
			  `extra_contact_info` TINYTEXT NULL DEFAULT NULL ,
			  `status` VARCHAR(255) NULL DEFAULT NULL ,
			  `preferences` TEXT NULL DEFAULT NULL COMMENT 'JSON structure with user preferences. not sure what will go here but is good to have.' ,
			  `last_login` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'last time the user logged in to the site. Not related to last_login from AD.' ,
			  `person_id` BIGINT NULL DEFAULT NULL ,
			  `start_date` DATE NULL DEFAULT NULL ,
			  `pref_contact_method` ENUM('Phone','Email','Cellphone','Fax','Instant Messenger','Face to Face') NULL DEFAULT 'Phone' ,
			  `hidden` TINYINT(1)  NOT NULL DEFAULT 0 ,
			  PRIMARY KEY (`user_id`) ,
			  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC) )
			ENGINE = InnoDB
			AUTO_INCREMENT = 606
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_swedish_ci,
			COMMENT = 'Custom fields for users, things that cant be kept in AD'
		");


		// -----------------------------------------------------
		// Table `intranet`.`pages`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`pages` (
		  `page_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'id to reference internally' ,
		  `title` VARCHAR(255) NOT NULL COMMENT 'page title this is the full title, as it appears in the <title></title> tag.' ,
		  `template_id` INT NOT NULL COMMENT 'link to the template table to a template entry' ,
		  `created_by` BIGINT NOT NULL DEFAULT 1 ,
		  `published` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'true = page is published and be seen by anyone.\nfalse = page can only be seen by people with editing rights to it' ,
		  `date_published` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
		  `show_until` DATE NULL DEFAULT NULL ,
		  `public` TINYINT(1) NOT NULL DEFAULT 1 ,
		  `featured` TINYINT(1) NOT NULL DEFAULT 0 ,
		  `featured_from` DATE NULL DEFAULT NULL ,
		  `featured_until` DATE NULL DEFAULT NULL ,
		  `expiration_date` DATE NULL DEFAULT NULL COMMENT 'in case a page needs to be up for a while then banish.' ,
		  `page_views` BIGINT(20) NOT NULL DEFAULT 0 ,
		  `allow_comments` TINYINT(1) NOT NULL DEFAULT 1 ,
		  `redirect_url` VARCHAR(255) NULL DEFAULT NULL COMMENT 'if this is not null then the page is just a redirect.' ,
		  `deleted` TINYINT(1) NOT NULL DEFAULT 0 ,
		  `page_type` ENUM('page','section','calendar') NOT NULL DEFAULT 'page' ,
		  PRIMARY KEY (`page_id`) ,
		  INDEX `fk_pages_templates` (`template_id` ASC) ,
		  INDEX `fk_pages_users` (`created_by` ASC) ,
		  CONSTRAINT `fk_pages_templates`
			FOREIGN KEY (`template_id` )
			REFERENCES `intranet`.`templates` (`template_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		  CONSTRAINT `fk_pages_users`
			FOREIGN KEY (`created_by` )
			REFERENCES `intranet`.`users` (`user_id` )
			ON DELETE RESTRICT
			ON UPDATE CASCADE)
		ENGINE = InnoDB
		AUTO_INCREMENT = 44
		DEFAULT CHARACTER SET = latin1
		COLLATE = latin1_swedish_ci,
		COMMENT = 'this table holds all pages in the site' ");


		// -----------------------------------------------------
		// Table `intranet`.`comments`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`comments` (
		  `comment_id` BIGINT NOT NULL AUTO_INCREMENT ,
		  `page_id` BIGINT NOT NULL ,
		  `comment_text` MEDIUMTEXT NOT NULL ,
		  `user_id` BIGINT NOT NULL ,
		  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
		  `response_to` BIGINT NULL DEFAULT NULL COMMENT 'response to id' ,
		  PRIMARY KEY (`comment_id`) ,
		  INDEX `fk_comments_pages` (`page_id` ASC) ,
		  INDEX `fk_comments_users` (`user_id` ASC) ,
		  CONSTRAINT `fk_comments_pages`
			FOREIGN KEY (`page_id` )
			REFERENCES `intranet`.`pages` (`page_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		  CONSTRAINT `fk_comments_users`
			FOREIGN KEY (`user_id` )
			REFERENCES `intranet`.`users` (`user_id` )
			ON DELETE RESTRICT
			ON UPDATE CASCADE)
		ENGINE = InnoDB
		AUTO_INCREMENT = 60
		DEFAULT CHARACTER SET = latin1
		COLLATE = latin1_swedish_ci,
		COMMENT = 'comments for each page that allows comments. ' ");


		// -----------------------------------------------------
		// Table `intranet`.`favorites`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`favorites` (
		  `favorite_id` BIGINT NOT NULL AUTO_INCREMENT ,
		  `user_id` BIGINT NOT NULL ,
		  `title` VARCHAR(255) NOT NULL ,
		  `url` VARCHAR(255) NOT NULL ,
		  `type` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '0 - url\n1 - rss feed' ,
		  PRIMARY KEY (`favorite_id`) ,
		  INDEX `fk_favorites_users` (`user_id` ASC) ,
		  CONSTRAINT `fk_favorites_users`
			FOREIGN KEY (`user_id` )
			REFERENCES `intranet`.`users` (`user_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE)
		ENGINE = InnoDB
		AUTO_INCREMENT = 10
		DEFAULT CHARACTER SET = latin1
		COLLATE = latin1_swedish_ci,
		COMMENT = 'keep the user selected favorite pages and rss feeds' ");


		// -----------------------------------------------------
		// Table `intranet`.`sessions`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`sessions` (
		  `session_id` VARCHAR(40) NOT NULL DEFAULT 0 ,
		  `ip_address` VARCHAR(16) NOT NULL DEFAULT 0 ,
		  `user_agent` VARCHAR(50) NOT NULL ,
		  `last_activity` INT(10) UNSIGNED NOT NULL DEFAULT 0 ,
		  `user_data` TEXT NULL ,
		  PRIMARY KEY (`session_id`) )
		ENGINE = InnoDB,
		COMMENT = 'Table for Codeigniter to store its session information' ");


		// -----------------------------------------------------
		// Table `intranet`.`groups`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`groups` (
		  `group_id` BIGINT NOT NULL AUTO_INCREMENT ,
		  `group_name` VARCHAR(60) NOT NULL ,
		  `group_type` ENUM('security','department') NOT NULL DEFAULT 'security' ,
		  `group_parent` BIGINT NULL ,
		  INDEX `index_groups_group_name` (`group_name` ASC) ,
		  PRIMARY KEY (`group_id`) ,
		  UNIQUE INDEX `group_name_UNIQUE` (`group_name` ASC) )
		ENGINE = InnoDB");


		// -----------------------------------------------------
		// Table `intranet`.`revisions`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`revisions` (
		  `revision_id` BIGINT NOT NULL AUTO_INCREMENT ,
		  `revision_text` MEDIUMTEXT NOT NULL ,
		  `page_id` BIGINT NOT NULL ,
		  `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
		  `created_by` BIGINT NOT NULL ,
		  PRIMARY KEY (`revision_id`) ,
		  INDEX `fk_revisions_pages` (`page_id` ASC) ,
		  INDEX `fk_revisions_users` (`created_by` ASC) ,
		  CONSTRAINT `fk_revisions_pages`
			FOREIGN KEY (`page_id` )
			REFERENCES `intranet`.`pages` (`page_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		  CONSTRAINT `fk_revisions_users`
			FOREIGN KEY (`created_by` )
			REFERENCES `intranet`.`users` (`user_id` )
			ON DELETE RESTRICT
			ON UPDATE CASCADE)
		ENGINE = InnoDB
		AUTO_INCREMENT = 90
		DEFAULT CHARACTER SET = latin1
		COLLATE = latin1_swedish_ci");


		// -----------------------------------------------------
		// Table `intranet`.`tags`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`tags` (
		  `tag_id` BIGINT NOT NULL AUTO_INCREMENT ,
		  `tag_name` VARCHAR(60) CHARACTER SET 'latin1' COLLATE 'latin1_swedish_ci' NOT NULL ,
		  PRIMARY KEY (`tag_id`) ,
		  UNIQUE INDEX `tag_name_UNIQUE` (`tag_name` ASC) )
		ENGINE = InnoDB
		DEFAULT CHARACTER SET = latin1
		COLLATE = latin1_swedish_ci");


		// -----------------------------------------------------
		// Table `intranet`.`tag_matches`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`tag_matches` (
		  `page_id` BIGINT(20) NOT NULL ,
		  `tag_id` BIGINT(20) NOT NULL ,
		  PRIMARY KEY (`page_id`, `tag_id`) ,
		  INDEX `fk_tag_match_taglist` (`tag_id` ASC) ,
		  INDEX `fk_tag_match_pages` (`page_id` ASC) ,
		  CONSTRAINT `fk_tag_match_pages`
			FOREIGN KEY (`page_id` )
			REFERENCES `intranet`.`pages` (`page_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		  CONSTRAINT `fk_tag_match_taglist`
			FOREIGN KEY (`tag_id` )
			REFERENCES `intranet`.`tags` (`tag_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE)
		ENGINE = InnoDB
		DEFAULT CHARACTER SET = latin1
		COLLATE = latin1_swedish_ci");


		// -----------------------------------------------------
		// Table `intranet`.`searchindex`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`searchindex` (
		  `si_page_id` BIGINT NOT NULL AUTO_INCREMENT ,
		  `si_page_title` VARCHAR(255) NOT NULL ,
		  `si_page_content` MEDIUMTEXT NOT NULL ,
		  `si_page_date_published` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
		  `si_section_id` BIGINT NULL ,
		  `si_section_title` VARCHAR(255) NULL ,
		  PRIMARY KEY (`si_page_id`) ,
		  FULLTEXT INDEX `ft_si_page_title` (`si_page_title` ASC) ,
		  FULLTEXT INDEX `ft_si_page_content` (`si_page_content` ASC) ,
		  CONSTRAINT `fk_searchindex_page`
			FOREIGN KEY (`si_page_id` )
			REFERENCES `intranet`.`pages` (`page_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE)
		ENGINE = MyISAM
		DEFAULT CHARACTER SET = latin1
		COLLATE = latin1_swedish_ci,
		COMMENT = 'searchindex for pages' ");


		// -----------------------------------------------------
		// Table `intranet`.`permissions`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`permissions` (
		  `page_id` BIGINT NOT NULL ,
		  `group_id` BIGINT NOT NULL ,
		  `access` TINYINT(4) NOT NULL ,
		  PRIMARY KEY (`page_id`, `group_id`) ,
		  INDEX `fk_permissions_page` (`page_id` ASC) ,
		  INDEX `fk_permissions_groups` (`group_id` ASC) ,
		  CONSTRAINT `fk_permissions_page`
			FOREIGN KEY (`page_id` )
			REFERENCES `intranet`.`pages` (`page_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		  CONSTRAINT `fk_permissions_groups`
			FOREIGN KEY (`group_id` )
			REFERENCES `intranet`.`groups` (`group_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE)
		ENGINE = InnoDB");


		// -----------------------------------------------------
		// Table `intranet`.`events`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`events` (
		  `event_id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
		  `event_title` VARCHAR(255) NOT NULL ,
		  `where` VARCHAR(255) NOT NULL ,
		  `where_room` VARCHAR(255) NULL DEFAULT NULL ,
		  `organizer` VARCHAR(45) NOT NULL ,
		  `organizer_name` VARCHAR(255) NOT NULL ,
		  `event_desc` MEDIUMTEXT NOT NULL ,
		  `start_date` DATE NOT NULL ,
		  `end_date` DATE NULL DEFAULT NULL ,
		  `start_time` TIME NOT NULL ,
		  `end_time` TIME NOT NULL ,
		  `all_day` TINYINT(1)  NULL ,
		  `rec_rule` VARCHAR(50) NULL DEFAULT NULL ,
		  `rec_factor` VARCHAR(50) NULL DEFAULT NULL ,
		  `rec_serial` VARCHAR(510) NOT NULL ,
		  `rrule` VARCHAR(255) NULL DEFAULT NULL ,
		  `creator` BIGINT(20) NOT NULL COMMENT 'user_id' ,
		  `is_exception_of` BIGINT(20) NULL DEFAULT NULL ,
		  PRIMARY KEY (`event_id`) ,
		  INDEX `fk_events_events` (`is_exception_of` ASC) ,
		  INDEX `fk_events_users` (`creator` ASC) ,
		  CONSTRAINT `fk_events_event`
			FOREIGN KEY (`is_exception_of` )
			REFERENCES `intranet`.`events` (`event_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		  CONSTRAINT `fk_events_users`
			FOREIGN KEY (`creator` )
			REFERENCES `intranet`.`users` (`user_id` )
			ON DELETE RESTRICT
			ON UPDATE CASCADE)
		ENGINE = InnoDB
		AUTO_INCREMENT = 4
		DEFAULT CHARACTER SET = latin1
		COLLATE = latin1_swedish_ci");


		// -----------------------------------------------------
		// Table `intranet`.`cal_searchindex`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`cal_searchindex` (
		  `si_event_id` BIGINT(20) NOT NULL ,
		  `si_event_title` VARCHAR(255) NOT NULL ,
		  `si_event_desc` MEDIUMTEXT NOT NULL ,
		  PRIMARY KEY (`si_event_id`) ,
		  INDEX `fk_cal_searchindex_event` (`si_event_id` ASC) ,
		  FULLTEXT INDEX `ft_event_desc` (`si_event_desc` ASC) ,
		  FULLTEXT INDEX `ft_event_title` (`si_event_title` ASC) ,
		  CONSTRAINT `fk_cal_searchindex_event`
			FOREIGN KEY (`si_event_id` )
			REFERENCES `intranet`.`events` (`event_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE)
		ENGINE = MyISAM
		DEFAULT CHARACTER SET = latin1
		COLLATE = latin1_swedish_ci");


		// -----------------------------------------------------
		// Table `intranet`.`audit`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`audit` (
		  `audit_id` BIGINT NOT NULL AUTO_INCREMENT ,
		  `page_id` BIGINT NOT NULL ,
		  `what` ENUM('created','edited','deleted','undeleted','published','unpublished','public','private') NOT NULL ,
		  `when` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
		  `who` BIGINT NOT NULL ,
		  PRIMARY KEY (`audit_id`) ,
		  INDEX `fk_audit_users` (`who` ASC) ,
		  CONSTRAINT `fk_audit_users`
			FOREIGN KEY (`who` )
			REFERENCES `intranet`.`users` (`user_id` )
			ON DELETE RESTRICT
			ON UPDATE CASCADE)
		ENGINE = InnoDB");


		// -----------------------------------------------------
		// Table `intranet`.`page2page`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`page2page` (
		  `page_id` BIGINT NOT NULL ,
		  `section_id` BIGINT NOT NULL ,
		  PRIMARY KEY (`page_id`, `section_id`) ,
		  INDEX `fk_page2page_pages1` (`section_id` ASC) ,
		  CONSTRAINT `fk_section_page_relationships_pages1`
			FOREIGN KEY (`page_id` )
			REFERENCES `intranet`.`pages` (`page_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		  CONSTRAINT `fk_page2page_pages1`
			FOREIGN KEY (`section_id` )
			REFERENCES `intranet`.`pages` (`page_id` )
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB");


		// -----------------------------------------------------
		// Table `intranet`.`section_event_relationships`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`section_event_relationships` (
		  `page_id` BIGINT NOT NULL ,
		  `event_id` BIGINT NOT NULL ,
		  PRIMARY KEY (`page_id`, `event_id`) ,
		  INDEX `fk_section_event_relationships_events1` (`event_id` ASC) ,
		  INDEX `fk_section_event_relationships_pages1` (`page_id` ASC) ,
		  CONSTRAINT `fk_section_event_relationships_events1`
			FOREIGN KEY (`event_id` )
			REFERENCES `intranet`.`events` (`event_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		  CONSTRAINT `fk_section_event_relationships_pages1`
			FOREIGN KEY (`page_id` )
			REFERENCES `intranet`.`pages` (`page_id` )
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB");


		// -----------------------------------------------------
		// Table `intranet`.`group_user_relationships`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`group_user_relationships` (
		  `group_id` BIGINT NOT NULL ,
		  `user_id` BIGINT NOT NULL ,
		  PRIMARY KEY (`group_id`, `user_id`) ,
		  INDEX `fk_group_user_relationships_groups` (`group_id` ASC) ,
		  INDEX `fk_group_user_relationships_users` (`user_id` ASC) ,
		  CONSTRAINT `fk_group_user_relationships_groups`
			FOREIGN KEY (`group_id` )
			REFERENCES `intranet`.`groups` (`group_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		  CONSTRAINT `fk_group_user_relationships_users`
			FOREIGN KEY (`user_id` )
			REFERENCES `intranet`.`users` (`user_id` )
			ON DELETE CASCADE
			ON UPDATE CASCADE)
		ENGINE = InnoDB");


		// -----------------------------------------------------
		// Table `intranet`.`user_subscriptions`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`user_subscriptions` (
		  `page_id` BIGINT NOT NULL ,
		  `user_id` BIGINT NOT NULL ,
		  PRIMARY KEY (`page_id`, `user_id`) ,
		  INDEX `fk_user_subscriptions_pages1` (`page_id` ASC) ,
		  INDEX `fk_user_subscriptions_users1` (`user_id` ASC) ,
		  CONSTRAINT `fk_user_subscriptions_pages1`
			FOREIGN KEY (`page_id` )
			REFERENCES `intranet`.`pages` (`page_id` )
			ON DELETE NO ACTION
			ON UPDATE NO ACTION,
		  CONSTRAINT `fk_user_subscriptions_users1`
			FOREIGN KEY (`user_id` )
			REFERENCES `intranet`.`users` (`user_id` )
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		ENGINE = InnoDB");


		// -----------------------------------------------------
		// Table `intranet`.`mail_queue`
		// -----------------------------------------------------
		$this->db->query("CREATE  TABLE IF NOT EXISTS `intranet`.`mail_queue` (
		  `email_id` BIGINT NOT NULL AUTO_INCREMENT ,
		  `subject` VARCHAR(255) NOT NULL ,
		  `message` TEXT NOT NULL ,
		  `to` TEXT NOT NULL ,
		  PRIMARY KEY (`email_id`) )
		ENGINE = InnoDB");


		// -----------------------------------------------------
		// procedure hr_update
		// -----------------------------------------------------

		$this->db->query("DELIMITER $$
			USE `intranet`$$
			CREATE PROCEDURE `intranet`.`hr_update` ()
			BEGIN

				UPDATE intranet.users,employees.employees SET


				intranet.users.person_id=employees.employees.person_id,
				intranet.users.displayname=CONCAT_WS(' ', employees.employees.`FIRST NAME (PREFERRED)`, employees.employees.`LAST NAME (PREFERRED)`),
				intranet.users.firstname=employees.employees.first_name,
				intranet.users.lastname=employees.employees.last_name,
				intranet.users.location=employees.employees.`work location`,
				intranet.users.phonenumber=employees.employees.`direct dial`,
				intranet.users.fax=employees.employees.fax,
				intranet.users.title=employees.employees.title

				WHERE intranet.users.email=`employees`.`employees`.`email address`;

			END

			$$

			DELIMITER
		");

		$this->db->query("SET SQL_MODE=@OLD_SQL_MODE");
		$this->db->query("SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS");
		$this->db->query("SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS");*/
	}

	public function down(){
		//there is no down from this
	}
}

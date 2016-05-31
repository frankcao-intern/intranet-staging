<?php
/**
 * @author cravelo
 * @date   1/9/12
 * @time   10:23 AM
 */

/**
 * Migration_Drop_public_boolean
 * @package Migrations
 * @author cravelo
 * @property CI_DB_forge $dbforge
 */
class Migration_Drop_public_boolean extends CI_Migration {
	public function up(){
		$this->dbforge->drop_column('pages', 'public');
	}

	public function down(){
		$this->dbforge->add_column('pages', array(
												 'public' => array(
													'type' => 'TINYINT',
													 'constraint' => 1,
													 'default' => 1
												 )
											));
	}
}

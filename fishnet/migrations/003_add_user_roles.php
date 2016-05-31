<?php
/**
 * @author cravelo
 * @date   1/9/12
 * @time   11:11 AM
 */

/**
 * Migration_Add_user_roles
 * @package
 * @author cravelo
 * @property CI_DB_forge $dbforge
 */
class Migration_Add_user_roles extends CI_Migration {
	public function up(){
		$this->dbforge->add_column('users',
			array(
				 'role' => array(
					'type' => "ENUM('admin','editor','user')",
					 'default' => 'user'
				 )
			)
		);
	}

	public function down(){
		$this->dbforge->drop_column('users', 'role');
	}
}

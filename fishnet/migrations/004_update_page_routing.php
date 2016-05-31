<?php
/**
 * @author cravelo
 * @date   3/22/12
 * @time   10:57 AM
 */

/**
 * Update_page_routing
 * @package
 * @author cravelo
 */
class Migration_Update_page_routing extends CI_Migration {
	function up(){ //there is no going back from this, this is the site's data changing.
		$this->db
			->set('redirect_url', null)
			->update('pages');

		$this->db
			->set('redirect_url', '/news')
			->where('page_id', 2)
			->update('pages');
		$this->db
			->set('redirect_url', '/calendar')
			->where('page_id', 3)
			->update('pages');
		$this->db
			->set('redirect_url', '/btl')
			->where('page_id', 6)
			->update('pages');
		$this->db
			->set('redirect_url', '/efjournal')
			->where('page_id', 10)
			->update('pages');
	}

	function down(){

	}
}

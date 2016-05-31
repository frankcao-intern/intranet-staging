<?php
/**
 * @filename stores.php
 * @author   : cravelo
 * @date     : 6/18/12 9:45 PM
 */

class Stores extends CI_Model {
	/**
	 *
	 */
	function getSpecialtyStores(){
		/**
		 * @var CI_DB_result $query
		 */
		$query = $this->db
			->where('type', 'specialty')
			->get('stores');

		$result = array('stores' => array());
		if ($query){
			$result['stores'] = $query->result_array();
		}

		return $result;
	}
}

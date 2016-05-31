<?php
/**
 * @author cravelo
 */

/**
 * Templates model
 * @package Models
 * @author cravelo
 * @property CI_DB_active_record $db
 */
class Templates extends CI_Model {
	/**
	 * Get a template by id or get all templates except the ones categorized as 'system'
	 * @param int $id
	 * @return array
	 */
	function getTemplate($id = null){
		if (isset ($id)){
			$this->db->where('id', $id);
		}else{
			$this->db->where('category !=', 'system');
		}

		$query = $this->db->order_by('template_title')->get('templates');
		
		if ($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}
}
?>

<?php
/**
 * @author cravelo
 * @date Oct 8, 2010
 * @time 10:24:40 PM
 */

/**
 * Audit model
 * @package Models
 * @author cravelo
 */
class Audit extends CI_Model {
	/**
	 * @param array $data array of records to be inserted
	 * @return int the new record's id
	 */
	function newEntry($data){
		foreach($data as $entry){
			$this->db->insert('audit', $entry);	
		}
		
		if ($this->db->affected_rows() > 0){
			return $this->db->insert_id();
		}else{
			return FALSE;
		}
	}
}
